<?php
  /**
   * DisplayErr: Scrive un messaggio diagnostico a fronte di un errore dell'azione utente.
   */ 
  function DisplayErr($FROM, $Act)
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
    
    $Title = "SACS - ERRORE";
    $Msg = "";

    try {
      switch ($Act) {
        case "LOGIN_L":
          $Title = "SACS - ERRORE NELLA LOGIN";
          $Msg = "UTENZA O PASSWORD ERRATI";
          break;
        case "LOGIN_F": 
          $Title = "SACS - CAMBIO FORZATO PASSWORD";
          $Msg = "NON È POSSIBILE CAMBIARE LA PASSWORD.<br>RIPROVARE.<br><br>SE L'ERRORE PERSISTE CONTATTARE SACS<br>TEL: XXX XXX XXXX<br>MAIL: aaaa@bbb.cccc";  
          break;
        case "UPR_U": 
          $Title = "SACS - RECUPERO UTENZA";
          $Msg = "NON ESISTE ALCUNA UTENZA<br>A FRONTE DEI DATI IMMESSI"; 
          break;
        case "UPR_P": 
          $Title = "SACS - RECUPERO PASSWORD";
          $Msg = "NON ESISTE ALCUNA PASSWORD<br>A FRONTE DEI DATI IMMESSI";  
          break;
        case "UPR_R": 
          $Title = "SACS - REGISTRAZIONE";
          $Msg = "LA REGISTRAZIONE È FALLITA<br>A FRONTE DEI DATI IMMESSI";  
          break;
        default:
          if (function_exists('WriteLog')) {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - action not found ", NULL);
          }
          if (function_exists('WriteErr')) {
              WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "{$THIS_FUNCTION} - action ({$Act}) not found ");
          }
          throw new Exception("{$THIS_FUNCTION} - action not found");
      }
    }
    catch (Exception | Error $e) {
      if (function_exists('WriteLog')) {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error", NULL);
      }
      if (function_exists('WriteErr')) {
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      }
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - got error");
    }    
    
    // CHIUDIAMO il blocco PHP per inviare in sicurezza l'HTML ed evitare il crash (Parse Error)
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Management of a sheep farm, diagnostics error page." />
    <link rel="stylesheet" href="../MSG/DISPLAY_ERR.css">      
    <title><?php echo htmlspecialchars($Title, ENT_QUOTES, 'UTF-8'); ?></title>
  </head>
  <body>

    <!-- Header Semantico Responsive -->
    <header class="err-header">
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
      </div>
      <div class="header-title"><?php echo htmlspecialchars($Title, ENT_QUOTES, 'UTF-8'); ?></div>
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <!-- Rimosso l'attributo onclick incoerente con il reload dell'errore post -->
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
      </div>
    </header>

    <!-- Card Centrale con Layout Fluido -->
    <main class="err-card">
      <img class="err-image" alt="Goat waiting" src="../icone/Goat01_250x250.png">
      <div class="err-text">
        <p><?php echo $Msg; ?></p>
      </div>
      
      <!-- Mostra il pulsante di ritorno solo se l'azione fallita è la login -->
      <?php if($Act == "LOGIN_L"): ?>
        <a class="btn-retry" href="../login/00_Login.html">CI RIPROVO: LOGIN</a>
      <?php endif; ?>
    </main>

  </body>
</html>
<?php
    return; // Fine regolare della funzione PHP
  }
?>
