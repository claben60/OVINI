
function GestAdd(Open,Act)
{
  if(Act=='New')
  {
    /*
    DoVisible("ID_TrAddId",false);
    document.getElementById("ID_DtNsc").value="";
    DtRestore('ID_DtNsc','ID_AddDtNscTxt','ID_AddDtNscModify');
    BtnRestore('ID_Sx','ID_AddSxTxt','ID_AddSxModify');
    BtnRestore('ID_Padre','ID_AddPadreTxt','ID_AddPadreModify');
    BtnRestore('ID_Madre','ID_AddMadreTxt','ID_AddMadreModify');
    DoVisible("ID_TrAddNome",false);
    DoVisible("ID_TrAddNomeAOC",false);
    DoVisible("ID_TrAddSituaz",false);
    DoVisible("ID_TrAddDtUsc",false);
    DoVisible("ID_TrAddVacc",false);
    DoVisible("ID_TrAddAlim",false);
    DoVisible("ID_TrAddHold",false);
    DoVisible("ID_TrAddNote",false);
    */
    v=1;
  }
  try
  {
    /* 0.5 second transition effect to slide in the sidenav */
    document.getElementById("ID_GestDivAdd").style.transition="0.5s";
  }
  catch (err)
  {
    console.log(err);
    alert("Function: GestAdd - Errore non recuperabile.\n Causa:"+err.message);
    throw new error("Function: GestAdd - Errore non recuperabile.\n Causa:"+err.message); 
  }    
  
  try
  {
    if (Open=='O')
    {
      document.getElementById("ID_GestDivAdd").style.left = "0%";  
      DoVisible("ID_GestAddBtn",false);    
    }
    if (Open=='C')       
    {
      document.getElementById("ID_GestDivAdd").style.left = "100%";
      DoVisible("ID_GestAddBtn",true);     
    }
  }
  catch (err)
  {
    console.log(err);
    alert("Function: GestAdd - Errore non recuperabile.\n Causa:"+err.message);
    throw new error("Function: GestAdd - Errore non recuperabile.\n Causa:"+err.message); 
  }    
}

      function PrepareAddFrm()
      {
        /*
        document.getElementById("ID_AddFrm_DtNsc").value=document.getElementById("ID_AddDtNscTxt").innerHTML;
        document.getElementById("ID_AddFrm_Sx").value=document.getElementById("ID_AddSxTxt").innerHTML;
        document.getElementById("ID_AddFrm_Padre").value=document.getElementById("ID_AddPadreTxt").innerHTML;
        document.getElementById("ID_AddFrm_Madre").value=document.getElementById("ID_AddMadreTxt").innerHTML;
        */
        
      }

      function ValidateAddFrm()
      {
        /*
        var RC=true;
        if(document.getElementById("ID_FORMNAME").value != 'AddFrm') 
        {
          alert( 'Errore 01 - Form sbagliata' );
          RC=false;
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmUID").value!='<?php echo $_POST["UID"]; ?>') 
          {
            alert( 'Errore 02 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmPWD").value!='<?php echo $_POST["PWD"]; ?>') 
          {
            alert( 'Errore 03 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmSITE").value!='<?php echo $_POST["SITE"]; ?>') 
          {
            alert( 'Errore 04 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmCHOOSE").value!='<?php echo $_POST["CHOOSE"]; ?>') 
          {
            alert( 'Errore 05 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmHOSTNAME").value!='<?php echo $_POST["HOSTNAME"]; ?>') 
          {
            alert( 'Errore 06 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmDBNAME").value!='<?php echo $_POST["DBNAME"]; ?>') 
          {
            alert( 'Errore 07 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmDBUSR").value!='<?php echo $_POST["DBUSR"]; ?>') 
          {
            alert( 'Errore 08 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrmDBPWD").value!='<?php echo $_POST["DBPWD"]; ?>') 
          {
            alert( 'Errore 09 - Form sbagliata' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrm_DtNsc").value=='') 
          {
            alert( 'Errore 10 - Form sbagliata - Data di nascita' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrm_Sx").value=='') 
          {
            alert( 'Errore 11 - Form sbagliata - Sesso' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if(document.getElementById("ID_AddFrm_Padre").value=='') 
          {
            alert( 'Errore 12 - Form sbagliata - Padre' );
            RC=false;
          }
        }
        if(RC==true)
        {
          if (document.getElementById("ID_AddFrm_Madre").value=='') 
          {
            alert( 'Errore 13 - Form sbagliata - Madre' );
            RC=false;
          }
        }
        return RC;
        */
      }

/*  echo "      <form Name=\"AddFrm\" ID=\"ID_AddFrm\" action=\"500_Insert.php\">"; 
  
  echo "        <input type=\"hidden\" id=\"ID_FORMNAME\" name=\"FORMNAME\" value=\"AddFrm\" ><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrmUID\" name=\"UID\" value=\"".$_POST["UID"]."\" ><br>";     
  echo "        <input type=\"hidden\" id=\"ID_AddFrmPWD\" name=\"PWD\" value=\"".$_POST["PWD"]."\" ><br>";        
  echo "        <input type=\"hidden\" id=\"ID_AddFrmSITE\" name=\"SITE\" value=\"".$_POST["SITE"]."\" ><br>";  
  echo "        <input type=\"hidden\" id=\"ID_AddFrmCHOOSE\" name=\"CHOOSE\" value=\"".$_POST["CHOOSE"]."\" ><br>";    
        
  echo "        <input type=\"hidden\" id=\"ID_AddFrmHOSTNAME\" name=\"HOSTNAME\" value=\"".$_POST["HOSTNAME"]."\"><br>";      
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBNAME\" name=\"DBNAME\" value=\"".$_POST["DBNAME"]."\"><br>";   
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBUSR\" name=\"DBUSR\" value=\"".$_POST["DBUSR"]."\"><br>";    
  echo "        <input type=\"hidden\" id=\"ID_AddFrmDBPWD\" name=\"DBPWD\" value=\"".$_POST["DBPWD"]."\"><br>"; 
                  
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_DtNsc\" name=\"DtNsc\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_sx\" name=\"Sx\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_Padre\" name=\"Padre\" value=\"\"><br>";
  echo "        <input type=\"hidden\" id=\"ID_AddFrm_Madre\" name=\"Madre\" value=\"\"><br>";
  echo "      </form>";  */

      function InsertValues(Act)
      {   
        if(Act=='ADD')
        {
          PrepareAddFrm();
          if(ValidateAddFrm())
          {
            document.getElementById("ID_AddFrm").submit();
            Insert('O');
          }
        }
      }