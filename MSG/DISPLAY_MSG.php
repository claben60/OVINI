<?php
  function DisplayMSG($FROM, $Act, $Par, $ArrData)
  {
    $THIS_FILE = basename(__FILE__,".php");
    $THIS_FUNCTION = $THIS_FILE."(".__FUNCTION__ .")";
    $Title = "SACS - NOTIFICA";
    $Msg = "";

    try {
      if ($Act == "LOGIN_F") {
        $Title = "SACS - RINNOVO PASSWORD";
        $Msg = "Password aggiornata correttamente.<br><br>Ora si deve fare login con la nuova password.";
      }
      if ($Act == "UPR_U" && isset($Par) && isset($ArrData[0]["UID"])) {
        $Title = "SACS - RECUPERO UTENZA";
        $Msg = "L'UTENTE:<br>"; 
        $Msg .= "<br>Nome: " . htmlspecialchars($Par[0], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>Cognome: " . htmlspecialchars($Par[1], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>CF: " . htmlspecialchars($Par[2], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br><br>HA UTENZA: <strong>" . htmlspecialchars($ArrData[0]["UID"], ENT_QUOTES, 'UTF-8') . "</strong>"; 
      }
      if ($Act == "UPR_P" && isset($Par) && isset($ArrData[0]["PWD"])) {
        $Title = "SACS - RECUPERO PASSWORD";
        $Msg = "L'UTENTE:<br>"; 
        $Msg .= "<br>Nome: " . htmlspecialchars($Par[0], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>Cognome: " . htmlspecialchars($Par[1], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>CF: " . htmlspecialchars($Par[2], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br><br>HA PASSWORD: <strong>" . htmlspecialchars($ArrData[0]["PWD"], ENT_QUOTES, 'UTF-8') . "</strong>"; 
        $Msg .= "<br><br><em>NOTA: Alla prossima login sarà richiesto di cambiare la password.</em>";
      }
      if ($Act == "UPR_R" && isset($Par)) {
        $Title = "SACS - REGISTRAZIONE";
        $Msg = "L'UTENTE:<br>"; 
        $Msg .= "<br>Nome: " . htmlspecialchars($Par[0], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>Cognome: " . htmlspecialchars($Par[1], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br>CF: " . htmlspecialchars($Par[2], ENT_QUOTES, 'UTF-8');
        $Msg .= "<br><br>È STATO REGISTRATO."; 
        $Msg .= "<br><br><em>NOTA: È ora necessario ottenere le ABILITAZIONI per iniziare l'attività.</em>";
      }
    }
    catch (Exception | Error $e) {
      if (function_exists('WriteLog')) { WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Got error", NULL); }
      if (function_exists('WriteErr')) { WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage()); }
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - got error");
    }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../MSG/DISPLAY_MSG.css">      
    <title><?php echo htmlspecialchars($Title, ENT_QUOTES, 'UTF-8'); ?></title>
  </head>
  <body>

    <header class="msg-header">
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <img class="HeadIcon" alt="SACS" src="../icone/IcoLogo_50x50.png">
      </div>
      <div class="header-title"><?php echo htmlspecialchars($Title, ENT_QUOTES, 'UTF-8'); ?></div>
      <div class="icon-group">
        <img class="HeadIcon" alt="SACS" src="../icone/IcoLogo_50x50.png">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
      </div>
    </header>

    <main class="msg-card">
      <img class="msg-image" alt="Goat waiting" src="../icone/Goat01_250x250.png">
      <div class="msg-text">
        <p><?php echo $Msg; ?></p>            
      </div>
      <a class="btn-login" href="../login/00_Login.html">LOGIN</a>    
    </main>

  </body>
</html>
<?php
    return;
  }
?>
