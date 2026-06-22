/**
 * Funzione MakeForm originale riadattata per supportare la notazione stateless a staffe []
 * mantenendo intatti i campi standard richiesti dal dispatcher SRVR.php.
 */
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
/**
 * Crea un singolo elemento input hidden.
 * FUNZIONE CHIAMATA: Propaga l'errore aggiungendo il proprio contesto _FF_.
 */
function creaInputHidden(name, value) 
{
  const _FF_ = 'cmn_js(creaInputHidden)';
  try 
  {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value === null || value === undefined ? '' : value;
    return input;
  } 
  catch(err) 
  {
    console.error(err);
    throw new Error('ERRORE: FILE:' + _FF_ + ' ' + err.message);
  }
}

/**
 * Funzione MakeForm ottimizzata per supportare la notazione stateless a staffe [].
 * FUNZIONE CHIAMATA: Propaga l'errore verso la logica chiamante finale.
 */
function MakeForm(url, actionParams, payloadData) 
{
  const _FF_ = 'cmn_js(MakeForm)';
  try 
  {
    // 1. Crea il form temporaneo nascosto
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    // 2. Inserisce i parametri strutturali fissi per il dispatcher SRVR.php
    for (const param in actionParams) 
    {
      if (actionParams.hasOwnProperty(param)) 
      {
        form.appendChild(creaInputHidden(param, actionParams[param]));
      }
    }

    // 3. Elabora l'area dati del payload supportando gli array a staffe []
    for (const chiave in payloadData) 
    {
      if (payloadData.hasOwnProperty(chiave)) 
      {
        const valore = payloadData[chiave];

        // Se il valore è un array, usiamo un ciclo for...of (Risolve JSHint W083)
        if (Array.isArray(valore)) 
        {
          const nomeCampo = chiave.endsWith('[]') ? chiave : `${chiave}[]`;
                    
          for (const singoloValore of valore) 
          {
            form.appendChild(creaInputHidden(nomeCampo, singoloValore));
          }
        } 
        else 
        {
          // Caso standard: parametro singolo dell'anagrafica
          form.appendChild(creaInputHidden(chiave, valore));
        }
      }
    }

    // 4. Sottomissione sincrona
    document.body.appendChild(form);
    form.submit();

  } 
  catch(err) 
  {
    console.error(err);
    throw new Error('ERRORE: FILE:' + _FF_ + ' ' + err.message);
  }
}
