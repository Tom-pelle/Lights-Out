<!--L'utente inserisce le proprie credenziali o decide di registrarsi-->
<?php 
require_once('login/php/loginDB.php');
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../img/lighton.png">
    <link rel="stylesheet" href="../css/login.css">
    <title>Lights Out - Login</title>
</head>
<body>
    <form id="loginForm" action="login.php" method="post">
        <h1>Effettua l'accesso o registrati per giocare</h1>
        <br>
        <label for="username"> Username: </label> 
        <input type="text" id="username" name="username" placeholder="Username" required >
        <div id="errorUsr" class="error"></div>
        <br>
        <label for="password"> Password: </label> 
        <input type="password" id="password" name="password" placeholder="Password" required >
        <div id="errorPwd" class="error"></div>
        <br>
        <button type="submit" id="loginButton" value="Accedi">Accedi</button>
        <br>
        <button type="button" id="goToSignup" onclick="window.location.href='signup.php'">Registrati</button>
        <br>
        <button type="button" id="man" onclick="window.location.href='../documentazione.html'">Manuale Utente</button>
        <div id="errorGen" class="error"><?php echo($error_msg)?></div>
    </form>
    <script src="login/js/login.js"></script>
</body>
</html>