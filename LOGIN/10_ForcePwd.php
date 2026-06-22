<?php
function ForcePwd($FROM, $ArrRec)
{
  // Lettura del record utente passato in input
  $Row = $ArrRec;
  
  // Interrompiamo momentaneamente il blocco PHP per inviare l'HTML fluido nativo senza errori sintattici
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="User data retrieving and mandatory password update." />
    <title>SACS - NUOVA PASSWORD NECESSARIA</title>
    <link rel="stylesheet" href="../LOGIN/10_ForcePwd.css">
    <script src="../CMN_JS/CMN_JS.js"></script>
    <script src="../LOGIN/10_ForcePwd.js"></script>
  </head>
  <body>

    <!-- Header Semantico e Flessibile -->
    <header class="force-header">
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
      </div>
      <div class="header-title">SI DEVE CAMBIARE LA PASSWORD</div>
      <div class="icon-group">
        <a href="./00_Login.html" onclick="window.history.go(-1); return false;" aria-label="Torna indietro">
          <img class="HeadIcon" alt="Indietro" src="../icone/IcoBack_50x50.png">
        </a>
        <img class="HeadIcon" alt="Ricarica" src="../icone/IcoReload_50x50.png" onclick="location.reload()">
      </div>
    </header>

    <!-- Contenitore Principale (Struttura Fluida senza Tabelle) -->
    <main class="main-container">
      
      <img class="hero-image" alt="Modifica password" src="../icone/CambiaPWD.png">
      
      <section class="form-card">
        <p><strong>IMMETTERE I NUOVI DATI</strong></p>
        
        <!-- Box Informativo Dati Utente Corrente protetti da attacchi XSS -->
        <div class="user-info-box">
          <strong>Nome:</strong> <?php echo htmlspecialchars($Row["Nome"], ENT_QUOTES, 'UTF-8'); ?><br>
          <strong>Cognome:</strong> <?php echo htmlspecialchars($Row["Cognome"], ENT_QUOTES, 'UTF-8'); ?><br>
          <strong>CF:</strong> <?php echo htmlspecialchars($Row["CF"], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        
        <!-- Form Nativo per ottimizzare l'uso della tastiera virtuale mobile -->
        <form onsubmit="event.preventDefault(); Validate('<?php echo htmlspecialchars($FROM, ENT_QUOTES, 'UTF-8'); ?>','<?php echo htmlspecialchars($Row['CF'], ENT_QUOTES, 'UTF-8'); ?>');" style="margin-top: 20px;">
          
          <div class="form-group">
            <label for="ID_PWD">Nuova Password</label>
            <input id="ID_PWD" name="PWD" type="password" placeholder="Immettere la password" required autocomplete="new-password">
          </div>
          
          <div class="form-group">
            <label for="ID_CONFPWD">Conferma password</label>
            <input id="ID_CONFPWD" name="CONFPWD" type="password" placeholder="Confermare la password" required autocomplete="new-password">
          </div>

          <p class="warning-text">ATTENZIONE! Dopo il cambio della password si deve accedere di nuovo con le nuove credenziali.</p>

          <button type="submit" class="btn-submit">Fatto!</button>
        </form>
      </section>

    </main>

  </body>
</html>
<?php
  return;  
}
?>
