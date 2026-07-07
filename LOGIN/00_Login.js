function Validate(Act)
{
  const _FF_ = '00_Login.js(Validate)';
  
  switch (Act) 
  {
    case 'LOGIN_L':
      const uidField = document.getElementById('ID_UID');
      const pwdField = document.getElementById('ID_PWD');

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
        // Costruiamo una busta di oggetti coerente. 
        // Passando questo oggetto strutturato, MakeForm invocherà _flattenObjectToFormParameters
        // generando in automatico i campi standard: PAYLOAD[UTENTE][username] e PAYLOAD[UTENTE][password]
        const oPayload = {
          PAYLOAD: {
            UTENTE: {
              username: uidField.value.trim(),
              password: pwdField.value.trim()
            }
          }
        };

        // Passiamo direttamente l'oggetto oPayload al posto dell'array
        const L_FRM = MakeForm(document, 'FrmLogin', '../SRVR/SRVR.php', '00_Login', 'LOGIN', Act, oPayload);
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