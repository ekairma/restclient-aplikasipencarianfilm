<?php
// Load .env secara manual (WORK di XAMPP)
$env = parse_ini_file(__DIR__ . '/.env');

$apiKey = $env['APIKEY'] ?? null;

if (!$apiKey) {
    die("API Key tidak ditemukan di file .env");
}
 laman-tahunfilm
function callAPI($method, $url) {

function callAPI($method, $url)
{
 main
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
    ]);

    $result = curl_exec($curl);

    if ($result === false) {
        die("cURL Error: " . curl_error($curl));
    }

    curl_close($curl);
    return $result;
}