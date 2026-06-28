<?php
  /*
   *  SERVER LOG DIRECTORY - INIZIO 
   */
  define("C_LOG_DIR", "C:/xampp/htdocs/Ovini/logs/");
  /*
   *  SERVER LOG DIRECTORY - FINE 
   */
  /*
   * PrintScalar funzione che scrive come un' unica stringa il contenuto di una query o di una form,
   * per adattarla al formato dei log.
   * Potrebbe essere anche messa in un file SRVR_CMN
   * PrintScalar: funzione che prende in input: 
   *              Type        - il tipo di struttura dati da stampare (F=Form, Q=Query)
   *              Arg         - la referenza alla struttura dati
   */
  function PrintScalar($Type,$DTS)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";

    $RetTxt="";
    switch ($Type)
    {
      /*
       * FORM 
       */
      case "F":
        foreach ($DTS as $key => $value) 
        {
          $RetTxt = $RetTxt ."\n" . $key . ":" . $value;
        }
        break;
      /*
       * QUERY
       */ 
      case "Q":
        $RetTxt=$DTS->print();
        break;  
      default:
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: parameter {$Type} not known", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: parameter {$Type} not known", NULL);
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - parameter {$Type} not known");
    }
    return  $RetTxt;
  }
  /*
   * trattamento errori nel caso non sono disponibili i files
   */
$errNoLog1 = <<<HTMLTEXT1
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="SACS - Pagina di blocco per errore fatale di sistema." />
  <title>SACS - ERRORE DI SISTEMA</title>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
    }
    body {
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
HTMLTEXT1;
  $errNoLog3 = <<<HTMLTEXT3
    <p class="Advice">SI FERMA L'ELABORAZIONE</p>
    <div class="Address">
      <strong>PER CONTATTARE L'ASSISTENZA SACS:</strong><br>
      TEL: XXX XXX XXXX<br>
      MAIL: aaaa@bbb.cccc
    </div>
  </main>
</body>
</html>
HTMLTEXT3;
  /*
   * WriteLog: Scrive su un file di log messaggi contenuti in una riga di tipo:
   * [ aa-mm-gg hh:mm:ss ] + FROM (origine dell' azione)  + FILE E FUNZIONE - HEADER - DATI (Form, Query o Stringa) - TIPO (F;Q;S)
   * WriteLog: funzione che prende in input: 
   *              Type        - il tipo di struttura dati da stampare (F=Form, Q=Query, S=String)
   *              FROM        - L' ID della funzione e dell' utente che ha generato la richiesta
   *              FUNCTION    - Il nome del file ed eventualmente la funzione che gera la richiesta 
   *                            nel formato: FILE(FUNZIONE)
   *              HDR         - Stringa. Un messaggio utente
   *              DT          - DT - Struttura dati (Form, Query, ""=Stringa)
   */ 
  function WriteLog($Type, $FROM, $FUNCTION, $FILE, $HDR, $DT)
  {
    global $errNoLog1, $errNoLog3;
    
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
        
    $LOGFILE = C_LOG_DIR."SACS.log";
    /*
     * fopen: Restituisce una risorsa puntatore a file in caso di successo, o false in caso di fallimento
     *        In caso di errore, viene inviato un E_WARNING.
     * 
     * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
     */      
    try 
    {
      $LogFile = fopen($LOGFILE, "a+");
    } 
    catch ( Exception | Error $e) 
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoLog2_1 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 74 <br>
         <strong>EXCEPTION/ERROR:</strong> {$LOGFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoLog2_1.$errNoLog3; 
      throw new Exception("{$ERR_FUNCTION} failed");    
    } 
    $LogTxt = "";  
    $LogTxt = "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - " .
              "FROM: {$FROM} - ".
              "FUNCTION: {$FUNCTION} - " .
              "FILE: {$FILE} - ";
    if($HDR !== "")
    {
      $LogTxt=$LogTxt . $HDR . " - "; 
    }
    try
    {
      if($Type==="F")
      {
        $LogTxt=$LogTxt . "FORM: ". $DT["FN"] . " Parameters:" . PrintScalar("F",$DT);
      }
      if($Type==="Q")
      {
        $LogTxt=$LogTxt . PrintScalar("Q",$DT) . " -";      }
    }
    catch ( Exception | Error $e)
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling PrintScalar", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} - got error calling PrintScalar");
    }
    try
    {
      fwrite($LogFile, $LogTxt . "\n" );
      fclose($LogFile);
    }
    catch ( Exception | Error $e) 
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoLog2_2 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 253 <br>
         <strong>EXCEPTION/ERROR:</strong> {$LOGFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoLog2_2.$errNoLog3; 
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
  }
  /*
   * WriteErr: Scrive su un file di errore messaggi contenuti in una riga di tipo:
   * [ aa-mm-gg hh:mm:ss ] + Stringa con i dati dell' errore (linea, tipo...)
   * WriteErr: funzione che prende in input: 
   *           $FROM   - utente e routine che usa il modulo, 
   *           $FILE   - Il file dove e' la routine che emette il messaggio,  
   *           $eLine  - la linea del file dove si verifica l' errore: e->getline,
   *           $eMsg   - il messaggio di errore emesso dal sistema e->message.
   * es:
    $ErrTxt = "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - FROM: SRVR ". 
              " - FILE: SRVR.php - line: " . $e->getLine() . 
              " + Non e' possibile leggere i dati di post. - Exception/Error: " . $e->getMessage() . " +";
  */
  function WriteErr($FROM, $FUNCTION, $FILE, $eLine, $eMsg)
  { 
    global $errNoLog1, $errNoLog3;

    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";


    $ERRFILE = C_LOG_DIR."SACS.err";
    try 
    {
      $ErrFile = fopen($ERRFILE, "a+");
    } 
    catch ( Exception | Error $e) 
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoErr2_1 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 297 <br>
         <strong>EXCEPTION/ERROR:</strong> {$ERRFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoErr2_1.$errNoLog3; 
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
    try
    {
      $ErrTxt = ""; 
      $ErrTxt = "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
                "FROM: {$FROM} - ".
                "FUNCTION: {$FUNCTION} - " .
                "FILE: {$FILE} - ".
                "LINE: ". $eLine . " - ".
                "EXCEPTION/ERROR: " . $eMsg . " - ";
      fwrite($ErrFile, $ErrTxt . "\n" );
      fclose($ErrFile);
    }
    catch ( Exception | Error $e) 
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoErr2_2 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 326 <br>
         <strong>EXCEPTION/ERROR:</strong> {$ERRFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoErr2_2.$errNoLog3;     throw new Exception("{$ERR_FUNCTION} failed");    
    }
  }

  /*
   * WriteJrnl: Scrive su un file di log delle azioni SQL (JournalFile) messaggi di tipo:
   * [ aa-mm-gg hh:mm:ss ] + TestoSQL + ** param1 = 'value1' ** param2 = 'value2' **... ** +
   */

  function WriteJrnl($FROM,$QryArr)
  {
    global $errNoLog1, $errNoLog3;

    $THIS_FILE=basename(__FILE__);
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    $JRNLFILE = C_LOG_DIR."SACS.jrnl";
    try 
    {
      $JrnlFile = fopen($JRNLFILE, "a+");
    } 
    catch ( Exception | Error $e)
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoJrnl2_1 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 364 <br>
         <strong>EXCEPTION/ERROR:</strong> {$JRNLFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoJrnl2_1.$errNoLog3;
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
    $JrnlTxt="";
    $JrnlTxt = "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - " .
               "FROM: {$FROM} - ".
               PrintScalar("Q",$QryArr) . "\n"; 

    try
    {
      fwrite($JrnlFile, $JrnlTxt . "\n" );
      fclose($JrnlFile);
    }
    catch ( Exception | Error $e) 
    {
      $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
      http_response_code(500); 
    $errNoJrnl2_2 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 391 <br>
         <strong>EXCEPTION/ERROR:</strong> {$JRNLFILE} not avaliable
      </p>   
    </div>
HTMLTEXT2;        
      echo $errNoLog1.$errNoJrnl2_2.$errNoLog3;
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
  }


?>