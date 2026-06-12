
function MsgPg(Act)
{ 
  document.getElementById("ID_DivMsgPg").style.transition="0.5s"; /* 0.5 second transition effect to slide in the sidenav */
  if (Act=='O')
  {
    document.getElementById("ID_DivMsgPg").style.left = "0%";  
    DisplayMnu('C','PRO MEMORIA');
    DoVisible("ID_GestAddBtn",false);    
  }
  if (Act=='C')
  {
    document.getElementById("ID_DivMsgPg").style.left = "100%";
    DisplayMnu('O','PRO MEMORIA');
    DoVisible("ID_GestAddBtn",true);     
  } 
}

