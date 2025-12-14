<?php
include "api.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Movie / Film Finder</title>

<style>
/* ================= RESET ================= */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ================= BODY & BACKGROUND ================= */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #0a0a0a;
    min-height: 100vh;
    padding: 40px 20px;
    overflow-x: hidden;
    perspective: 1000px;
    color: #ffd700;
}

/* Animated Cinema Background */
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse at top, rgba(255,215,0,.15), transparent 50%),
        radial-gradient(ellipse at bottom, rgba(220,20,60,.15), transparent 50%),
        linear-gradient(180deg, #0a0a0a, #1a0a0a, #0a0a0a);
    animation: backgroundPulse 8s ease-in-out infinite;
    z-index: -2;
}

/* Film Strip */
body::after {
    content: '';
    position: fixed;
    inset: 0;
    background: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 50px,
        rgba(255,215,0,.03) 50px,
        rgba(255,215,0,.03) 60px
    );
    animation: filmStrip 20s linear infinite;
    pointer-events: none;
    z-index: -1;
}

@keyframes backgroundPulse {
    0%,100% { opacity: 1 }
    50% { opacity: .8 }
}

@keyframes filmStrip {
    from { transform: translateX(0); }
    to { transform: translateX(50%); }
}

/* ================= CONTAINER ================= */
.container {
    max-width: 1100px;
    margin: auto;
    text-align: center;
}

/* ================= HEADER ================= */
h1 {
    font-size: 3.5em;
    letter-spacing: 3px;
    margin-bottom: 15px;
    text-shadow:
        0 0 10px rgba(255,215,0,.8),
        0 0 20px rgba(255,215,0,.6),
        0 0 30px rgba(255,215,0,.4);
    animation: neonFlicker 3s infinite, fadeInDown 1s ease;
}

.subtitle {
    font-size: 1.3em;
    margin-bottom: 35px;
    animation: fadeInDown 1.2s ease;
}

@keyframes neonFlicker {
    0%,100% {
        text-shadow:
            0 0 10px rgba(255,215,0,.8),
            0 0 20px rgba(255,215,0,.6),
            0 0 30px rgba(255,215,0,.4);
    }
    50% {
        text-shadow:
            0 0 15px rgba(255,215,0,1),
            0 0 30px rgba(255,215,0,.8),
            0 0 45px rgba(255,215,0,.6);
    }
}

/* ================= SEARCH FORM ================= */
.search-box form {
    max-width: 600px;
    margin: auto;
    background: linear-gradient(135deg, rgba(26,10,10,.95), rgba(10,10,10,.95));
    padding: 35px;
    border-radius: 20px;
    box-shadow:
        0 25px 70px rgba(0,0,0,.8),
        0 0 40px rgba(220,20,60,.3);
    animation: fadeInUp 1s ease;
}

.search-box input {
    width: 100%;
    padding: 18px 22px;
    border-radius: 12px;
    border: 2px solid rgba(255,215,0,.3);
    font-size: 1.1em;
    background: rgba(0,0,0,.6);
    color: #ffd700;
    transition: .4s;
}

.search-box input::placeholder {
    color: rgba(255,215,0,.5);
}

.search-box input:focus {
    outline: none;
    transform: translateY(-3px) scale(1.02);
    box-shadow:
        0 0 0 4px rgba(255,215,0,.2),
        0 0 25px rgba(255,215,0,.5);
}

/* ================= MOVIE LIST ================= */
.movie-list {
    margin-top: 50px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 30px;
}

/* ================= MOVIE CARD ================= */
.movie-card {
    position: relative;
    background: radial-gradient(circle at top, #3a2a00, #120c00);
    padding: 15px;
    border-radius: 15px;
    box-shadow:
        0 15px 40px rgba(0,0,0,.6),
        inset 0 0 10px rgba(255,215,0,.2);
    transform-style: preserve-3d;
    animation: fadeInUp .8s ease backwards;
    transition: .4s;
}

.movie-card::before {
    content: '';
    position: absolute;
    inset: -50%;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255,215,0,.3),
        transparent 70%
    );
    transform: rotate(45deg);
    transition: .6s;
}

.movie-card:hover::before {
    left: 100%;
}

.movie-card:hover {
    transform: translateY(-10px) rotateX(6deg) scale(1.05);
    box-shadow:
        0 30px 60px rgba(0,0,0,.7),
        0 0 30px rgba(255,215,0,.4);
}

.movie-card img {
    width: 100%;
    height: 260px;
    object-fit: cover;
    border-radius: 10px;
}

.movie-card h4 {
    margin-top: 12px;
}

.movie-card span {
    color: #ffe066;
}

/* ================= NOT FOUND ================= */
.not-found {
    grid-column: 1 / -1;
    font-size: 1.4em;
    color: #ff7777;
    animation: fadeInUp 1s ease;
}

/* ================= ANIMATIONS ================= */
@keyframes fadeInDown {
    from { opacity:0; transform:translateY(-40px) rotateX(-20deg); }
    to { opacity:1; transform:none; }
}

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(40px) scale(.95); }
    to { opacity:1; transform:none; }
}
</style>
</head>

<body>

<div class="container">
    <h1>🎬 MOVIE FINDER</h1>
    <p class="subtitle">Cari film favoritmu </p>

    <div class="search-box">
        <form method="get">
            <input
                type="text"
                name="judul"
                placeholder="Cari judul film..."
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
            $url = "https://www.omdbapi.com/?apikey=$apiKey&s=$judul";
            $response = callAPI("GET",$url);
            $data = json_decode($response, true);


            if ($data['Response'] === "True") {
                $i = 0;
                foreach ($data['Search'] as $film) {
                    echo "<div class='movie-card' style='animation-delay:".($i*0.1)."s'>";
                    echo "<img src='{$film['Poster']}'>";
                    echo "<h4>{$film['Title']}</h4>";
                    echo "<span>{$film['Year']}</span>";
                    echo "</div>";
                    $i++;
                }
            } else {
                echo "<p class='not-found'>🎞 Film tidak ditemukan</p>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>
