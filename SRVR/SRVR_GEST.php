<?php
function Gest($FROM, $_POST)
{
  $THIS_FILE=basename(__FILE__,".php");
  $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";    
  
  $IDACTION=$_POST["IDACTION"]    
  switch ($IDACTION) 
  {
    case "GEST_OPEN":
      try 
      {
        @require "../GEST/100_GestContainer.php";
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} file 100_GestContainer not found", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} file 100_GestContainer not found");      
      }
      try
      {
        $FROM=$_POST["FROM"];
        $MNU_ACT=$_POST["MNU_ACT"];
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} MANAGEMENT CONTAINER START", NULL);
        GestContainer($FROM, $_POST,$MNU_ACT);
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} MANAGEMENT CONTAINER END", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "got error from GestContainer", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
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
}
?>