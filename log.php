            <?php
session_start();

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "database_name"
);


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = $_POST["login"];
    $haslo = $_POST["haslo"];

    $sql = "SELECT * FROM uzytkownicy  WHERE kod_uzytkownika = '$login'";
    $result = $conn->query($sql);
     if($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($haslo, $user["haslo"])){
            $_SESSION["login"] = $user["kod_uzytkownika"];
            $_SESSION["id"] = $user["id"];
            $_SESSION["rola"] = $user["rola"];
            $_SESSION["zalogowany"] = true;

            header("Location: index.php");
            exit();
            
        }
        else {
            $komunikat =  "Błędne hasło";
        }
        
    } 
    
}

?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeZone</title>
    <link rel="stylesheet" href="log.css">
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
        <a href="index.php">Strona główna </a>
        <a href="therapist.php">Nasi terapeuci</a>
        <a class="active" href="log.php">Logowanie</a>
        <a href="rej.php">Rejestracja</a>
        <a href="about.php">O nas</a>
      </nav>
 
</header>
<main>
    <form action="#" method="post">
        <h2 class="form-title">Logowanie</h2>
        <input placeholder="Login" type="text" name="login" id="login"><br>
        <input placeholder="Haslo" type="password" name="haslo" id="haslo"><br>
        <input id="zaloguj" type="submit" value="Zaloguj">
    </form>
</main>
 
<footer>
    <div>
        <p>Contact: 198-605-Safe Zone</p>
        <p>Contact us licensed therapist</p>
        <p>Emergency? 24hour call: 988</p>
    </div>
 
    <button class="hotline">
        Kocham Programowanie
    </button>
</footer>
 
</body>
</html>