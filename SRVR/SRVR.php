<?php
  /*
   * Questo file contiene il dispatcher e l' interprete delle azioni da compiere
   * In genere e' quello che in caso di malfunzionamento ferma l' esecuzione,
   * ma il messaggio di errore e' emesso da chi lo rileva
   */
  /*
   * NOTA: require è identico a include tranne che in caso di 
   *       errore produce anche una eccezione Error.
   *       @require significa di non mostrare errori diagnostici.
   */
  $FROM="SRVR";
  $ACT="";
  //$FUNCTION="SRVR(main)";

  $THIS_FILE=basename(__FILE__,".php");
  /*
   * nelle funzioni si usa:
   * $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
   * qui si usa invece:
   */
  $THIS_FUNCTION=$THIS_FILE."(main)";
  /*
   * Preparazione del testo di errore.
   */
  $errNoLog1 = <<<HTMLTEXT1
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="SACS - Pagina di blocco per errore fatale di sistema." />
  <title>SACS - ERRORE DI SISTEMA</title>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #00ff7f; /* SpringGreen */
      color: #000000;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .sys-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 20px;
      background-color: rgba(255, 255, 255, 0.3);
      font-weight: bold;
    }
    .header-title {
      font-size: 1.2rem;
    }
    .icon-group {
      display: flex;
      gap: 10px;
      align-items: center;
    }
    .HeadIcon {
      width: 35px; 
      height: 35px;   
      object-fit: contain;
    }
    .sys-container {
      flex: 1;
      max-width: 600px;
      width: 90%;
      margin: 40px auto;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }
    .sys-image {
      width: 100px;
      height: auto;
      margin-bottom: 10px;
    }
    h2 {
      font-size: 1.8rem;
      margin: 0;
    }
    .Advice {
      font-size: 1.2rem;
      font-weight: bold;
      margin: 5px 0;
    }
    .error-box { 
      background: white; 
      border: 2px solid #f5c6cb; 
      padding: 25px; 
      border-radius: 8px; 
      max-width: 90%; 
      margin: 20px auto; 
      box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
      text-align: left;
      color: #721c24;
    }
    .Address {
      font-size: 1rem;
      background: rgba(255, 255, 255, 0.5);
      padding: 20px;
      border-radius: 8px;
      width: 100%;
      max-width: 400px;
      margin-top: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      line-height: 1.6;
    }
    @media screen and (max-width: 480px) {
      .sys-header { padding: 10px 12px; }
      .header-title { font-size: 1.05rem; }
      .icon-group { gap: 4px; }
      .HeadIcon { width: 28px; height: 28px; }
    }
  </style>
</head>
<body>
  <header class="sys-header">
    <div class="icon-group">
      <img class="HeadIcon" alt="" src="../icone/IcoVoid_50x50.png">
      <img class="HeadIcon" alt="SACS Logo" src="../icone/IcoLogo_50x50.png">
    </div>
    <div class="header-title">SACS - ERRORE</div>
    <div class="icon-group">
      <a href="/ovini/Index.html" aria-label="Torna all'inizio">
        <img class="HeadIcon" alt="Indietro" src="../icone/IcoBack_50x50.png">
      </a>
      <img class="HeadIcon" alt="Ricarica" src="../icone/IcoReload_50x50.png" onclick="location.reload()">
    </div>
  </header>
  <main class="sys-container">
  <img class="sys-image" alt="Icona Errore Critico" src="../icone/IcoErr-250x250.png">
  <h2>ERRORE DI SISTEMA</h2>
HTMLTEXT1;
  $errNoLog3 = <<<HTMLTEXT3
    <p class="Advice">SI FERMA L'ELABORAZIONE</p>
    <div class="Address">
      <strong>PER CONTATTARE L'ASSISTENZA SACS:</strong><br>
      TEL: XXX XXX XXXX<br>
      MAIL: aaaa@bbb.cccc
    </div>
  </main>
</body>
</html>
HTMLTEXT3;
  /*
   * DIRECTORY E FILE DI LOG
   */ 
  if (!is_dir("../logs"))
  {
    $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
    http_response_code(500); 
    $errNoLog2 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> 21 <br>
         <strong>EXCEPTION/ERROR:</strong> Directory ../logs not available.
      </p>   
    </div>
HTMLTEXT2;     
    echo $errNoLog1.$errNoLog2.$errNoLog3;  
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  try 
  {
    //e' prima la richiesta fatta dal programma, avere le risorse per scrivere su output
    @require "./SRVR_LOG.php";
  } 
  catch (Exception | Error $e) 
  {
    $oraCrash = (new \DateTime())->format("Y-m-d H:i:s");
    http_response_code(500); 
    $errNoLog2 = <<<HTMLTEXT2
    <div class="error-box">
      <p>
         [{$oraCrash}] <br>
         <strong>FUNCTION:</strong> {$THIS_FUNCTION} <br>
         <strong>FILE:</strong> {$THIS_FILE}.php<br>
         <strong>LINE:</strong> {$e->getLine()} <br>
         <strong>EXCEPTION/ERROR:</strong>{$e->getMessage()}
      </p>   
    </div>
HTMLTEXT2;     
    echo $errNoLog1.$errNoLog2.$errNoLog3;  
    /*
     * Non trova una risorsa e finisce
     */
    die();
  }
  /*
   * Da ora i file di log sono disponibili
   */
  WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File SRVR_LOG avaliable", NULL);
?>