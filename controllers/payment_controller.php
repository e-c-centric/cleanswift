<?php

require_once("../classes/payment_class.php");

class PaymentController
{
    private $payment;

    public function __construct()
    {
        $this->payment = new payment_class();
    }

    /**
     * Controller function to add a new payment.
     *
     * @param int $order_id
     * @param float $amt
     * @param string $status
     * @return void
     */
    public function add_payment_ctr($order_id)
    {
        $this->payment->add_payment($order_id);
    }

    /**
     * Controller function to update an existing payment.
     *
     * @param int $payment_id
     * @param float|null $amt
     * @param string|null $status
     * @return void
     */
    public function update_payment_ctr($payment_id, $amt = null, $status = null)
    {
        return $this->payment->update_payment($payment_id, $amt, $status);
    }

    /**
     * Controller function to delete a payment.
     *
     * @param int $payment_id
     * @return void
     */
    public function delete_payment_ctr($payment_id)
    {
        $this->payment->delete_payment($payment_id);
    }

    /**
     * Controller function to get a payment by ID.
     *
     * @param int $payment_id
     * @return array|false
     */
    public function get_payment_by_id_ctr($payment_id)
    {
        return $this->payment->get_payment_by_id($payment_id);
    }

    /**
     * Controller function to get payments by Order ID.
     *
     * @param int $order_id
     * @return array
     */
    public function get_payments_by_order_id_ctr($order_id)
    {
        return $this->payment->get_payments_by_order_id($order_id);
    }

    /**
     * Controller function to get all payments.
     *
     * @return array
     */
    public function get_all_payments_ctr()
    {
        return $this->payment->get_all_payments();
    }

    /**
     * Controller function to get payments by Customer ID.
     *
     * @param int $customer_id
     * @return array
     */
    public function get_payments_by_customer_ctr($customer_id)
    {
        return $this->payment->get_payments_by_customer($customer_id);
    }

    /**
     * Controller function to get payments by Provider ID.
     *
     * @param int $provider_id
     * @return array
     */
    public function get_payments_by_provider_ctr($provider_id)
    {
        return $this->payment->get_payments_by_provider($provider_id);
    }

    /**
     * Controller function to get payments by Driver ID.
     *
     * @param int $driver_id
     * @return array
     */
    public function get_payments_by_driver_ctr($driver_id)
    {
        return $this->payment->get_payments_by_driver($driver_id);
    }

    
}
