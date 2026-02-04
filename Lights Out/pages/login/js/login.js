const submit = document.getElementById("loginButton");
submit.disabled = true;
const usrInput = document.getElementById("username");
const pwdInput = document.getElementById("password");
let usrOk = false; //varibiabile per controllare che l'input username sia valido
let pwdOk = false; //varibiabile per controllare che l'input password sia valido

//uso 'blur' così l'eventuale avviso di errore appare quando l'utente lascia il campo input
usrInput.addEventListener("blur", validate);
pwdInput.addEventListener("blur", validate);

function validate(e){
    e.preventDefault(); //impedisce l'invio del form
    const w = e.target.value.trim(); //ignoro eventuali spazi a inizio e fine input
    let errMsg = "";
    const input = e.target.id == "username" ? "errorUsr" : "errorPwd";
    
    //controllo la presenza di spazi
    if(w.includes(" ")){
        submit.disabled = true;
        errMsg = "non può contenere spazi";
        setErrorMessage(input, errMsg);
        return;
    }

    //controllo la lunghezza
    if(w.length < 8 || w.length > 45){
        submit.disabled = true;
        errMsg = "deve essere tra 8 e 45 caratteri";
        setErrorMessage(input, errMsg);
        return;
    }

    //controllo la presenza di solo caratteri alfanumerici
    if(!/^[a-zA-Z0-9]+$/.test(w)){
        submit.disabled = true;
        errMsg = "deve contenere solamente caratteri alfanumerici";
        setErrorMessage(input, errMsg);
        return;
    }

    submit.disabled = usrOk && pwdOk; 
    setErrorMessage(input, errMsg);
}

function setErrorMessage(input, errMsg){
    
    const msg = document.getElementById(input);
    if(msg.id == "errorUsr"){ //errore nello username
        msg.textContent = (errMsg == "" ? "" : "Lo username " + errMsg);
        usrOk = (errMsg == "" ? true : false);
    } else { //errore nella password
        msg.textContent = (errMsg == "" ? "" : "La password " + errMsg);
        pwdOk = (errMsg == "" ? true : false);
    }

    submit.disabled = !(usrOk && pwdOk); //attivo il bottone solo se sia username che password rispettano i vincoli
}