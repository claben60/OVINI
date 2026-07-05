<!DOCTYPE html>
<?php
  /*
   * DisplayERR: Scrive un messaggio diagnostico a fronte di una azione utente
   * DisplayERR: funzione che prende in input: 
   *             $Act     = azione da intraprendere 
   */ 
  /*
   * DISPLAYERR
   */
  function DisplayErr($FROM,$Act)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    try
    {
      switch ($Act)
      {
        case "LOGIN_L":
          $Title="SACS - ERORE NELLA LOGIN";
          $Msg="UTENZA O PASSWORD ERRATI";
          break;
        case "LOGIN_F": 
          $Title="SACS - CAMBIO FORZATO PASSWORD";
          $Msg="NON E&grave POSSIBILE CAMBIARE LA PASSWORD <br> RIPROVARE.<br>SE L&grave ERRORE PERSISTE CONTATTARE SACS<br>TEL: XXX XXX XXXX<br>MAIL:aaaa@bbb.cccc";  
          break;
        case "UPR_U": 
          $Title="SACS - RECUPERO UTENZA";
          $Msg="NON ESISTE ALCUNA UTENZA <br> A FRONTE DEI DATI IMMESSI"; 
          break;
        case "UPR_P": 
          $Title="SACS - RECUPERO PASSWORD";
          $Msg="NON ESISTE ALCUNA PASSWORD <br> A FRONTE DEI DATI IMMESSI";  
          break;
        case "UPR_R": 
          $Title="SACS - REGISTRAZIONE";
          $Msg="LA REGISTRAZIONE E&grave; FALLITA <br> A FRONTE DEI DATI IMMESSI";  
          break;
        default:
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - action not found ", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "{$THIS_FUNCTION} - action ({$Act}) not found ");
          throw new Exception("{$THIS_FUNCTION} - action not found");
      }
    }
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - got error");
    }    
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Management of a sheep farm, especially taking care that there are no inbreeding animals." />
    <meta name="keywords" content="Sheeps, goats, inbreeding animals " />
    <link rel="stylesheet" href="../MSG/DISPLAY_ERR.css">      
    <title><?php echo $Title ?></title>
  </head>
  <body>
    <table style="width: 100%; height:auto" border="0" >
      <tbody>
        <tr>
          <td style="text-align:top; width: 25%; text-align: left;">
            <img class="HeadIcon" style="text-align:left;" alt="" src="../icone/IcoVoid_50x50.png">
            <img class="HeadIcon" style="text-align:left;" alt="" src="../icone/IcoLogo_50x50.png"></td>
          <td style="text-align: top; width: 50%; text-align: center;"><?php echo $Title ?></td>
          <td style="text-align: top; width: 25%; text-align: right;">
            <img class="HeadIcon" style="text-align:right;" alt="" src="../icone/IcoVoid_50x50.png">
            <img class="HeadIcon" style="text-align:right;" alt="" src="../icone/IcoVoid_50x50.png" onclick="location.reload()" >
          </td>
        </tr>
        <tr>
          <td class="imgcontainer" colspan="3" rowspan="1" ><br>
            <img class="image" alt="Goat waiting" src="../icone/Goat01_250x250.png">
            <p><?php echo $Msg ?></p>
<?php              
    if($Act=="LOGIN_L")
    {
      echo" <a Style=\"color: black\" href=\"../login/00_Login.html\">\n";                
      echo"   CI RIPROVO: LOGIN\n";
      echo" </a>\n";  
    }
?>      
          </td>               
        </tr>
      </tbody>
    </table>
  </body>
</html>
<?php
    return;
  }
?>    
