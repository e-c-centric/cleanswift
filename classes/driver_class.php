<?php
require_once("../settings/db_class.php");

class driver_class extends db_connection
{

    //-- Add Driver --//
    public function add_driver($name, $email, $password, $contact, $license_number, $vehicle_number, $vehicle_type, $vehicle_class)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = password_hash(mysqli_real_escape_string($this->db_conn(), $password), PASSWORD_BCRYPT);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        $license_number = mysqli_real_escape_string($this->db_conn(), $license_number);
        $vehicle_number = mysqli_real_escape_string($this->db_conn(), $vehicle_number);
        $vehicle_type = mysqli_real_escape_string($this->db_conn(), $vehicle_type);
        $vehicle_class = mysqli_real_escape_string($this->db_conn(), $vehicle_class);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "INSERT INTO users (user_name, user_email, user_password, user_contact, role_id) 
                    VALUES ('$name', '$email', '$password', '$contact', 2)";
            if (!$this->db_query($sqlUser)) {
                throw new Exception("Failed to insert into users table");
            }

            $sqlFetchUserId = "SELECT user_id FROM users WHERE user_name = '$name' AND user_email = '$email'";
            $result = $this->db_fetch_one($sqlFetchUserId);
            if (!$result) {
                throw new Exception("Failed to fetch user ID");
            }
            $user_id = $result['user_id'];

            $sqlVehicle = "INSERT INTO vehicles (vehicle_number, vehicle_type, vehicle_class) 
                           VALUES ('$vehicle_number', '$vehicle_type', '$vehicle_class')";
            if (!$this->db_query($sqlVehicle)) {
                throw new Exception("Failed to insert into vehicles table");
            }

            $sqlFetchVehicleId = "SELECT vehicle_id FROM vehicles WHERE vehicle_number = '$vehicle_number'";
            $vehicleResult = $this->db_fetch_one($sqlFetchVehicleId);
            if (!$vehicleResult) {
                throw new Exception("Failed to fetch vehicle ID");
            }
            $vehicle_id = $vehicleResult['vehicle_id'];

            $sqlDriver = "INSERT INTO drivers (driver_id, license_number, vehicle_id) 
                          VALUES ('$user_id', '$license_number', '$vehicle_id')";
            if (!$this->db_query($sqlDriver)) {
                throw new Exception("Failed to insert into drivers table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Update Driver --//
    public function update_driver($user_id, $name, $email, $password, $contact, $license_number, $vehicle_id, $vehicle_class)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = mysqli_real_escape_string($this->db_conn(), $password);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        $license_number = mysqli_real_escape_string($this->db_conn(), $license_number);
        $vehicle_id = mysqli_real_escape_string($this->db_conn(), $vehicle_id);
        $vehicle_class = mysqli_real_escape_string($this->db_conn(), $vehicle_class);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "UPDATE users SET user_name = '$name', user_email = '$email', user_password = '$password', user_contact = '$contact' 
                        WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $sqlDriver = "UPDATE drivers SET license_number = '$license_number', vehicle_id = '$vehicle_id' 
                          WHERE driver_id = '$user_id'";
            $this->db_query($sqlDriver);

            $sqlVehicle = "UPDATE vehicles SET vehicle_class = '$vehicle_class' WHERE vehicle_id = '$vehicle_id'";
            $this->db_query($sqlVehicle);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Delete Driver --//
    public function delete_driver($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $this->db_conn()->begin_transaction();
        try {
            $sqlDriver = "DELETE FROM drivers WHERE driver_id = '$user_id'";
            $this->db_query($sqlDriver);

            $sqlUser = "DELETE FROM users WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }
}