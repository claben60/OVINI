/*
 * Funzioni Generali
 */
function DoVisible(Name, Par) 
{
  if (Par == true) 
  {
    try 
    {
      document.getElementById(Name).style.display = "inline-block";
    } 
    catch (err) 
    {
      console.log(err);
    }
  }
  if (Par == false) 
  {
    try 
    {
      document.getElementById(Name).style.display = "none";
    } 
    catch (err) 
    {
      console.log(err);
    }
  }
}

function IsVisible(Name) 
{
  var RC = false;
  if (document.getElementById(Name).style.display == "none") 
  {
    RC = false;
  } 
  else 
  {
    RC = true;
  }
  return RC;
}

function enable(Par) 
{
  document.getElementById(Par).style.enabled = true;
}

function ValidateRadioGroup(NameRG, Act) 
{
  var RetVal = "";
  NItems = document.getElementsByName(NameRG).length;
  //alert(NItems);
  for (Ctr = 0; Ctr < NItems; Ctr++) 
  {
    //alert(Ctr);
    //alert(document.getElementsByName(NameRG)[Ctr].checked);
    if (document.getElementsByName(NameRG)[Ctr].checked == true) 
    {
      RetVal = document.getElementsByName(NameRG)[Ctr].value;
      Choosen = true;
      break;
    }
  }
  //alert(RetVal);
  return RetVal;
}

function DtSwitch(Obj, Txt, Btn) 
{
  document.getElementById(Txt).innerHTML = document.getElementById(Obj).value;
  DoVisible(Txt, true);
  DoVisible(Obj, false);
  DoVisible(Btn, true);
}

function DtRestore(Obj, Txt, Btn) 
{
  document.getElementById(Obj).value = document.getElementById(Txt).innerHTML;
  DoVisible(Txt, false);
  DoVisible(Obj, true);
  DoVisible(Btn, false);      
}

function BtnSwitch(Obj, Txt, Btn) 
{
  DoVisible(Obj,false);
  DoVisible(Txt,true);
  DoVisible(Btn,true);
}

function BtnRestore(Obj, Txt, Btn) 
{            
  DoVisible(Obj,true);
  DoVisible(Txt,false);
  DoVisible(Btn,false);  
}





/*
 * Inizializzazioni
 */
function GestContainerInitializePage() 
{
  try
  {
    DimScreen();
    LstHdrDisplaySrc("T");
    LstHdrFltrBtn_Init();
  }
  catch (err)
  {
    console.log(err);
    alert("Function: GestContainerInitializePage - Errore non recuperabile.\n Causa:"+err.message);
    throw new error("Function: GestContainerInitializePage - Errore non recuperabile.\n Causa:"+err.message); 
  }        
  return;    
}  

function DimScreen() 
{
  try
  {
    document.getElementById("ID_DivMnu").style.width = "0%";
    document.getElementById("ID_DivAdd").style.height = 0.93*height-100 + "px"; 
    document.getElementById("ID_DivAdd").style.left = "100%";
    //document.getElementById("ID_DivAddResumePg").style.height = 0.93*height-100 + "px";
    ////document.getElementById("ID_DivAddResumePg").style.left = "100%";
    document.getElementById("ID_DivMsgPg").style.left = "100%";
  }
  catch (err)
  {
    console.log(err);
    alert("Function: DimScreen - Errore non recuperabile.\n Causa:"+err.message);
    throw new error("Function: DimScreen - Errore non recuperabile.\n Causa:"+err.message); 
  }      
  return;
}
