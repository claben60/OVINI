/**
 * UPR_InitializePage: Mostra o nasconde i campi extra in base all'azione.
 * @param {string} Act - L'azione normalizzata passata dal server ('UPR_U', 'UPR_P', 'UPR_R')
 */
function UPR_InitializePage(Act)
{
  const _FF_ = '30_UPR.js(UPR_InitializePage)';
  const registrationBlock = document.getElementById('ID_BloccoRegistrazione');

  switch(Act) 
  {
    case 'UPR_U':
    case 'UPR_P':
      // Se l'utente richiede un recupero credenziali, isoliamo e nascondiamo i campi extra
      try {
        if (registrationBlock) {
          registrationBlock.style.display = "none";
        }
      } 
      catch (err) {
        console.error('Errore in inizializzazione:', err);
      }
      break;

    case 'UPR_R':
      // Se si tratta di una registrazione, mostriamo il blocco credenziali
      try {
        if (registrationBlock) {
          registrationBlock.style.display = "block";
        }
      } 
      catch (err) {
        console.error('Errore in inizializzazione:', err);
      }
      break;

    default:      
      let ErrText = 'ERRORE: FILE:' + _FF_ + ' Errore: Azione non riconosciuta. Valore ricevuto: ' + Act;
      alert(ErrText);
      console.error(ErrText);
  }
}

/**
 * Validate: Esegue i controlli formali sui campi di testo prima della sottomissione.
 */
function Validate(Act)
{
  const nomeField = document.getElementById('ID_NOME');
  const cognomeField = document.getElementById('ID_COGNOME');
  const cfField = document.getElementById('ID_CF');

  if(!nomeField || nomeField.value.trim() === '') { alert('Immettere il nome'); nomeField.focus(); return; }
  if(!cognomeField || cognomeField.value.trim() === '') { alert('Immettere il cognome'); cognomeField.focus(); return; }
  if(!cfField || cfField.value.trim() === '') { alert('Immettere il codice fiscale'); cfField.focus(); return; }

  // Controlli specifici ed esclusivi per la registrazione nuovo utente
  if(Act === 'UPR_R')
  {
    const usrField = document.getElementById('ID_USR');
    const respwdField = document.getElementById('ID_RESPWD');
    const confpwdField = document.getElementById('ID_CONFPWD');

    if(!usrField || usrField.value.trim() === '') { alert('Immettere l\'utenza desiderata'); usrField.focus(); return; }
    if(!respwdField || respwdField.value === '') { alert('Inserire la password'); respwdField.focus(); return; }
    if(!confpwdField || confpwdField.value === '') { alert('Confermare la password'); confpwdField.focus(); return; }
    if(confpwdField.value !== respwdField.value) { alert('Le password inserite non coincidono'); respwdField.focus(); return; }
  }

  try {
    // Generazione dei parametri base per l'invio POST asincrono/dinamico
    const ArrPar = [
      { name: "USRNAME", type: "hidden", value: nomeField.value.trim() },
      { name: "USRCOGNOME", type: "hidden", value: cognomeField.value.trim() },
      { name: "USRCF", type: "hidden", value: cfField.value.trim().toUpperCase() } // Forza il CF in maiuscolo
    ];

    if(Act === 'UPR_R')
    {
      ArrPar.push(
        { name: "USR_ID", type: "hidden", value: document.getElementById("ID_USR").value.trim() },
        { name: "PWD", type: "hidden", value: document.getElementById("ID_RESPWD").value }
      );
    }

    // Chiamata alla libreria MakeForm in CMN_JS.js (Ripulita dalle ridondanze di testo)
    const UPR_FRM = MakeForm(document, 'FrmUPR', '../SRVR/SRVR.php', '30_UPR', Act, ArrPar);

    if (UPR_FRM) {
      document.body.appendChild(UPR_FRM);  
      UPR_FRM.submit();
    }
  }
  catch(err) {
    console.error(err);
  }    
}
