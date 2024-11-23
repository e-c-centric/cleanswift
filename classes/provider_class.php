<?php
require_once("../settings/db_class.php");

class provider_class extends db_connection
{

    //-- Add Provider --//
    public function add_provider($name, $email, $password, $contact, $provider_name, $provider_address)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = password_hash(mysqli_real_escape_string($this->db_conn(), $password), PASSWORD_BCRYPT);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        $provider_name = mysqli_real_escape_string($this->db_conn(), $provider_name);
        $provider_address = mysqli_real_escape_string($this->db_conn(), $provider_address);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "INSERT INTO users (user_name, user_email, user_password, user_contact, role_id) 
                    VALUES ('$name', '$email', '$password', '$contact', 3)";
            if (!$this->db_query($sqlUser)) {
                throw new Exception("Failed to insert into users table");
            }

            $sqlFetchUserId = "SELECT user_id FROM users WHERE user_name = '$name' AND user_email = '$email'";
            $result = $this->db_fetch_one($sqlFetchUserId);
            if (!$result) {
                throw new Exception("Failed to fetch user ID");
            }
            $user_id = $result['user_id'];

            $sqlProvider = "INSERT INTO providers (provider_id, provider_name, provider_address) 
                        VALUES ('$user_id', '$provider_name', '$provider_address')";
            if (!$this->db_query($sqlProvider)) {
                throw new Exception("Failed to insert into providers table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Update Provider --//
    public function update_provider($user_id, $name, $contact, $provider_name, $provider_address)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        $provider_name = mysqli_real_escape_string($this->db_conn(), $provider_name);
        $provider_address = mysqli_real_escape_string($this->db_conn(), $provider_address);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "UPDATE users SET user_name = '$name', user_contact = '$contact' 
                    WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $sqlProvider = "UPDATE providers SET provider_name = '$provider_name', provider_address = '$provider_address' 
                        WHERE provider_id = '$user_id'";
            $this->db_query($sqlProvider);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Delete Provider --//
    public function delete_provider($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $this->db_conn()->begin_transaction();
        try {
            $sqlProvider = "DELETE FROM providers WHERE provider_id = '$user_id'";
            $this->db_query($sqlProvider);

            $sqlUser = "DELETE FROM users WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Get Provider By ID --//
    public function get_provider_by_id($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);
        $sql = "SELECT * FROM providers JOIN users ON providers.provider_id = users.user_id WHERE providers.provider_id = '$user_id'";
        return $this->db_fetch_one($sql);
    }

    //-- Get All Providers --//
    public function get_all_providers()
    {
        $sql = "SELECT * FROM providers JOIN users ON providers.provider_id = users.user_id";
        return $this->db_fetch_all($sql);
    }
}
