<!-- Pagina di gioco -->
<?php 
session_start();
if (!isset($_SESSION["user"])) {   
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/lighton.png">
    <link rel="stylesheet" href="../css/game.css">
    <title>Lights Out</title>
</head>
<body>
    <div id="stats">
        <p id="user"><?php echo($_SESSION["user"]); ?></p>
        <p id="mosse"> Mosse: </p>
        <p id="daSpegnere"> Da spegnere: </p>
        <p id="tempo"> Tempo: 00:00</p>
        <p id="recordTempo"> Record tempo: 00:00</p>
        <p id="recordMosse"> Record mosse: 00</p>
    </div>
    <div id="gioco">
        <div id="tabella">      
        </div>
    </div>
    <div id="menu">
        <button id="rankingButton">Classifiche</button>
        <button id="logoutButton">Logout</button>
    </div>
    <script src="game/js/DBgame.js"></script>
    <script src="game/js/gameFunctions.js"></script>
    <script src="game/js/game.js"></script>
</body>
</html>