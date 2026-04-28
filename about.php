<?php
session_start();

if(isset($_SESSION["login"])) {
    $login = $_SESSION["login"];
} else {
    $login = "Gościu";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpzone - O nas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
</head>
<body>
   <header>
    <a href="index.php">
      <div class="logo">
        <img src="logo.png" alt="logo " class="icon" />
        <span></span>HelpZone</span>
      </div>
      </a>

      <nav>
        <a href="">Witaj <?php echo $login; ?></a>
        <a href="index.php">Strona główna</a>
        <a href="therapist.php">Nasi terapeuci</a>
        <a href="log.php">Logowanie</a>
        <a href="rej.php">Rejestracja</a>
        <a class="active" href="about.html">O nas</a>
      </nav>
    </header>

    <main class="about">
        <h1>O NAS</h1>

        <div class="about-box">
            <p>Wierzymy, że w świecie pełnym cyfrowego szumu, każdy zasługuje na chwilę ciszy i bezpieczną przestrzeń do rozmowy. HelpZone to nie tylko kolejna platforma – to nasza odpowiedź na rosnącą potrzebę wsparcia emocjonalnego w nowoczesnym wydaniu. Naszym celem było stworzenie miejsca, w którym technologia służy empatii. Chcemy, aby dostęp do pomocy psychologicznej był tak prosty, jak wysłanie wiadomości do przyjaciela, a jednocześnie profesjonalny i całkowicie bezpieczny. HelpZone to strefa wolna od ocen, gdzie Twoje samopoczucie jest jedynym priorytetem.</p>
        </div>
    </main>

    <footer>
      <div>
        <p>Kontakt: 600 100 100</p>
        <p>Lokalizacja firmy: Pikulice</p>
        <p>W razie nagłej potrzeby: 24 godzinny telefon - 112</p>
      </div>

      <button class="hotline">Szacun Dla Kornela Sidełka,aby tak dalej</button>
    </footer>
</body>
</html>