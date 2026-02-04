//funzione che parte prima di effettuare la connessione al DB
document.getElementById("signUpForm").addEventListener("submit", function(event){
    event.preventDefault(); //impedisce l'invio del form

    //prendo i valori dei campi
    const usr = document.getElementById("username").value;
    const pwd = document.getElementById("password").value;
    const pwd2 = document.getElementById("passwordRep").value;

    const errorUsr = document.getElementById("errorUsr");
    const errorPwd = document.getElementById("errorPwd");

    //inizializzo messaggi di errore
    errorUsr.textContent = "";
    errorPwd.textContent = "";

    let valid = true;

    //controllo username
    if(usr.length < 8 || usr.length > 45){
        errorUsr.textContent = "Lo username deve essere compreso tra gli 8 e i 45 caratteri";
        valid = false;
    } else if (/\s/.test(usr)){
        errorUsr.textContent = "Lo username non deve contenere spazi";
        valid = false;
    } else if (!/^[a-zA-Z0-9]+$/.test(usr)){
        errorUsr.textContent = "Lo username deve contenere solamente caratteri alfanumerici";
        valid = false;
    }

    //controllo password
    if(pwd.length < 8 || pwd.length > 45){
        errorPwd.textContent = "La password deve essere compresa tra gli 8 e i 45 caratteri";
        valid = false;
    } else if(!/[a-z]/.test(pwd) || !/[A-Z]/.test(pwd) || !/[0-9]/.test(pwd)){
        errorPwd.textContent = "La password deve contenere almeno una lettera minuscola, una maiuscola e un numero";
        valid = false;
    } else if (/\s/.test(pwd)){
        errorPwd.textContent = "La password non deve contenere spazi";
        valid = false;
    } else if (!/^[a-zA-Z0-9]+$/.test(usr)){
        errorPwd.textContent = "La password deve contenere solamente caratteri alfanumerici";
        valid = false;
    } else if (pwd != pwd2){
        errorPwd.textContent = "Le password non coincidono";
        valid = false;
    }

    if(valid){ //se non ci sono errori proseguo con la connessione al DB
        document.getElementById("signUpForm").submit();
    }
})
