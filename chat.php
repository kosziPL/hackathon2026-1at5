<?php
session_start();

$conn = mysqli_connect(
    "s135.cyber-folks.pl",
    "rotsu81_mleczko",
    "wB7vz)zTw4d--HlY",
    "rotsu81_hackathon"
);

if(!isset($_SESSION['id']))
{
    header("Location: log.php");
    exit();
}

$id_uzytkownika = $_SESSION['id'];
$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;

if ($teacher_id === 0) {
    die("Brak podanego identyfikatora nauczyciela. Wróć do listy terapeutów.");
}

// Pobranie imienia nauczyciela
$sql_teacher = "SELECT kod_uzytkownika FROM uzytkownicy WHERE id='$teacher_id' AND rola='admin'";
$res_teacher = mysqli_query($conn, $sql_teacher);
if(!$res_teacher) {
    die("Błąd SQL (teacher): " . mysqli_error($conn));
}
if(mysqli_num_rows($res_teacher) == 0) {
    die("Nie znaleziono takiego nauczyciela.");
}
$teacher_row = mysqli_fetch_assoc($res_teacher);
$teacher_name = htmlspecialchars($teacher_row['kod_uzytkownika']);

// Sprawdzenie czy czat istnieje
$sql = "SELECT * FROM chaty WHERE id_uzytkownika='$id_uzytkownika' AND id_nauczyciela='$teacher_id' LIMIT 1";
$wynik = mysqli_query($conn, $sql);
if(!$wynik) {
    die("Błąd SQL (chaty): " . mysqli_error($conn));
}

if(mysqli_num_rows($wynik) == 0)
{
    $ins = mysqli_query($conn, "INSERT INTO chaty(id_uzytkownika, id_nauczyciela) VALUES('$id_uzytkownika', '$teacher_id')");
    if(!$ins) die("Błąd INSERT: " . mysqli_error($conn));
    $chat_id = mysqli_insert_id($conn);
}
else
{
    $row = mysqli_fetch_assoc($wynik);
    $chat_id = $row['id'];
}

if(isset($_POST['wiadomosc']))
{
    $tresc = htmlspecialchars($_POST['wiadomosc']);

    if(!empty($tresc))
    {
        mysqli_query($conn, "
        INSERT INTO wiadomosci(chat_id, nadawca, tresc)
        VALUES('$chat_id', 'user', '$tresc')
        ");

        header("Location: chat.php?teacher_id=" . $teacher_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>HelpZone - Czat</title>
<link rel="stylesheet" href="style.css">
<style>
.hz-wrapper { max-width: 800px; margin: 40px auto; font-family: 'Inter', sans-serif; }
.hz-header { background: #5141b9; padding: 20px; color: white; text-align: center; border-radius: 12px 12px 0 0; font-size: 24px; font-weight: bold; }
.hz-chat-box { background: white; border-radius: 0 0 12px 12px; padding: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.hz-title { margin-top: 0; color: #333; font-size: 18px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
.hz-messages { height: 400px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; padding-right: 10px; }
.hz-user { align-self: flex-end; background: #6fd6f7; color: #080b3f; padding: 12px 18px; border-radius: 18px 18px 0 18px; max-width: 70%; }
.hz-admin { align-self: flex-start; background: #f0f0f0; color: #333; padding: 12px 18px; border-radius: 18px 18px 18px 0; max-width: 70%; }
.hz-form { display: flex; gap: 10px; }
.hz-form input { flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
.hz-form button { background: #5141b9; color: white; border: none; padding: 12px 24px; border-radius: 20px; cursor: pointer; font-weight: bold; transition: background 0.3s; }
.hz-form button:hover { background: #3f3192; }
.back-btn { display: inline-block; margin-bottom: 15px; color: #5141b9; text-decoration: none; font-weight: bold; }
</style>
</head>
<body style="background: #f4f6f9;">

<div class="hz-wrapper">
<header class="hz-header">
    HelpZone
</header>
<section class="hz-chat-box">
<a href="therapist.php" class="back-btn">&larr; Wróć do terapeutów</a>
<h2 class="hz-title">Czat z nauczycielem: <?php echo $teacher_name; ?></h2>

<div class="hz-messages" id="messages-container">
<?php
$wiad = mysqli_query($conn,"
SELECT * FROM wiadomosci
WHERE chat_id='$chat_id'
ORDER BY data_wyslania ASC, id ASC
");

if(mysqli_num_rows($wiad) == 0) {
    echo "<p style='text-align:center; color:#999; margin-top:50px;'>Brak wiadomości. Rozpocznij konwersację.</p>";
}

while($m = mysqli_fetch_assoc($wiad))
{
    if($m['nadawca']=="user")
    {
        echo "<div class='hz-user'><span>".htmlspecialchars($m['tresc'])."</span></div>";
    }
    else
    {
        echo "<div class='hz-admin'><span>".htmlspecialchars($m['tresc'])."</span></div>";
    }
}
?>
</div>

<form method="POST" class="hz-form">
<input type="text" name="wiadomosc" placeholder="Napisz wiadomość..." required autocomplete="off" autofocus>
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