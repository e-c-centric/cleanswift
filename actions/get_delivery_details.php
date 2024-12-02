<?php
// Filepath: /c:/xampp/htdocs/cleanswift/actions/get_delivery_details.php

header('Content-Type: application/json');
require_once("../controllers/delivery_controller.php");
require_once("proxy.php");

session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$details = new delivery_controller();

if (isset($_REQUEST['delivery_id'])) {
    // Sanitize the input to prevent potential security issues
    $delivery_id = $_REQUEST['delivery_id'];

    $delivery_details = $details->get_delivery_details($delivery_id);

    error_log("Delivery Details for ID: " . $delivery_id . " - " . json_encode($delivery_details));

    if ($delivery_details === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch delivery details.']);
        exit();
    }

    if (!empty($delivery_details)) {
        // Check if $delivery_details is an array of deliveries
        if (is_array($delivery_details) && count($delivery_details) > 0) {
            // Assuming delivery_id is unique, we take the first (and only) delivery detail
            $delivery = $delivery_details[0];

            // Ensure 'customer_city' and 'provider_address' exist
            if (isset($delivery['customer_city']) && isset($delivery['provider_address'])) {
                $pickup_location = $delivery['customer_city'];
                $dropoff_location = $delivery['provider_address'];
            } else {
                error_log("Missing 'customer_city' or 'provider_address' in delivery details for delivery ID: " . $delivery_id);
                echo json_encode(['status' => 'error', 'message' => 'Incomplete delivery details.']);
                exit();
            }

            // Fetch Pickup Location Data
            $pickupResponse = getLocation($pickup_location);
            error_log("Pickup Location Response for '$pickup_location': " . json_encode($pickupResponse));

            // Fetch Dropoff Location Data
            $dropoffResponse = getLocation($dropoff_location);
            error_log("Dropoff Location Response for '$dropoff_location': " . json_encode($dropoffResponse));

            /**
             * Function to determine the district based on the response.
             *
             * @param array  $response     The response from getLocation function.
             * @param string $locationType Either 'pickup' or 'dropoff' for logging purposes.
             * @param string $address      The address being processed.
             *
             * @return string The determined district.
             */
            function determineDistrict($response, $locationType, $address)
            {
                // Default district value
                $district = "Unknown " . ucfirst($locationType) . " District";

                // Check if response is valid and data is found
                if (is_array($response) && isset($response['found']) && $response['found']) {
                    if (isset($response['data']['Table'][0])) {
                        $entry = $response['data']['Table'][0];

                        // Extract relevant fields with trimming
                        $street = isset($entry['Street']) ? trim($entry['Street']) : '';
                        $area = isset($entry['Area']) ? trim($entry['Area']) : '';
                        $district_field = isset($entry['District']) ? trim($entry['District']) : '';
                        $region = isset($entry['Region']) ? trim($entry['Region']) : '';

                        // Apply the logic: Start with Street and Area
                        if (!empty($area) && strtoupper($street) !== '[UNKNOWN]') {
                            $district = $area;
                        }
                        // If Area is empty or Street is '[UNKNOWN]', use District
                        elseif (!empty($district_field)) {
                            $district = $district_field;
                        }
                        // If District is also empty, use Region
                        elseif (!empty($region)) {
                            $district = $region;
                            error_log("District missing, using Region for {$locationType} location: {$address}");
                        } else {
                            // If all are empty, log the issue
                            error_log("No valid 'Area', 'District', or 'Region' found for {$locationType} location: {$address}");
                            $district = "Location data unavailable for {$locationType}";
                        }
                    } else {
                        // 'Table' key is missing or empty
                        error_log("Invalid location response structure for {$locationType} location: {$address}");
                        $district = "Invalid location response for {$locationType}";
                    }
                } else {
                    // Handle cases where location data is not found
                    if (is_array($response) && isset($response['found']) && !$response['found']) {
                        error_log("Location not found for {$locationType} address: {$address}");
                        $district = "Location not found for {$locationType}";
                    } else {
                        // Handle invalid response formats
                        error_log("Invalid response format for {$locationType} address: {$address}");
                        $district = "Invalid response format for {$locationType} location";
                    }
                }

                return $district;
            }

            // Determine Pickup District
            $pickup_district = determineDistrict($pickupResponse, 'pickup', $pickup_location);

            // Determine Dropoff District
            $dropoff_district = determineDistrict($dropoffResponse, 'dropoff', $dropoff_location);

            // Assign the districts to the delivery details
            $delivery['pickup_district'] = $pickup_district;
            $delivery['dropoff_district'] = $dropoff_district;

            echo json_encode(['status' => 'success', 'data' => $delivery]);
        } else {
            error_log("Delivery details for delivery ID: " . $delivery_id . " are not in the expected format.");
            echo json_encode(['status' => 'error', 'message' => 'Invalid delivery details format.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No delivery details found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Delivery ID not provided.']);
}
