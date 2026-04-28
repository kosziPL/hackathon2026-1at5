<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
$zalogowany = isset($_SESSION["login"]) ? true : false;
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Umów się na konsultacje - HelpZone</title>
    <link rel="stylesheet" href="konsultacje.css">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="logo.png" alt="logo " class="icon" />
        <span>HelpZone</span>
      </div>

      <nav>
        <a href="index.php">Strona główna</a>
        <a href="#">Nasi terapeuci</a>
        <?php if(!$zalogowany): ?>
        <a href="log.php">Logowanie</a>
        <a href="rej.php">Rejestracja</a>
        <?php else: ?>
        <a href="about.php">O nas</a>
        <a href="index.php" style="color: #d9534f;">Wyloguj (<?php echo htmlspecialchars($_SESSION["login"]); ?>)</a>
        <?php endif; ?>
      </nav>
    </header>

    <main class="hero konsultacje-main">
        <div class="form-container">
            <?php if($zalogowany): ?>
                <h1>Umów się na konsultacje</h1>
                <p>Wypełnij formularz, aby zarezerwować termin rozmowy ze specjalistą.</p>
                <form action="zapisz_konsultacje.php" method="POST" class="consultation-form">
                    <label for="temat">Temat rozmowy:</label>
                    <select name="temat" id="temat" required>
                        <option value="">Wybierz temat...</option>
                        <option value="stres">Stres i lęk</option>
                        <option value="depresja">Depresja</option>
                        <option value="relacje">Problemy w relacjach</option>
                        <option value="nauka">Problemy w nauce</option>
                        <option value="inne">Inne</option>
                    </select>

                    <label for="data">Preferowana data:</label>
                    <input type="date" id="data" name="data" required>

                    <label for="godzina">Preferowana godzina:</label>
                    <input type="time" id="godzina" name="godzina" required>

                    <label for="opis">Dodatkowe informacje (opcjonalnie):</label>
                    <textarea id="opis" name="opis" rows="4" placeholder="Opisz krótko z czym chciał(a)byś porozmawiać..."></textarea>

                    <button type="submit" class="blue btn-submit">Wyślij zgłoszenie</button>
                </form>
            <?php else: ?>
                <div class="not-logged-in">
                    <h1>Wymagane logowanie</h1>
                    <p>Aby umówić się na konsultacje z naszym specjalistą, musisz być zalogowany.</p>
                    <div class="login-buttons">
                        <a href="log.php"><button class="blue">Zaloguj się</button></a>
                        <a href="rej.php"><button class="white">Zarejestruj się</button></a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
  </body>
</html>
