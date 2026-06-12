function DoVisibleSrc(Par)
{
  if(Par=='S')   
  {
    DoVisible("ID_HdrTxt",false);
    DoVisible("ID_CmbSrcTxt",true);
    DoVisible("ID_CmbSrcBtn",true);
    DoVisible("ID_CmbSrcIco",true);    
    DoVisible("ID_SrcBtn",false); 
    DoVisible("ID_NoSrcBtn",true);     
  }
  if(Par=='T')
  {
    DoVisible("ID_HdrTxt",true);
    DoVisible("ID_CmbSrcTxt",false);
    DoVisible("ID_CmbSrcBtn",false);
    DoVisible("ID_CmbSrcIco",false);   
    DoVisible("ID_SrcBtn",true); 
    DoVisible("ID_NoSrcBtn",false);              
  }
}

function LstHdrDisplaySrc(Par)
{
  switch (Par)
  {
    case 'S':
    {        
      //alert(Par);
      //DoVisible("ID_AddClose",false); 
      DoVisible("ID_MnuOpen",true); 
      DoVisibleSrc(Par);
      DoVisible("ID_BtnRld",true);   
              
      /*                
      document.getElementById("ID_SrcBtn").style.display = "none";                 
      document.getElementById("ID_DsrcTxt").style.display = "none";  
      document.getElementById("ID_NoSrcBtn").style.display = "inline-block";  
      document.getElementById("ID_BtnRld").style.display = "inline-block";*/            
      break;  
    }
    case 'T':
    { 
      //DoVisible("ID_AddClose",false); 
      DoVisible("ID_MnuOpen",true); 
      DoVisibleSrc(Par);   
      DoVisible("ID_BtnRld",true);  
      /*             
      document.getElementById("ID_SrcBtn").style.display = "inline-block";                 
      document.getElementById("ID_DsrcTxt").style.display = "inline-block";
      document.getElementById("ID_NoSrcBtn").style.display = "none";  
      document.getElementById("ID_BtnRld").style.display = "inline-block";*/
      break;    
    }
  }          
}      

function LstHdrFltrBtn_Init()
{
  DoVisible("ID_UsdFltrNome",false);
  DoVisible("ID_UsdFltrNomeAOC",false); 
  DoVisible("ID_UsdFltrNascita",false); 
  DoVisible("ID_UsdFltrSesso",false);   
  DoVisible("ID_UsdFltrPadre",false); 
  DoVisible("ID_UsdFltrMadre",false);    
  DoVisible("ID_UsdFltrGravida",false); 
  DoVisible("ID_UsdFltrDaTenere",false);  
  DoVisible("ID_UsdFltrVaccini",false);  
  DoVisible("ID_UsdFltrAlimen",false);
          
  DoVisible("ID_UsdFltrAll",false);  
       
  DoVisible("ID_UsdFltrTh",false);
  DoVisible("ID_UsdFltrRow",false); 
}
 
function txtFilter() 
{
  // Declare variables
  var input; 
  var filter; 
  var table; 
  var tr; 
  var td;
  var i; 
  var txtValue;
  input = document.getElementById("ID_CmbSrcTxt");
  filter = input.value.toUpperCase();
  table = document.getElementById("ID_TblLst");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) 
  {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) 
    {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) 
      {
        tr[i].style.display = "";
      } 
      else 
      {
        tr[i].style.display = "none";
      }
    }
  }
}

