<?php
include("../classes/cart_class.php");

//--INSERT--//
function add_to_cart_ctr($customer_id, $service_id, $quantity)
{
    $cart = new cart_class();
    return $cart->add_to_cart($customer_id, $service_id, $quantity);
}

//--SELECT--//
function get_cart_by_customer_id_ctr($customer_id)
{
    $cart = new cart_class();
    return $cart->get_cart_by_customer_id($customer_id);
}

function get_cart_item_ctr($customer_id, $service_id)
{
    $cart = new cart_class();
    return $cart->get_cart_item($customer_id, $service_id);
}

//--UPDATE--//
function update_cart_item_ctr($customer_id, $service_id, $quantity)
{
    $cart = new cart_class();
    return $cart->update_cart_item($customer_id, $service_id, $quantity);
}

//--DELETE--//
function delete_cart_item_ctr($customer_id, $service_id)
{
    $cart = new cart_class();
    return $cart->delete_cart_item($customer_id, $service_id);
}

function clear_cart_ctr($customer_id)
{
    $cart = new cart_class();
    return $cart->clear_cart($customer_id);
}
