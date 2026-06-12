function DisplayMnu(Act,Header)
{
  document.getElementById("ID_DivMnu").style.transition="0.5s"; /* 0.5 second transition effect to slide in the sidenav */
  if (Act=='O')
  {
    document.getElementById("ID_DivMnu").style.width = "80%";  
    //DoVisible("ID_AddBtn",false);      
  }
  if (Act=='C')
  {
    document.getElementById("ID_DivMnu").style.width = "0%";    
    //DoVisible("ID_AddBtn",true);          
  }
}

function MnuInit()
{
  document.getElementById("ID_SOC").innerHTML="<i>GESTIONE SOCIETA&#39;</i>";
  document.getElementById("ID_SITI").innerHTML="<i>Siti</i>";    
  document.getElementById("ID_USR").innerHTML="<i>Utenti</i>";  
  document.getElementById("ID_DIZ_SOC").innerHTML="<i>Dizionari(Soc.)</i>";    
  document.getElementById("ID_DIZ_ABIL").innerHTML="<i>Abilitazioni(DIZ.)</i>";    
  document.getElementById("ID_GEST").innerHTML="<i>GESTIONALE</i>";
  document.getElementById("ID_GEST_ACQ").innerHTML="<i>Acquisti</i>";  
  document.getElementById("ID_GEST_VEN").innerHTML="<i>Vendite</i>";   
  document.getElementById("ID_GEST_PRINOT").innerHTML="<i>Prima Nota</i>";  
  document.getElementById("ID_GEST_STAT").innerHTML="<i>Statistiche</i>";  
  document.getElementById("ID_GEST_DIZ_APP").innerHTML="<i>Dizionari(App.)</i>";
  document.getElementById("ID_GEST_DIZ_FORN").innerHTML="<i>Fornitori</i>";
  document.getElementById("ID_GEST_DIZ_CLI").innerHTML="<i>Clienti</i>";   
  document.getElementById("ID_PROMEM").innerHTML="<i>PRO MEMORIA</i>";   
  document.getElementById("ID_GEST_FILE").innerHTML="<i>GESTIONE FILE</i>";   
  document.getElementById("ID_GEST_SALVA").innerHTML="<i>SALVATAGGI</i>";   
  document.getElementById("ID_CNCT").innerHTML="<i>Contact</i>";   
  document.getElementById("ID_ABOUT").innerHTML="<i>About</i>";   
}

function FormatMnu(ID_USR,SITE,ABIL,MNU_ACT,QryFnct,Refresh)
{
  MnuInit();  
  switch (MNU_ACT) 
  {
    case 'SOC':
      document.getElementById("ID_SOC").innerHTML="<b>GESTIONE SOCIETA&#39;</b>";
      break;
    case 'SITI':
      document.getElementById("ID_SOC").innerHTML="<b>GESTIONE SOCIETA&#39;</b>";
      document.getElementById("ID_SITI").innerHTML="<b>Siti</b>";
      break;
    case 'USR':
      document.getElementById("ID_SOC").innerHTML="<b>GESTIONE SOCIETA&#39;</b>";
      document.getElementById("ID_USR").innerHTML="<b>Utenti</b>";
      break;    
    case 'DIZ_SOC':
      document.getElementById("ID_SOC").innerHTML="<b>GESTIONE SOCIETA&#39;</b>";
      document.getElementById("ID_DIZ_SOC").innerHTML="<b>Dizionari(Soc)</b>";
      break;   
    case 'DIZ_ABIL':
      document.getElementById("ID_SOC").innerHTML="<b>GESTIONE SOCIETA&#39;</b>";
      document.getElementById("ID_DIZ_SOC").innerHTML="<b>Dizionari(Soc.)</b>";
      document.getElementById("ID_DIZ_ABIL").innerHTML="<b>Abilitazioni(DIZ.)</b>";
      break;
    case 'GEST':
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      // Codice eseguito se espressione === valore2
      break;       
    case 'FORN':
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_FORN").innerHTML="<b>Fornitori</b>";
      break;    
    case 'ACQ':
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_ACQ").innerHTML="<b>Fornitori</b>";
      break;        
    case 'CLI':
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_CLI").innerHTML="<b>Clienti</b>";   
      break;  
    case 'VEN':      
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_VEN").innerHTML="<b>Vendite</b>";   
      break; 
    case 'PN':      
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_PN").innerHTML="<b>Prima Nota</b>";  
      break;
    case 'STAT':      
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_STAT").innerHTML="<b>Statistiche</b>";  
      break;        
    case 'APP_DIZ':      
      document.getElementById("ID_GEST").innerHTML="<b>GESTIONALE</b>";
      document.getElementById("ID_APP_DIZ").innerHTML="<b>Dizionari(APP.)</b>";
      break;    
    case 'GEST_PM':    
      document.getElementById("ID_MEM").innerHTML="<b>PRO MEMORIA</b>";   
      MsgPg('O');
      break; 
    case 'GESTFIL':      
      document.getElementById("ID_GESTFIL").innerHTML="<b>GESTIONE FILE</b>";   
      break;     
    case 'SALVA':      
      document.getElementById("ID_SALVA").innerHTML="<b>SALVATAGGI</b>";   
      break;   
    case 'CNCT':      
      document.getElementById("ID_CNCT").innerHTML="<b>Contact</b>";   
      break;        
    default:
      console.log("FormatMnu: azione '+Act+' non tovata");
  }
  if(Refresh==true)
  {
    RefreshMnu(ID_USR,SITE,ABIL,MNU_ACT,QryFnct);
  }
  return;
}

function RefreshMnu(ID_USR,SITE,ABIL,MNU_ACT,QryFnct)
{
  //Creo l' array dei campi
  const ArrPar = 
  [
     { name: "ID_USR", type: "hidden", value: ID_USR },
     { name: "SITE", type: "hidden", value: SITE },
     { name: "ABIL", type: "hidden", value:ABIL },
     { name: "MNU_ACT", type: "hidden", value:MNU_ACT },
     { name: "QRY_FNC", type: "hidden", value:QryFnct},
  ];
  //function MakeForm(Doc, FrmName, SrvrAction, FrmFrom, FrmIDAction, ArrPar)
  FrmFrom=ID_USR;
  const GEST_FRM=MakeForm(document, "FrmGest", "../SRVR/SRVR.php", FrmFrom, "GEST_OPEN", ArrPar);

  // Aggiunta al documento
  document.body.appendChild(GEST_FRM);  
  GEST_FRM.submit();   
  return;
}
