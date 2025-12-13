<?php

$apiKey = getenv('APIKEY');

if (!$apiKey) {
    die("API KEY tidak ditemukan");
}

// contoh penggunaan
$url = "https://api.example.com/data?key=" . $apiKey;

