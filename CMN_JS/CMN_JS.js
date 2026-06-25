/**
 * CMN_JS.js
 * Funzioni JavaScript condivise e di utilità comune per l'applicazione SACS.
 */

/**
 * MakeForm: Genera dinamicamente un modulo HTML in memoria popolandolo con i parametri di controllo
 *           e l'array dei campi nascosti (o visibili) richiesti per la comunicazione POST.
 * 
 * @param {Document} Doc - Il riferimento al documento (es. document)
 * @param {string} FrmName - Il nome da assegnare al modulo (attributo name)
 * @param {string} SrvrAction - L'endpoint o file server di destinazione (attributo action)
 * @param {string} FrmFrom - Il file sorgente o la funzionalità che ha originato la chiamata
 * @param {string} Sector - L'area applicativa di riferimento (es. LOGIN, GEST, APP)
 * @param {string} IDAction - L'identificativo dell'azione specifica che dovrà elaborare il server
 * @param {Array} ArrPar - Array di oggetti contenenti i singoli campi [{name: "...", value: "...", type: "..."}]
 * @returns {HTMLFormElement} - Il form generato e pronto per essere inserito nel DOM e sottomesso
 */
function MakeForm(Doc, FrmName, SrvrAction, FrmFrom, Sector, IDAction, ArrPar)
{
  const _FF_ = 'CMN_JS.js(MakeForm)';
  
  try {
    // 1. Inizializzazione del form nativo
    const FRM = Doc.createElement("form");
    FRM.setAttribute("name", FrmName);
    FRM.setAttribute("method", "post");  
    FRM.setAttribute("action", SrvrAction);

    // 2. Creazione del campo di controllo per il nome del Form
    const FRM_NAME = Doc.createElement("input");
    FRM_NAME.setAttribute("type", "hidden");
    FRM_NAME.setAttribute("name", "FN");
    FRM_NAME.setAttribute("value", FrmName);
    FRM.appendChild(FRM_NAME);  

    // 3. Creazione del campo di controllo per la provenienza (FROM)
    const FRM_FROM = Doc.createElement("input");
    FRM_FROM.setAttribute("type", "hidden");
    FRM_FROM.setAttribute("name", "FROM");
    FRM_FROM.setAttribute("value", FrmFrom);
    FRM.appendChild(FRM_FROM);  

    // 4. Creazione del campo di controllo per l'area applicativa (SECTOR)
    const FRM_SECT = Doc.createElement("input");
    FRM_SECT.setAttribute("type", "hidden");
    FRM_SECT.setAttribute("name", "SECTOR");
    FRM_SECT.setAttribute("value", Sector);  
    FRM.appendChild(FRM_SECT);  
    
    // 5. Creazione del campo di controllo per l'azione specifica sul server (IDACTION)
    const FRM_IDA = Doc.createElement("input");
    FRM_IDA.setAttribute("type", "hidden");
    FRM_IDA.setAttribute("name", "IDACTION");
    FRM_IDA.setAttribute("value", IDAction);  
    FRM.appendChild(FRM_IDA);  

    // 6. Ciclo di popolamento dei parametri dinamici passati dalle singole interfacce
    if (Array.isArray(ArrPar)) {
      ArrPar.forEach(ParRow => {
        const FRM_INPUT = Doc.createElement('input');
        FRM_INPUT.type = ParRow.type || 'hidden'; // Default a 'hidden' se non specificato
        FRM_INPUT.name = ParRow.name;
        FRM_INPUT.value = ParRow.value !== undefined ? ParRow.value : '';   
        FRM.appendChild(FRM_INPUT);  
      });
    }
    
    // Restituisce l'elemento form strutturato senza appenderlo (operazione delegata al chiamante)
    return FRM;                 
  }
  catch(err) {
    // Corretti i bug bloccanti del catch (rimosso line.caller e normalizzata la variabile dell'azione)
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' IDAction: ' + IDAction + ' Errore: ' + err.name + ' - ' + err.message;
    alert(ErrText);
    console.error(ErrText);
    throw new Error(ErrText); // "Error" con la E maiuscola
  }
}

/**
 * ToUpper: Converte e restituisce una stringa interamente in caratteri maiuscoli.
 * @param {string} String - La stringa di input da convertire
 * @returns {string}
 */
function ToUpper(String)
{
  if (typeof String === 'string') {
    return String.toUpperCase();
  }
  return String;
}
