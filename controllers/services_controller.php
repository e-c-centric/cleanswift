<?php
include("../classes/services_class.php");

//--INSERT--//
function add_service_ctr($provider_id, $service_name, $price) {
    $service = new services_class();
    return $service->add_service($provider_id, $service_name, $price);
}

//--SELECT--//
function get_service_by_id_ctr($service_id) {
    $service = new services_class();
    return $service->get_service_by_id($service_id);
}

function get_all_services_ctr() {
    $service = new services_class();
    return $service->get_all_services();
}

function get_all_services_with_details_ctr() {
    $service = new services_class();
    return $service->get_all_services_with_details();
}

function get_services_by_provider_id_ctr($provider_id) {
    $service = new services_class();
    return $service->get_services_by_provider_id($provider_id);
}

//--UPDATE--//
function update_service_ctr($service_id, $service_name, $price) {
    $service = new services_class();
    return $service->update_service($service_id, $service_name, $price);
}

//--DELETE--//
function delete_service_ctr($service_id) {
    $service = new services_class();
    return $service->delete_service($service_id);
}
?>