<?php
//salva la partita ogni volta che l'utente fa una mossa, in modo 
//che il server la ricarichi quando l'utente effettua nuovamente l'accesso

require_once "../../../phputils/dbparams.php";
session_start();
if (!isset($_SESSION["user"])) {   
    echo json_encode(["success" => false, "message" => "utente non autenticato"]);
    exit;
}
$user = $_SESSION["user"];

header('Content-Type: application/json');

try{
    $data = json_decode(file_get_contents('php://input',true));

    $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
    $pdo = new PDO($connString, DBUSER, DBPASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($data->stato, $data->mosse, $data->tempo)){
        $stato = $data->stato;
        $mosse = $data->mosse;
        $tempo = $data->tempo;
        //aggiorna tabella users, con relativo stato attuale della partita
        $sql = "UPDATE users SET stato = :stato, mosse = :mosse, tempo = :tempo, completata = 0 WHERE username = :username";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':stato', $stato);
        $statement->bindParam(':mosse', $mosse);
        $statement->bindParam(':tempo', $tempo);
        $statement->bindParam(':username', $user);
        $statement->execute();
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Dati mancanti."]);
    }
} catch (PDOException $e){
    echo json_encode(["success" => false, "message" => "Errore del server: " . $e->getMessage()]);
} finally {
    $pdo = null;
}

?>