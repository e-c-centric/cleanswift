<?php
require_once("../settings/db_class.php");

class vehicle_options_class extends db_connection
{

    public function get_vehicle_options()
    {
        $sql = "SELECT * FROM vehicle_options";
        return $this->db_fetch_all($sql);
    }

    public function get_vehicle_option($id)
    {
        $id = mysqli_real_escape_string($this->db_conn(), $id);

        $sql = "SELECT * FROM vehicle_options WHERE option_id = '$id'";
        return $this->db_fetch_one($sql);
    }

    public function add_vehicle_option($option_name, $option_description)
    {
        $option_name = mysqli_real_escape_string($this->db_conn(), $option_name);
        $option_description = mysqli_real_escape_string($this->db_conn(), $option_description);

        $sql = "INSERT INTO vehicle_options (option_description) VALUES ('$option_description')";
        return $this->db_query($sql);
    }

    public function update_vehicle_option($id, $option_name, $option_description)
    {
        $id = mysqli_real_escape_string($this->db_conn(), $id);
        $option_name = mysqli_real_escape_string($this->db_conn(), $option_name);
        $option_description = mysqli_real_escape_string($this->db_conn(), $option_description);

        $sql = "UPDATE vehicle_options SET option_description = '$option_description' WHERE option_id = '$id'";
        return $this->db_query($sql);
    }

    public function delete_vehicle_option($id)
    {
        $id = mysqli_real_escape_string($this->db_conn(), $id);

        $sql = "DELETE FROM vehicle_options WHERE option_id = '$id'";
        return $this->db_query($sql);
    }

    public function get_vehicle_option_by_description($option_description)
    {
        $option_description = mysqli_real_escape_string($this->db_conn(), $option_description);

        $sql = "SELECT * FROM vehicle_options WHERE option_description = '$option_description'";
        return $this->db_fetch_one($sql);
    }
}
