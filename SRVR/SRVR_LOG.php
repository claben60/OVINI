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
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$ERR_FILE}.php - ".
            "LINE: ". $e->getLine() . " - ".
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
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
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$THIS_FILE} - ".
            "LINE: ". $e->getLine() . " - ".
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
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
    $ERR_FILE=basename(__FILE__,".php");
    $ERR_FUNCTION=$ERR_FILE."(".__FUNCTION__ .")";


    $ERRFILE = C_LOG_DIR."SACS.err";
    try 
    {
      $ErrFile = fopen($ERRFILE, "a+");
    } 
    catch ( Exception | Error $e) 
    {
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$ERR_FILE} - ".
            "LINE: ". $e->getLine() . " - ".
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
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
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$THIS_FILE} - ".
            "LINE: ". $e->getLine() . " - ".
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
  }

  /*
   * WriteJrnl: Scrive su un file di log delle azioni SQL (JournalFile) messaggi di tipo:
   * [ aa-mm-gg hh:mm:ss ] + TestoSQL + ** param1 = 'value1' ** param2 = 'value2' **... ** +
   */

  function WriteJrnl($FROM,$QryArr)
  {
    $THIS_FILE=basename(__FILE__);
    $ERR_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    $JRNLFILE = C_LOG_DIR."SACS.jrnl";
    try 
    {
      $JrnlFile = fopen($JRNLFILE, "a+");
    } 
    catch ( Exception | Error $e)
    {
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$THIS_FILE} - ".
            "LINE: ". $e->getLine() . " - " .
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
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
      /*
       * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
       */
      echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
            "FROM: {$FROM} - ".
            "FUNCTION: {$ERR_FUNCTION} - " .
            "FILE: {$THIS_FILE} - ".
            "LINE: ". $e->getLine() . " - ".
            "EXCEPTION/ERROR: " . $e->getMessage() . " - ";
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$ERR_FUNCTION} failed");    
    }
  }


?>