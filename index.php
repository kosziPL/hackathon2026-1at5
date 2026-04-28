<?php
session_start();
$zalogowany = isset($_SESSION['id']);
$rola = isset($_SESSION['rola']) ? $_SESSION['rola'] : '';

if(isset($_SESSION["login"])) {
    $login = $_SESSION["login"];
} else {
    $login = "Gościu";
}
?>





<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SafeZone</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <header>
    <a href="index.php">
      <div class="logo">
        <img src="logo.png" alt="logo " class="icon" />
        <span>HelpZone</span>
      </div>
      </a>


      <nav>
        <a href="">Witaj <?php echo $login; ?></a>
        <a class="active" href="index.php">Strona główna</a>
        <a href="therapist.php">Nasi terapeuci</a>
        <?php if($zalogowany): ?>
            <?php if($rola === 'admin'): ?>
                <a href="admin_chats.php">Czat z uczniami</a>
            <?php endif; ?>
            <a href="wyloguj.php">Wyloguj</a>
        <?php else: ?>
            <a href="log.php">Logowanie</a>
            <a href="rej.php">Rejestracja</a>
        <?php endif; ?>
        <a href="about.php">O nas</a>
      </nav>

      <!-- <div class="menu">☰</div> -->
    </header>

    <main class="hero">
      <section class="left">
        <h1>Jesteś w bezpiecznej strefie</h1>
        <p>Nam możesz powiedzieć wszystko</p>

        <div class="image-box">
          <img src="image1.jpg" alt="Psychologist chat" />
          <img src="image2.jpg" alt="Psychologist chat" />
        </div>
      </section>

      <section class="buttons">
        <a href="therapist.php" style="text-decoration: none;"><button class="white">Zacznij rozmawiać</button></a>
        <a href="konsultacje.php" style="text-decoration: none;"><button class="blue">Umów się na sesję</button></a>
        <a href="moodtracker.php"><button class="orange">Mood Tracker</button></a>
      </section>
    </main>

    <footer>
      <div>
        <p>Kontakt: 600 100 100</p>
        <p>Lokalizacja firmy: Pikulice</p>
        <p>W razie nagłej potrzeby: 24 godzinny telefon - 112</p>
      </div>
    </footer>
  </body>
</html>
