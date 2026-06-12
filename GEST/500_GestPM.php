<?php
  $MsgTxt="<b>Msg1</b><br>
           Lorem Ipsum<br>Lorem Ipsum<br>Lorem Ipsum<br>
           Lorem<br>Ipsum
           <b>Msg2</b><br>
          Ipsum Lorem<br>Ipsum Lorem<br>Ipsum Lorem<br>Ipsum
          <br>Lorem<br>Ipsum<br>Lorem<br>Ipsum<br>Lorem<br>Ipsum<br>Lorem<br>Ipsum
          <br>Lorem<br>Ipsum<br>Lorem<br>Ipsum<br>Lorem<br>Ipsum<br>Lorem<br>Ipsum
          <br>Lorem<br>Ipsum<br>Lorem<br>Ipsum<br>"; 
?>
    <section ID="ID_PMPgSec">
      <div id="ID_DivPMPg" class="C_DivPMPg">
        <table id="ID_GestTblMnu" class="C_GestTblMnu"> 
          <thead> 
            <tr>      
              <th style="width:10%; text-align:center">
                <img class="C_GestIcoHdr" alt="" src="./icone/IcoLogo.png">
              </th>
              <th colspan="3" style="width:80%; text-align:center">COSE DA FARE</th>
              <th style="width:10%; text-align: center">
                <img class="C_GestIcoHdr" alt="" src="../icone/IcoRimuovi-500.png" onclick="PMPg('C')">
              </th>
            </tr>  
          </thead>          
        </table>  
        <p><?php echo $MsgTxt ?></p>
      </div>
    </section>

