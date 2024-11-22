<?php
require_once("../classes/vehicle_options_class.php");

class v_options_controller
{

    private $vehicle_options;

    public function __construct()
    {
        $this->vehicle_options = new vehicle_options_class();
    }

    public function get_vehicle_options()
    {
        try {
            $result = $this->vehicle_options->get_vehicle_options();
            if ($result) {
                return $result;
            } else {
                return "No vehicle options found.";
            }
        } catch (Exception $e) {
            return "Error getting vehicle options: " . $e->getMessage();
        }
    }

    public function get_vehicle_option($id)
    {
        try {
            $result = $this->vehicle_options->get_vehicle_option($id);
            if ($result) {
                return $result;
            } else {
                return "Vehicle option not found.";
            }
        } catch (Exception $e) {
            return "Error getting vehicle option: " . $e->getMessage();
        }
    }

    public function add_vehicle_option($option_name, $option_description)
    {
        try {
            $result = $this->vehicle_options->add_vehicle_option($option_name, $option_description);
            if ($result) {
                return "Vehicle option added successfully.";
            } else {
                return "Error adding vehicle option.";
            }
        } catch (Exception $e) {
            return "Error adding vehicle option: " . $e->getMessage();
        }
    }

    public function update_vehicle_option($id, $option_name, $option_description)
    {
        try {
            $result = $this->vehicle_options->update_vehicle_option($id, $option_name, $option_description);
            if ($result) {
                return "Vehicle option updated successfully.";
            } else {
                return "Error updating vehicle option.";
            }
        } catch (Exception $e) {
            return "Error updating vehicle option: " . $e->getMessage();
        }
    }

    public function delete_vehicle_option($id)
    {
        try {
            $result = $this->vehicle_options->delete_vehicle_option($id);
            if ($result) {
                return "Vehicle option deleted successfully.";
            } else {
                return "Error deleting vehicle option.";
            }
        } catch (Exception $e) {
            return "Error deleting vehicle option: " . $e->getMessage();
        }
    }
}
