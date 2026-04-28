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

$sql = "SELECT c.id AS chat_id, c.id_uzytkownika, MAX(w.data_wyslania) as last_msg 
        FROM chaty c 
        LEFT JOIN wiadomosci w ON c.id = w.chat_id 
        WHERE c.id_nauczyciela = '$teacher_id' 
        GROUP BY c.id 
        ORDER BY last_msg DESC";
$wynik = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>HelpZone - Panel Nauczyciela</title>
<link rel="stylesheet" href="style.css">
<style>
.hz-wrapper { max-width: 800px; margin: 40px auto; font-family: 'Inter', sans-serif; }
.hz-header { background: #5141b9; padding: 20px; color: white; text-align: center; border-radius: 12px 12px 0 0; font-size: 24px; font-weight: bold; }
.hz-box { background: white; border-radius: 0 0 12px 12px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.hz-title { margin-top: 0; color: #333; font-size: 22px; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
.chat-list { display: flex; flex-direction: column; gap: 15px; }
.chat-card { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #f4f6f9; border-radius: 12px; border-left: 5px solid #6fd6f7; transition: transform 0.2s; }
.chat-card:hover { transform: translateY(-2px); background: #ebf0f5; }
.chat-info h3 { margin: 0; color: #080b3f; font-size: 18px; }
.chat-info p { margin: 5px 0 0; color: #666; font-size: 14px; }
.chat-btn { background: #5141b9; color: white; text-decoration: none; padding: 10px 20px; border-radius: 20px; font-weight: bold; transition: background 0.3s; }
.chat-btn:hover { background: #3f3192; }
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
</header>

<div class="hz-wrapper">
<div class="hz-box" style="border-radius: 12px;">
<h2 class="hz-title">Wiadomości od uczniów</h2>

<div class="chat-list">
<?php
if(mysqli_num_rows($wynik) == 0) {
    echo "<p style='text-align:center; color:#999;'>Nie masz jeszcze żadnych aktywnych konwersacji z uczniami.</p>";
} else {
    while($row = mysqli_fetch_assoc($wynik)) {
        // Generujemy anonimowy identyfikator dla ucznia bazując na id
        $anon_name = "Anonimowy Uczeń #" . str_pad($row['id_uzytkownika'], 3, "0", STR_PAD_LEFT);
        $last_date = $row['last_msg'] ? date('d.m.Y H:i', strtotime($row['last_msg'])) : "Brak wiadomości";
        
        echo '<div class="chat-card">';
        echo '  <div class="chat-info">';
        echo '    <h3>' . $anon_name . '</h3>';
        echo '    <p>Ostatnia aktywność: ' . $last_date . '</p>';
        echo '  </div>';
        echo '  <a href="admin_chat_view.php?chat_id=' . $row['chat_id'] . '" class="chat-btn">Otwórz czat</a>';
        echo '</div>';
    }
}
?>
</div>

</div>
</div>

</body>
</html>
