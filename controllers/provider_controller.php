<?php
require_once("../classes/provider_class.php");

class provider_controller
{

    private $provider;

    public function __construct()
    {
        $this->provider = new provider_class();
    }

    public function add_provider($name, $email, $password, $contact, $provider_name, $provider_address)
    {
        try {
            $this->provider->add_provider($name, $email, $password, $contact, $provider_name, $provider_address);
            return "Added successfully.";
        } catch (Exception $e) {
            return "Error adding you: " . $e->getMessage();
        }
    }

    public function update_provider($user_id, $name, $contact, $provider_name, $provider_address)
    {
        try {
            $this->provider->update_provider($user_id, $name, $contact, $provider_name, $provider_address);
            return "Provider updated successfully.";
        } catch (Exception $e) {
            return "Error updating provider: " . $e->getMessage();
        }
    }

    public function delete_provider($user_id)
    {
        try {
            $this->provider->delete_provider($user_id);
            return "Provider deleted successfully.";
        } catch (Exception $e) {
            return "Error deleting provider: " . $e->getMessage();
        }
    }

    public function get_provider_by_id($user_id)
    {
        return $this->provider->get_provider_by_id($user_id);
    }

    public function get_all_providers()
    {
        return $this->provider->get_all_providers();
    }
}
