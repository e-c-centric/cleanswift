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

    public function change_password($user_id, $old_password, $new_password)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $sql = "SELECT user_password FROM users WHERE user_id = '$user_id'";
        $result = $this->db_fetch_one($sql);

        if ($result && password_verify($old_password, $result['user_password'])) {
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET user_password = '$new_password' WHERE user_id = '$user_id'";
            $this->db_query($sql);
            return true;
        } else {
            return false;
        }
    }
}