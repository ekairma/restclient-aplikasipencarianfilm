<?php include "api.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Movie / Film Finder</title>

<style>
/* ===== RESET ===== */
* {
    box-sizing: border-box;
}

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
    color: #ffd700;
}

/* ===== CONTAINER ===== */
.container {
    width: 900px;
    padding: 40px;
}

/* ===== HEADER ===== */
.header h1 {
    font-size: 48px;
    letter-spacing: 3px;
    margin: 0;
    color: #ffd700;
    text-shadow:
        0 0 10px rgba(255,215,0,.8),
        0 0 25px rgba(255,215,0,.6);
}

.subtitle {
    margin-top: 10px;
    font-size: 22px;
    color: #ffd54a;
    text-shadow: 0 0 10px rgba(255,215,0,.6);
}

/* ===== SEARCH BOX ===== */
.search-box {
    margin-top: 40px;
}

.search-box input {
    width: 100%;
    padding: 20px 26px;
    font-size: 20px;
    border-radius: 16px;
    border: none;
    outline: none;
    color: #ffd700;
    background: linear-gradient(135deg, #ff4500, #b30000);
    box-shadow:
        0 0 25px rgba(255,0,0,.8),
        inset 0 0 10px rgba(255,255,255,.2);
}

.search-box input::placeholder {
    color: #ffe066;
    letter-spacing: 1px;
}

/* ===== RESULT ===== */
.movie-list {
    margin-top: 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 25px;
}

/* ===== CARD ===== */
.movie-card {
    background: radial-gradient(circle at top, #3a2a00, #120c00);
    border-radius: 14px;
    padding: 15px;
    text-align: center;
    box-shadow:
        0 0 20px rgba(255,200,0,.35),
        inset 0 0 10px rgba(255,200,0,.15);
    transition: .2s;
}

.movie-card:hover {
    transform: scale(1.05);
}

.movie-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}

.movie-card h4 {
    margin: 8px 0 4px;
    font-size: 16px;
    color: #ffd700;
}

.movie-card span {
    font-size: 14px;
    color: #ffe066;
}

/* ===== NOT FOUND ===== */
.not-found {
    grid-column: 1 / -1;
    text-align: center;
    font-size: 20px;
    color: #ff7777;
    text-shadow: 0 0 10px rgba(255,0,0,.7);
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🎬 MOVIE / FILM FINDER</h1>
        <p class="subtitle">Selamat datang di Movie Finder!</p>
    </div>

    <div class="search-box">
        <form method="get">
            <input
                type="text"
                name="judul"
                placeholder="Cari Film Berdasarkan Judul"
                value="<?= isset($_GET['judul']) ? htmlspecialchars($_GET['judul']) : '' ?>"
                onkeyup="this.form.submit()"
                autofocus
            >
        </form>
    </div>

    <div class="movie-list">
        <?php
        if (!empty($_GET['judul'])) {
            $judul = urlencode($_GET['judul']);
            $url = "https://www.omdbapi.com/?apikey=$APIKEY&s=$judul";
            $response = callAPI($url);
            $data = json_decode($response, true);

            if ($data['Response'] === "True") {
                foreach ($data['Search'] as $film) {
                    echo "<div class='movie-card'>";
                    echo "<img src='{$film['Poster']}' alt='Poster'>";
                    echo "<h4>{$film['Title']}</h4>";
                    echo "<span>{$film['Year']}</span>";
                    echo "</div>";
                }
            } else {
                echo "<p class='not-found'>Film tidak ditemukan</p>";
            }
        }
        ?>
    </div>

</div>

</body>
</html>
