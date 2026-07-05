<?php
  /*
   * SERVER LOG DIRECTORY - INIZIO
   */
  define("C_LOG_DIR", "../logs/");
  /*
   * SERVER LOG DIRECTORY - FINE
   */
  /*
   * HTML ERRORE FATALE - Copia di NoLogError, ma locale a SRVR_LOG
   * Usato SOLO se non riesco a scrivere log
   */
/*
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
/*      color: #000000;
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
  */

  /**
   * Appiattisce un array multidimensionale in notazione a staffa per i log
   * Input: ['utente'=>['ID_USR'=>'SACS', 'Siti'=>[0=>['SITE'=>'SACS']]]]
   * Output: "\nutente[ID_USR]:SACS\nutente[Siti][0][SITE]:SACS"
   */
  function PrintArray($arr, $prefix = '') 
  {
    $lines = [];
    foreach ($arr as $key => $value) 
    {
      $fullKey = $prefix === ''? $key : $prefix. '['. $key. ']';
      if (is_array($value)) 
      {
        $lines[] = PrintArray($value, $fullKey);
      } 
      else 
      {
        $lines[] = "\n". $fullKey. ":". $value;
      }
    }
    return implode('', $lines);
  }

  /*
   * PrintScalar: dispatcher che formatta Form o Query per il log
   * Type: F=Form array, Q=Query oggetto con metodo print()
   */
  function PrintScalar($FROM, $Type, $DTS)
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE. "(". __FUNCTION__. ")";

    $RetTxt="";
    switch ($Type) 
    {  
      case "F":
        $RetTxt=PrintArray($DTS, '');
        break;
      case "Q":
          $RetTxt= $DTS->print();
        break;
      default:
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: parameter {$Type} not known", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: parameter {$Type} not known", NULL);
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - parameter {$Type} not known");
    }
    return $RetTxt;
  }

  /*
   * WriteLog: Scrive su SACS.log
   * Formato: [timestamp] - FROM:... - FUNCTION:... - FILE:... - HDR - DATI
   * Type: F=Form, Q=Query, S=String
   */
  function WriteLog($Type, $FROM, $FUNCTION, $FILE, $HDR, $DT)
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE. "(". __FUNCTION__. ")";

    $LOGFILE = C_LOG_DIR. "SACS.log";

    if (!$LogFile = fopen($LOGFILE, "a+")) 
    {
      NoLogError("Impossibile aprire il file di log", 72, $THIS_FILE, $THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }

    $LogTxt = "[". (new \DateTime())->format("Y-m-d H:i:s"). "] - ".
              "FROM: {$FROM} - ".
              "FUNCTION: {$FUNCTION} - ".
              "FILE: {$FILE} - ";

    if ($HDR!== "") 
    {
      $LogTxt.= $HDR. " - ";
    }

    try 
    {
      if ($Type === "F") 
      {
        $LogTxt.= "FORM: ". $DT["FRMNAME"]. " Parameters:". PrintScalar($FROM, "F", $DT);
      }
      if ($Type === "Q") 
      {
        $LogTxt.= PrintScalar($FROM, "Q", $DT). " -";
      }
      if ($Type === "S") 
      {
        $LogTxt.= $DT. " -";
      }
    } 
    catch (Exception | Error $e) 
    {
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} - got error calling PrintScalar");
    }

    try 
    {
      fwrite($LogFile, $LogTxt. "\n");
      fclose($LogFile);
    } 
    catch (Exception | Error $e) 
    {
      NoLogError($e->getMessage(), $e->getLine(), $THIS_FILE,$THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }
  }

  /*
   * WriteErr: Scrive su SACS.err
   */
  function WriteErr($FROM, $FUNCTION, $FILE, $eLine, $eMsg)
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE. "(". __FUNCTION__. ")";
    $ERRFILE = C_LOG_DIR. "SACS.err";

    if (!$ErrFile = fopen($ERRFILE, "a+")) 
    {
      NoLogError("Impossibile aprire il file degli errori", 130, $THIS_FILE, $THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }

    try 
    {
      $ErrTxt = "[". (new \DateTime())->format("Y-m-d H:i:s"). "] - ".
                "FROM: {$FROM} - ".
                "FUNCTION: {$FUNCTION} - ".
                "FILE: {$FILE} - ".
                "LINE: ". $eLine. " - ".
                "EXCEPTION/ERROR: ". $eMsg. " - ";
      fwrite($ErrFile, $ErrTxt. "\n");
      fclose($ErrFile);
    } 
    catch (Exception | Error $e) 
    {
      NoLogError($e->getMessage(), $e->getLine(), $THIS_FILE,$THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }
  }

  /*
   * WriteJrnl: Scrive su SACS.jrnl le query SQL
   */
  function WriteJrnl($FROM, $QryArr)
  {
    
    $THIS_FILE = basename(__FILE__);
    $THIS_FUNCTION = $THIS_FILE. "(". __FUNCTION__. ")";
    $JRNLFILE = C_LOG_DIR. "SACS.jrnl";

    if (!$JrnlFile = fopen($JRNLFILE, "a+")) 
    {
      NoLogError("Impossibile aprire il file di journalin", 164, $THIS_FILE, $THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }

    $JrnlTxt = "[". (new \DateTime())->format("Y-m-d H:i:s"). "] - ".
               "FROM: {$FROM} - ".
               PrintScalar($FROM, "Q", $QryArr);

    try 
    {
      fwrite($JrnlFile, $JrnlTxt. "\n");
      fclose($JrnlFile);
    } 
    catch (Exception | Error $e) 
    {
      NoLogError($e->getMessage(), $e->getLine(), $THIS_FILE,$THIS_FUNCTION);
      throw new Exception("{$THIS_FUNCTION} failed");
    }
  }
?>