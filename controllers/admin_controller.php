<?php
require_once("../classes/admin_class.php");

class admin_controller
{

    private $admin;

    public function __construct()
    {
        $this->admin = new admin_class();
    }

    public function add_admin($name, $email, $password, $contact)
    {
        try {
            $this->admin->add_admin($name, $email, $password, $contact);
            return "Added successfully.";
        } catch (Exception $e) {
            return "Error adding you: " . $e->getMessage();
        }
    }

    public function update_admin($user_id, $name, $email, $password, $contact)
    {
        try {
            $this->admin->update_admin($user_id, $name, $email, $password, $contact);
            return "Admin updated successfully.";
        } catch (Exception $e) {
            return "Error updating admin: " . $e->getMessage();
        }
    }

    public function delete_admin($user_id)
    {
        try {
            $this->admin->delete_admin($user_id);
            return "Admin deleted successfully.";
        } catch (Exception $e) {
            return "Error deleting admin: " . $e->getMessage();
        }
    }
}
