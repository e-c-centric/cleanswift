<?php
// filepath: /c:/xampp/htdocs/cleanswift/controllers/order_controller.php
include("../classes/order_class.php");

//--INSERT--//
function place_orders_ctr($customer_id, $service_provider_ids)
{
    $order = new order_class();
    return $order->place_orders($customer_id, $service_provider_ids);
}

//--UPDATE--//
function fulfill_order_ctr($order_id)
{
    $order = new order_class();
    return $order->fulfill_order($order_id);
}

function cancel_order_ctr($order_id)
{
    $order = new order_class();
    return $order->cancel_order($order_id);
}

//--SELECT--//
function get_orders_by_customer_ctr($customer_id)
{
    $order = new order_class();
    return $order->get_orders_by_customer($customer_id);
}

function get_orders_by_provider_ctr($provider_id)
{
    $order = new order_class();
    return $order->get_orders_by_provider($provider_id);
}

function get_order_details_ctr($order_id)
{
    $order = new order_class();
    return $order->get_order_details($order_id);
}
