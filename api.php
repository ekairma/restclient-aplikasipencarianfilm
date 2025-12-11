<?php
// API Key OMDb
$apiKey = "a938f6aa";

// Fungsi untuk call API
function callAPI($url) {
    $response = file_get_contents($url);
    return json_decode($response, true);
}
?>
