<?php
require("../settings/db_class.php");

/**
 * Cart class to handle all functions related to the cart
 */
class cart_class extends db_connection
{
    //--INSERT--//
    public function add_to_cart($customer_id, $service_id, $quantity)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $quantity = mysqli_real_escape_string($ndb->db_conn(), $quantity);

        // Check if the item already exists in the cart
        $check_sql = "SELECT * FROM `cart` WHERE `customer_id` = '$customer_id' AND `service_id` = '$service_id'";
        $existing_item = $this->db_fetch_one($check_sql);

        if ($existing_item) {
            // If the item exists, increment the quantity
            $new_quantity = $existing_item['quantity'] + $quantity;
            $update_sql = "UPDATE `cart` SET `quantity` = '$new_quantity' WHERE `customer_id` = '$customer_id' AND `service_id` = '$service_id'";
            return $this->db_query($update_sql);
        } else {
            // If the item does not exist, add it to the cart
            $sql = "INSERT INTO `cart`(`customer_id`, `service_id`, `quantity`, `added_at`) VALUES ('$customer_id', '$service_id', '$quantity', NOW())";
            return $this->db_query($sql);
        }
    }

    //--SELECT--//
    public function get_cart_by_customer_id($customer_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $sql = "SELECT c.service_id, s.service_name, p.provider_name, p.provider_id, p.provider_address, c.quantity, c.added_at, s.price
                FROM `cart` c 
                JOIN `services` s ON c.service_id = s.service_id 
                JOIN `providers` p ON s.provider_id = p.provider_id 
                WHERE c.customer_id = '$customer_id'";
        return $this->db_fetch_all($sql);
    }

    public function get_cart_item($customer_id, $service_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $sql = "SELECT c.service_id, s.service_name, p.provider_name, p.provider_address, c.quantity, c.added_at, s.price 
                FROM `cart` c 
                JOIN `services` s ON c.service_id = s.service_id 
                JOIN `providers` p ON s.provider_id = p.provider_id 
                WHERE c.customer_id = '$customer_id' AND c.service_id = '$service_id'";
        return $this->db_fetch_one($sql);
    }

    //--UPDATE--//
    public function update_cart_item($customer_id, $service_id, $quantity)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $quantity = mysqli_real_escape_string($ndb->db_conn(), $quantity);
        $sql = "UPDATE `cart` SET `quantity` = '$quantity' WHERE `customer_id` = '$customer_id' AND `service_id` = '$service_id'";
        return $this->db_query($sql);
    }

    //--DELETE--//
    public function delete_cart_item($customer_id, $service_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $sql = "DELETE FROM `cart` WHERE `customer_id` = '$customer_id' AND `service_id` = '$service_id'";
        return $this->db_query($sql);
    }

    public function clear_cart($customer_id)
    {
        $ndb = new db_connection();
        $customer_id = mysqli_real_escape_string($ndb->db_conn(), $customer_id);
        $sql = "DELETE FROM `cart` WHERE `customer_id` = '$customer_id'";
        return $this->db_query($sql);
    }
}
