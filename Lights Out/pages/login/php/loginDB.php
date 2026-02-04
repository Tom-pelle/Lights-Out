<?php
require_once "../phputils/dbparams.php";
$error_msg ="";

//legge i dati tramite POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) { 
    //rimuove eventuali spazi a inizio e fine parola 
    $user = trim($_POST['username']);
    $pwd = trim($_POST['password']);
    
    $res = tryLogin($user, $pwd); //funzione che effettivamente accede al database
        switch ($res) { 
            case 0: // accesso al gioco
                header('Location: game.php');
                break;
            case 1: 
                $error_msg = "Password errata";
                break;
            case 2: 
                $error_msg = "Questo utente non esiste, effettua la registrazione";
                break;
            default:
                $error_msg = "Errore.";
            break;
        }
} 

function tryLogin($u, $p){
    try {
        $connString = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . "";
        $pdo = new PDO($connString, DBUSER, DBPASS); //connette al DB

        // Configura PDO per generare eccezioni in caso di errori di connessione o query, 
        // così da poterli gestire con il blocco try-catch
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT username, password FROM users WHERE username = :username;";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':username', $u);
        $statement->execute();
        $row = $statement->fetch();
            
        if($row && $row["username"] == $u){ 
            //l'username esiste controllo la password 
            if(password_verify($p, $row['password'])){
                session_start();  
                $_SESSION["user"] = $row["username"]; //imposto la sessione per l'utente corrente
                return 0;
            } else {
                //password errata
                return 1;
            }
        } else {
            //l'username non esiste, l'utente deve registrarsi
            return 2;    
        }

    } catch (PDOException $e) {
        error_log("errore di connessione al database: " . $e->getMessage());
        return -1;
    } finally {
        $pdo = null; //chiudo in ogni caso la connessione pdo
    }
        
}
