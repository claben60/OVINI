<?php
  /*
   * SERVER LOG DIRECTORY - INIZIO
   */
  define("C_LOG_DIR", "../LOGS/");
  /*
   * SERVER LOG DIRECTORY - FINE
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