
    <section>
      <th id="ID_GestDivLst" class="C_GestDivLst">
        <table id="ID_GestTblLst" class="C_GestTblLst"> 
          <thead> 
            <tr>      
              <th style="width:10%; text-align:center">
                <img class="C_GestIcoHdr" src="../icone/IcoLogo_50x50.png">
              </th>
              <th style="width:10%; text-align:center">
                <img ID="ID_MnuOpen" class="C_GestIcoHdr" style="text-align:left;" alt="" src="../icone/IcoMenu100.png" onclick="DisplayMnu('O','<?php echo $HDRTXT; ?>')">
                <!--img ID="ID_AddClose" class="C_GestIcoHdr" style="text-align:left;" alt="" src="./icone/IcoQuit100.png" onclick="Add('C')"-->
              </th>
              <th style="width:60%; text-align:center">
                <!--input ID="ID_HdrTxt" type="text" disabled style="text-align:center;height:30px; border:0;" value="<?php echo $HDRTXT; ?> "-->
                  <p ID="ID_HdrTxt" type="text" style="text-align:center;height:30px; border:0;"><?php echo $HDRTXT; ?></p>                 
                <input ID="ID_CmbSrcTxt" class="C_CmbSrcTxt" style="height:30px;" type="search" placeholder="Search.." onkeyup="txtFilter();" >
<?php
  echo "            <button ID=\"ID_CmbSrcBtn\" class=\"C_CmbSrcBtn\" type=\"submit\" style=\"height:30px;\" onclick=\"Fltr('O')\">\n";
  echo "              <img ID=\"ID_CmbSrcIco\" class=\"C_IcoBtn\" style=\"text-align:center; width:20px; height:20px;\" alt=\"\" src=\"../icone/IcoFilter.png\">\n";
  echo "            </button>\n";
?>                       
              </th>  
              <th style="width:10%; text-align:center">
                <img ID="ID_SrcBtn" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoSearch.png" onclick="DoVisibleSrc('S')">
                <img ID="ID_NoSrcBtn" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoFilterRemove.png" onclick="DoVisibleSrc('T');">    
              </th>
              <th style="width:10%; text-align:center">
                <img ID="ID_BtnRld" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoReload_50x50.png" onclick="location.reload()" >  
              </th>
            </tr>  
          </thead>    
          <tbody>
<?php 
  if ($ABIL == "G00") 
  {
    switch ($MnuAct) 
    {
      case "SITI":
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
          $HtmlStr = 
  <<<EOD
                <tr>
                  <td colspan="3" style="width:80%; text-align:center">
                    <div>{$Rec["Sito"]}</div><br><div>{$Rec["Indirizzo"]}</div>
                  </td>
                  <td colspan="2" style="width:20%; text-align:right">
                    <img ID="ID_ModificaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoModifica_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" >
                    <br>
                    <img ID="ID_EliminaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoElimina_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" > 
                  </td>
                </tr>
  EOD;        
          echo $HtmlStr."\n";  
          $Ctr++;
        }
        break;
      case "USR":
        
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
      $HtmlStr = 
  <<<EOD
                <tr>
                  <td colspan="3" style="width:80%; text-align:left">
                    <div>Sito:<b>{$Rec["Sito"]}</b></div>
                    <div><b>{$Rec["Nome"]}&nbsp;&nbsp;{$Rec["Cognome"]}</b></div>
                    <div>Abil:<b>{$Rec["Descrizione"]}</b></div>
                  </td>
                  <td colspan="2" style="width:20%; text-align:right">
                    <img ID="ID_ModificaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoModifica_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" >
                    <br>
                    <img ID="ID_EliminaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoElimina_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" > 
                  </td>
                </tr>
  EOD;        
          echo $HtmlStr."\n";  
          $Ctr++;
        }
        break;
      case "DIZ_ABIL":
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
          $HtmlStr = 
  <<<EOD
                <tr>
                  <td colspan="3" style="width:80%; text-align:left">
                    <div>Abil:<b>{$Rec["ID_Abil"]}</b></div>
                    <div><b>{$Rec["Descrizione"]}</b></div>
                    <div>
                      <b>{$Rec["Icona"]}</b><img  class="C_IcoLst" style="text-align:center;" alt="" src="{$Rec["ICONA"]}">
                    </div>
                  </td>
                  <td colspan="2" style="width:20%; text-align:right">
                    <img ID="ID_ModificaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoModifica_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" >
                    <br>
                    <img ID="ID_EliminaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoElimina_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" > 
                  </td>
                </tr>
  EOD;        
          echo $HtmlStr."\n";  
          $Ctr++;
        }
        break;        
      case "PROMEM":
        $Ctr = 0;
        if( $RetArr==NULL)
        {
           $HtmlStr = 
  <<<EOD
                <tr>
                  <td colspan="3" style="width:80%; text-align:left">
                    <div><b>Nessun promemoria disponibile<br></b></div>
                  </td>
                  <td colspan="2" style="width:20%; text-align:right">
                  </td>
                </tr> 
  EOD;        
          echo $HtmlStr."\n";          
        }
        else
        {
          foreach ($RetArr as $Rec) 
          {
            $HtmlStr = 
  <<<EOD
                <tr>
                  <td colspan="3" style="width:80%; text-align:left">
                    <div ID="ID_PM{$Ctr}" style="display:none">{$Rec["ID"]}</div>
                    <div><b>{$Rec["DataOpen"]}</b></div>
                    <div><b>{$Rec["Motivo"]}</b></div>
                  </td>
                  <td colspan="2" style="width:20%; text-align:right">
                    <img ID="ID_ModificaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoModifica_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" >
                    <br>
                    <img ID="ID_EliminaN1" class="C_GestIcoHdr" style="text-align:right;" alt="" src="../icone/IcoElimina_BN_50x50.png" onclick="alert('Modifica'+{$Rec["ID_Sito"]})" > 
                  </td>
                </tr> 
  EOD;        
            echo $HtmlStr."\n";  
            $Ctr++;
          }
        }
        break;        
    }
  } 
?>            
          </tbody>    
        </table> 
      </th>
    </section>
 
    <!-- BTNADD - INIZIO -->
<?php
    switch ($MnuAct) 
    {
      case "SITI":
        $Action="ADD_GEST_SITI";
        break;
      case "USR":
        $Action="ADD_GEST_USR";
        break;
      case "DIZ_ABIL":
        $Action="ADD_DIZ_SOC_ABIL";
        break;
    }
?>
    <img ID="ID_GestAddBtn" class="C_GestAddBtn" src="../icone/IcoAddList-512x512.png" alt="" onclick="GestAdd('O','New');" >

    <!-- BTNADD - FINE -->


