/**
 * ChooseInitializePage: Nasconde tutte le card delle abilitazioni all'avvio.
 * @param {Array} Siti - Array dei siti unici estratti dal server
 */
function ChooseInitializePage(Siti)
{
  const _FF_ = '20_Choose.js(ChooseInitializePage)';
  try {
    for(let SitiCtr = 0; SitiCtr < Siti.length; SitiCtr++) { 
      let jsArrSiti = document.getElementsByClassName(Siti[SitiCtr]);
      for (let Ctr = 0; Ctr < jsArrSiti.length; Ctr++) {
        jsArrSiti[Ctr].style.display = "none";
        jsArrSiti[Ctr].style.borderColor = "transparent";
        let img = jsArrSiti[Ctr].querySelector('.abil-icon');
        if(img) img.style.backgroundColor = "transparent";
      }      
    }
    const parScelta = document.getElementById("ID_ParScelta");
    if(parScelta) parScelta.innerHTML = ""; 
  } 
  catch(err) {
    throw new Error('ERRORE: FILE:' + _FF_ + ' ' + err.message);
  }
}

/**
 * SetSite: Mostra le sole abilitazioni collegate al sito cliccato.
 * @param {string} Sito - Il sito selezionato
 * @param {Array} Siti - L'elenco completo dei siti per il reset grafico
 */
function SetSite(Sito, Siti) 
{
  try {
    ChooseInitializePage(Siti);
    let ArrSito = document.getElementsByClassName(Sito);
    for (let Ctr = 0; Ctr < ArrSito.length; Ctr++) {
      ArrSito[Ctr].style.display = "block"; // CSS Grid gestirà l'incolonnamento automatico
    }
    document.getElementById("ID_ParScelta").innerHTML = "2. Scegliere un'abilitazione";
    document.getElementById("ID_SITE").value = Sito;
    
    // Resetta le selezioni dell'abilitazione precedente per evitare invii incoerenti
    document.getElementById("ID_ABIL").value = "";
    document.getElementById("ID_DESC").value = "";
  }
  catch(err) {
    console.error(err);
  }  
}

/**
 * SetAbil: Evidenzia visivamente l'abilitazione scelta e ne memorizza i dati.
 */
function SetAbil(ChoosenAbil, Desc, ID, ClassSito) 
{
  try {
    // Reset dei bordi solo per le abilitazioni del sito corrente
    let sibs = document.getElementsByClassName(ClassSito);
    for (let i = 0; i < sibs.length; i++) {
      sibs[i].style.borderColor = "transparent";
      let innerImg = sibs[i].querySelector('.abil-icon');
      if(innerImg) innerImg.style.backgroundColor = "transparent";
    }
    
    // Attivazione visiva degli elementi selezionati (tramite ID numerico idx coerente)
    const targetCard = document.getElementById("ID_Card_" + ID);
    const targetImg = document.getElementById("ID_Img_" + ID);
    
    if(targetCard) targetCard.style.borderColor = "#27ae60"; // Verde evidenza
    if(targetImg) targetImg.style.backgroundColor = "#e8f8f5";
    
    document.getElementById("ID_ABIL").value = ChoosenAbil;
    document.getElementById("ID_DESC").value = Desc;
  }
  catch (err) {
    console.error(err);
  }        
}

/**
 * Validate: Controlla che i dati obbligatori siano presenti ed effettua l'invio POST.
 */
function Validate()
{
  if(document.getElementById('ID_SITE').value === "") {    
    alert('Selezionare prima un sito di lavoro.');
    return;  
  }
  if(document.getElementById('ID_ABIL').value === "") {    
    alert('Selezionare un\'abilitazione operativa.');
    return;  
  }

  try {
    // Costruzione dell'array dei parametri per la form dinamica di CMN_JS.js
    const ArrPar = [
      { name: "USERID", type: "hidden", value: document.getElementById("ID_UID").value },
      { name: "SITE", type: "hidden", value: document.getElementById("ID_SITE").value },
      { name: "ABIL", type: "hidden", value: document.getElementById("ID_ABIL").value },
      { name: "DESCABIL", type: "hidden", value: document.getElementById("ID_DESC").value },
      { name: "QRY_FNC", type: "hidden", value: "" }
    ];

    let FrmFrom = document.getElementById("ID_USR").value;
    const CHOOSE_FRM = MakeForm(document, "FrmChoose", "../SRVR/SRVR.php", FrmFrom, "GEST", "SEL_GEST_SITE", ArrPar);
    
    if (CHOOSE_FRM) {
      document.body.appendChild(CHOOSE_FRM);  
      CHOOSE_FRM.submit();   
    }
  }
  catch (err) {
    console.error(err);
  }        
}
