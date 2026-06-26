function Validate(Act)
{
  const _FF_ = '00_Login.js(Validate)';
  
  // 1. BLOCCO IMMEDIATO DI TUTTA L'INTERFACCIA (PC / Tablet / Smartphone)
  // Questo impedisce all'utente di cliccare altre opzioni mentre la richiesta parte
  MostraBloccoSchermo("Elaborazione della richiesta in corso...");
  
  switch (Act) 
  {
    case 'LOGIN_L':
      const uidField = document.getElementById('ID_UID');
      const pwdField = document.getElementById('ID_PWD');

      // Protezione dagli spazi vuoti inseriti accidentalmente su mobile
      if (!uidField || uidField.value.trim() === '') 
      {
        alert('Immettere Username');
        RimuoviBloccoSchermo(); // Sblocca se la validazione fallisce
        if(uidField) uidField.focus();
        return;  
      }
      if (!pwdField || pwdField.value.trim() === '') 
      {
        alert('Immettere Password');
        RimuoviBloccoSchermo(); // Sblocca se la validazione fallisce
        if(pwdField) pwdField.focus();
        return;  
      }

      try 
      {
        const ArrParL = [
          { name: "UID", type: "hidden", label: "Nome Utente", value: uidField.value.trim() },
          { name: "PWD", type: "hidden", label: "Password", value: pwdField.value }
        ];

        const L_FRM = MakeForm(document, 'FrmLogin', '../SRVR/SRVR.php', '00_Login', 'LOGIN', Act, ArrParL);
        if (L_FRM) 
        {
          // NOTA: Se MakeForm fa già l'append al body internamente, questa riga potrebbe essere duplicata.
          // Lasciamola se richiesto dalla tua libreria.
          document.body.appendChild(L_FRM);  
          L_FRM.submit();
        }
        else
        {
          RimuoviBloccoSchermo(); // Sblocca se la creazione del form fallisce
        }
      }
      catch(err) 
      {
        RimuoviBloccoSchermo(); // Sblocca in caso di eccezione
        alert('ERRORE: FILE:' + _FF_ + ' Act:' + Act + ' Errore: ' + err.message);
      }
      break;
        
    case 'UPR_U':
    case 'UPR_P':
    case 'UPR_R':  
      try {
        const ArrParUPR = [];
        const UPR_FRM = MakeForm(document, 'FrmUPR', '../LOGIN/30_UPR.php', '00_Login', 'LOGIN', Act, ArrParUPR);
        if (UPR_FRM) {
          document.body.appendChild(UPR_FRM);  
          UPR_FRM.submit();
        }
        else
        {
          RimuoviBloccoSchermo();
        }
      }
      catch(err) {
        RimuoviBloccoSchermo();
        alert('ERRORE: FILE:' + _FF_ + ' Act:' + Act + ' Errore: ' + err.message);
      }
      break;
      
    default:
      // Se viene passato un Act sconosciuto, rimuoviamo il blocco
      RimuoviBloccoSchermo();
      break;
  }
}

/**
 * Funzione di utilità da aggiungere in fondo al file 00_Login.js 
 * Crea una copertura a schermo intero invisibile o semitrasparente che blocca ogni interazione
 */
function MostraBloccoSchermo(messaggio) {
    if (document.getElementById("blocco-schermo-loading")) return;

    var overlay = document.createElement("div");
    overlay.id = "blocco-schermo-loading";
    
    // Stile CSS iniettato direttamente per compatibilità cross-device immediata
    overlay.style.position = "fixed";
    overlay.style.top = "0";
    overlay.style.left = "0";
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0.3)"; // Oscura leggermente lo sfondo
    overlay.style.zIndex = "99999"; // Sopra a qualsiasi elemento grafico
    overlay.style.display = "flex";
    overlay.style.justifyContent = "center";
    overlay.style.alignItems = "center";    overlay.style.cursor = "wait"; 

    var boxMessaggio = document.createElement("div");
    boxMessaggio.style.background = "#ffffff";
    boxMessaggio.style.padding = "15px 25px";
    boxMessaggio.style.borderRadius = "6px";
    boxMessaggio.style.boxShadow = "0px 4px 12px rgba(0,0,0,0.15)";
    boxMessaggio.style.fontFamily = "Arial, sans-serif";
    boxMessaggio.style.fontSize = "15px";
    boxMessaggio.style.color = "#333333";
    boxMessaggio.innerText = messaggio;

    overlay.appendChild(boxMessaggio);
    document.body.appendChild(overlay);
}

/**
 * Rimuove la copertura in caso di errore di validazione o eccezione prima della post
 */
function RimuoviBloccoSchermo() {
    var overlay = document.getElementById("blocco-schermo-loading");
    if (overlay) {
        overlay.parentNode.removeChild(overlay);
    }
}
