<?php
  /*
   * Questo file contiene il dispatcher e l' interprete delle azioni da compiere
   * In genere e' quello che in caso di malfunzionamento ferma l' esecuzione,
   * ma il messaggio di errore e' emesso da chi lo rileva
   */
  /*
   * NOTA: require è identico a include tranne che in caso di 
   *       errore produce anche una eccezione Error.
   *       @require significa di non mostrare errori diagnostici.
   */
  $FROM="SRVR";
  $ACT="";
  //$FUNCTION="SRVR(main)";

  /*
   * HTML ERRORE FATALE - Usato solo se WriteLog non è disponibile
   */
  function NoLogError($msg, $line = 0, $file = '', $function = '') 
  {
    http_response_code(500);
    $ora = (new \DateTime())->format("Y-m-d H:i:s");
    echo <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="SACS - Pagina di blocco per errore fatale di sistema." />
  <title>SACS - ERRORE DI SISTEMA</title>
  <style>
    *, *::before, *::after 
    {
      box-sizing: border-box;
    }
    body 
    {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #00ff7f; /* SpringGreen */
      color: #000000;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .sys-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 20px;
      background-color: rgba(255, 255, 255, 0.3);
      font-weight: bold;
    }
    .header-title {
      font-size: 1.2rem;
    }
    .icon-group {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .HeadIcon {
      width: 35px; 
      height: 35px;   
      object-fit: contain;
    }
    .sys-container {
      flex: 1;
      max-width: 600px;
      width: 90%;
      margin: 40px auto;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }
    .sys-image {
      width: 100px;
      height: auto;
      margin-bottom: 10px;
    }
    h2 {
      font-size: 1.8rem;
      margin: 0;
    }
    .Advice {
      font-size: 1.2rem;
      font-weight: bold;
      margin: 5px 0;
    }
    .error-box { 
      background: white; 
      border: 2px solid #f5c6cb; 
      padding: 25px; 
      border-radius: 8px; 
      max-width: 90%; 
      margin: 20px auto; 
      box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
      text-align: left;
      color: #721c24;
    }
    .Address {
      font-size: 1rem;
      background: rgba(255, 255, 255, 0.5);
      padding: 20px;
      border-radius: 8px;
      width: 100%;
      max-width: 400px;
      margin-top: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      line-height: 1.6;
    }
    @media screen and (max-width: 480px) {
      .sys-header { padding: 10px 12px; }
      .header-title { font-size: 1.05rem; }
      .icon-group { gap: 4px; }
      .HeadIcon { width: 28px; height: 28px; }
    }
  </style>
</head>
<body>
  <header class="sys-header">
    <div class="icon-group">
      <img class="HeadIcon" alt="" src="../ICONE/IcoVoid_50x50.png">
      <img class="HeadIcon" alt="SACS Logo" src="../ICONE/IcoLogo_50x50.png">
    </div>
    <div class="header-title">SACS - ERRORE</div>
    <div class="icon-group">
      <a href="/ovini/Index.html" aria-label="Torna all'inizio">
        <img class="HeadIcon" alt="Indietro" src="../ICONE/IcoBack_50x50.png">
      </a>
      <img class="HeadIcon" alt="Ricarica" src="../ICONE/IcoReload_50x50.png" onclick="location.reload()">
    </div>
  </header>
  <main class="sys-container">
    <img class="sys-image" alt="Icona Errore Critico" src="../ICONE/IcoErr-250x250.png">
    <h2>ERRORE DI SISTEMA</h2>
    <div class="error-box">
      <p>
         [{$ora}] <br>
         <strong>FUNCTION:</strong> {$function}<br>
         <strong>FILE:</strong> {$file}<br>
         <strong>LINE:</strong> {$line} <br>
         <strong>ERRORE:</strong> {$msg}
      </p>
    </div>
      <p class="Advice">SI FERMA L'ELABORAZIONE</p>
      <div class="Address">
        <strong>PER CONTATTARE L'ASSISTENZA SACS:</strong><br>
        TEL: XXX XXX XXXX<br>
        MAIL: aaaa@bbb.cccc
      </div>
    </main>
  </body>
</html>
HTML;
  }


  $THIS_FILE=basename(__FILE__,".php");
  /*
   * nelle funzioni si usa:
   * $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
   * qui si usa invece:
   */
  $THIS_FUNCTION=$THIS_FILE."(main)";
  /*
   * DIRECTORY E FILE DI LOG
   */ 
  if (!is_dir("../LOGS"))
  {
    NoLogError("Directory ../LOGS not available.", 157, $THIS_FILE,$THIS_FUNCTION);
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  try 
  {
    //e' prima la richiesta fatta dal programma, avere le risorse per scrivere su output
    require "./SRVR_LOG.php";
  } 
  catch (Exception | Error $e) 
  {
    NoLogError($e->getMessage(), $e->getLine(), $THIS_FILE,$THIS_FUNCTION);
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  /*
   * Da ora i file di log sono disponibili:
   */
  WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_LOG avaliable", NULL);
  /*
   * MSG - DIRECTORY E FILE DI MESSAGGI UTENTE
   */ 
  /*
   * NOTA: Non si richiede il file SYS_ERR.html, perche' non viene interpretato dal server,
   * quindi l' istruzione:
   * require "../MSG/SYS_ERR.html"
   * significa di scriverlo immediatamente, e quindi o esiste o va in errore
   * la richiesta a questo punto significa mostrare SYS_ERR incondizionatamente.
   */
  if (!is_dir("../MSG"))
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Directory ../MSG not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Directory ../MSG not avaliable.");
    die();
  }
  try 
  {
    require "../MSG/DISPLAY_ERR.php";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File DISPLAY_ERR avaliable", NULL);
    require "../MSG/DISPLAY_MSG.php";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File DISPLAY_MSG avaliable", NULL);
  } 
  catch (Exception | Error $e) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "DISPLAY_ERR or DISPLAY_MSG not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    die();    
  }
  /*
   * SRVR_CLASS - OGGETTI DEL PROGRAMMA
   */ 
  try
  {  
    require "./SRVR_CLASS.php"; // Oggetti del Programma
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_CLASS avaliable", NULL);
  }
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "A file in the directory SRVR is not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    die();
  }
  /*
   * SRVRDB - GESTIONE DEL DB
   */ 
  try 
  {  
    require "./SRVRDB_CLASS.php"; // Oggetti del DB
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_CLASS avaliable", NULL);
    require "./SRVRDB_INI.php";   // Parametri di inizializzazione per l' accesso ad DB
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_INI avaliable", NULL);
    require "./SRVRDB_LOGIN_ACT.php";   // Funzioni ad alto livello e medio livello di accesso ai dati 
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_LOGIN_ACT avaliable", NULL);    
    require "./SRVRDB_SQL.php";   // Funzioni a basso livello di accesso ai dati
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_SQL avaliable", NULL);
  }
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "A file in the directory SRVR is not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    die();
  }
  /* 
   * LETTURA DEI PARAMETRI DI POST - INIZIO 
   * FrmName, SrvrAction, FrmFrom, Sector, IDAction, ArrPar
   */
  // Usiamo l'operatore coalescente per intercettare l'assenza dei parametri

  WriteLog("S", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "POST RECEIVED", NULL);

  $FORMNAME   = $_POST["FRMNAME"] ?? NULL;
  $FROM_POST   = $_POST["FROM"] ?? NULL;
  $SECTOR = $_POST["SECTOR"] ?? NULL;

  // Se i dati minimi strutturali non esistono, fermiamo tutto usando una stringa di fallback sicura per il log
  if ( $FROM_POST === NULL || $SECTOR === NULL || $FORMNAME ===NULL ) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "Error: Missing vital POST parameters (FROM, SECTOR,FORMNAME)", NULL);
    WriteErr($FROM, $THIS_FUNCTION."[post]", $THIS_FILE, __LINE__, "POST structure invalid or empty.");
    require "../MSG/SYS_ERR.html";
    die();
  }

  WriteLog("F", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "RECEIVED POST VALIDATED", $_POST);

  try 
  {
    echo "_POST: " . var_dump($_POST);
    $postObj = new clPost($_POST);

    // Se devi fare un log completo in formato var_dump/PrintArray:
    WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "Oggetto clPost istanziato con successo", $postObj->toLogArray());
  } 
  catch (Exception $e) 
  {
    WriteLog("F", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "Error: unable to build postObj. Parameters:", $_POST);
    WriteErr($FROM, $THIS_FUNCTION."[post]", $THIS_FILE, __LINE__, "Unable to build postObj: " . $e->getMessage());
    require "../MSG/SYS_ERR.html";
    die();
  }

  switch($SECTOR)
  {
    case "LOGIN":
      try 
      {
        require "../SRVR/SRVR_LOGIN.php";
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_LOGIN avaliable", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "SRVR_LOGIN not avaliable", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        
        unset($postObj);
        require "../MSG/SYS_ERR.html";
        die();    
      }
      try
      {
        /*
         * NOTA DI RIFATTORIZZAZIONE: Passiamo l'oggetto tipizzato $postObj 
         * invece di $_POST per coerenza con il nuovo modello a oggetti.
         * La funzione Login() in SRVR_LOGIN.php dovrà essere aggiornata per accettarlo.
         */
        Login($FROM, $postObj);
      }
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN]", $THIS_FILE, "Login error", NULL);
        
        // Usiamo il nuovo approccio standardizzato per scrivere l'errore con i dettagli dell'oggetto
        WriteErr($FROM, $THIS_FUNCTION."[LOGIN]", $THIS_FILE, $e->getLine(), $e->getMessage());
        
        unset($postObj);
        die();
      }      
      break;
    /*
     * NON INTERPRETABILE
     */      
    default:
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: POST action(SECTOR) not found", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: POST action(SECTOR) not found. Got:".$SECTOR);
      unset($postObj);
      require "../MSG/SYS_ERR.html";
      die();             
  }  
  /*  
   * ==========================================
   *  PUNTO UNICO DI CHIUSURA E PULIZIA RISORSE
   * ==========================================
   */
  // Liberiamo la memoria eliminando l'oggetto prima dell'output
  if (isset($postObj)) 
  {
    unset($postObj);
  }

  // Unica uscita fisica dello script
  WriteLog("S", $FROM, "SRVR(main)", $THIS_FILE, "SRVR.PHP END", null);
  exit();
?>
