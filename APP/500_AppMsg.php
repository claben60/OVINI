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
    <section ID="ID_MsgPgSec">
      <div id="ID_DivMsgPg" class="ID_DivMsgPg">
        <table id="ID_TblMnu" class="C_TblMnu"> 
          <thead> 
            <tr>      
              <th style="width:10%; text-align:center">
                <img class="C_IcoHdr" alt="" src="./icone/IcoLogo.png">
              </th>
              <th colspan="3" style="width:80%; text-align:center">COSE DA FARE</th>
              <th style="width:10%; text-align: center">
                <img class="C_IcoHdr" alt="" src="../icone/IcoRimuovi-500.png" onclick="MsgPg('C')">
              </th>
            </tr>  
          </thead>          
        </table>  
        <p><?php echo $MsgTxt ?></p>
      </div>
    </section>

