<?php
//salva la partita quando è finita aggiornando la tabella users (con gli eventuali nuovi record)
//inserisce la partita nella tabella games con le relative statistiche ai fini della classifica

require_once "../../../phputils/dbparams.php";
session_start();

if (!isset($_SESSION["user"])) {   
    //imposta l'errore nella richiesta e il relativo messaggio
    echo json_encode(["success" => false, "message" => "utente non autenticato"]);
    exit;
}
$user = $_SESSION["user"];

header('Content-Type: application/json');

try{
    $data = json_decode(file_get_contents('php://input',true)); //decodifica i dati JSON nel flusso php://input

    $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
    $pdo = new PDO($connString, DBUSER, DBPASS); //conn. al DB
    
    // Configura PDO per generare eccezioni in caso di errori di connessione o query, 
    // così da poterli gestire con il blocco try-catch
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($data->mosse, $data->tempo)){
        $mosse = $data->mosse;
        $tempo = $data->tempo;

        $salva = "UPDATE users 
                SET stato = '0000000000000000000000000',
                mosse = 0,
                tempo = 0, 
                completata = 1, 
                record_mosse = CASE 
                    WHEN record_mosse = 0 OR :mosse < record_mosse THEN :mosse
                    ELSE record_mosse
                END, 
                record_tempo = CASE
                    WHEN record_tempo = 0 OR :tempo < record_tempo THEN :tempo
                    ELSE record_tempo
                END
                WHERE username = :username;";
        $statement = $pdo->prepare($salva);
        $statement->bindParam(':mosse', $mosse);
        $statement->bindParam(':tempo', $tempo);
        $statement->bindParam(':username', $user);
        $statement->execute();

        //nuovo record nella tabella games, in cui viene salvato anche il momento di fine partita
        $registra = "INSERT INTO games (username, mosse, tempo, ora)
                VALUES (:username, :mosse, :tempo, NOW());";                 
        $statement2 = $pdo->prepare($registra);
        $statement2->bindParam(':mosse', $mosse);
        $statement2->bindParam(':tempo', $tempo);
        $statement2->bindParam(":username", $user);
        $statement2->execute();

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