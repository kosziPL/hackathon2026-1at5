<?php


session_start();

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "database_name"
);

if(isset($_SESSION["login"])) {
    $login = $_SESSION["login"];
} else {
    $login = "Gościu";
}

if(!$conn)
{
    die("Błąd połączenia z bazą: " . mysqli_connect_error());
}

mysqli_set_charset($conn,"utf8");

if(!isset($_SESSION['id'])){
    header("Location: log.php");
    exit();
}

$id_uzytkownika = $_SESSION['id'];
$message = '';
$message_type = '';

// Tworzenie tabeli jeśli nie istnieje
$create_table = "CREATE TABLE IF NOT EXISTS mood_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_uzytkownika INT NOT NULL,
    data DATE NOT NULL,
    godzina TIME NOT NULL,
    nastroj VARCHAR(50) NOT NULL,
    opis TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_entry (id_uzytkownika, data, godzina),
    FOREIGN KEY (id_uzytkownika) REFERENCES uzytkownicy(id)
)";
mysqli_query($conn, $create_table);

// Dodanie nowego wpisu
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add'){
    $data = $_POST['data'];
    $godzina = $_POST['godzina'];
    $nastroj = $_POST['nastroj'];
    $opis = htmlspecialchars($_POST['opis']);
    
    if(!empty($data) && !empty($godzina) && !empty($nastroj)){
        $sql = "INSERT INTO mood_entries (id_uzytkownika, data, godzina, nastroj, opis) 
                VALUES ('{$id_uzytkownika}', '{$data}', '{$godzina}', '{$nastroj}', '{$opis}')
                ON DUPLICATE KEY UPDATE nastroj = '{$nastroj}', opis = '{$opis}', godzina = '{$godzina}'";
        
        if(mysqli_query($conn, $sql)){
            $message = 'Nastrój został zapisany!';
            $message_type = 'success';
        } else {
            $message = 'Błąd przy zapisie: ' . mysqli_error($conn);
            $message_type = 'error';
        }
    }
}

// Usunięcie wpisu
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete'){
    $entry_id = $_POST['entry_id'];
    $sql = "DELETE FROM mood_entries WHERE id = '{$entry_id}' AND id_uzytkownika = '{$id_uzytkownika}'";
    if(mysqli_query($conn, $sql)){
        $message = 'Wpis został usunięty';
        $message_type = 'success';
    }
}

// Pobranie wpisów dla bieżącego miesiąca
$today = date('Y-m-d');
$first_day = date('Y-m-01');
$last_day = date('Y-m-t');

$sql = "SELECT * FROM mood_entries 
        WHERE id_uzytkownika = '{$id_uzytkownika}' 
        AND data BETWEEN '{$first_day}' AND '{$last_day}'
        ORDER BY data DESC, godzina DESC";

$result = mysqli_query($conn, $sql);
$entries_by_date = [];

while($row = mysqli_fetch_assoc($result)){
    $date = $row['data'];
    if(!isset($entries_by_date[$date])){
        $entries_by_date[$date] = [];
    }
    $entries_by_date[$date][] = $row;
}

// Pobranie wszystkich dni miesiąca
$first_date = new DateTime($first_day);
$last_date = new DateTime($last_day);
$days_in_month = $last_date->format('d');
$first_weekday = $first_date->format('w');

$moods_by_day = [];
foreach($entries_by_date as $date => $entries){
    $day = date('d', strtotime($date));
    // Bierz pierwszą (najnowszą) pozycję dla tego dnia
    $moods_by_day[$day] = $entries[0]['nastroj'];
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpZone - Mood Tracker</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="moodtracker.css">
</head>
<body>
    <header>
      <a href="index.php">
        <div class="logo">
          <img src="logo.png" alt="logo" class="icon" />
          <span>HelpZone</span>
        </div>
      </a>

      <nav>
        <a href="">Witaj <?php echo $login; ?></a>
        <a href="index.php">Strona główna</a>
        <a href="therapist.php">Nasi terapeuci</a>
        <a href="log.php">Logowanie</a>
        <a href="rej.php">Rejestracja</a>
        <a class="active" href="moodtracker.php">Mood Tracker</a>
        <a href="about.php">O nas</a>
      </nav>

    </header>

    <main class="mood-tracker-page">
      <section class="tracker-header">
        <span class="eyebrow">Twój nastrój</span>
        <h1>Mood Tracker - Śledzenie samopoczucia</h1>
        <p>Zanotuj jak się czujesz każdego dnia. Obserwuj wzorce swojego nastroju i emocji przez czas.</p>
      </section>

      <div class="tracker-container">
        <!-- Lewy panel - Formularz -->
        <section class="mood-form-section">
          <h2>Dodaj nastrój</h2>
          
          <?php if($message): ?>
            <div class="message message-<?php echo $message_type; ?>">
              <?php echo $message; ?>
            </div>
          <?php endif; ?>

          <form method="POST" class="mood-form">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
              <label for="data">Data</label>
              <input type="date" id="data" name="data" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
              <label for="godzina">Godzina</label>
              <input type="time" id="godzina" name="godzina" required value="<?php echo date('H:i'); ?>">
            </div>

            <div class="form-group">
              <label for="nastroj">Jak się czujesz?</label>
              <select id="nastroj" name="nastroj" required>
                <option value="">-- Wybierz --</option>
                <option value="😄">Świetnie 😄</option>
                <option value="😊">Dobrze 😊</option>
                <option value="😐">Neutralnie 😐</option>
                <option value="😢">Smutno 😢</option>
                <option value="😠">Zły 😠</option>
                <option value="😰">Zaniepokojony 😰</option>
              </select>
            </div>

            <div class="form-group">
              <label for="opis">Opis (opcjonalnie)</label>
              <textarea id="opis" name="opis" placeholder="Co cię dziś martwi lub cieszy?" maxlength="500"></textarea>
            </div>

            <button type="submit" class="btn-submit">Zapisz nastrój</button>
          </form>
        </section>

        <!-- Prawy panel - Kalendarz -->
        <section class="mood-calendar-section">
          <h2><?php echo date('F Y', strtotime($first_day)); ?></h2>
          
          <div class="calendar-container">
            <!-- Nagłówki dni tygodnia -->
            <div class="weekdays-header">
              <div class="weekday">Pon</div>
              <div class="weekday">Wto</div>
              <div class="weekday">Śro</div>
              <div class="weekday">Czw</div>
              <div class="weekday">Pią</div>
              <div class="weekday">Sob</div>
              <div class="weekday">Nie</div>
            </div>

            <!-- Dni miesiąca -->
            <div class="calendar-grid">
              <?php
              // Puste miejsca na początku
              for($i = 0; $i < $first_weekday; $i++){
                echo '<div class="calendar-day empty"></div>';
              }

              // Dni miesiąca
              for($day = 1; $day <= $days_in_month; $day++){
                $date_str = sprintf("%04d-%02d-%02d", $first_date->format('Y'), $first_date->format('m'), $day);
                $has_entry = isset($moods_by_day[$day]);
                $mood = $has_entry ? $moods_by_day[$day] : '';
                $is_today = ($date_str === $today) ? 'today' : '';
                
                echo '<div class="calendar-day ' . $is_today . '" data-date="' . $date_str . '">';
                echo '<div class="day-number">' . $day . '</div>';
                if($has_entry){
                  echo '<div class="day-mood">' . $mood . '</div>';
                }
                echo '</div>';
              }
              ?>
            </div>
          </div>
        </section>
      </div>

      <!-- Historia wpisów -->
      <section class="mood-history">
        <h2>Historia wpisów</h2>
        
        <?php if(count($entries_by_date) > 0): ?>
          <div class="entries-list">
            <?php foreach($entries_by_date as $date => $entries): ?>
              <div class="date-group">
                <h3 class="date-header"><?php echo date('d.m.Y', strtotime($date)); ?></h3>
                
                <?php foreach($entries as $entry): ?>
                  <div class="entry-card">
                    <div class="entry-header">
                      <span class="entry-time"><?php echo $entry['godzina']; ?></span>
                      <span class="entry-mood"><?php echo htmlspecialchars($entry['nastroj']); ?></span>
                    </div>
                    
                    <?php if(!empty($entry['opis'])): ?>
                      <p class="entry-description"><?php echo htmlspecialchars($entry['opis']); ?></p>
                    <?php endif; ?>
                    
                    <form method="POST" class="entry-delete-form">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="entry_id" value="<?php echo $entry['id']; ?>">
                      <button type="submit" class="btn-delete">Usuń</button>
                    </form>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="no-entries">Brak wpisów. Zacznij od dodania swojego nastroju!</p>
        <?php endif; ?>
      </section>
    </main>

    <footer>
      <div>
        <p>Kontakt: 600 100 100</p>
        <p>Lokalizacja firmy: Pikulice</p>
        <p>W razie nagłej potrzeby: 24 godzinny telefon - 112</p>
      </div>

      <button class="hotline">$265-85-333Z Crisis Hotline →</button>
    </footer>

    <script src="moodtracker.js"></script>
</body>
</html>