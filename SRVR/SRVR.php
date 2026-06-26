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

  $THIS_FILE=basename(__FILE__,".php");;
  $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
 
  /*
   * DIRECTORY E FILE DI LOG
   */ 
  if (!is_dir("../logs"))
  {
    /*
     * La directory di log non esiste, quindi echo
     */
    echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
          "FROM: {$FROM} - ".
          "FUNCTION: {$THIS_FUNCTION} - " .
          "FILE: {$THIS_FILE}.php - ".
          "LINE: 21 - " .
          "EXCEPTION/ERROR: Directory ../logs not avaliable. - ";    
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  try 
  {
    //e' prima la richiesta fatta dal programma, avere le risorse per scrivere su output
    @require "./SRVR_LOG.php";
  } 
  catch (Exception | Error $e) 
  {
    /*
     * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
     */
    echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
          "FROM: {$FROM} - ".
          "FUNCTION: {$THIS_FUNCTION} - " .
          "FILE: {$THIS_FILE}.php - ".
          "LINE:".$e->getLine(). " - " .
          "EXCEPTION/ERROR:".$e->getMessage();  
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  /*
   * Da ora i file di log sono disponibili
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
    @require "../MSG/DISPLAY_ERR.php";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File DISPLAY_ERR avaliable", NULL);
    @require "../MSG/DISPLAY_MSG.php";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File DISPLAY_MSG avaliable", NULL);
  } 
  catch (Exception | Error $e) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "DISPLAY_ERR or DISPLAY_MSG not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    die();    
  }
  /*
   * Da ora disponibili i file:
   * SYS_ERR, che avverte di un errore di sistema irrecoverabile
   * Tale messaggio e' emesso appena si verifica un errore. 
   * il programma chiamante si preoccupera' invece di fermare il
   * programma dopo le opportune azioni di chiusura.
   * DISPLAY_ERR - Errori utente
   * DISPLAY_MSG - Messaggi all' utente
   */
 /*
   * SRVR_CNSL - UTENTE (CONSOLE) VIRTUALE
   */ 
  try 
  {  
    @require "./SRVR_CNSL.php"; // Oggetti del Programma
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
    @require "./SRVR_CLASS.php"; // Oggetti del Programma
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
    @require "./SRVRDB_CLASS.php"; // Oggetti del DB
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_CLASS avaliable", NULL);
    @require "./SRVRDB_INI.php";   // Parametri di inizializzazione per l' accesso ad DB
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_INI avaliable", NULL);
    @require "./SRVRDB_LOGIN_ACT.php";   // Funzioni ad alto livello e medio livello di accesso ai dati 
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_LOGIN_ACT avaliable", NULL);
    @require "./SRVRDB_GEST_ACT.php";   // Funzioni ad alto livello e medio livello di accesso ai dati 
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVRDB_GEST_ACT avaliable", NULL);    
    @require "./SRVRDB_SQL.php";   // Funzioni a basso livello di accesso ai dati
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
  catch (Exception | Error | warning $e) 
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
        @require "../SRVR/SRVR_LOGIN.php";
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_LOGIN avaliable", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "SRVR_LOGIN not avaliable", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
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
    case "GEST":
      try 
      {
        @require "../SRVR/SRVR_GEST.php";
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_GEST avaliable", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "SRVR_GEST not avaliable", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        die();    
      }
      try
      {      
        Gest($FROM, $_POST);
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
    case "APP":
      try 
      {
        @require "../SRVR/SRVR_GEST.php";
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_GEST avaliable", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "SRVR_GEST not avaliable", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        die();    
      }
      try
      {      
        App($FROM, $_POST);
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
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: POST action(FIDA) not found", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: POST action(FIDA) not found. Got:".$FIDA);
      require "../MSG/SYS_ERR.html";
      die();             
  }
?>