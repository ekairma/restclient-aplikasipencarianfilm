<?php include "api.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cari Berdasarkan Tipe</title>

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

/* ===== JUDUL ===== */
h2 {
    text-align: center;
    color: #ffd700;
    margin-bottom: 25px;
    letter-spacing: 1px;
    text-shadow: 0 0 10px rgba(255,215,0,.6);
}

/* ===== CARD ===== */
.card-box {
    background: radial-gradient(circle at top, #3a2a00, #120c00);
    padding: 35px;
    width: 420px;
    border-radius: 16px;
    box-shadow:
        0 0 30px rgba(255,200,0,.35),
        inset 0 0 20px rgba(255,200,0,.15);
}

/* ===== FORM ===== */
form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ===== INPUT & SELECT ===== */
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

select:focus,
input:focus {
    box-shadow: 0 0 18px rgba(255,215,0,.7);
}

/* ===== BUTTON ===== */
button {
    margin-top: 10px;
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
    transition: .2s;
}

button:hover {
    transform: scale(1.03);
    box-shadow:
        0 0 30px rgba(255,0,0,.9),
        inset 0 0 12px rgba(255,255,255,.25);
}

/* ===== HASIL ===== */
.result {
    margin-top: 25px;
    color: #fff;
}

.result p {
    padding: 6px 0;
    border-bottom: 1px solid rgba(255,255,255,.15);
}
</style>
</head>

<body>

<div class="card-box">
    <h2>🎬 Cari Berdasarkan Tipe</h2>

    <form method="GET">
        <select name="tipe" required>
            <option value="">Pilih Tipe</option>
            <option value="movie">Movie</option>
            <option value="series">Series</option>
        </select>

        <input type="text" name="judul" placeholder="Keyword (misal: Batman)" required>

        <button type="submit">CARI</button>
    </form>

<?php
if (!empty($_GET['tipe']) && !empty($_GET['judul'])) {
    $tipe  = $_GET['tipe'];
    $judul = urlencode($_GET['judul']);

    $url = "https://www.omdbapi.com/?apikey=$apiKey&s=$judul&type=$tipe";
    $response = callAPI("GET", $url);
    $data = json_decode($response, true);

    echo "<div class='result'>";

    if (isset($data['Response']) && $data['Response'] === "True") {
        foreach ($data['Search'] as $film) {
            echo "<p>{$film['Title']} ({$film['Year']})</p>";
        }
    } else {
        echo "<p>{$data['Error']}</p>";
    }

    echo "</div>";
}
?>

</div>
</body>
</html>
