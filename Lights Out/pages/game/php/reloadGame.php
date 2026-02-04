<?php
//esegue una query sulla tabella users e passa i dati tramite file JSON, 
//a seconda dei valori poi la partita verrà ricaricata o ne verrà generata uan nuova
require_once "../../../phputils/dbparams.php";
session_start();

if(!isset($_SESSION["user"])){
    echo json_encode(["success" => false, "message" => "utente non autenticato"]);
    exit;
}
$user = $_SESSION["user"];

header('Content-Type: application/json');

try{
    $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
    $pdo = new PDO($connString, DBUSER, DBPASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT stato, mosse, tempo, completata, record_mosse, record_tempo
            FROM users WHERE username = :username";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":username", $user);
    $statement->execute();

    $row = $statement->fetch(); //può esserci solo una riga visto che lo username è unico
    if($row){
        //crea l'oggetto JSON da un array associativo
        echo json_encode(["success" => true, 
            "data" => [
                        "stato" => $row["stato"],
                        "mosse" => $row["mosse"], 
                        "tempo" => $row["tempo"], 
                        "completata" => $row["completata"], 
                        "record_mosse" => $row["record_mosse"], 
                        "record_tempo" => $row["record_tempo"]
                        ]
            ]);
    }
} catch (PDOException $e){
    echo json_encode(["success" => false, "message" => "Errore del server: " . $e->getMessage()]);
} finally {
    $pdo = null;
}
?>