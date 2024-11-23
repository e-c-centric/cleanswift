<?php
require_once("../classes/customer_class.php");

class customer_controller
{

    private $customer;

    public function __construct()
    {
        $this->customer = new customer_class();
    }

    public function add_customer($name, $email, $password, $contact, $country, $city, $image = null)
    {
        try {
            $this->customer->add_customer($name, $email, $password, $contact, $country, $city, $image);
            return "Registered successfully.";
        } catch (Exception $e) {
            return "Error adding you: " . $e->getMessage();
        }
    }

    public function update_customer($user_id, $name = null, $contact = null, $country = null, $city = null, $image = null)
    {
        try {
            $this->customer->update_customer($user_id, $name, $contact, $country, $city, $image);
            return ["status" => "success", "message" => "Profile updated successfully."];
        } catch (Exception $e) {
            return ["status" => "error", "message" => "Error updating profile: " . $e->getMessage()];
        }
    }

    public function delete_customer($user_id)
    {
        try {
            $this->customer->delete_customer($user_id);
            return "Customer deleted successfully.";
        } catch (Exception $e) {
            return "Error deleting customer: " . $e->getMessage();
        }
    }

    public function get_customer_by_id($user_id)
    {
        try {
            return $this->customer->get_customer_by_id($user_id);
        } catch (Exception $e) {
            return "Error getting customer: " . $e->getMessage();
        }
    }
}
