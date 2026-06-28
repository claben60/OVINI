<?php
  /**
   * Funzione Console: Gestisce la manipolazione dei parametri ed esegue la sottomissione POST
   */
  function Cnsl($FROM, $FrmName, $SrvrAction, $FrmFrom, $Sector, $IDAction, $ArrPar = [])
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
    
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} WRITING FORM ", NULL);
    try
    {
      // Generazione form nascosto con ID dinamico coerente
      echo '<form id="' . htmlspecialchars($FrmName) . '" action="' . htmlspecialchars($SrvrAction) . '" method="POST" style="display:none;">';

      // Parametri fissi di instradamento
      echo '<input type="hidden" name="FN" value="' . htmlspecialchars($FrmName) . '">';
      echo '<input type="hidden" name="SECTOR" value="' . htmlspecialchars($Sector) . '">';
      echo '<input type="hidden" name="IDAction" value="' . htmlspecialchars($IDAction) . '">';
      echo '<input type="hidden" name="FROM" value="' . htmlspecialchars($FrmFrom) . '">';

      // Iniezione dinamica dei parametri variabili
      foreach ($ArrPar as $param) 
      {
        if (isset($param['name']) && isset($param['value'])) 
        {
          echo '<input type="hidden" name="' . htmlspecialchars($param['name']) . '" value="' . htmlspecialchars($param['value']) . '">';
        }
      }

      echo '</form>';
      
      // Tracciamento dei parametri della form appena scritta
      WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} FORM WRITTEN", $ArrPar);
      
      // Esecuzione del submit puntando all'ID corretto della form
      echo '<script>document.getElementById("' . htmlspecialchars($FrmName) . '").submit();</script>';
      exit;
    }
    catch (Exception | Error $e)
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} Cannot write a form", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require_once "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} Cannot write a form: " . $e->getMessage());         
    }
  }
?>
