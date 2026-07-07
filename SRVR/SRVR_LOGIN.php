<?php

function Login($FROM, clPost $postObj)
{
  $THIS_FILE=basename(__FILE__,".php");
  $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";    
  
  /*
   * LETTURA DEI PARAMETRI DALL'OGGETTO clPost
   */    
  $IDACTION = $postObj->getIDAction();
    
  switch ($IDACTION) 
  {
    //LOGIN 
    case "LOGIN_L":
      //$FROM=$FROM."->SRVR[LOGIN_L]";
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_L]", $THIS_FILE, "LOGIN - START", NULL);
        ActLogin($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_L]", $THIS_FILE, "LOGIN - END", NULL);
      } 
      catch (Exception | Error $e) 
      {
        //catch exception
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_L]", $THIS_FILE, "Login error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[LOGIN_L]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - Login error");    
      }
      break;
    // FORCE PASSWORD
    case "LOGIN_F":
      //$FROM=$FROM."->SRVR(LOGIN_F)";
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_F]", $THIS_FILE, "FORCE PWD START", NULL);
        ActForcePWD($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_F]", $THIS_FILE, "FORCE PWD END", NULL);
      } 
      catch (Exception | Error $e) 
      {
        //catch exception
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_F]", $THIS_FILE, "Force PWD error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[LOGIN_F]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - Force PWD error");    
      }
      break;
    /*
     * CHOOSE
     */ 
    case "LOGIN_C":
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_C]", $THIS_FILE, "USER CHOOSE START", NULL); 
        Choose($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_C]", $THIS_FILE, "USER CHOOSE END", NULL);       
      } 
      catch (Exception | Error $e) 
      {        
        WriteLog("S", $FROM, $THIS_FUNCTION."[LOGIN_C]", $THIS_FILE, "Choose error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[LOGIN_C]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - Choose error");  
      }
      break;
    /*
     * RECUPERO UID 
     */ 
    case "UPR_U":
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_U]", $THIS_FILE, "UID RECOVERY START", NULL);
        ActRecuperoUID($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_U]", $THIS_FILE, "UID RECOVERY END", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_U]", $THIS_FILE, "UID recovery error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[UPR_U]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - UID recovery error");  
      }
      break;
    /*
     * RECUPERO PWD
     */         
    case "UPR_P":
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_P]", $THIS_FILE, "PWD RECOVERY START", NULL);
        ActRecuperoPWD($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_P]", $THIS_FILE, "PWD RECOVERY END", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_P]", $THIS_FILE, "PWD recovery error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[UPR_P]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - PWD recovery error");  
      }
      break;
    /*
     * REGISTRAZIONE UTENTE
     */ 
    case "UPR_R":
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_R]", $THIS_FILE, "USER REGISTRATION START", NULL); 
        ActRegistrazione($FROM, $postObj);
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_R]", $THIS_FILE, "USER REGISTRATION END", NULL);       
      } 
      catch (Exception | Error $e) 
      {        
        WriteLog("S", $FROM, $THIS_FUNCTION."[UPR_R]", $THIS_FILE, "User registration error", NULL);
        WriteErr($FROM, $THIS_FUNCTION."[UPR_R]", $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - User registration error");  
      }
      break;
    /*
     * POST NON INTERPRETABILE
     */         
    default:
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: POST action(IDACTION) not found", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: POST action(IDACTION) not found. Got:".$IDACTION);
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - POST action(IDACTION) not found. Got:".$IDACTION);
  }
}
?>