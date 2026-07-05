<?php
  $THIS_FILE = basename(__FILE__, ".php");
  $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
  $FROM = "30_UPR";

  $Titolo = "";
  $BckGrndClr = "";
  $Purpose = "";
  $ParmTxt = "";
  $PgImg = "";

  // 1. VERIFICA PREVENTIVA STRUTTURA LOGS
  if (!is_dir("../logs")) 
  {
    header('Content-Type: text/html; charset=UTF-8');
    echo "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] FROM:SRVR - Error: the directory ../logs not exists.<br>";  
    die();
  }

  try 
  {
    @require "../SRVR/SRVR_LOG.php";
  } 
  catch (Exception | Error $e) {
    header('Content-Type: text/html; charset=UTF-8');
    echo "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] FROM:SRVR - Error loading log module.<br>";
    die();
  }

  // 2. RECUPERO PARAMETRI DA POST CON PROTEZIONE
  try 
  {
    $FIDA = isset($_POST["IDACTION"]) ? $_POST["IDACTION"] : '';
    $FROM = isset($_POST["FROM"]) ? $_POST["FROM"] : '30_UPR';
    
    if (function_exists('WriteLog')) 
    {
      WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - POST RECEIVED", $_POST);
    }
  }
  catch (Exception | Error $e) 
  {
    if (function_exists('WriteErr')) 
    {
      WriteErr($FROM, $THIS_FUNCTION."[post]", $THIS_FILE, $e->getLine(), $e->getMessage());
    }
    require "../MSG/SYS_ERR.html";
    die();    
  }

  // Normalizzazione dell'azione per allinearsi al file JavaScript ottimizzato
  $JsAct = "UPR_" . $FIDA; 

  // 3. CONFIGURAZIONE GRAFICA IN BASE ALL'AZIONE SCELTA
  switch ($FIDA) 
  { 
    case "U":
      $Titolo = "SACS - RECUPERO UTENZA";
      $BckGrndClr = "background-color: #ff7f50;"; /* Coral */      
      $Purpose = "SACS - RECUPERO UTENZA";
      $ParmTxt = "RECUPERA UTENZA";   
      $PgImg = "../icone/NoUID_250x250.png";
      break;      
    case "P":
      $Titolo = "SACS - RECUPERO PASSWORD";
      $BckGrndClr = "background-color: #ffff00;"; /* Yellow */      
      $Purpose = "SACS - RECUPERO PASSWORD";
      $ParmTxt = "RECUPERA PASSWORD";   
      $PgImg = "../icone/NoPassword_250x250.png";
      break;      
    case "R":
      $Titolo = "SACS - REGISTRAZIONE";
      $BckGrndClr = "background-color: #00ffff;"; /* Aqua */      
      $Purpose = "SACS - REGISTRAZIONE";
      $ParmTxt = "MI REGISTRO"; 
      $PgImg = "../icone/Registrati_250x250.png";
      break;
    default:
      // Protezione da accessi diretti malevoli senza dati di fondo
      header("Location: ./00_Login.html");
      die();
  }
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="User registration and credential retrieval system." />
    <title><?php echo htmlspecialchars($Titolo, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="30_UPR.css">
    <script src="../COMMON/CMN_JS.js"></script>
    <script src="30_UPR.js"></script>
  </head>
  <!-- Passiamo l'azione corretta ed estesa ($JsAct) a UPR_InitializePage() per azionare lo switch JS senza bug grafici -->
  <body style="<?php echo $BckGrndClr; ?>" onload="UPR_InitializePage('<?php echo htmlspecialchars($JsAct, ENT_QUOTES, 'UTF-8'); ?>')">

    <!-- Header Flessibile e Compatto su Mobile -->
    <header class="upr-header">
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
      </div>
      <div class="header-title"><?php echo htmlspecialchars($Purpose, ENT_QUOTES, 'UTF-8'); ?></div>
      <div class="icon-group">
        <a href="./00_Login.html" aria-label="Torna al Login">
          <img class="HeadIcon" alt="Indietro" src="../icone/IcoBack_50x50.png">
        </a>
        <img class="HeadIcon" alt="Ricarica" src="../icone/IcoReload_50x50.png" onclick="location.reload()">
      </div>
    </header>

    <!-- Contenitore Centrale Unico Organizzato -->
    <main class="main-container">
      
      <img class="hero-image" src="<?php echo htmlspecialchars($PgImg, ENT_QUOTES, 'UTF-8'); ?>" alt="Stato Richiesta">
      
      <section class="form-card">
        <!-- Form Nativo per abilitare l'invio tramite tasto Invio/Vai delle tastiere smartphone -->
        <form onsubmit="event.preventDefault(); Validate('<?php echo htmlspecialchars($JsAct, ENT_QUOTES, 'UTF-8'); ?>');">
          
          <!-- Campi Anagrafici Comuni -->
          <div class="form-group">
            <label for="ID_NOME">Nome</label>
            <input id="ID_NOME" autocomplete="name" name="name" type="text" placeholder="Immettere il nome" required>
          </div>
          
          <div class="form-group">
            <label for="ID_COGNOME">Cognome</label>
            <input id="ID_COGNOME" autocomplete="additional-name" name="COGNOME" type="text" placeholder="Immettere il cognome" required>
          </div>   
          
          <div class="form-group">
            <label for="ID_CF">Codice Fiscale</label>
            <input id="ID_CF" autocomplete="off" name="CF" type="text" placeholder="Immettere il codice fiscale" required oninput="this.value = this.value.toUpperCase()">                
          </div>

          <!-- Blocco Unico dei Campi facoltativi/condizionali gestito da JavaScript -->
          <div id="ID_BloccoRegistrazione" class="conditional-fields">
            
            <div class="form-group">
              <label id="ID_LBL_USR" for="ID_USR">Utenza desiderata</label>
              <input id="ID_USR" autocomplete="username" name="USR" type="text" placeholder="Immettere l'utenza">                
            </div>    
            
            <div class="form-group">
              <label id="ID_LBL_RESPWD" for="ID_RESPWD">Password</label>
              <input id="ID_RESPWD" autocomplete="new-password" name="PWD" type="password" placeholder="Immettere la password">               
            </div>
            
            <div class="form-group">
              <label id="ID_LBL_CONFPWD" for="ID_CONFPWD">Conferma password</label>
              <input id="ID_CONFPWD" autocomplete="new-password" name="CONFPWD" type="password" placeholder="Confermare la password">
            </div>

          </div>   

          <!-- Pulsante di Invio Dinamico -->
          <button type="submit" class="btn-submit"><?php echo htmlspecialchars($ParmTxt, ENT_QUOTES, 'UTF-8'); ?></button>
        </form>
      </section>

    </main>

  </body>
</html>
