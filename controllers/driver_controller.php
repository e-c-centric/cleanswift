<?php
require_once("../classes/driver_class.php");

class driver_controller
{

    private $driver;

    public function __construct()
    {
        $this->driver = new driver_class();
    }

    public function add_driver($name, $email, $password, $contact, $license_number, $vehicle_number, $vehicle_type, $vehicle_class)
    {
        try {
            $this->driver->add_driver($name, $email, $password, $contact, $license_number, $vehicle_number, $vehicle_type, $vehicle_class);
            return "Registered successfully.";
        } catch (Exception $e) {
            return "Error adding you: " . $e->getMessage();
        }
    }

    public function update_driver($user_id, $name, $email, $contact, $license_number)
    {
        try {
            $this->driver->update_driver_info($user_id, $name, $email, $contact, $license_number);
            return "Driver updated successfully.";
        } catch (Exception $e) {
            return "Error updating driver: " . $e->getMessage();
        }
    }

    public function update_vehicle($user_id, $vehicle_number, $vehicle_type, $vehicle_class)
    {
        try {
            $this->driver->update_vehicle_info($user_id, $vehicle_number, $vehicle_type, $vehicle_class);
            return "Vehicle updated successfully.";
        } catch (Exception $e) {
            return "Error updating vehicle: " . $e->getMessage();
        }
    }

    public function delete_driver($user_id)
    {
        try {
            $this->driver->delete_driver($user_id);
            return "Driver deleted successfully.";
        } catch (Exception $e) {
            return "Error deleting driver: " . $e->getMessage();
        }
    }

    public function get_profile_by_id($user_id)
    {
        try {
            return $this->driver->get_profile_by_id($user_id);
        } catch (Exception $e) {
            return "Error getting profile: " . $e->getMessage();
        }
    }
}
