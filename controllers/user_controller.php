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
}
