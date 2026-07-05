function Validate(Act)
{
  const _FF_ = '00_Login.js(Validate)';
  
  switch (Act) 
  {
    case 'LOGIN_L':
      const uidField = document.getElementById('ID_UID');
      const pwdField = document.getElementById('ID_PWD');

      // Protezione dagli spazi vuoti inseriti accidentalmente su mobile
      if (!uidField || uidField.value.trim() === '') 
      {
        alert('Immettere Username');
        if(uidField) uidField.focus();
        return;  
      }
      if (!pwdField || pwdField.value.trim() === '') 
      {
        alert('Immettere Password');
        if(pwdField) pwdField.focus();
        return;  
      }

      try 
      {
        const ArrParL = [
          { name: "UID", type: "hidden", label: "Nome Utente", value: uidField.value.trim() },
          { name: "PWD", type: "hidden", label: "Password", value: pwdField.value }
        ];

        // Creazione dinamica tramite la tua funzione di libreria in CMN_JS.js
        // function MakeForm(Doc, FrmName, SrvrAction, FrmFrom, Sector, IDAction, ArrPar)
        const L_FRM = MakeForm(document, 'FrmLogin', '../SRVR/SRVR.php', '00_Login', 'LOGIN', Act, ArrParL);
        if (L_FRM) 
        {
          document.body.appendChild(L_FRM);  
          L_FRM.submit();
        }
      }
      catch(err) 
      {
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
      }
      catch(err) {
        alert('ERRORE: FILE:' + _FF_ + ' Act:' + Act + ' Errore: ' + err.message);
      }
      break;
  }
}
