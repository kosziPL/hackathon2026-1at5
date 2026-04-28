<?php
session_start();

$conn = mysqli_connect(
    "s135.cyber-folks.pl",
    "rotsu81_mleczko",
    "wB7vz)zTw4d--HlY",
    "rotsu81_hackathon"
);

if(!isset($_SESSION['id']) || $_SESSION['rola'] !== 'admin')
{
    header("Location: index.php");
    exit();
}

$teacher_id = $_SESSION['id'];
$chat_id = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

if ($chat_id === 0) {
    die("Brak podanego identyfikatora czatu.");
}

// Sprawdzenie czy czat należy do tego nauczyciela
$sql_check = "SELECT id_uzytkownika FROM chaty WHERE id='$chat_id' AND id_nauczyciela='$teacher_id'";
$res_check = mysqli_query($conn, $sql_check);
if(!$res_check) {
    die("Błąd SQL (sprawdzenie): " . mysqli_error($conn));
}
if(mysqli_num_rows($res_check) == 0) {
    die("Brak dostępu do tego czatu.");
}
$chat_info = mysqli_fetch_assoc($res_check);
$anon_name = "Anonimowy Uczeń #" . str_pad($chat_info['id_uzytkownika'], 3, "0", STR_PAD_LEFT);

if(isset($_POST['wiadomosc']))
{
    $tresc = htmlspecialchars($_POST['wiadomosc']);

    if(!empty($tresc))
    {
        mysqli_query($conn, "
        INSERT INTO wiadomosci(chat_id, nadawca, tresc)
        VALUES('$chat_id', 'admin', '$tresc')
        ");

        header("Location: admin_chat_view.php?chat_id=" . $chat_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>HelpZone - Czat z uczniem</title>
<link rel="stylesheet" href="style.css">
<style>
.hz-wrapper { max-width: 800px; margin: 40px auto; font-family: 'Inter', sans-serif; }
.hz-header { background: #5141b9; padding: 20px; color: white; text-align: center; border-radius: 12px 12px 0 0; font-size: 24px; font-weight: bold; }
.hz-chat-box { background: white; border-radius: 0 0 12px 12px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.hz-title { margin-top: 0; color: #333; font-size: 18px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
.hz-messages { height: 400px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; padding-right: 10px; }
.hz-admin { align-self: flex-end; background: #6fd6f7; color: #080b3f; padding: 12px 18px; border-radius: 18px 18px 0 18px; max-width: 70%; }
.hz-user { align-self: flex-start; background: #f0f0f0; color: #333; padding: 12px 18px; border-radius: 18px 18px 18px 0; max-width: 70%; }
.hz-form { display: flex; gap: 10px; }
.hz-form input { flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
.hz-form button { background: #5141b9; color: white; border: none; padding: 12px 24px; border-radius: 20px; cursor: pointer; font-weight: bold; transition: background 0.3s; }
.hz-form button:hover { background: #3f3192; }
.back-btn { display: inline-block; margin-bottom: 15px; color: #5141b9; text-decoration: none; font-weight: bold; }
</style>
</head>
<body style="background: #f4f6f9;">

<header>
    <div class="logo">
    <img src="logo.png" alt="logo" class="icon" />
    <span>HelpZone</span>
    </div>
    <nav>
    <a href="index.php">Strona główna</a>
    <a href="admin_chats.php" class="active">Twoje Czaty</a>
    <a href="wyloguj.php" style="color:#d9534f;">Wyloguj</a>
    </nav>
    <div class="menu">☰</div>
</header>

<div class="hz-wrapper">
<section class="hz-chat-box" style="border-radius: 12px;">
<a href="admin_chats.php" class="back-btn">&larr; Wróć do listy czatów</a>
<h2 class="hz-title">Rozmowa: <?php echo $anon_name; ?></h2>

<div class="hz-messages" id="messages-container">
<?php
$wiad = mysqli_query($conn,"
SELECT * FROM wiadomosci
WHERE chat_id='$chat_id'
ORDER BY data_wyslania ASC, id ASC
");

if(mysqli_num_rows($wiad) == 0) {
    echo "<p style='text-align:center; color:#999; margin-top:50px;'>Brak wiadomości od tego ucznia.</p>";
}

while($m = mysqli_fetch_assoc($wiad))
{
    // Dla nauczyciela, jego wiadomości to "admin", wiadomości ucznia to "user"
    if($m['nadawca']=="admin")
    {
        echo "<div class='hz-admin'><span>".htmlspecialchars($m['tresc'])."</span></div>";
    }
    else
    {
        echo "<div class='hz-user'><span>".htmlspecialchars($m['tresc'])."</span></div>";
    }
}
?>
</div>

<form method="POST" class="hz-form">
<input type="text" name="wiadomosc" placeholder="Odpowiedz uczniowi..." required autocomplete="off" autofocus>
<button type="submit">Wyślij</button>
</form>

</section>
</div>

<script>
    // Auto-scroll to bottom of messages
    var messagesContainer = document.getElementById("messages-container");
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
</script>
</body>
</html>
