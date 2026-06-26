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

  $THIS_FILE=basename(__FILE__,".php");;
  $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
 
  /*
   * DIRECTORY E FILE DI LOG
   */ 
  if (!is_dir("../logs"))
  {
    /*
     * La directory di log non esiste, quindi echo
     */
    echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
          "FROM: {$FROM} - ".
          "FUNCTION: {$THIS_FUNCTION} - " .
          "FILE: {$THIS_FILE}.php - ".
          "LINE: 21 - " .
          "EXCEPTION/ERROR: Directory ../logs not avaliable. - ";    
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
    /*
     * Non essendo disponibile il file di log, viene effettuato l' echo del messaggio
     */
    echo  "[" . (new \DateTime())->format("Y-m-d H:i:s") . "] - ".
          "FROM: {$FROM} - ".
          "FUNCTION: {$THIS_FUNCTION} - " .
          "FILE: {$THIS_FILE}.php - ".
          "LINE:".$e->getLine(). " - " .
          "EXCEPTION/ERROR:".$e->getMessage();  
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
