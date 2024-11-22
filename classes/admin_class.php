<?php
require_once("../settings/db_class.php");

class admin_class extends db_connection
{

    //-- Add Admin --//
    public function add_admin($name, $email, $password, $contact)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = password_hash(mysqli_real_escape_string($this->db_conn(), $password), PASSWORD_BCRYPT);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "INSERT INTO users (user_name, user_email, user_password, user_contact, role_id) 
                    VALUES ('$name', '$email', '$password', '$contact', 4)";
            if (!$this->db_query($sqlUser)) {
                throw new Exception("Failed to insert into users table");
            }

            $sqlFetchUserId = "SELECT user_id FROM users WHERE user_name = '$name' AND user_email = '$email'";
            $result = $this->db_fetch_one($sqlFetchUserId);
            if (!$result) {
                throw new Exception("Failed to fetch user ID");
            }
            $user_id = $result['user_id'];

            $sqlAdmin = "INSERT INTO admins (admin_id) VALUES ('$user_id')";
            if (!$this->db_query($sqlAdmin)) {
                throw new Exception("Failed to insert into admins table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Update Admin --//
    public function update_admin($user_id, $name, $email, $password, $contact)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = mysqli_real_escape_string($this->db_conn(), $password);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "UPDATE users SET user_name = '$name', user_email = '$email', user_password = '$password', user_contact = '$contact' 
                        WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Delete Admin --//
    public function delete_admin($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $this->db_conn()->begin_transaction();
        try {
            $sqlAdmin = "DELETE FROM admins WHERE admin_id = '$user_id'";
            $this->db_query($sqlAdmin);

            $sqlUser = "DELETE FROM users WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }
}
