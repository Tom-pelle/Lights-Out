<!-- Pagina delle classifiche -->
<?php
require_once "../phputils/dbparams.php";
session_start();
if (!isset($_SESSION["user"])) {   
    header("Location: ../index.php"); //non può accedere alla pagina se non autenticato
    exit;
}

try{
    $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
    $pdo = new PDO($connString, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ordine = 'ora ASC';
    if (isset($_GET['ordina'])) {
        switch ($_GET['ordina']) {
            case 'mosse':
                $ordine = 'mosse ASC';
                break;
            case 'tempo':
                $ordine = 'tempo ASC';
                break;
            case 'data':
                $ordine = 'ora ASC';
                break;
            default:
                break;
            }
    }   

    //recupero tutte le partite fatte con le relative statistiche
    $sql = "SELECT username, tempo, mosse, ora FROM games ORDER BY $ordine";
    $statement = $pdo->prepare($sql);
    $statement->execute();

    $partite = $statement->fetchAll();

    $recordTempo = null;
    $recordMosse = null;
    $recordTempoUtente = "";
    $recordMosseUtente = "";

    //ricavo il record tempo e mosse
    foreach($partite as $partita){
        if($recordTempo === null || $partita["tempo"] < $recordTempo){
            $recordTempo = $partita["tempo"];
            $recordTempoUtente = $partita["username"];
        }
        if($recordMosse === null || $partita["mosse"] < $recordMosse){
            $recordMosse = $partita["mosse"];
            $recordMosseUtente = $partita["username"];
        }
    }

    //htmlspecialchars -> converte eventuali caratteri speciali in entità HTML 
    if(!empty($partite)){
        $record = "<p>Minor tempo: <strong> " . htmlspecialchars(formatTime($recordTempo)) . 
        " </strong> fatto da <strong>" . htmlspecialchars($recordTempoUtente) . " </strong></p>
        <p>Minor numero di mosse: <strong> " . htmlspecialchars($recordMosse) . 
        "</strong> fatto da <strong> " . htmlspecialchars($recordMosseUtente). 
        " </strong></p>";
    } else {
        //nel caso in cui non ci siano ancora partite fatte
        $record = "";
    }
    
} catch (PDOException $e){
    die("Errore di connessione al database: " . $e->getMessage());
} finally {
    $pdo = null;
}

function generaTabella($partite) {
    if (empty($partite)) {
        //nel caso in cui non ci siano partite fatte
        return "<tr><td colspan='4'>Nessuna partita trovata.</td></tr>";
    }

    $html = '';
    foreach ($partite as $partita) {        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($partita['username']) . '</td>';
        $html .= '<td>' . htmlspecialchars($partita['mosse']) . '</td>';
        $html .= '<td>' . htmlspecialchars(formatTime($partita["tempo"])) . '</td>';
        $html .= '<td>' . htmlspecialchars($partita['ora']) . '</td>';
        $html .= '</tr>';
    }
    return $html;
}

//formatta i secondi in mm:ss
function formatTime($secondi){
    $minuti = floor($secondi/60);
    $secondiRimanenti = $secondi % 60;
    $mmss = sprintf("%02d:%02d", $minuti, $secondiRimanenti); 
    return $mmss;
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/ranking.css">
    <link rel="icon" type="image/x-icon" href="../img/lighton.png">
    <title>Lights Out - Classifiche</title>
</head>
<body>
    <h1>Classifiche</h1>
    <div id="ordinamento">
        <form method="GET" >
            <button type="submit" name="ordina" value="mosse">Ordina per Mosse</button>
            <button type="submit" name="ordina" value="tempo">Ordina per Tempo</button>
            <button type="submit" name="ordina" value="data">Ordina per Data</button>
        </form>
    </div>
    <table>
        <th>Utente</th>
        <th>Mosse</th>
        <th>Tempo (s)</th>
        <th>Data Partita</th>
        <?= generaTabella($partite) ?>
    </table>

    <div class="record">
        <?php echo $record;?>
    </div>
    <div id="menu">
        <button type="button" id="game" onclick="window.location.href='game.php'">Torna al gioco</button>
        <button type="button" id="logout" onclick="window.location.href='../logout.php'">Logout</button>
    </div>
</body>
</html>