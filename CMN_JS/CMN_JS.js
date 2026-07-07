/**
 * Genera un form HTML dinamico completo di metadati strutturali e parametri piatti.
 * Accetta in ArrPar sia un array piatto di {name, value} sia un oggetto strutturato (grazie all'auto-flattening).
 */
function MakeForm(Doc, FrmName, SrvrAction, FrmFrom, Sector, IDAction, ArrPar)
{
  const _FF_ = 'CMN_JS.js(MakeForm)';
  
  try  
  {
    const FRM = Doc.createElement("form");
    FRM.setAttribute("name", FrmName);
    FRM.setAttribute("method", "post");  
    FRM.setAttribute("action", SrvrAction);

    // Helper interno per iniettare gli input hidden
    const addHiddenInput = (name, value) => 
    {
      const input = Doc.createElement("input");
      input.setAttribute("type", "hidden");
      input.setAttribute("name", name);
      input.setAttribute("value", value !== undefined && value !== null ? value : '');
      FRM.appendChild(input);
    };

    // 1. Iniezione dei metadati strutturali della busta (Coerenti con clPost)
    addHiddenInput("FRMNAME", FrmName);  
    addHiddenInput("FROM", FrmFrom);  
    addHiddenInput("SECTOR", Sector);  
    addHiddenInput("IDACTION", IDAction);  
    addHiddenInput("ACTION", SrvrAction); // Allineato al campo ACTION di clPost

    // 2. Popolamento dei parametri dinamici
    let finalParams = [];

    if (Array.isArray(ArrPar)) 
    {
      // Se è già un array piatto di parametri, lo usiamo direttamente
      finalParams = ArrPar;
    }
    else if (ArrPar && typeof ArrPar === 'object')
    {
      // EVOLUZIONE: Se viene passato un oggetto complesso (es. l'intero payload), lo appiattiamo al volo
      _flattenObjectToFormParameters(ArrPar, '', finalParams);
    }

    // 3. Rendering effettivo dei campi nel form
    finalParams.forEach(ParRow => 
    {
      // Saltiamo i metadati se per caso sono stati re-inclusi nell'oggetto per evitare duplicati
      const upperName = String(ParRow.name).toUpperCase();
      if (['FRMNAME', 'FROM', 'SECTOR', 'IDACTION', 'ACTION'].indexOf(upperName) !== -1) {
        return;
      }

      const FRM_INPUT = Doc.createElement('input');
      FRM_INPUT.type = ParRow.type || 'hidden';
      FRM_INPUT.name = ParRow.name;
      FRM_INPUT.value = ParRow.value !== undefined && ParRow.value !== null ? ParRow.value : '';   
      FRM.appendChild(FRM_INPUT);  
    });
    
    return FRM;                 
  }
  catch(err) 
  {
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Errore: ' + err.name + ' - ' + err.message + '\n' +
                  'PARAMETRI:\n' + 
                  'FrmName: ' + FrmName + ' | SrvrAction: ' + SrvrAction + ' | FrmFrom: ' + FrmFrom + 
                  ' | Sector: ' + Sector + ' | IDAction: ' + IDAction;
    alert(ErrText);
    console.error(ErrText);
    throw new Error(ErrText);
  }
}

/**
 * Funzione di utilità ricorsiva per appiattire oggetti complessi nel formato a staffe di PHP.
 * Scritta con cicli classici per retrocompatibilità e conformità JSHint (zero warning W083).
 */
function _flattenObjectToFormParameters(obj, prefix, targetArray) 
{
  for (const key in obj) 
  {
    if (!obj.hasOwnProperty(key)) continue;

    // Se c'è un prefisso, creiamo la struttura a staffe (es: utente[idUtente]), altrimenti usiamo la chiave base
    const newKey = prefix ? `${prefix}[${key}]` : key;
    const value = obj[key];

    if (value === null || value === undefined) 
    {
      targetArray.push({ name: newKey, value: '', type: 'hidden' });
    } 
    else if (typeof value === 'object' && !Array.isArray(value)) 
    {
      _flattenObjectToFormParameters(value, newKey, targetArray);
    } 
    else if (Array.isArray(value)) 
    {
      for (let idx = 0; idx < value.length; idx++) 
      {
        const item = value[idx];
        if (typeof item === 'object' && item !== null) 
        {
          _flattenObjectToFormParameters(item, `${newKey}[${idx}]`, targetArray);
        } 
        else 
        {
          targetArray.push({ name: `${newKey}[${idx}]`, value: String(item), type: 'hidden' });
        }
      }
    } 
    else 
    {
      const valStr = typeof value === 'boolean' ? (value ? '1' : '0') : String(value);
      targetArray.push({ name: newKey, value: valStr, type: 'hidden' });
    }
  }
}

/**
 * EVOLUZIONE: Mappa l'intero oggetto di stato JS (busta completa) nel formato ArrPar a staffe.
 * Risolve il problema del baco di capitalizzazione (da UTENTE a utente) per clPost.
 */
/**
 * EVOLUZIONE: Mappa l'intero oggetto di stato JS (busta completa) nel formato ArrPar a staffe.
 * Risolve il problema del baco di capitalizzazione allineandosi allo standard MAIUSCOLO (UTENTE, GEST, APP) per clPost.
 */
/**
 * Mappa l'intero oggetto di stato JS (busta completa) nel formato ArrPar a staffe.
 * Allineato allo standard MAIUSCOLO (UTENTE, GEST, APP) per il corretto parsing clPost in PHP.
 */
function PostObjToArrPar(postDump) 
{
  const _FF_ = 'CMN_JS.js(PostObjToArrPar)';
  const arrPar = [];

  try 
  {
    if (!postDump || typeof postDump !== 'object') 
    {
      throw new Error('Payload postDump non valido o non valorizzato');
    }

    // Estraiamo i dati indipendentemente dal case dell'oggetto JS originale
    const utenteData = postDump.UTENTE || postDump.utente || null;
    const gestData = postDump.GEST || postDump.gest || null;
    const appData = postDump.APP || postDump.app || null;

    // Standard SACS: Uso dei prefissi rigorosamente MAIUSCOLI
    if (utenteData) _flattenObjectToFormParameters(utenteData, 'PAYLOAD[UTENTE]', arrPar);
    if (gestData)   _flattenObjectToFormParameters(gestData, 'PAYLOAD[GEST]', arrPar);
    if (appData)    _flattenObjectToFormParameters(appData, 'PAYLOAD[APP]', arrPar);
    
    return arrPar;
  } 
  catch(err) 
  {
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Errore: ' + err.name + ' - ' + err.message;
    console.error(ErrText);
    throw new Error(ErrText);
  }
}

/**
 * Mantiene la retrocompatibilità per il singolo oggetto utente, 
 * incapsulando correttamente i parametri sotto la radice globale 'PAYLOAD[UTENTE]'.
 * NOTA: La versione duplicata precedente in minuscolo è stata rimossa per evitare conflitti di sovrascrittura.
 */
function UtenteToArrPar(utenteDump) 
{
  const _FF_ = 'CMN_JS.js(UtenteToArrPar)';
  const arrPar = [];

  try 
  {
    if (!utenteDump || typeof utenteDump !== 'object') 
    {
      let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Parametro utenteDump non corretto';
      alert(ErrText);
      throw new Error('utenteDump non valido');
    }

    // Inietta i dati sotto l'albero PAYLOAD standard richiesto dal server PHP
    _flattenObjectToFormParameters(utenteDump, 'PAYLOAD[UTENTE]', arrPar);
    
    return arrPar;
  } 
  catch(err) 
  {
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Errore: ' + err.name + ' - ' + err.message;
    console.error(ErrText);
    throw new Error(ErrText);
  }
}
/**
 * Mantiene la retrocompatibilità per il singolo oggetto utente, 
 * allineando il prefisso allo standard di macro-chiave MAIUSCOLA ('UTENTE').
 */
function UtenteToArrPar(utenteDump) 
{
  const _FF_ = 'CMN_JS.js(UtenteToArrPar)';
  const arrPar = [];

  try 
  {
    if (!utenteDump || typeof utenteDump !== 'object') 
    {
      let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Parametro utenteDump non corretto';
      alert(ErrText);
      throw new Error('utenteDump non valido');
    }

    // CORREZIONE CRITICA: 'UTENTE' maiuscolo per agganciarsi a $_POST['UTENTE'] di PHP
    _flattenObjectToFormParameters(utenteDump, 'UTENTE', arrPar);
    
    return arrPar;
  } 
  catch(err) 
  {
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Errore: ' + err.name + ' - ' + err.message;
    console.error(ErrText);
    throw new Error(ErrText);
  }
}
/**
 * Mantiene la retrocompatibilità per il singolo oggetto utente, 
 * ma corregge il prefisso in minuscolo ('utente') per non rompere il parsing PHP.
 */
function UtenteToArrPar(utenteDump) 
{
  const _FF_ = 'CMN_JS.js(UtenteToArrPar)';
  const arrPar = [];

  try 
  {
    if (!utenteDump || typeof utenteDump !== 'object') 
    {
      let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Parametro utenteDump non corretto';
      alert(ErrText);
      throw new Error('utenteDump non valido');
    }

    // CORREZIONE CRITICA: 'utente' minuscolo per specchiarsi con $_POST['utente'] di PHP
    _flattenObjectToFormParameters(utenteDump, 'utente', arrPar);
    
    return arrPar;
  } 
  catch(err) 
  {
    let ErrText = 'ERRORE: FILE: ' + _FF_ + ' Errore: ' + err.name + ' - ' + err.message;
    console.error(ErrText);
    throw new Error(ErrText);
  }
}