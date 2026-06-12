
    <section>
      <th id="ID_DivLst" class="C_DivLst">
        <table id="ID_TblLst" class="C_TblLst"> 
          <thead> 
            <tr>      
              <th style="width:10%; text-align:center">
                <img class="C_IcoHdr" src="../icone/IcoLogo_50x50.png">
              </th>
              <th style="width:10%; text-align:center">
                <img ID="ID_MnuOpen" class="C_IcoHdr" style="text-align:left;" alt="" src="../icone/IcoMenu100.png" onclick="DisplayMnu('O','<?php echo $HDRTXT; ?>')">
                <!--img ID="ID_AddClose" class="C_IcoHdr" style="text-align:left;" alt="" src="./icone/IcoQuit100.png" onclick="Add('C')"-->
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
                <img ID="ID_SrcBtn" class="C_IcoHdr" style="text-align:right;" alt="" src="../icone/IcoSearch.png" onclick="DoVisibleSrc('S')">
                <img ID="ID_NoSrcBtn" class="C_IcoHdr" style="text-align:right;" alt="" src="../icone/IcoFilterRemove.png" onclick="DoVisibleSrc('T');">    
              </th>
              <th style="width:10%; text-align:center">
                <img ID="ID_BtnRld" class="C_IcoHdr" style="text-align:right;" alt="" src="../icone/IcoReload_50x50.png" onclick="location.reload()" >  
              </th>
            </tr>  

            
            <tr ID="ID_UsdFltrRow">      
              <th ID="ID_UsdFltrTh" colspan="5" style="width:100%;">
                <div ID="ID_UsdFltrAll" >  
                  <button class="UsdFltrAll" type="submit" onclick="Fltr('O');">
                      Filtri...<img  style="text-align:center; width:20px; height:15px;" alt="" src="../icone/IcoFilter.png">
                  </button>
                </div>
                <div ID="ID_UsdFltrNome" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Nome</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>                  
                <div ID="ID_UsdFltrNomeAOC" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">NomeAOC</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>           
                <div ID="ID_UsdFltrNascita" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Nascita</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>       
                <div ID="ID_UsdFltrSesso" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Sesso</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>      
                <div ID="ID_UsdFltrPadre" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Padre</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>   
                <div ID="ID_UsdFltrMadre" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Madre</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>                
                <div ID="ID_UsdFltrGravida" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Gravida</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>    
                <div ID="ID_UsdFltrDaTenere" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Tenere</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>      
                <div ID="ID_UsdFltrVaccini" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Vaccini</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div> 
                <div ID="ID_UsdFltrAlimen" onclick="Fltr('O');" >
                  <button class="UsdFltrLft">Alimentaz.</button>
                  <button type="submit" class="UsdFltrRght">
                    <img class="IcoBtnUsdFltr" src="../icone/IcoFilter.png">
                  </button> 
                </div>     
                  
              </th>
            </tr>    
            
            
            
            
          </thead>    
          <tbody>
            <!--tr>
              <td style="width:10%; text-align:left"><a href="#" onclick="Mnu('O')">Mnu</a></td>
              <td style="width:10%; text-align:center"><a href="#" onclick="Fltr('O')">Fltr</a></td>
              <td style="width:60%; text-align:center"><a href="#" onclick="FltrPg('O')">FPg</a></td>
              <td style="width:10%; text-align:center"><a href="#" onclick="Add('O')">Add</a></td>
              <td style="width:10%; text-align:right"><a href="#" onclick="AddResumePg('O')">APg</a></td>                
            </tr-->
<?php 
  if ($ABIL == "G00") 
  {
    switch ($MnuAct) 
    {
      case "SITI":
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
          echo "                <tr>";
          echo "                  <td colspan=\"3\" style=\"width:80%; text-align:center\">";
          echo "                    <!--p>{$Rec["Sito"]}<br>{$Rec["Indirizzo"]}</p-->";
          echo "                    <div>{$Rec["Sito"]}</div><br><div>{$Rec["Indirizzo"]}</div>";
          echo "                  </td>";
          echo "                  <td colspan=\"2\" style=\"width:20%; text-align:right\">";
          echo "                    <img ID=\"ID_ModificaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoModifica_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" >";
          echo "                    <br>";
          echo "                    <img ID=\"ID_EliminaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoElimina_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" > ";
          echo "                  </td>";
          echo "                </tr>";
          $Ctr++;
        }
        break;
      case "USR":
        
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
          echo "                <tr>";
          echo "                  <td colspan=\"3\" style=\"width:80%; text-align:left\">";
          echo "                    <div>Sito:<b>{$Rec["Sito"]}</b></div>";
          echo "                    <div><b>{$Rec["Nome"]}&nbsp;&nbsp;{$Rec["Cognome"]}</b></div>";
          echo "                    <div>Abil:<b>{$Rec["Descrizione"]}</b></div>";
          echo "                  </td>";
          echo "                  <td colspan=\"2\" style=\"width:20%; text-align:right\">";
          echo "                    <img ID=\"ID_ModificaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoModifica_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" >";
          echo "                    <br>";
          echo "                    <img ID=\"ID_EliminaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoElimina_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" > ";
          echo "                  </td>";
          echo "                </tr>";
          $Ctr++;
        }
        break;
      case "DIZ_ABIL":
        $Ctr = 0;
        foreach ($RetArr as $Rec) 
        {
          echo "                <tr>";
          echo "                  <td colspan=\"3\" style=\"width:80%; text-align:left\">";
          echo "                    <div>Abil:<b>{$Rec["ID_Abil"]}</b></div>";
          echo "                    <div><b>{$Rec["Descrizione"]}</b></div>";
          echo "                    <div>";
          echo "                      <b>{$Rec["Icona"]}</b><img  class=\"C_IcoLst\" style=\"text-align:center;\" alt=\"\" src=\"{$Rec["ICONA"]}\">";
          echo "                    </div>";
          echo "                  </td>";
          echo "                  <td colspan=\"2\" style=\"width:20%; text-align:right\">";
          echo "                    <img ID=\"ID_ModificaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoModifica_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" >";
          echo "                    <br>";
          echo "                    <img ID=\"ID_EliminaN1\" class=\"C_IcoHdr\" style=\"text-align:right;\" alt=\"\" src=\"../icone/IcoElimina_BN_50x50.png\" onclick=\"alert('Modifica'+{$Rec["ID_Sito"]})\" > ";
          echo "                  </td>";
          echo "                </tr>";
          $Ctr++;
        }
        break;        
    }
  } 
?>            
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
            <tr>
              <td style="width:10%; text-align:left">Left Col.</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr>
              
            <tr>
              <td style="width:10%; text-align:left">Left Col. Last</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:60%; text-align:center">Cell cnt  longer</td>
              <td style="width:10%; text-align:center">Cell cnt </td>
              <td style="width:10%; text-align:right">Cell cnt </td>
            </tr> 
          </tbody>    
        </table> 
      </th>
    </section>
 
    <!-- BTNADD - INIZIO --> 
    <img ID="ID_AddBtn" class="C_AddBtn" src="../icone/IcoAddList-512x512.png" alt="" onclick="Add('O','New');" >

    <!-- BTNADD - FINE -->


