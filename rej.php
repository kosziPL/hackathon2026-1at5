<?php
session_start();

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "database_name"
);

if(!$conn)
{
    die("Błąd połączenia z bazą");
}

mysqli_set_charset($conn,"utf8");

$blad = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $login = trim($_POST['login']);
    $haslo = $_POST['haslo'];
    $haslo2 = $_POST['haslo2'];

    if(empty($login) || empty($haslo) || empty($haslo2))
    {
        $blad = "Uzupełnij wszystkie pola.";
    }
    elseif(strlen($haslo) < 6)
    {
        $blad = "Hasło musi mieć co najmniej 6 znaków.";
    }
    elseif($haslo !== $haslo2)
    {
        $blad = "Hasła nie są identyczne.";
    }
    else
    {
        // Sprawdzenie czy login już istnieje
        $check = mysqli_prepare($conn, "SELECT id FROM uzytkownicy WHERE kod_uzytkownika = ?");
        mysqli_stmt_bind_param($check, "s", $login);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if(mysqli_stmt_num_rows($check) > 0)
        {
            $blad = "Ten login jest już zajęty.";
        }
        else
        {
            $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO uzytkownicy(kod_uzytkownika, haslo, rola) VALUES(?, ?, 'user')");
            mysqli_stmt_bind_param($stmt, "ss", $login, $haslo_hash);

            if(mysqli_stmt_execute($stmt))
            {
                $_SESSION['login'] = $login;
                $_SESSION['zalogowany'] = true;

                header("Location: index.php");
                exit();
            }
            else
            {
                $blad = "Błąd rejestracji. Spróbuj ponownie.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rejestracja - HelpZone</title>
<link rel="stylesheet" href="rej.css">
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
        <a href="index.php">Strona główna</a>
        <a href="therapist.html">Nasi terapeuci</a>
        <a href="log.php">Logowanie</a>
        <a class="active" href="rej.php">Rejestracja</a>
        <a href="about.php">O nas</a>
    </nav>

</header>

<main>
    <form method="POST">
        <h2 class="form-title">Rejestracja</h2>

        <?php if($blad != ""): ?>
            <div class="error-msg"><?php echo htmlspecialchars($blad); ?></div>
        <?php endif; ?>

        <input id="login" type="text" name="login" placeholder="Login">
        <input id="haslo" type="password" name="haslo" placeholder="Hasło">
        <input id="haslo2" type="password" name="haslo2" placeholder="Potwierdź hasło">
        <button id="zaloguj" type="submit">Załóż konto</button>
        <p class="login-link">Masz już konto? <a href="log.php">Zaloguj się</a></p>
    </form>
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

