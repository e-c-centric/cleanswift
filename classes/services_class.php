<?php
require("../settings/db_class.php");

/**
 * Services class to handle all functions related to services
 */
class services_class extends db_connection
{
    //--INSERT--//
    public function add_service($provider_id, $service_name, $price)
    {
        $ndb = new db_connection();
        $provider_id = mysqli_real_escape_string($ndb->db_conn(), $provider_id);
        $service_name = mysqli_real_escape_string($ndb->db_conn(), $service_name);
        $price = mysqli_real_escape_string($ndb->db_conn(), $price);
        $sql = "INSERT INTO `services`(`provider_id`, `service_name`, `price`) VALUES ('$provider_id', '$service_name', '$price')";
        return $this->db_query($sql);
    }

    //--SELECT--//
    public function get_service_by_id($service_id)
    {
        $ndb = new db_connection();
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $sql = "SELECT * FROM `services` WHERE `service_id` = '$service_id'";
        return $this->db_fetch_one($sql);
    }

    public function get_all_services()
    {
        $sql = "SELECT * FROM `services`";
        return $this->db_fetch_all($sql);
    }

    public function get_all_services_with_details()
    {
        $sql = "SELECT * FROM `services` JOIN `providers` ON services.provider_id = providers.provider_id";
        return $this->db_fetch_all($sql);
    }

    public function get_services_by_provider_id($provider_id)
    {
        $ndb = new db_connection();
        $provider_id = mysqli_real_escape_string($ndb->db_conn(), $provider_id);
        $sql = "SELECT * FROM `services` WHERE `provider_id` = '$provider_id'";
        return $this->db_fetch_all($sql);
    }

    //--UPDATE--//
    public function update_service($service_id, $service_name, $price)
    {
        $ndb = new db_connection();
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $service_name = mysqli_real_escape_string($ndb->db_conn(), $service_name);
        $price = mysqli_real_escape_string($ndb->db_conn(), $price);
        $sql = "UPDATE `services` SET `service_name` = '$service_name', `price` = '$price' WHERE `service_id` = '$service_id'";
        return $this->db_query($sql);
    }

    //--DELETE--//
    public function delete_service($service_id)
    {
        $ndb = new db_connection();
        $service_id = mysqli_real_escape_string($ndb->db_conn(), $service_id);
        $sql = "DELETE FROM `services` WHERE `service_id` = '$service_id'";
        return $this->db_query($sql);
    }
}
