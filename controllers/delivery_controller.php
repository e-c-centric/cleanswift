<?php
require_once("../classes/delivery_class.php");

class delivery_controller
{
    private $delivery;

    public function __construct()
    {
        $this->delivery = new delivery_class();
    }

    // Customer requests a delivery
    public function request_delivery($customer_id, $provider_id, $dropoff_time, $pickup_time = null)
    {
        return $this->delivery->add_delivery($customer_id, $provider_id, $dropoff_time, $pickup_time);
    }

    // Driver accepts the delivery
    public function accept_delivery($delivery_id, $driver_id, $cost)
    {
        return $this->delivery->accept_delivery($delivery_id, $driver_id, $cost);
    }

    // Driver picks up laundry from customer
    public function pick_up_laundry($delivery_id)
    {
        $delivery_status = 'Transit to laundry';
        return $this->delivery->update_delivery_status($delivery_id, $delivery_status);
    }

    // Provider indicates laundry is done, driver returns for it
    public function laundry_ready($delivery_id)
    {
        $delivery_status = 'Transit from laundry';
        return $this->delivery->update_delivery_status($delivery_id, $delivery_status);
    }

    // Customer receives their laundry back
    public function complete_delivery($delivery_id)
    {
        $delivery_status = 'Returned to customer';
        return $this->delivery->update_delivery_status($delivery_id, $delivery_status);
    }

    // Get total cost of all deliveries
    public function get_total_cost()
    {
        return $this->delivery->get_total_cost();
    }

    // Get total cost by driver ID
    public function get_total_cost_by_driver($driver_id)
    {
        return $this->delivery->get_total_cost_by_driver($driver_id);
    }

    // Get total cost by customer ID
    public function get_total_cost_by_customer($customer_id)
    {
        return $this->delivery->get_total_cost_by_customer($customer_id);
    }

    // Get delivery details by ID
    public function get_delivery_by_id($delivery_id)
    {
        return $this->delivery->get_delivery_by_id($delivery_id);
    }

    // Get deliveries by driver ID
    public function get_deliveries_by_driver_id($driver_id)
    {
        return $this->delivery->get_deliveries_by_driver_id($driver_id);
    }

    // Get deliveries by customer ID
    public function get_deliveries_by_customer_id($customer_id)
    {
        return $this->delivery->get_deliveries_by_customer_id($customer_id);
    }
}
?>