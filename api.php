<?php
// Load .env
$env = parse_ini_file(__DIR__ . '/.env');

$APIKEY = $env['APIKEY'] ?? null;

if (!$APIKEY) {
    die("API Key tidak ditemukan di file .env");
}

function callAPI($url)
{
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);

    if ($result === false) {
        die("cURL Error: " . curl_error($curl));
    }

    curl_close($curl);
    return $result;
}
