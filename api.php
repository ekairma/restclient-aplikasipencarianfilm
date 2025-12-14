<?php

// Load .env jika ada (tidak error di GitHub)
$env = [];
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
}

// Ambil API Key (fallback supaya tidak crash di CI)
$apiKey = $env['APIKEY'] ?? getenv('APIKEY') ?? 'DUMMY_KEY';

/**
 * Function untuk call API
 */
function callAPI(string $method, string $url): string
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
    ]);

    $result = curl_exec($curl);

    if ($result === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return json_encode([
            'error' => $error
        ]);
    }

    curl_close($curl);
    return $result;
}