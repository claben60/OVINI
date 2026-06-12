<?php
$ACT="ADD"
?>
<section ID="ID_AddSec">
      <div id="ID_DivAdd" class="C_DivAdd">
        <table id="ID_TblAdd" class="C_TblAdd"> 
          <thead>    
            <tr>      
              <th style="width:10%; text-align:left">
                <img ID="ID_AddClose" class="C_IcoHdr" style="text-align:left;" alt="" src="../icone/IcoRightArrow-100x100.png" onclick="Add('C')">
              </th> 
              <th style="width:10%; text-align:center">
                <img class="C_IcoHdr" src="../icone/IcoLogo_50x50.png">
              </th>
              <th style="width:80%; text-align:left">
                 <p ID="ID_AddHdrTxt" style="height:30px; border:0;"><?php echo $SITE . " - " .$MnuAct. " - " .$ACT ?></p> 
              </th>                  
             </tr>    
          </thead>  
          <tbody>
              
            <!--tr ID="ID_TrAddId" style="display:none"-->      
            <tr ID="ID_TrAddId">      
              <td colspan="3" style="width:100%; text-align:Center">
                <div>
                  <p ID="ID_IDLabel" style="color:blue;font-size: 14px ">ID</p>
                  <p IC="ID_IDValue" class="C_Anim" style="color:blue;font-size: 14px ">ID</p>
                </div>  
              </td>
            </tr>              
            <!--tr>      
              <td colspan="3" style="width:90%; text-align:center">
                <input ID="ID_CmbSrcTxt" class="C_CmbSrcTxt" style="height:30px;" type="search" placeholder="Search..">
                <button ID="ID_CmbSrcBtn" class="C_CmbSrcBtn" type="submit" style="height:30px;" onclick="Fltr('O')">
                  <img ID="ID_CmbSrcIco" class="C_IcoBtn" style="text-align:center; width:20px; height:20px;" alt="" src="./icone/IcoFilter.png">
                </button>          
                  <p ID="ID_HdrTxt" type="text" disabled style="text-align:center;height:30px; border:0;"><?php echo $HDRTXT ?></p>               
              </td>  
              <td colspan="3" style="width:10%; text-align:center">
                <img ID="ID_SrcBtn" class="C_IcoHdr" style="text-align:right;" alt="" src="./icone/IcoSearch.png" onclick="DoVisibleSrc('S')">
                <img ID="ID_NoSrcBtn" class="C_IcoHdr" style="text-align:right;" alt="" src="./icone/IcoFilterRemove.png" onclick="DoVisibleSrc('T');">    
              </td>
            </tr-->              
            
            <tr ID="ID_TrAddDtNsc" style="align:center; ">
              <td colspan="3" style="width:100%; text-align:center; color:blue;">                
                <div ID="ID_DivAddDtNsc">
                  <p style="font-size: 14px ">Data di Nascita/Entrata </p>
                  <input type="date" ID="ID_DtNsc" oninput="DtSwitch('ID_DtNsc','ID_AddDtNscTxt','ID_AddDtNscModify');">
                  <p>
                    <span id="ID_AddDtNscTxt" style="align:center; "></span>
                    <img id="ID_AddDtNscModify" class="C_IcoBtn" src="icone/IcoEdit.png" style="float:right" onclick="DtRestore('ID_DtNsc','ID_AddDtNscTxt','ID_AddDtNscModify');">
                  </p>
                </div>               
              </td>
            </tr>
            <tr ID="ID_TrAddSx"  style="align:center;">
              <td colspan="3" style="width:100%; text-align:center; color:blue;">                         
                <div ID="ID_DivAddSx" style="padding-bottom: 15px;">
                  <p  style="font-size: 14px">Sesso: </p>
                  <button class="C_AddResumePgCallBtn" ID="ID_Sx" value="" onclick="AddResumePg('O','Sesso')">-- selezionare -- &#9661;</button>
                  <span id="ID_AddSxTxt" style="align:center; "></span>
                  <img id="ID_AddSxModify" class="C_IcoBtn" src="icone/IcoEdit.png" style="float:right" onclick="BtnRestore('ID_Sx','ID_AddSxTxt','ID_AddSxModify');">
                  <br>
                </div>                  
              </td>      
            </tr>
            <tr ID="ID_TrAddNome">      
              <td colspan="3" style="width:100%; text-align:Center">
                <div>
                  <p style="color:blue;font-size: 14px ">Nome</p>
                  <p class="C_Anim" style="color:blue;font-size: 14px ">nome</p>
                </div>  
              </td>
            </tr> 
             <tr ID="ID_TrAddNomeAOC">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">NomeAOC *</p>
                  <p><input type="text" ID="ID_NomeAOC" ><button>OK</button></p>
                </div>      
              </td>      
            </tr>
            <tr ID="ID_TrAddSituaz">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">Situazione</p>
                  <p><button class="C_AddResumePgCallBtn" onclick="AddResumePg('O','Situaz')">-- selezionare -- &#9661;</button></p>
                </div>      
              </td>      
            </tr>  
            <tr ID="ID_TrAddDtUsc">
              <td colspan="3" style="width:100%; text-align:Center;">                
                <div>
                  <p style="color:blue;font-size: 14px ">Data di uscita<br>(Solo per non presenti)</p>
                  <p><input type="date" ID="DtNsc" ></p>
                </div> 
              </td>      
            </tr>  
            <tr ID="ID_TrAddPadre">
              <td colspan="3" style="width:100%; text-align:center; color:blue;">                         
                <div ID="ID_DivAddPadre" style="padding-bottom: 15px;">
                  <p  style="font-size: 14px">Padre: </p>
                  <button class="C_AddResumePgCallBtn" ID="ID_Padre" value="" onclick="AddResumePg('O','Padre')">-- selezionare -- &#9661;</button>
                  <span id="ID_AddPadreTxt" style="align:center; "></span>
                  <img id="ID_AddPadreModify" class="C_IcoBtn" src="icone/IcoEdit.png" style="float:right" onclick="BtnRestore('ID_Padre','ID_AddPadreTxt','ID_AddPadreModify');">
                  <br>
                </div>                  
              </td>   
            </tr> 
            <tr ID="ID_TrAddMadre">
              <td colspan="3" style="width:100%; text-align:center; color:blue;">                         
                <div ID="ID_DivAddMadre" style="padding-bottom: 15px;">
                  <p  style="font-size: 14px">Madre: </p>
                  <button class="C_AddResumePgCallBtn" ID="ID_Madre" value="" onclick="AddResumePg('O','Madre')">-- selezionare -- &#9661;</button>
                  <span id="ID_AddMadreTxt" style="align:center; "></span>
                  <img id="ID_AddMadreModify" class="C_IcoBtn" src="icone/IcoEdit.png" style="float:right" onclick="BtnRestore('ID_Madre','ID_AddMadreTxt','ID_AddMadreModify');">
                  <br>
                </div>                  
              </td>       
            </tr> 
            <tr ID="ID_TrAddVacc">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">Vaccinazioni</p>
                  <p><button class="C_AddResumePgCallBtn" onclick="AddResumePg('O','Vacc')">-- scegliere -- </button></p>
                </div>      
              </td>      
            </tr> 
            <tr ID="ID_TrAddAlim">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">Alimentazione</p>
                  <p><button class="C_AddResumePgCallBtn" onclick="AddResumePg('O','Alim')">-- scegliere -- </button></p>
                </div>      
              </td>      
            </tr>
             <tr ID="ID_TrAddHold">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">Da tenere</p>
                  <p><button class="C_AddResumePgCallBtn" onclick="AddResumePg('O','Keep')">-- selezionare -- &#9661;</button></p>
                </div>      
              </td>      
            </tr>
            <tr ID="ID_TrAddNote">
              <td colspan="3" style="width:100%; text-align:Center">                
                <div>
                  <p  style="color:blue;font-size: 14px">Note</p>
                  <p><button class="C_AddResumePgCallBtn" onclick="AddResumePg('O','Note')">-- scegliere -- </button></p>
                </div>      
              </td>      
            </tr>    
            <tr ID="ID_TrAddDone">
            <td class="FormContainer" colspan="3" rowspan="1"><br>
              <table style="width: 100%; text-align: center; border-spacing: 10px;">
                <tbody>
                  <tr>
                    <td style="width:25%; align:center">

                    </td>                    
                    <td style="width:25%; align:center">
                      <input type="submit" id="AddBtnCancel" name="AddBtnCancel" VALUE="Cancella" OnClick='Add("C");'>
                    </td>
                    <td style="width:25%; align:center">
                      <input type="submit" id="AddBtnOK" name="AddBtnOK" VALUE="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" OnClick='InsertValues("<?php echo $ACT;?>");'>  
                    </td>
                    <td style="width:25%; align:center">

                    </td>                    
                  </tr>
                  <!--tr ID="ID_TrAdd">
                    <td colspan="3" style="width:100%; height:500px; text-align:Center">                
                      <div>
                     </div>      
                    </td>      
                  </tr-->                  
                </tbody>
              </table>
            </td>
            </tr>               
          </tbody>    
          <!--tfoot>
            <tr>
              <td colspan="3" style="width:100%;">Footer 1</td>
            </tr>
          </tfoot-->   
        </table>    
      </div>
<?php  
  echo "      <form Name=\"AddFrm\" ID=\"ID_AddFrm\" action=\"500_Insert.php\" method=\"POST\">"; 
  
  echo "        <input type=\"hidden\" id=\"ID_FORMNAME\" name=\"FORMNAME\" value=\"AddFrm\" ><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrmUID\" name=\"UID\" value=\"".$_POST["UID"]."\" ><br>";     
  echo "        <input type=\"hidden\" id=\"ID_AddFrmPWD\" name=\"PWD\" value=\"".$_POST["PWD"]."\" ><br>";        
  echo "        <input type=\"hidden\" id=\"ID_AddFrmSITE\" name=\"SITE\" value=\"".$_POST["SITE"]."\" ><br>";  
  echo "        <input type=\"hidden\" id=\"ID_AddFrmCHOOSE\" name=\"CHOOSE\" value=\"".$_POST["CHOOSE"]."\" ><br>";    
        
  echo "        <input type=\"hidden\" id=\"ID_AddFrmHOSTNAME\" name=\"HOSTNAME\" value=\"".$_POST["HOSTNAME"]."\"><br>";      
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBNAME\" name=\"DBNAME\" value=\"".$_POST["DBNAME"]."\"><br>";   
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBUSR\" name=\"DBUSR\" value=\"".$_POST["DBUSR"]."\"><br>";    
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBPWD\" name=\"DBPWD\" value=\"".$_POST["DBPWD"]."\"><br>"; 
                  
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_DtNsc\" name=\"DTNSC\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_Sx\" name=\"SX\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_Padre\" name=\"PADRE\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_Madre\" name=\"MADRE\" value=\"\"><br>";
  echo "      </form>";  
?>
</section>

