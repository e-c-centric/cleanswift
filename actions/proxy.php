<?php
// Filepath: /c:/xampp/htdocs/cleanswift/actions/proxy.php

/**
 * Retrieves location data for a given address by communicating with the Ghanapost GPS service.
 *
 * @param string $address The address to retrieve location data for.
 * @return array|string Returns the response from the GPS service on success or an error array on failure.
 */
function getLocation($address)
{
    // Initialize cURL session
    $curl = curl_init();

    // Construct the POST fields as a raw string
    $postFields = "address=" . urlencode($address);

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ghanapostgps.sperixlabs.org/get-location",
        CURLOPT_RETURNTRANSFER => true,              // Return the response as a string
        CURLOPT_ENCODING => "",                      // Handle all encodings
        CURLOPT_MAXREDIRS => 10,                     // Maximum number of redirects
        CURLOPT_TIMEOUT => 30,                       // Timeout after 30 seconds
        CURLOPT_FOLLOWLOCATION => true,              // Follow redirects
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Use HTTP 1.1
        CURLOPT_CUSTOMREQUEST => "POST",             // Use POST method
        CURLOPT_POSTFIELDS => $postFields,           // Attach POST data directly
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded" // Set content type
        ),
    ));

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        curl_close($curl);
        return [
            'status' => 'error',
            'message' => "cURL Error: $error_msg"
        ];
    }

    // Get HTTP response status code
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Handle non-successful HTTP status codes
    if ($httpCode < 200 || $httpCode >= 300) {
        return [
            'status' => 'error',
            'message' => "HTTP Error: Received status code $httpCode."
        ];
    }

    // Optionally, decode JSON response if applicable
    $decodedResponse = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $decodedResponse;
    }

    // If response is not JSON, return it as is
    return $response;
}

// Conditionally handle POST requests only if this file is accessed directly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    // Check if 'address' parameter is set and not empty
    if (isset($_POST['address']) && !empty(trim($_POST['address']))) {
        $address = trim($_POST['address']);
        $result = getLocation($address);

        // Determine response based on the result
        if (is_array($result) && isset($result['status']) && $result['status'] === 'error') {
            // Return error as JSON
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            // Assume successful response; set appropriate content type
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $result
            ]);
        }
    } else {
        // Return error for missing or empty 'address' parameter
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'The "address" parameter is missing or empty.'
        ]);
    }
}
