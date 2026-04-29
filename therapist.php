<?php
session_start();
$zalogowany = isset($_SESSION['id']);
$rola = isset($_SESSION['rola']) ? $_SESSION['rola'] : '';
if(isset($_SESSION["login"])) {
    $login = $_SESSION["login"];
} else {
    $login = "Gościu";
}
$conn = mysqli_connect("localhost", "root", "", "database_name");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HelpZone - Nasi terapeuci</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="therapist.css" />
</head>
<body>
    <header>
      <div class="logo">
        <a href="index.php" style="text-decoration:none; display:flex; align-items:center; gap:10px; color:#080b3f;">
            <img src="logo.png" alt="logo" class="icon" />
            <span>HelpZone</span>
        </a>
      </div>

      <nav>
        <a href="">Witaj <?php echo $login; ?></a>
        <a href="index.php">Strona główna</a>
        <a class="active" href="therapist.php">Nasi terapeuci</a>
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

    </header>

    <main class="therapist-page">
      <section class="intro">
        <div>
          <span class="eyebrow">Nasi specjaliści</span>
          <h1>Profesjonalna pomoc w bezpiecznej przestrzeni</h1>
          <p>Wybierz terapeutę, który najlepiej rozumie Twoje potrzeby. Każdy specjalista w HelpZone oferuje wsparcie indywidualne, dostosowane do Twojego tempa i sytuacji.</p>
        </div>
        <div class="intro-actions">
        </div>
      </section>

      <section class="therapist-grid">
        <?php
        $query = "SELECT * FROM uzytkownicy WHERE rola='admin'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $name = htmlspecialchars($row['kod_uzytkownika']);
                $avatar = strtoupper(substr($name, 0, 2));
                if (strlen($avatar) < 2) $avatar = "NA";
                
                $teacher_id = $row['id'];
                echo '<article class="therapist-card">';
                echo '  <div class="avatar">' . $avatar . '</div>';
                echo '  <h2>' . $name . '</h2>';
                echo '  <p class="role">Szkolny specjalista</p>';
                echo '  <ul>';
                echo '    <li>Wsparcie w kryzysie</li>';
                echo '    <li>Pomoc psychologiczna</li>';
                echo '    <li>Rozwój osobisty</li>';
                echo '  </ul>';
                echo '  <div class="card-footer" style="flex-wrap: wrap; justify-content: flex-end;">';
                echo '    <span style="margin-right: auto;">Nauczyciel</span>';
                echo '    <a href="konsultacje.php" style="text-decoration: none;"><button>Umów się</button></a>';
                echo '    <a href="chat.php?teacher_id=' . $teacher_id . '" style="text-decoration: none;"><button>Napisz</button></a>';
                echo '  </div>';
                echo '</article>';
            }
        } else {
            echo '<p style="grid-column: 1 / -1; text-align: center;">Brak dostępnych specjalistów w tym momencie.</p>';
        }
        ?>
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