<?php
require_once("../classes/user_class.php");

class user_controller
{

    private $user;

    public function __construct()
    {
        $this->user = new user_class();
    }

    public function login($email, $password)
    {
        try {
            $result = $this->user->login($email, $password);
            if ($result) {
                return $result;
            } else {
                return "Invalid email or password.";
            }
        } catch (Exception $e) {
            return "Error logging in: " . $e->getMessage();
        }
    }

    public function change_password($user_id, $old_password, $new_password)
    {
        try {
            $result = $this->user->change_password($user_id, $old_password, $new_password);
            if ($result) {
                return "Password changed successfully.";
            } else {
                return "Failed to change password.";
            }
        } catch (Exception $e) {
            return "Error changing password: " . $e->getMessage();
        }
    }
}