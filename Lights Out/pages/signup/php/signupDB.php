<?php
require_once "../phputils/dbparams.php";
//eventuale messaggio di errore 
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {  
    $user = trim($_POST['username']);
    $pwd = trim($_POST['password']);

    $res = trySignup($user, $pwd); //funzione che effettivamente accede al database
        switch ($res) { 
            case 0: // accesso al gioco
                $error_msg = "Registrazione effettuata con successo: torna al login e accedi per giocare";
                break;
            case 1: 
                $error_msg = "Lo username scelto esiste già: provane un altro";
                break;
            default:
                $error_msg = "Errore nel database";
            break;
        }
} 

function trySignup($u, $p){
    try{
        $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
        $pdo = new PDO($connString, DBUSER, DBPASS);

        // Configura PDO per generare eccezioni in caso di errori di connessione o query, 
        // così da poterli gestire con il blocco try-catch
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sqlCheckUser = "SELECT username FROM users WHERE username = :username;";
        $statement = $pdo->prepare($sqlCheckUser);
        $statement->bindParam(":username", $u);
        $statement->execute();
        $row = $statement->fetch();

        if($row){
            //l'username esiste già
            return 1;
        }

        //registro il nuovo utente
        $sqlInsertUser = "INSERT INTO users (username, password) VALUES (:username, :password);";
        $statement2 = $pdo->prepare($sqlInsertUser);
        $hashedPassword = password_hash($p, PASSWORD_BCRYPT); //cripto la password
        $statement2->bindParam(':username', $u);
        $statement2->bindParam(':password', $hashedPassword);
        $statement2->execute();

        return 0;        

    } catch (PDOException $e){
        error_log("errore di connessione al database: " . $e->getMessage());
        return -1;
    } finally {
        $pdo = null;
    }
}

?>