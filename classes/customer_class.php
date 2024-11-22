<?php
require_once("../settings/db_class.php");

class customer_class extends db_connection
{

    //-- Add Customer --//
    public function add_customer($name, $email, $password, $contact, $country, $city, $image = null)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $email = mysqli_real_escape_string($this->db_conn(), $email);
        $password = password_hash(mysqli_real_escape_string($this->db_conn(), $password), PASSWORD_BCRYPT);
        $contact = mysqli_real_escape_string($this->db_conn(), $contact);
        $country = mysqli_real_escape_string($this->db_conn(), $country);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $image = mysqli_real_escape_string($this->db_conn(), $image);

        $this->db_conn()->begin_transaction();
        try {
            $sqlUser = "INSERT INTO users (user_name, user_email, user_password, user_contact, role_id) 
                    VALUES ('$name', '$email', '$password', '$contact', 1)";
            if (!$this->db_query($sqlUser)) {
                throw new Exception("Failed to insert into users table");
            }

            $sqlFetchUserId = "SELECT user_id FROM users WHERE user_name = '$name' AND user_email = '$email'";
            $result = $this->db_fetch_one($sqlFetchUserId);
            if (!$result) {
                throw new Exception("Failed to fetch user ID");
            }
            $user_id = $result['user_id'];

            $sqlCustomer = "INSERT INTO customers (customer_id, customer_country, customer_city, customer_image) 
                        VALUES ('$user_id', '$country', '$city', '$image')";
            if (!$this->db_query($sqlCustomer)) {
                throw new Exception("Failed to insert into customers table");
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Update Customer --//
    public function update_customer($user_id, $name = null, $email = null, $password = null, $contact = null, $country = null, $city = null, $image = null)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $this->db_conn()->begin_transaction();
        try {
            // Update users table
            $userUpdates = [];
            if ($name !== null) {
                $name = mysqli_real_escape_string($this->db_conn(), $name);
                $userUpdates[] = "user_name = '$name'";
            }
            if ($email !== null) {
                $email = mysqli_real_escape_string($this->db_conn(), $email);
                $userUpdates[] = "user_email = '$email'";
            }
            if ($password !== null) {
                $password = mysqli_real_escape_string($this->db_conn(), $password);
                $userUpdates[] = "user_password = '$password'";
            }
            if ($contact !== null) {
                $contact = mysqli_real_escape_string($this->db_conn(), $contact);
                $userUpdates[] = "user_contact = '$contact'";
            }

            if (!empty($userUpdates)) {
                $sqlUser = "UPDATE users SET " . implode(', ', $userUpdates) . " WHERE user_id = '$user_id'";
                $this->db_query($sqlUser);
            }

            // Update customers table
            $customerUpdates = [];
            if ($country !== null) {
                $country = mysqli_real_escape_string($this->db_conn(), $country);
                $customerUpdates[] = "customer_country = '$country'";
            }
            if ($city !== null) {
                $city = mysqli_real_escape_string($this->db_conn(), $city);
                $customerUpdates[] = "customer_city = '$city'";
            }
            if ($image !== null) {
                $image = mysqli_real_escape_string($this->db_conn(), $image);
                $customerUpdates[] = "customer_image = '$image'";
            }

            if (!empty($customerUpdates)) {
                $sqlCustomer = "UPDATE customers SET " . implode(', ', $customerUpdates) . " WHERE customer_id = '$user_id'";
                $this->db_query($sqlCustomer);
            }

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Delete Customer --//
    public function delete_customer($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);

        $this->db_conn()->begin_transaction();
        try {
            $sqlCustomer = "DELETE FROM customers WHERE customer_id = '$user_id'";
            $this->db_query($sqlCustomer);

            $sqlUser = "DELETE FROM users WHERE user_id = '$user_id'";
            $this->db_query($sqlUser);

            $this->db_conn()->commit();
        } catch (Exception $e) {
            $this->db_conn()->rollback();
            throw $e;
        }
    }

    //-- Get Customer By ID --//
    public function get_customer_by_id($user_id)
    {
        $user_id = mysqli_real_escape_string($this->db_conn(), $user_id);
        $sql = "SELECT u.user_id, u.user_name, u.user_email, u.user_contact, c.customer_country, c.customer_city, c.customer_image 
                FROM users u 
                JOIN customers c ON u.user_id = c.customer_id 
                WHERE u.user_id = '$user_id'";
        return $this->db_fetch_one($sql);
    }
}
