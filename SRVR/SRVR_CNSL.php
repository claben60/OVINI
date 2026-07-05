<?php
  /**
   * Funzione Console: Server-side POST redirect
   * Usata quando SRVR deve rilanciare se stesso con parametri diversi
   */
  function Cnsl($FROM, $FrmName, $SrvrAction, $FrmFrom, $Sector, $IDAction, $ArrPar = [])
  {
    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE. "(". __FUNCTION__. ")";

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} WRITING FORM", NULL);
    try
    {
      // Form nascosto con ID dinamico
      echo '<form id="'. htmlspecialchars($FrmName). '" action="'. htmlspecialchars($SrvrAction). '" method="POST" style="display:none;">';

      // Parametri fissi di instradamento SACS
      echo '<input type="hidden" name="FRMNAME" value="'. htmlspecialchars($FrmName). '">';
      echo '<input type="hidden" name="SECTOR" value="'. htmlspecialchars($Sector). '">';
      echo '<input type="hidden" name="IDACTION" value="'. htmlspecialchars($IDAction). '">';
      echo '<input type="hidden" name="FROM" value="'. htmlspecialchars($FrmFrom). '">';

      // Iniezione parametri variabili da ArrPar
      foreach ($ArrPar as $param)
      {
        if (isset($param['name']) && isset($param['value']))
        {
          echo '<input type="hidden" name="'. htmlspecialchars($param['name']). '" value="'. htmlspecialchars($param['value']). '">';
        }
      }

      echo '</form>';

      // Log parametri scritti
      WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} FORM WRITTEN", array_merge(
        ['FRMNAME' => $FrmName, 'SECTOR' => $Sector, 'IDACTION' => $IDAction, 'FROM' => $FrmFrom],
        array_column($ArrPar, 'value', 'name')
      ));

      // Submit automatico
      echo '<script>document.getElementById("'. htmlspecialchars($FrmName). '").submit();</script>';
      exit;
    }
    catch (Exception | Error $e)
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} Cannot write a form", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require_once "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} Cannot write a form: ". $e->getMessage());
    }
  }
?>