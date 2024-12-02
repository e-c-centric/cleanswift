<?php
require("../settings/db_class.php");

/**
 * Order class to handle all functions related to orders
 */
class order_class extends db_connection
{
    /**
     * Place multiple orders for an array of service provider IDs.
     *
     * @param int $customer_id
     * @param array $service_provider_ids
     * @return array|false Returns an array of order IDs on success, or false on failure.
     */
    public function place_orders($customer_id, $service_provider_ids)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);

        // Remove duplicate provider IDs
        $unique_providers = array_unique($service_provider_ids);

        $placed_orders = [];

        foreach ($unique_providers as $provider_id) {
            $provider_id = mysqli_real_escape_string($ndb->db_conn(), $provider_id);

            // Insert the order
            $insert_order_sql = "INSERT INTO `orders` (`customer_id`, `service_provider_id`, `order_date`, `status`) 
                                 VALUES ('$customer_id', '$provider_id', NOW(), 'In progress')";
            $insert_order = $this->db_query($insert_order_sql);

            if (!$insert_order) {
                // Rollback previous inserts if any insert fails
                foreach ($placed_orders as $placed_order_id) {
                    $this->cancel_order($placed_order_id);
                }
                return false;
            }

            // Retrieve the order_id just inserted
            $retrieve_order_sql = "SELECT `order_id` FROM `orders` 
                                   WHERE `customer_id` = '$customer_id' 
                                   AND `service_provider_id` = '$provider_id'
                                   ORDER BY `order_date` DESC 
                                   LIMIT 1";
            $order = $this->db_fetch_one($retrieve_order_sql);

            if (!$order) {
                // Rollback previous inserts if unable to fetch order_id
                foreach ($placed_orders as $placed_order_id) {
                    $this->cancel_order($placed_order_id);
                }
                return false;
            }

            $order_id = $order['order_id'];
            $placed_orders[] = $order_id;

            // Retrieve the customer's cart items for this provider
            $get_cart_sql = "SELECT c.service_id, c.quantity 
                             FROM `cart` c
                             JOIN `services` s ON c.service_id = s.service_id
                             WHERE c.customer_id = '$customer_id' 
                             AND s.provider_id = '$provider_id'";
            $cart_items = $this->db_fetch_all($get_cart_sql);

            if (!$cart_items) {
                // No items in cart for this provider, skip to next
                continue;
            }

            foreach ($cart_items as $item) {
                $service_id = mysqli_real_escape_string($ndb->db_conn(), $item['service_id']);
                $quantity = mysqli_real_escape_string($ndb->db_conn(), $item['quantity']);

                // Retrieve the price of the service
                $get_price_sql = "SELECT `price` FROM `services` 
                                  WHERE `service_id` = '$service_id'";
                $service = $this->db_fetch_one($get_price_sql);

                if (!$service) {
                    // Rollback all placed orders if service price retrieval fails
                    foreach ($placed_orders as $placed_order_id) {
                        $this->cancel_order($placed_order_id);
                    }
                    return false;
                }

                $price = $service['price'];
                $total_price = $price * $quantity;

                // Insert into order_details
                $insert_order_details_sql = "INSERT INTO `order_details` (`order_id`, `service_id`, `quantity`, `price`) 
                                             VALUES ('$order_id', '$service_id', '$quantity', '$total_price')";
                $insert_order_details = $this->db_query($insert_order_details_sql);

                if (!$insert_order_details) {
                    // Rollback all placed orders if inserting order details fails
                    foreach ($placed_orders as $placed_order_id) {
                        $this->cancel_order($placed_order_id);
                    }
                    return false;
                }
            }
        }

        // Clear the customer's cart after successfully placing all orders
        $clear_cart_sql = "DELETE FROM `cart` WHERE `customer_id` = '$customer_id'";
        $this->db_query($clear_cart_sql);

        return $placed_orders;
    }

    //--UPDATE--//
    public function fulfill_order($order_id)
    {
        $ndb = new db_connection();
        $order_id = mysqli_real_escape_string($ndb->db_conn(), $order_id);

        $update_sql = "UPDATE `orders` SET `status` = 'Fulfilled' 
                       WHERE `order_id` = '$order_id'";
        return $this->db_query($update_sql);
    }

    public function cancel_order($order_id)
    {
        $ndb = new db_connection();
        $order_id = mysqli_real_escape_string($ndb->db_conn(), $order_id);

        $update_sql = "UPDATE `orders` SET `status` = 'Cancelled' 
                       WHERE `order_id` = '$order_id'";
        return $this->db_query($update_sql);
    }

    //--SELECT--//
    public function get_orders_by_customer($customer_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);

        $sql = "SELECT 
                    o.order_id, 
                    o.order_date, 
                    o.status, 
                    p.provider_name, 
                    p.provider_address, 
                    u.user_name AS driver_name, 
                    u.user_contact AS driver_contact
                FROM `orders` o
                LEFT JOIN `providers` p ON o.service_provider_id = p.provider_id
                LEFT JOIN `drivers` d ON o.service_provider_id = d.driver_id
                LEFT JOIN `users` u ON d.driver_id = u.user_id
                JOIN `order_details` od ON o.order_id = od.order_id
                WHERE o.customer_id = '$customer_id'
                ORDER BY o.order_date DESC";

        return $this->db_fetch_all($sql);
    }

    public function get_orders_by_provider($provider_id)
    {
        $ndb = new db_connection();
        $provider_id = mysqli_real_escape_string($ndb->db_conn(), $provider_id);

        $sql = "SELECT o.order_id, o.order_date, o.status, 
                       u.user_name, u.user_email, u.user_contact
                FROM `orders` o
                JOIN `users` u ON o.customer_id = u.user_id
                WHERE o.service_provider_id = '$provider_id'
                ORDER BY o.order_date DESC";
        return $this->db_fetch_all($sql);
    }

    public function get_order_details($order_id)
    {
        $ndb = new db_connection();
        $order_id = mysqli_real_escape_string($ndb->db_conn(), $order_id);

        $sql = "SELECT od.order_details_id, od.service_id, od.quantity, od.price, 
                       s.service_name, s.price AS unit_price
                FROM `order_details` od
                JOIN `services` s ON od.service_id = s.service_id
                WHERE od.order_id = '$order_id'";
        return $this->db_fetch_all($sql);
    }

    public function create_order_from_delivery($delivery_id)
    {
        $ndb = new db_connection();
        $delivery_id = mysqli_real_escape_string($ndb->db_conn(), $delivery_id);

        $sql = "SELECT d.customer_id, d.driver_id, d.cost
                FROM `deliveries` d
                WHERE d.delivery_id = '$delivery_id'";

        $delivery = $this->db_fetch_one($sql);

        if (!$delivery) {
            return false;
        }

        $customer_id = $delivery['customer_id'];
        $driver_id = $delivery['driver_id'];

        $order = "INSERT INTO `orders` (`customer_id`, `service_provider_id`, `order_date`, `status`) 
                  VALUES ('$customer_id', '$driver_id', NOW(), 'Fulfilled')";

        $insert_order = $this->db_query($order);

        if (!$insert_order) {
            return false;
        }

        $order_id = "SELECT `order_id` FROM `orders` 
                     WHERE `customer_id` = '$customer_id' 
                     AND `service_provider_id` = '$driver_id'
                     ORDER BY `order_date` DESC 
                     LIMIT 1";

        $order = $this->db_fetch_one($order_id);

        if (!$order) {
            return false;
        }

        $order_id = $order['order_id'];

        $create_details = "INSERT INTO `order_details` (`order_id`, `quantity`, `price`)
                            VALUES ('$order_id', '1', '{$delivery['cost']}')";

        $insert_details = $this->db_query($create_details);

        if (!$insert_details) {
            return false;
        }

        return $order_id;
    }
}
