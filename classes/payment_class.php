<?php
require_once("../settings/db_class.php");

class payment_class extends db_connection
{
    /**
     * Adds a new payment record to the database.
     *
     * @param int $order_id The ID of the associated order.
     * @param float $amt The amount of the payment.
     * @param string $status The status of the payment (e.g., 'pending', 'completed', 'failed').
     * @return void
     * @throws Exception If the insertion into the payments table fails.
     */
    public function add_payment($order_id)
    {
        // Sanitize input data to prevent SQL injection
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        $status = 'Incomplete';

        // Begin transaction
        $this->db_conn()->begin_transaction();
        try {
            $sqlAmount = "SELECT SUM(quantity * price) AS total_amt FROM order_details WHERE order_id = '$order_id'";
            $total_amt = $this->db_fetch_one($sqlAmount)['total_amt'];

            $sqlPayment = "INSERT INTO payments (order_id, amt, status, payment_date) 
                           VALUES ('$order_id', '$total_amt', '$status', NOW())";
            if (!$this->db_query($sqlPayment)) {
                throw new Exception("Failed to insert into payments table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    /**
     * Updates an existing payment record in the database.
     *
     * @param int $payment_id The ID of the payment to update.
     * @param float|null $amt The new amount of the payment.
     * @param string|null $status The new status of the payment.
     * @return void
     * @throws Exception If the update fails.
     */
    public function update_payment($payment_id, $amt = null, $status = null)
    {
        $payment_id = mysqli_real_escape_string($this->db_conn(), $payment_id);

        $this->db_conn()->begin_transaction();
        try {
            $paymentUpdates = [];

            if ($amt !== null) {
                $amt = mysqli_real_escape_string($this->db_conn(), $amt);
                $paymentUpdates[] = "amt = '$amt'";
            }
            if ($status !== null) {
                $status = mysqli_real_escape_string($this->db_conn(), $status);
                $paymentUpdates[] = "status = '$status'";
            }

            if (!empty($paymentUpdates)) {
                $sqlPayment = "UPDATE payments SET " . implode(', ', $paymentUpdates) . " WHERE payment_id = '$payment_id'";
                if (!$this->db_query($sqlPayment)) {
                    throw new Exception("Failed to update payments table");
                }
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    /**
     * Deletes a payment record from the database.
     *
     * @param int $payment_id The ID of the payment to delete.
     * @return void
     * @throws Exception If the deletion fails.
     */
    public function delete_payment($payment_id)
    {
        $payment_id = mysqli_real_escape_string($this->db_conn(), $payment_id);

        $this->db_conn()->begin_transaction();
        try {
            // Delete from payments table
            $sqlPayment = "DELETE FROM payments WHERE payment_id = '$payment_id'";
            if (!$this->db_query($sqlPayment)) {
                throw new Exception("Failed to delete from payments table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    /**
     * Retrieves a payment record by its ID.
     *
     * @param int $payment_id The ID of the payment to retrieve.
     * @return array|false An associative array of the payment data or false if not found.
     */
    public function get_payment_by_id($payment_id)
    {
        $payment_id = mysqli_real_escape_string($this->db_conn(), $payment_id);
        $sql = "SELECT payment_id, order_id, amt, status, payment_date 
                FROM payments 
                WHERE payment_id = '$payment_id'";
        return $this->db_fetch_one($sql);
    }

    /**
     * Retrieves all payments associated with a specific order ID.
     *
     * @param int $order_id The ID of the order.
     * @return array An array of associative arrays containing payment data.
     */
    public function get_payments_by_order_id($order_id)
    {
        $order_id = mysqli_real_escape_string($this->db_conn(), $order_id);
        $sql = "SELECT payment_id, order_id, amt, status, payment_date 
                FROM payments 
                WHERE order_id = '$order_id'
                ORDER BY payment_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieves all payment records from the database.
     *
     * @return array An array of associative arrays containing all payment data.
     */
    public function get_all_payments()
    {
        $sql = "SELECT payment_id, order_id, amt, status, payment_date 
                FROM payments 
                ORDER BY payment_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieves all payments made by a specific customer.
     *
     * @param int $customer_id The ID of the customer.
     * @return array An array of associative arrays containing payment data.
     */
    public function get_payments_by_customer($customer_id)
    {
        $customer_id = mysqli_real_escape_string($this->db_conn(), $customer_id);
        $sql = "SELECT 
                    p.payment_id, 
                    p.order_id, 
                    p.amt, 
                    p.status AS payment_status, 
                    p.payment_date,
                    o.order_date,
                    o.status AS order_status,
                    u.user_name AS customer_name,
                    u.user_email AS customer_email,
                    u.user_contact AS customer_contact,
                    COALESCE(pr.provider_name, ud.user_name) AS service_provider_name
                FROM payments p
                JOIN orders o ON p.order_id = o.order_id
                JOIN users u ON o.customer_id = u.user_id
                LEFT JOIN providers pr ON o.service_provider_id = pr.provider_id
                LEFT JOIN drivers d ON o.service_provider_id = d.driver_id
                LEFT JOIN users ud ON d.driver_id = ud.user_id
                WHERE o.customer_id = '$customer_id'
                ORDER BY p.payment_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieves all payments associated with a specific provider.
     *
     * @param int $provider_id The ID of the provider.
     * @return array An array of associative arrays containing payment data.
     */
    public function get_payments_by_provider($provider_id)
    {
        $provider_id = mysqli_real_escape_string($this->db_conn(), $provider_id);
        $sql = "SELECT 
                    p.payment_id, 
                    p.order_id, 
                    p.amt, 
                    p.status AS payment_status, 
                    p.payment_date,
                    o.order_date,
                    o.status,
                    pr.provider_name,
                    pr.provider_address,
                    u.user_name AS customer_name,
                    u.user_email AS customer_email,
                    u.user_contact AS customer_contact
                FROM payments p
                JOIN orders o ON p.order_id = o.order_id
                JOIN providers pr ON o.service_provider_id = pr.provider_id
                JOIN users u ON o.customer_id = u.user_id
                WHERE o.service_provider_id = '$provider_id'
                ORDER BY p.payment_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieves all payments associated with a specific driver.
     *
     * @param int $driver_id The ID of the driver.
     * @return array An array of associative arrays containing payment data.
     */
    public function get_payments_by_driver($driver_id)
    {
        $driver_id = mysqli_real_escape_string($this->db_conn(), $driver_id);
        $sql = "SELECT 
                    p.payment_id, 
                    p.order_id, 
                    p.amt, 
                    p.status AS payment_status, 
                    p.payment_date,
                    o.order_date,
                    o.status,
                    u.user_name AS customer_name,
                    u.user_email AS customer_email,
                    u.user_contact AS customer_contact
                FROM payments p
                JOIN orders o ON p.order_id = o.order_id
                JOIN drivers d ON o.service_provider_id = d.driver_id
                JOIN users u ON o.customer_id = u.user_id
                WHERE o.service_provider_id = '$driver_id'
                ORDER BY p.payment_date DESC";
        return $this->db_fetch_all($sql);
    }
}
