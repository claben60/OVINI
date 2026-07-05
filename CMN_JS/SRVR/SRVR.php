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
      <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
      <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
    </div>
    <div class="header-title">SACS - ERRORE</div>
    <div class="icon-group">
      <a href="/ovini/Index.html" aria-label="Torna all'inizio">
        <img class="HeadIcon" alt="Indietro" src="../icone/IcoBack_50x50.png">
      </a>
      <img class="HeadIcon" alt="Ricarica" src="../icone/IcoReload_50x50.png" onclick="location.reload()">
    </div>
  </header>
  <main class="sys-container">
    <img class="sys-image" alt="Icona Errore Critico" src="../icone/IcoErr-250x250.png">
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
  if (!is_dir("../logs"))
  {
    NoLogError("Directory ../logs not available.", 157, $THIS_FILE,$THIS_FUNCTION);
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
   * SRVR_CNSL - UTENTE (CONSOLE) VIRTUALE
   */ 
  try 
  {  
    require "./SRVR_CNSL.php"; // Oggetti del Programma
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_CNSL avaliable", NULL);
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
  try 
  {
    $FROM = $_POST["FROM"];
    $SECTOR=$_POST["SECTOR"];
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, $FROM.", ".$SECTOR, NULL);
    WriteLog("F", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "POST RECEIVED", $_POST);
  } 
  catch (Exception | Error  $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "Error reading post", NULL);
    WriteErr($FROM, $THIS_FUNCTION."[post]", $THIS_FILE, $e->getLine(), $e->getMessage());
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
        require "../MSG/SYS_ERR.html";
        die();    
      }
      try
      {
        Login($FROM, $_POST);
      }
      catch (Exception | Error $e) 
      {
        //catch exception
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN]", $THIS_FILE, "Login error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[LOGIN]", $THIS_FILE, $e->getLine(), $e->getMessage());
        /* NOTA:
         * In queste azioni la richiesta di mostrare che e' accaduto un errore di sistema, 
         * require "../MSG/SYS_ERR.html" la emette chi trova l' errore.
         */ 
        die();
      }      
      break;
    /*
     * POST NON INTERPRETABILE
     */         
    default:
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: POST action(SECTOR) not found", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: POST action(SECTOR) not found. Got:".$SECTOR);
      require "../MSG/SYS_ERR.html";
      die();             
  }
?>
