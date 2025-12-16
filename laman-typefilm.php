<?php
include "api.php"; // pastikan $apiKey & function callAPI() ada di sini
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cari Film Berdasarkan Tipe</title>

<style>
/* ===== BACKGROUND ===== */
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

/* ===== CARD ===== */
.card-box {
    background: radial-gradient(circle at top, #3a2a00, #120c00);
    padding: 35px;
    width: 480px;
    border-radius: 16px;
    box-shadow:
        0 0 30px rgba(255,200,0,.35),
        inset 0 0 20px rgba(255,200,0,.15);
}

/* ===== JUDUL ===== */
h2 {
    text-align: center;
    color: #ffd700;
    margin-bottom: 25px;
    letter-spacing: 1px;
    text-shadow: 0 0 10px rgba(255,215,0,.6);
}

/* ===== FORM ===== */
form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ===== INPUT ===== */
select,
input[type="text"] {
    background: transparent;
    border: 2px solid #c9a400;
    border-radius: 10px;
    padding: 14px;
    color: #ffd54a;
    font-size: 15px;
    outline: none;
    box-shadow: 0 0 12px rgba(255,200,0,.25);
}

select option {
    color: #000;
}

/* ===== BUTTON ===== */
button {
    background: linear-gradient(90deg, #ff0033, #b30000);
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-size: 18px;
    font-weight: bold;
    color: #ffd700;
    letter-spacing: 2px;
    cursor: pointer;
    box-shadow:
        0 0 20px rgba(255,0,0,.7),
        inset 0 0 10px rgba(255,255,255,.2);
}

/* ===== HASIL ===== */
.result {
    margin-top: 25px;
}

/* ===== ITEM ===== */
.result-item {
    display: flex;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,.15);
}

.result-item img {
    width: 80px;
    height: 110px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(255,215,0,.4);
}

.result-info {
    font-size: 14px;
    color: #fff;
}

/* ===== DETAIL ===== */
.detail-btn {
    margin-top: 6px;
    background: transparent;
    border: 1px solid #ffd54a;
    color: #ffd54a;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
}

.detail-btn:hover {
    background: #ffd54a;
    color: #000;
}

.detail-box {
    display: none;
    margin-top: 8px;
    font-size: 12px;
    line-height: 1.4;
    color: #ddd;
    background: rgba(0,0,0,.35);
    padding: 8px;
    border-radius: 8px;
}

.rating {
    color: #ffd700;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="card-box">
<h2>🎬 Cari Film Berdasarkan Tipe</h2>

<form method="GET">
    <select name="tipe">
        <option value="">Semua Tipe</option>
        <option value="movie">Movie</option>
        <option value="series">Series</option>
    </select>

    <input type="text" name="judul" placeholder="Masukkan judul film..." required>

    <button type="submit">CARI</button>
</form>

<?php
if (!empty($_GET['judul'])) {

    $judul = urlencode($_GET['judul']);
    $tipe  = $_GET['tipe'] ?? '';

    $typeParam = $tipe ? "&type=$tipe" : "";

    $url = "https://www.omdbapi.com/?apikey=$apiKey&s=$judul$typeParam";
    $response = callAPI("GET", $url);
    $data = json_decode($response, true);

    echo "<div class='result'>";

    if (isset($data['Response']) && $data['Response'] === "True") {

        foreach ($data['Search'] as $film) {

            $poster = ($film['Poster'] !== "N/A")
                ? $film['Poster']
                : "https://via.placeholder.com/80x110?text=No+Image";

            /* === DETAIL FILM === */
            $imdbID = $film['imdbID'];
            $detailUrl = "https://www.omdbapi.com/?apikey=$apiKey&i=$imdbID&plot=short";
            $detailResponse = callAPI("GET", $detailUrl);
            $detail = json_decode($detailResponse, true);

            $plot    = $detail['Plot'] ?? 'Deskripsi tidak tersedia';
            $genre   = $detail['Genre'] ?? '-';
            $runtime = $detail['Runtime'] ?? '-';
            $rating  = ($detail['imdbRating'] !== "N/A")
                ? "⭐ {$detail['imdbRating']}/10"
                : "⭐ N/A";

            $detailId = "detail_" . $imdbID;

            echo "
            <div class='result-item'>
                <img src='$poster'>
                <div class='result-info'>
                    <strong>{$film['Title']}</strong><br>
                    Tahun : {$film['Year']}<br>
                    Tipe  : {$film['Type']}<br>
                    <span class='rating'>$rating</span><br>

                    <button class='detail-btn' onclick=\"toggleDetail('$detailId')\">
                        Detail
                    </button>

                    <div class='detail-box' id='$detailId'>
                        <strong>Genre:</strong> $genre<br>
                        <strong>Durasi:</strong> $runtime<br>
                        <strong>Deskripsi:</strong><br>
                        $plot
                    </div>
                </div>
            </div>
            ";
        }

    } else {
        echo "<p>{$data['Error']}</p>";
    }

    echo "</div>";
}
?>

</div>

<script>
function toggleDetail(id) {
    const el = document.getElementById(id);
    el.style.display = (el.style.display === "block") ? "none" : "block";
}
</script>

</body>
</html>
