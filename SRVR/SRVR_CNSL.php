<?php
  /**
   * Funzione Console: Gestisce la manipolazione dei parametri ed esegue la sottomissione POST
  */
  function Cnsl($FROM,$FrmName, $SrvrAction, $FrmFrom, $Sector, $IDAction, $ArrPar= [])
  {
  
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} WRITING FORM ", NULL);
    try
    {
      echo '<form id="'.$FrmName.'" action="' . htmlspecialchars($SrvrAction) . '" method="POST" style="display:none;">';

      // Parametri fissi della struttura MakeForm
      echo '<input type="hidden" name="SECTOR" value="' . htmlspecialchars($Sector) . '">';
      echo '<input type="hidden" name="IDAction" value="' . htmlspecialchars($IDAction) . '">';
      echo '<input type="hidden" name="FrmFrom" value="' . htmlspecialchars($FrmFrom) . '">';

      // Iniezione dinamica di tutti i parametri nell'array (incluso l'oggetto utente a staffe)
      foreach ($ArrPar as $param) 
      {
        echo '<input type="hidden" name="' . htmlspecialchars($param['name']) . '" value="' . htmlspecialchars($param['value']) . '">';
      }

      echo '</form>';
      WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} FORM WRITTEN", $frmVirtualConsole);
      echo '<script>document.getElementById("frmVirtualConsole").submit();</script>';
      exit;
    }
    catch
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} Cannot write a form", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} Cannot write a form");         
    }
  }
?>
