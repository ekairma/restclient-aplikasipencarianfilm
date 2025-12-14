<?php include "api.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cari Film Berdasarkan Tahun</title>

<style>
body {
    margin: 0;
    min-height: 100vh;
    font-family: "Segoe UI", Arial, sans-serif;
    background: repeating-linear-gradient(
        90deg,
        #0b0b0b,
        #0b0b0b 20px,
        #111 20px,
        #111 40px
    );
    display: flex;
    justify-content: center;
    align-items: center;
    color: #ffd54a;
}

h2 {
    text-align: center;
    color: #ffd700;
    margin-bottom: 25px;
    text-shadow: 0 0 10px rgba(255,215,0,.6);
}

.card-box {
    background: radial-gradient(circle at top, #3a2a00, #120c00);
    padding: 35px;
    width: 450px;
    border-radius: 16px;
    box-shadow:
        0 0 30px rgba(255,200,0,.35),
        inset 0 0 20px rgba(255,200,0,.15);
}

form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

input {
    background: transparent;
    border: 2px solid #c9a400;
    border-radius: 10px;
    padding: 14px;
    color: #ffd54a;
    font-size: 15px;
    outline: none;
}

button {
    margin-top: 10px;
    background: linear-gradient(90deg, #ff0033, #b30000);
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: #ffd700;
    cursor: pointer;
}

.result {
    margin-top: 25px;
    color: #fff;
}

.movie {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid rgba(255,255,255,.2);
    padding-bottom: 10px;
}

.movie img {
    width: 70px;
    border-radius: 6px;
}
</style>
</head>

<body>

<div class="card-box">
    <h2>🎬 Cari Film</h2>

    <form method="GET">
        <input type="text" name="judul" placeholder="Judul film (misal: Batman)" required>
        <input type="number" name="tahun" placeholder="Tahun (misal: 2010)" required>
        <button type="submit">CARI</button>
    </form>

<?php
if (isset($_GET['judul']) && isset($_GET['tahun'])) {

    $judul = urlencode($_GET['judul']);
    $tahun = $_GET['tahun'];

    $url = "https://www.omdbapi.com/?apikey=$apiKey&s=$judul&y=$tahun&type=movie";

    $response = callAPI("GET", $url);
    $data = json_decode($response, true);

    echo "<div class='result'>";

    if ($data['Response'] === "True") {
        foreach ($data['Search'] as $film) {
            $poster = ($film['Poster'] !== "N/A") ? $film['Poster'] : "https://via.placeholder.com/70x100";

            echo "
            <div class='movie'>
                <img src='$poster'>
                <div>
                    <strong>{$film['Title']}</strong><br>
                    Tahun: {$film['Year']}
                </div>
            </div>
            ";
        }
    } else {
        echo "<p>❌ Film tidak ditemukan</p>";
    }

    echo "</div>";
}
?>

</div>

</body>
</html>
