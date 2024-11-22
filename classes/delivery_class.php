<?php
require("../settings/db_class.php");

/**
 * Delivery class to handle all functions related to deliveries
 */
class delivery_class extends db_connection
{
    //--INSERT--//
    public function add_delivery($customer_id, $provider_id, $dropoff_time, $pickup_time = null)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $provider_id = mysqli_real_escape_string($ndb->db_conn(), $provider_id);
        $dropoff_time = mysqli_real_escape_string($ndb->db_conn(), $dropoff_time);
        $pickup_time = $pickup_time ? "'" . mysqli_real_escape_string($ndb->db_conn(), $pickup_time) . "'" : "NULL";
        $delivery_status = 'With Customer';

        $sql = "INSERT INTO `deliveries` (`customer_id`, `provider_id`, `delivery_status`, `pickup_time`, `dropoff_time`) 
                VALUES ('$customer_id', '$provider_id', '$delivery_status', $pickup_time, '$dropoff_time')";
        return $this->db_query($sql);
    }

    //--Driver Accepts Delivery--//
    public function accept_delivery($delivery_id, $driver_id, $cost)
    {
        $ndb = new db_connection();
        $delivery_id = mysqli_real_escape_string($ndb->db_conn(), $delivery_id);
        $driver_id = mysqli_real_escape_string($ndb->db_conn(), $driver_id);
        $cost = mysqli_real_escape_string($ndb->db_conn(), $cost);

        $sql = "UPDATE `deliveries` SET `driver_id` = '$driver_id', `cost` = '$cost' WHERE `delivery_id` = '$delivery_id'";
        return $this->db_query($sql);
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

        $sql = "SELECT * FROM `deliveries` WHERE `driver_id` = '$driver_id'";
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