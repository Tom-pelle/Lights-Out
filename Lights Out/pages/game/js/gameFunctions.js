//  --------------------------------------------------------------------
//                              VARIABILI 
//  --------------------------------------------------------------------
const NUM_COL = 5;
const NUM_RIG = 5;
let stato = new Array(25).fill("0"); //controllo e salvataggio dello stato della partita
let accese = 0; //numero di caselle ancora accese
let mosse = 0; //numero di caselle cliccate dall'utente
let timerid; //timer
let secondi = 0; //secondi passati dall'inizio della partita
let started = false; //è true se il gioco è in corso
let recordTempo = 0; 
let recordMosse = 0;

//  --------------------------------------------------------------------
//            FUNZIONI PER GENERAZIONE E ATTIVAZIONE TABELLA 
//  --------------------------------------------------------------------

function click(event){
    aggiornaTabella(event.target.id);
}

//crea una tabella vuota
function generaTabella() {
    const tabella = document.getElementById("tabella");
    for (let i = 0; i < NUM_RIG; i++) {
        const row = document.createElement("div"); // una riga
        tabella.appendChild(row);
        for (let j = 0; j < NUM_COL; j++) {
            const casella = document.createElement("input");
            casella.setAttribute("class", "casella");
            casella.setAttribute("readonly", "readonly");
            casella.id = i+"-"+j;
            row.appendChild(casella);
        }
    }
}

//accende a caso delle caselle cliccando in punti casuali della tabella
function generaLuci(){
    for(let i = 0; i<100; i++){
        let r = Math.floor(Math.random()*NUM_COL);
        let c = Math.floor(Math.random()*NUM_COL);
        aggiornaTabella(r+"-"+c);
    }
    mosse = 0;
    aggiornaStatistiche();
}

//  --------------------------------------------------------------------
//                           FUNZIONI DI GIOCO
//  --------------------------------------------------------------------

function aggiornaTabella(id){
    //recupero riga e colonna dall'id della casella
    let r = parseInt(id.split("-")[0]);
    let c = parseInt(id.split("-")[1]);
    mosse++;

    //aggiorno le caselle adiacenti
    aggiornaCasella(r,c);
    aggiornaCasella(r+1,c);
    aggiornaCasella(r-1,c);
    aggiornaCasella(r,c+1);
    aggiornaCasella(r,c-1);
    
    if(victory()){
        //se la partita è finita stoppo il timer, disattivo il click sulle caselle, la registro nel DB e mostro i risultati
        started = false;
        stopTimer();
        disabilitaCaselle();
        registraPartita(); 
        mostraRisultati();
    } else {
        //se la partita non è finita salvo il suo stato nel DB
        if(started)
            salvaStatoPartita();
        //se non è iniziata significa che sta venendo generato il pattern iniziale: non occorre far nulla
    }
}

//funzione che controlla se la partita è finita
function victory(){
    if(!started)
        return false;

    for(let x of stato){ //basta una luce accesa -> partita non terminata
        if(x == 1)
            return false;
    }
    return true;
}

//aggiorna lo stato della casella aggiungendo o togliendo la classe "accesa"
function aggiornaCasella(r,c){
    if(verificaCasella(r,c)){
        let id = r+"-"+c;
        let casella = document.getElementById(id);
        casella.classList.toggle("accesa");
        if(casella.classList.contains("accesa")){ 
            stato[r*NUM_RIG + c] = "1";
            accese++;
        } else {
            stato[r*NUM_RIG + c] = "0";
            accese--;
        }
        aggiornaStatistiche();
    }
}

//verifica validità casella
function verificaCasella(r,c){
    return r >= 0 && r < NUM_RIG && c >= 0 && c < NUM_COL;
}

//aggiorna statistiche relative a mosse, tempo e luci rimaste
function aggiornaStatistiche(){
    document.getElementById("daSpegnere").innerText = "Da spegnere: " + accese;
    document.getElementById("mosse").innerText = "Mosse: " + mosse;

    document.getElementById("recordTempo").innerText = "Record tempo: " + formatTime(recordTempo);
    document.getElementById("recordMosse").innerText = "Record mosse: " + recordMosse;
}

//  --------------------------------------------------------------------
//                  FUNZIONI PER LA FINE DELLA PARTITA
//  --------------------------------------------------------------------

function mostraRisultati(){
    //mostro risultati
    const menu = document.getElementById("menu");
    menu.innerHTML = `
        <p>Partita completata!</p>
        <p>Mosse: ${mosse}</p>
        <p>Tempo: ${formatTime(secondi)}</p>
        <button id="rankingButton">Classifiche</button>
        <button id="logoutButton">Logout</button>
        <button id="nuovaPartita">Nuova Partita</button>
    `;
    document.getElementById("nuovaPartita").addEventListener("click", resetGioco);
    
    //riattivo bottoni
    abilitaBottoni();
}

function nascondiRisultati(){
    //nascondo risultati
    const menu = document.getElementById("menu");
    menu.innerHTML = `
        <button id="rankingButton">Classifiche</button>
        <button id="logoutButton">Logout</button>
    `;

    //riattivo bottoni
    abilitaBottoni();
}

// Resetta lo stato della partita
function resetGioco(){
    stato.fill("0");
    accese = 0;
    mosse = 0;
    secondi = 0;
    recordMosse = 0;
    recordTempo = 0;

    //resetta griglia
    const celle = document.querySelectorAll(".casella");
    for(let c of celle){
        c.classList.remove("accesa");
    }

    //nascondo risultati
    nascondiRisultati();
    
    //riavvia, chiamo recupera partita perchè la scorsa partita è segnata come completata
    //quindi ne avvia automaticamente un'altra
    recuperaPartita();
    aggiornaStatistiche();
}

//  --------------------------------------------------------------------
//                    FUNZIONI TIMER E TEMPO
//  --------------------------------------------------------------------

function startTimer() {
    timerid = setInterval(aggiornaTimer, 1000); //ogni secondo chiama aggiornaTimer
}

function aggiornaTimer(){
    secondi++;
    document.getElementById("tempo").textContent = "Tempo: " + formatTime(secondi);
}

function stopTimer() {
    clearInterval(timerid);
}

//formatta i secondi in mm:ss
function formatTime(secondi) {
    let minuti = Math.floor(secondi / 60);
    let secondiRimanenti = secondi % 60;
    return `${minuti.toString().padStart(2, '0')}:${secondiRimanenti.toString().padStart(2, '0')}`;
}

//  --------------------------------------------------------------------
//           FUNZIONI DI ABILITAZIONE/DISABILITAZIONE EVENTI
//  --------------------------------------------------------------------

function disabilitaCaselle(){
    const caselle = document.querySelectorAll(".casella");
    for(let c of caselle){
        c.removeEventListener("click", click);
    }
}

function abilitaCaselle(){
    const caselle = document.querySelectorAll(".casella");
    for(let c of caselle){
        c.addEventListener("click", click);
    }
}

function abilitaBottoni(){
    const logoutButton = document.getElementById("logoutButton");
    const rankingButton = document.getElementById("rankingButton");

    logoutButton.addEventListener("click", function(){
        salvaStatoPartita();
        window.location.href = '../logout.php';
    })

    rankingButton.addEventListener("click", function(){
        salvaStatoPartita();
        window.location.href = 'ranking.php';
    })
}