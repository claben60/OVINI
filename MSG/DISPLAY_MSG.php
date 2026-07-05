<!DOCTYPE html>
<?php
  /*
   * DisplayMSG: Scrive un messaggio relativo all' azionedell' utente
   * DisplayMSG: funzione che prende in input: 
   *             $FROM    = utente/funzionalita' che ha iniziato l' azione
   *             $Act     = azione da intraprendere 
   *             $Par     = Dati cercati
   *             $ArrData = Dati usati per la ricerca
   */ 
  /*
   * DISPLAYMSG
   */
  function DisplayMSG($FROM, $Act, $Par, $ArrData)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    /*
     * $Title e $Msg sono interni al blocco try. Se non sono dichiarati esterni, non dono visibili
     * dal codice html
     */ 
    $Title="";
    $Msg="";
    try
    {
      if($Act=="LOGIN_F") 
      {
        //echo "\n\n". var_dump($Par)."\n\n".var_dump($ArrData).
        $Title="SACS - RINNOVO PASSWORD";
        $Msg="Password aggiornata correttamente.";

        $Msg=$Msg . "<br><br>Ora si deve fare login con la nuova password";
      }
      if($Act=="UPR_U") 
      {
        //echo "\n\n". var_dump($Par)."\n\n".var_dump($ArrData).
        $Title="SACS - RECUPERO UTENZA";
        $Msg="L&apos; UTENTE: <br>"; 
        $Msg=$Msg . "<br>" . "nome" ." : " . $Par[0];
        $Msg=$Msg . "<br>" . "cognome" ." : " . $Par[1];
        $Msg=$Msg . "<br>" . "CF" ." : " . $Par[2];

        $Msg=$Msg . "<br><br>HA UTENZA : " . $ArrData[0]["UID"] .""; 
      }
      if($Act=="UPR_P") 
      {
        //echo "\n\n". var_dump($Par)."\n\n".var_dump($ArrData).
        $Title="SACS - RECUPERO PASSWORD";
        $Msg="L&apos; UTENTE: <br>"; 
        $Msg=$Msg . "<br>" . "nome" ." : " . $Par[0];
        $Msg=$Msg . "<br>" . "cognome" ." : " . $Par[1];
        $Msg=$Msg . "<br>" . "CF" ." : " . $Par[2];

        $Msg=$Msg . "<br><br>HA PASSWORD : " . $ArrData[0]["PWD"] .""; 

        $Msg=$Msg . "<br><br>NOTA: Alla prossima login sara' richiesto di cambiare la password.";
      }
      if($Act=="UPR_R") 
      {
        //echo "\n\n". var_dump($Par)."\n\n".var_dump($ArrData).
        $Title="SACS - REGISTRAZIONE";
        $Msg="L&apos; UTENTE: <br>"; 
        $Msg=$Msg . "<br>" . "nome" ." : " . $Par[0];
        $Msg=$Msg . "<br>" . "cognome" ." : " . $Par[1];
        $Msg=$Msg . "<br>" . "CF" ." : " . $Par[2];

        $Msg=$Msg . "<br><br>E&apos; STATO REGISTRATO."; 

        $Msg=$Msg . "<br><br>NOTA: E&apos; ora necessario ottenere le ABILITAZIONI, e poi si potra&apos; iniziare l' attivita&apos;";
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
    <link rel="stylesheet" href="../MSG/DISPLAY_MSG.css">      
    <title><?php echo $Title ?></title>
  </head>
    <body>
      <table class="MainTbl">
        <tbody>
          <tr>
            <td style="width: 25%; text-align: left;">
              <img class="HeadIcon" style="text-align:left;" alt="" src="../icone/IcoVoid_50x50.png">
              <img class="HeadIcon" style="text-align:left;" alt="" src="../icone/IcoLogo_50x50.png"></td>
            <td style="width: 50%; text-align: center;"><?php echo $Title ?></td>
            <td style="width: 25%; text-align: right;">
              <img class="HeadIcon" style="text-align:right;" alt="" src="../icone/IcoLogo_50x50.png">
              <img class="HeadIcon" style="text-align:right;" alt="" src="../icone/IcoVoid_50x50.png">
            </td>
          </tr>
          <tr>
            <td class="imgcontainer" style="border:5px solid seagreen" colspan="3" rowspan="1" ><br>
              <img class="image" alt="Goat waiting" src="../icone/Goat01_250x250.png">
              <p><br><br><?php echo $Msg ?><br><br></p>            
              <a Style="color:darkblue" href="../login/00_Login.html">
                LOGIN
              </a>    
              <br><br>
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








