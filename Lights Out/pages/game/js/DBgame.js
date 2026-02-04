// contiene le funzioni che si occupano del caricamento delle statistiche, 
// della partita e salvataggio delle stesse

//salva la partita che non è ancora terminata, perchè l'utente è uscito dal gioco
function salvaStatoPartita(){
    //oggetto da inviare al server
    const data = {
        stato: stato.join(""),
        mosse: mosse,
        tempo: secondi
    };

    const x = new XMLHttpRequest();
    x.open("POST", "game/php/saveGame.php", true);
    x.setRequestHeader("Content-Type", "application/json"); //specifica che i dati saranno inviati tramite JSON

    x.onreadystatechange = function(){ //chimaat ogni volta che cambia lo stato della richiesta
        if(x.readyState === 4 && x.status === 200){
            const response = JSON.parse(x.responseText);
            if(!response.success){
                console.error("Errore:", response.error);
            }
        }
    };

    x.onerror = function () {
        console.error("Errore di rete durante la richiesta.");
    };

    x.send(JSON.stringify(data)); //invio effettivamente i dati al server
}

//recupera la partita iniziata, se non era stata iniziata ne inizializza un'altra
function recuperaPartita(){ 
    const x = new XMLHttpRequest();
    x.open("POST", "game/php/reloadGame.php", true);

    x.onreadystatechange = function () {
        if(x.readyState === 4 && x.status === 200){
            const response = JSON.parse(x.responseText);
            if(response.success){
                caricaPartita(response.data); //se la risposta va bene procedo a caricare la partita
            } else {
                console.error("Errore:", response.error);
            }
        }
    }

    x.onerror = function () {
        console.error("Errore di rete durante la richiesta.");
    };

    x.send();
}

function caricaPartita(data){
    recordTempo = parseInt(data.record_tempo);
    recordMosse = parseInt(data.record_mosse);
    
    if(data.completata == 1 || data.stato === "0000000000000000000000000"){
        //se nessuna partita era in corso, ne creo una nuova
        generaLuci();
        startTimer();
        abilitaCaselle();
        started = true;
        return;
    }
    
    //trasfroma stato di nuovo in un array, nel DB è slavato come VARCHAR(25)
    stato = data.stato.split("");
    
    // converto in interi così sono sicuro di non avere problemi
    // ripristina statistiche dell'utente e partita correnti
    mosse = parseInt(data.mosse);
    secondi = parseInt(data.tempo);

    //ripristino caselle
    for(let i = 0; i < NUM_RIG; i++){
        for(let j = 0; j < NUM_COL; j++){
            let id = i+"-"+j;
            const casella = document.getElementById(id);
            if(stato[i*NUM_RIG + j] === "1"){
                casella.classList.add("accesa");
                accese++;
            } else {
                casella.classList.remove("accesa");
            }
        }
    }

    aggiornaStatistiche();
    stopTimer();
    startTimer();
    abilitaCaselle();
    started = true;
}

//invia richiesta al server per registrare la partita terminata
function registraPartita(){
    //dati da inviare al server per il salvataggio
    const data = {
        mosse: mosse,
        tempo: secondi
    };

    const x = new XMLHttpRequest();
    x.open("POST", "game/php/recordGame.php", true); 
    x.setRequestHeader("Content-Type", "application/json");
    x.onreadystatechange = function(){
        if(x.readyState === 4 && x.status === 200){
            const response = JSON.parse(x.responseText);
            if(!response.success){
                console.error("Errore:", response.error);
            }
        }
    };
    
    x.onerror = function () {
        console.error("Errore di rete durante la richiesta.");
    };

    x.send(JSON.stringify(data));
}