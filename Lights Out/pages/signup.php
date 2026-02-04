<!-- Il giocatore si registra inserendo i propri username e password -->
<?php
require_once("signup/php/signupDB.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/lighton.png">
    <link rel="stylesheet" href="../css/login.css">
    <title>Lights Out - Signup</title>
</head>
<body>
    <!-- semmai abbellire con immagini -->
    <form id="signUpForm" action="signup.php" method="post">
        <h1>Effettua l'accesso o registrati per giocare</h1>
        <label for="username"> Username: </label> 
        <input type="text" id="username" name="username" placeholder="Username"required>
        <div id="errorUsr" class="error"></div>
        <br>
        <label for="password"> Password: </label> 
        <input type="password" id="password" name="password" placeholder="Password" required>
        <br>
        <label for="passwordRep"> Ripeti password: </label> 
        <input type="password" id="passwordRep" name="passwordRep" placeholder="Password" required>
        <div id="errorPwd" class="error"></div>
        <br>
        <button type="submit" id="signUpButton" value="Registrati">Registrati</button>
        <button type="button" id="goToSignup" onclick="window.location.href='login.php'">Torna al login</button>
        <br>
        <button type="button" id="man" onclick="window.location.href='../documentazione.html'">Manuale Utente</button>
        <div id="errorGen" class="error"><?php echo($error_msg)?></div>
    </form>
    <script src="signup/js/signup.js"></script>
</body>
</html>