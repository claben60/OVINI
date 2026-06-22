<?php
function Choose($FROM, $InArrRec)
{
  $THIS_FILE = basename(__FILE__, ".php");
  $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";    
  
  $ID_USR = "";
  $UID = "";
  $UsrName = "";
  $UsrSurname = "";
  $Permission = [];
  $Siti = [];
  $ReadSite = "";

  // 1. STRUTTURAZIONE DEI DATI PROVENIENTI DALLA QUERY SQL
  for($Ctr = 0; $Ctr < count($InArrRec); $Ctr++)
  {
    if($Ctr == 0)
    {
      $ID_USR = $InArrRec[$Ctr]["ID_Utente"];
      $UID = $InArrRec[$Ctr]["UID"];
      $UsrName = $InArrRec[$Ctr]["Nome"];
      $UsrSurname = $InArrRec[$Ctr]["Cognome"];
    }
    
    if($ReadSite != $InArrRec[$Ctr]["sito"])
    {
      $ReadSite = $InArrRec[$Ctr]["sito"];
      $Siti[] = $ReadSite;  
    }
    
    $Permission[] = [ 
      'Sito'         => $InArrRec[$Ctr]["sito"],
      'Abilitazione' => $InArrRec[$Ctr]["ID_Abil"],
      'Management'   => $InArrRec[$Ctr]["Management"],
      'Descrizione'  => $InArrRec[$Ctr]["Descrizione"],
      'Icona'        => $InArrRec[$Ctr]["Icona"]
    ];
  }

  // Generazione nativa automatica dell'array per JavaScript
  $Siti_jsStr = json_encode($Siti);
?>
<!DOCTYPE html>
<html lang="it">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Management of a sheep farm." />
    <title>SACS - Scelta Ambito</title>
    <link rel="stylesheet" href="../LOGIN/20_Choose.css">
    <script src="../COMMON/CMN_JS.js"></script>
    <script src="../LOGIN/20_Choose.js"></script>
  </head>
  <body onload="ChooseInitializePage(<?php echo htmlspecialchars($Siti_jsStr, ENT_QUOTES, 'UTF-8'); ?>)">

    <!-- Header Semantico Adattivo -->
    <header class="choose-header">
      <div class="icon-group">
        <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
        <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
      </div>
      <div class="header-title">SACS - SCELTA</div>
      <div class="icon-group">
        <a href="../Login/00_Login.html" aria-label="Torna al Login">
          <img class="HeadIcon" alt="Indietro" src="../icone/IcoBack_50x50.png">
        </a>
        <img class="HeadIcon" alt="Ricarica" src="../icone/IcoReload_50x50.png" onclick="location.reload()">
      </div>
    </header>

    <!-- Variabili di stato nascoste per Validate() di JavaScript -->
    <input type="hidden" id="ID_USR" value="<?php echo htmlspecialchars($ID_USR, ENT_QUOTES, 'UTF-8'); ?>" >
    <input type="hidden" id="ID_UID" value="<?php echo htmlspecialchars($UID, ENT_QUOTES, 'UTF-8'); ?>" >
    <input type="hidden" id="ID_SITE" value="">
    <input type="hidden" id="ID_ABIL" value="">
    <input type="hidden" id="ID_DESC" value="">

    <!-- Contenitore Centrale Unico con Layout Semantico Fluido -->
    <main class="main-container">
      
      <!-- Box Utente protetto da XSS -->
      <div class="user-badge">
        Utente: <strong><?php echo htmlspecialchars($UsrName . " " . $UsrSurname, ENT_QUOTES, 'UTF-8'); ?></strong> - ID: <em><?php echo htmlspecialchars($UID, ENT_QUOTES, 'UTF-8'); ?></em>
      </div>

      <!-- FASE 1: PULSANTI SELEZIONE SITO -->
      <section>
        <h2 class="section-title">1. Scegliere il sito di lavoro</h2>
        <div class="site-grid">
          <?php foreach ($Siti as $Sito): ?>
            <button type="button" class="btn-site" onclick="SetSite('<?php echo htmlspecialchars($Sito, ENT_QUOTES, 'UTF-8'); ?>', <?php echo htmlspecialchars($Siti_jsStr, ENT_QUOTES, 'UTF-8'); ?>)">
              <?php echo htmlspecialchars($Sito, ENT_QUOTES, 'UTF-8'); ?>
            </button>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- FASE 2: CARD DELLE ABILITAZIONI (Gestite in Grid ed evidenziate dinamicamente via idx) -->
      <section>
        <h2 class="section-title" id="ID_ParScelta">Scegliere un'abilitazione</h2>
        <div class="abil-grid">
          <?php foreach ($Permission as $idx => $Perm): ?>
            <div id="ID_Card_<?php echo $idx; ?>" class="abil-card <?php echo htmlspecialchars($Perm['Sito'], ENT_QUOTES, 'UTF-8'); ?>" 
                 onclick="SetAbil('<?php echo htmlspecialchars($Perm['Abilitazione'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($Perm['Descrizione'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo $idx; ?>', '<?php echo htmlspecialchars($Perm['Sito'], ENT_QUOTES, 'UTF-8'); ?>')">
              <img id="ID_Img_<?php echo $idx; ?>" class="abil-icon" src="<?php echo htmlspecialchars($Perm['Icona'], ENT_QUOTES, 'UTF-8'); ?>" alt="Icona">
              <div class="abil-title"><?php echo htmlspecialchars($Perm['Descrizione'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- FASE 3: CONFERMA E INVIO -->
      <button type="button" class="btn-confirm" onclick="Validate()">PROCEDI</button>

    </main>

  </body>
</html>
<?php
  return;  
}
?>
