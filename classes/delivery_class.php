<?php
require("../settings/db_class.php");

/**
 * Delivery class to handle all functions related to deliveries
 */
class delivery_class extends db_connection
{
    //--INSERT--//
    public function add_delivery($customer_id, $provider_ids, $dropoff_time, $vehicle_type, $pickup_time = null)
    {
        $ndb = new db_connection();
        $conn = $ndb->db_conn();

        // Sanitize input parameters
        $customer_id = mysqli_real_escape_string($conn, $customer_id);
        $dropoff_time = mysqli_real_escape_string($conn, $dropoff_time);
        $pickup_time = $pickup_time ? "'" . mysqli_real_escape_string($conn, $pickup_time) . "'" : "NULL";
        $delivery_status = 'With Customer';
        $vehicle_type = mysqli_real_escape_string($conn, $vehicle_type);

        // Log sanitized and prepared values
        error_log("Sanitized Values - Customer ID: $customer_id, Delivery Status: $delivery_status, Pickup Time: $pickup_time, Dropoff Time: $dropoff_time, Vehicle Type: $vehicle_type");

        // Begin transaction
        $conn->begin_transaction();
        try {
            // Validate vehicle_type exists in vehicle_options
            $sqlValidateVehicle = "SELECT COUNT(*) as count FROM `vehicle_options` WHERE `option_id` = '$vehicle_type'";
            error_log("Executing SQL: $sqlValidateVehicle");

            $validateResult = $this->db_fetch_one($sqlValidateVehicle);
            if ($validateResult['count'] == 0) {
                error_log("Invalid vehicle_type: $vehicle_type does not exist in vehicle_options.");
                throw new Exception("Invalid vehicle type selected.");
            }

            // Insert into deliveries table
            $sqlDeliveries = "INSERT INTO `deliveries` (`customer_id`, `delivery_status`, `pickup_time`, `dropoff_time`, `vehicle_class`)
                         VALUES ('$customer_id', '$delivery_status', $pickup_time, '$dropoff_time', '$vehicle_type')";

            // Log the SQL query
            error_log("Executing SQL: $sqlDeliveries");

            if (!$this->db_query($sqlDeliveries)) {
                $error = mysqli_error($conn);
                error_log("Failed to execute query: $sqlDeliveries. Error: $error");
                throw new Exception("Failed to insert into deliveries table.");
            }

            // Fetch the latest delivery_id
            $sqlFetchDeliveryId = "SELECT delivery_id FROM `deliveries` 
                               WHERE `customer_id` = '$customer_id' 
                               AND `delivery_status` = '$delivery_status' 
                               AND `dropoff_time` = '$dropoff_time' 
                               ORDER BY `delivery_id` DESC 
                               LIMIT 1";

            // Log the SQL query
            error_log("Executing SQL: $sqlFetchDeliveryId");

            $result = $this->db_fetch_one($sqlFetchDeliveryId);
            if (!$result || !isset($result['delivery_id'])) {
                error_log("Failed to retrieve delivery ID for customer ID: $customer_id.");
                throw new Exception("Failed to retrieve delivery ID.");
            }

            $delivery_id = $result['delivery_id'];
            error_log("Retrieved delivery_id: $delivery_id");

            // Validate that provider_ids is an array
            if (!is_array($provider_ids)) {
                error_log("Provider IDs are not in an array format.");
                throw new Exception("Provider IDs must be an array.");
            }

            // Insert into delivery_provider table for each provider_id
            foreach ($provider_ids as $provider_id) {
                $provider_id = mysqli_real_escape_string($conn, $provider_id);
                $sqlDeliveryProviders = "INSERT INTO `delivery_provider` (`delivery_id`, `provider_id`) 
                                     VALUES ('$delivery_id', '$provider_id')";

                // Log the SQL query
                error_log("Executing SQL: $sqlDeliveryProviders");

                if (!$this->db_query($sqlDeliveryProviders)) {
                    $error = mysqli_error($conn);
                    error_log("Failed to execute query: $sqlDeliveryProviders. Error: $error");
                    throw new Exception("Failed to insert into delivery_provider table for provider ID: $provider_id.");
                }

                error_log("Successfully inserted provider ID: $provider_id for delivery ID: $delivery_id.");
            }

            // Commit the transaction
            $conn->commit();
            error_log("Transaction committed successfully for delivery ID: $delivery_id.");
            return true;
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            error_log("Transaction rolled back due to error: " . $e->getMessage());
            return false;
        }
    }

    public function accept_delivery($delivery_id, $driver_id, $cost)
    {
        $ndb = new db_connection();
        $conn = $ndb->db_conn();

        // Validate inputs
        if (!is_numeric($delivery_id) || !is_numeric($driver_id) || !is_numeric($cost)) {
            return [
                'status' => 'error',
                'message' => 'Invalid input data.'
            ];
        }

        // Sanitize inputs to prevent SQL injection
        $delivery_id_safe = mysqli_real_escape_string($conn, $delivery_id);
        $driver_id_safe = mysqli_real_escape_string($conn, $driver_id);
        $cost_safe = mysqli_real_escape_string($conn, $cost);

        $check_sql = "SELECT COUNT(*) AS delivery_count 
                      FROM `deliveries` 
                      WHERE `driver_id` = '$driver_id_safe' 
                      AND `delivery_status` = 'Transit to laundry' OR `delivery_status` = 'Transit from laundry' OR `delivery_status` = 'Coming to pickup'";

        $check_result = mysqli_query($conn, $check_sql);

        if ($check_result) {
            $row = mysqli_fetch_assoc($check_result);
            if ($row['delivery_count'] > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Driver already has an ongoing delivery.'
                ];
            }
        } else {
            error_log("Database Query Failed: " . mysqli_error($conn));
            return [
                'status' => 'error',
                'message' => 'Failed to verify existing deliveries for the driver.'
            ];
        }

        $update_sql = "UPDATE `deliveries` 
                       SET `driver_id` = '$driver_id_safe', 
                           `cost` = '$cost_safe', 
                           `delivery_status` = 'Coming to pickup' 
                       WHERE `delivery_id` = '$delivery_id_safe'";

        if (mysqli_query($conn, $update_sql)) {
            if (mysqli_affected_rows($conn) > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Delivery accepted successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'No delivery found with the provided ID.'
                ];
            }
        } else {
            error_log("Delivery Acceptance Failed: " . mysqli_error($conn));
            return [
                'status' => 'error',
                'message' => 'Failed to accept the delivery. Please try again later.'
            ];
        }
    }

    public function get_available_deliveries($vehicle_class)
    {
        $ndb = new db_connection();
        $vehicle_class = mysqli_real_escape_string($ndb->db_conn(), $vehicle_class);

        $sql = "SELECT * FROM `deliveries` WHERE `driver_id` IS NULL AND `vehicle_class` = '$vehicle_class'";
        return $this->db_fetch_all($sql);
    }

    public function get_delivery_details($delivery_id)
    {
        $ndb = new db_connection();
        $conn = $ndb->db_conn();

        $sql = "SELECT deliveries.delivery_id, users.user_name, customers.customer_city, providers.provider_name, providers.provider_address, deliveries.pickup_time, deliveries.dropoff_time, deliveries.cost, deliveries.delivery_status 
        FROM `deliveries` 
        JOIN `users` ON deliveries.customer_id = users.user_id
        JOIN `customers` ON deliveries.customer_id = customers.customer_id
        JOIN `delivery_provider` ON deliveries.delivery_id = delivery_provider.delivery_id
        JOIN `providers` ON delivery_provider.provider_id = providers.provider_id
        WHERE `deliveries`.`delivery_id` = '$delivery_id'";

        return $this->db_fetch_all($sql);
    }

    //--Update Delivery Status--//
    public function update_delivery_status($delivery_id, $delivery_status)
    {
        $ndb = new db_connection();
        $delivery_id = mysqli_real_escape_string($ndb->db_conn(), $delivery_id);
        $delivery_status = mysqli_real_escape_string($ndb->db_conn(), $delivery_status);

        $sql = "UPDATE `deliveries` SET `delivery_status` = '$delivery_status' WHERE `delivery_id` = '$delivery_id'";
        return $this->db_query($sql);
    }

    //--Get Total Cost of All Deliveries--//
    public function get_total_cost()
    {
        $sql = "SELECT SUM(`cost`) AS `total_cost` FROM `deliveries`";
        return $this->db_fetch_one($sql);
    }

    //--Get Total Cost by Driver ID--//
    public function get_total_cost_by_driver($driver_id)
    {
        $ndb = new db_connection();
        $driver_id = mysqli_real_escape_string($ndb->db_conn(), $driver_id);

        $sql = "SELECT SUM(`cost`) AS `total_cost` FROM `deliveries` WHERE `driver_id` = '$driver_id'";
        return $this->db_fetch_one($sql);
    }

    //--Get Total Cost by Customer ID--//
    public function get_total_cost_by_customer($customer_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);

        $sql = "SELECT SUM(`cost`) AS `total_cost` FROM `deliveries` WHERE `customer_id` = '$customer_id'";
        return $this->db_fetch_one($sql);
    }

    //--Get Delivery by ID--//
    public function get_delivery_by_id($delivery_id)
    {
        $ndb = new db_connection();
        $delivery_id = mysqli_real_escape_string($ndb->db_conn(), $delivery_id);

        $sql = "SELECT * FROM `deliveries` WHERE `delivery_id` = '$delivery_id'";
        return $this->db_fetch_one($sql);
    }

    //--Get Deliveries by Driver ID--//
    public function get_deliveries_by_driver_id($driver_id)
    {
        $ndb = new db_connection();
        $driver_id = mysqli_real_escape_string($ndb->db_conn(), $driver_id);

        $sql = "SELECT deliveries.delivery_id, users.user_name, customers.customer_city, providers.provider_name, providers.provider_address, deliveries.pickup_time, deliveries.dropoff_time, deliveries.cost, deliveries.delivery_status 
        FROM `deliveries` 
        JOIN `users` ON deliveries.customer_id = users.user_id
        JOIN `customers` ON deliveries.customer_id = customers.customer_id
        JOIN `delivery_provider` ON deliveries.delivery_id = delivery_provider.delivery_id
        JOIN `providers` ON delivery_provider.provider_id = providers.provider_id
        WHERE `deliveries`.`driver_id` = '$driver_id'";

        return $this->db_fetch_all($sql);
    }

    //--Get Deliveries by Customer ID--//
    public function get_deliveries_by_customer_id($customer_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);

        $sql = "SELECT * FROM `deliveries` WHERE `customer_id` = '$customer_id'";
        return $this->db_fetch_all($sql);
    }
}
