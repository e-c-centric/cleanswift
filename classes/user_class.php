<?php
require_once("../settings/db_class.php");

class user_class extends db_connection
{

    //-- User Login --//
    public function login($email, $password)
    {
        $email = mysqli_real_escape_string($this->db_conn(), $email);

        $sql = "SELECT user_id, user_name, user_password, role_id FROM users WHERE user_email = '$email'";
        $result = $this->db_fetch_one($sql);

        if ($result && password_verify($password, $result['user_password'])) {
            return [
                'user_id' => $result['user_id'],
                'name' => $result['user_name'],
                'role_id' => $result['role_id']
            ];
        } else {
            return null;
        }
    }
}
