<?php

  try 
  {
    @require "../GEST/200_GestMenu_INI.php";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 200_GestMenu_INI avaliable", NULL);
  } 
  catch (Exception | Error $e) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "200_GestMenu_INI not avaliable", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    die();    
  }
  
?>
    <section>
      <div id="ID_GestDivMnu" class="C_GestDivMnu">
        <table id="ID_GestTblMnu" class="C_GestTblMnu"> 
          <thead> 
            <tr>      
              <th style="width:10%; text-align:center">
                <img class="C_GestIcoHdr" alt="" src="../icone/IcoLogo_50x50.png">
              </th>
              <th ID="ID_MnuHeader" colspan="3" style="width:80%; text-align:center"><?php echo $HDRTXT ?></th>
              <th style="width:10%; text-align: center">
                <img class="C_GestIcoHdr" alt="" src="../icone/IcoRimuovi-500.png" onclick="DisplayMnu('C')">
              </th>
            </tr>  
          </thead>          
          <tbody>
<?php
  foreach($MnuItemsArr as $Key => $Item) 
  { 
    $ItemAct=$Item["ACT"];
    $ItemRef=$Item["REFRESH"];
    $ItemNome=$Item["NOME"];
    $ItemFnct=$Item["QUERY_RIC_ACT"];
    if(($Item["LIV"])==0)
    {
      $HtmlStr = 
  <<<EOD
              <tr>
                <td colspan="5" style="width:100%;">
                  <a href="#" ID="ID_$Item[ACT]" OnClick="FormatMnu('$FROM','$SITE','$ABIL','$ItemAct','$ItemFnct',$ItemRef)"><i>$ItemNome</i></a>
                </td>
              </tr>\n 
  EOD;        
      echo $HtmlStr;  
    }
    if(($Item["LIV"])==1)
    {
      $HtmlStr = 
  <<<EOD
              <tr>
                <td style="width:10%; text-align:left"></td>
                <td colspan="2" style="width:90%; text-align:left">
                  <a href="#" ID="ID_$Item[ACT]" OnClick="FormatMnu('$FROM','$SITE','$ABIL','$ItemAct','$ItemFnct',$ItemRef)"><i>$ItemNome</i></a>
                </td>
              </tr>\n  
  EOD;        
      echo $HtmlStr;  
    }
    if(($Item["LIV"])==2)
    {
      $HtmlStr = 
  <<<EOD
              <tr>
                <td colspan="2" style="width:20%; text-align:left"></td>
                <td colspan="2" style="width:70%; text-align:left">
                  <a href="#" ID="ID_$Item[ACT]" OnClick="FormatMnu('$FROM','$SITE','$ABIL','$ItemAct','$ItemFnct',$ItemRef)"><i>$ItemNome</i></a>
                </td>
              </tr>\n  
  EOD;        
      echo $HtmlStr;  
    }
  }
?>  
            <!--tr>      
              <td colspan="3" style="width:100%; text-align:left">
                  <a ID="ID_SOC" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','SOC',false)"><b>GESTIONE SOCIETA&#39;</b></a>
              </td>
            </tr>
            <tr>      
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_SITI" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','SITI',true)"><b>Siti</b></a>
              </td>
            </tr>
            <tr>      
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_UTENTI" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','USR',true)"><i>Utenti</i></a>
              </td>
            </tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
              </td>          
            <tr>
            <tr>
              <td style="width:10%; text-align:left">
              </td>
              <td colspan="2" style="width:80%; text-align:left">
                <a ID="ID_DIZ_SOC" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','DIZ_SOC',false)"><i>Dizionari(Soc.)</i></a><br>
              </td> 
            </tr>              
            <tr>
              <td colspan="2" style="width:20%; text-align:left"></td>
              <td colspan="2" style="width:70%; text-align:left">
                <a ID="ID_DIZ_ABIL" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','DIZ_ABIL',true)"><i>Abilitazioni(DIZ.)</i></a><br>
              </td>
              <td style="width:10%; text-align:right"></td>
            </tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
                <a ID="ID_GEST" href="#" OnClick="FormatMnu('GEST')"><i>GESTIONALE</i></a>
              </td>
            </tr>  
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_FORN" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','FORN',true)"><i>Fornitori</i></a>
              </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_ACQ" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','ACQ',true)"><i>Acquisti</i></a>
              </td>
            </tr>              
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_CLI" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','CLI',true)"><i>Clienti</i></a>
              </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_VEN" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','VEN',true)"><i>Vendite</i></a>
              </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_PN" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','GEST_PM',true)"><i>Prima Nota</i></a>
              </td>
            </tr>            
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_STAT" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','STAT',true)"><i>Statistiche</i></a>
              </td>
            </tr>   
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
              </td>          
            <tr>
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_APP_DIZ" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','APP_DIZ',true)"><i>Dizionari(App)</i></a>
              </td>
            <tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
              </td>          
            <tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <a ID="ID_MEM" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','GEST_PM',false)"><i>PRO MEMORIA</i></a>
              </td>
            </tr>  
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
              </td>          
            <tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <a ID="ID_GESTFIL" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','GEST_FIL',true)"><i>GESTIONE FILE</i></a>
              </td>
            </tr>  
            <tr>            
              <td colspan="5" style="width:100%;">
                <hr>
              </td>    
            </tr>
            <tr>
              <td colspan="5" style="width:100%;">
                <a ID="ID_SALVA" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','SALVA',true)"><i>SALVATAGGI</i></a>
              </td>
            </tr>           
            <tr>
              <td colspan="5" style="width:100%;">
                <hr>
              </td>              
            <tr>                    
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a ID="ID_CNCT" href="#" OnClick="FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','CNCT',true)"><b>Contact</b></a>
              </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left"></td>
              <td colspan="2" style="width:90%; text-align:left">
                <a href="#"><b>About</b></a>
              </td>
            </tr-->  
          </tbody> 
          <tfoot>
            <tr>
              <td style="width:10%; text-align:center"></td>
              <td colspan="3" style="width:80%; text-align:center">Login come utente</td>
              <td style="width:10%; text-align: center"></td>
            </tr>
          </tfoot>            
        </table> 
      </div>
    </section>

