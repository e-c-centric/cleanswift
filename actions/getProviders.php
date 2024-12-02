<?php
// filepath: /c:/xampp/htdocs/cleanswift/actions/getProviders.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

require("../controllers/provider_controller.php");
require("proxy.php"); // Include proxy.php to use getLocation function

$providerController = new provider_controller();
$providers = $providerController->get_all_providers();

if ($providers) {
    // Iterate through each provider to fetch location details
    foreach ($providers as &$provider) { // Use reference to modify the array directly
        $address = $provider['provider_address'];
        $locationResponse = getLocation($address);

        if (is_array($locationResponse) && isset($locationResponse['found']) && $locationResponse['found']) {
            if (isset($locationResponse['data']['Table'][0])) {
                $locationEntry = $locationResponse['data']['Table'][0];

                // Extract and sanitize fields
                $street = !empty($locationEntry['Street']) && strtoupper($locationEntry['Street']) !== '[UNKNOWN]' ? $locationEntry['Street'] : "Unknown Street";
                $area = !empty($locationEntry['Area']) && strtoupper($locationEntry['Area']) !== '[UNKNOWN]' ? $locationEntry['Area'] : "Unknown Area";
                $district = !empty($locationEntry['District']) && strtoupper($locationEntry['District']) !== '[UNKNOWN]' ? $locationEntry['District'] : "Unknown District";
                $region = !empty($locationEntry['Region']) && strtoupper($locationEntry['Region']) !== '[UNKNOWN]' ? $locationEntry['Region'] : "Unknown Region";

                // Add location details to provider data
                $provider['street'] = $street;
                $provider['area'] = $area;
                $provider['district'] = $district;
                $provider['region'] = $region;
            } else {
                // Invalid response structure
                error_log("Invalid location response structure for address: {$address}");
                $provider['street'] = "Invalid location data";
                $provider['area'] = "Invalid location data";
                $provider['district'] = "Invalid location data";
                $provider['region'] = "Invalid location data";
            }
        } else {
            // Handle error response or 'found' = false
            if (is_array($locationResponse) && isset($locationResponse['status']) && $locationResponse['status'] === 'error') {
                error_log("Error fetching location for address: {$address}. Message: " . $locationResponse['message']);
            } else {
                error_log("Location not found for address: {$address}");
            }
            $provider['street'] = "N/A";
            $provider['area'] = "N/A";
            $provider['district'] = "N/A";
            $provider['region'] = "N/A";
        }
    }
    unset($provider); // Break the reference

    echo json_encode(['status' => 'success', 'data' => $providers]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No providers found']);
}
