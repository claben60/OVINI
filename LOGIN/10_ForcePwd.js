/**
 * Validate: Verifica la congruenza e la compilazione delle password lato client.
 * Genera e sottomette il modulo dinamico per effettuare il cambio forzato.
 */
function Validate(CF)
{
  const _FF_ = '10_ForcePwd.js(Validate)';
  const pwdField = document.getElementById('ID_PWD');
  const confPwdField = document.getElementById('ID_CONFPWD');

  // Validazione immediata dei campi vuoti
  if (!pwdField || pwdField.value === "") {    
    alert('Immettere la password');
    if (pwdField) pwdField.focus();  
    return false;  
  }
  if (!confPwdField || confPwdField.value === "") {    
    alert('Confermare la password');
    if (confPwdField) confPwdField.focus();    
    return false;  
  }
  if (pwdField.value !== confPwdField.value) {
    alert('Le due password non coincidono');
    if (pwdField) pwdField.focus();   
    return false;  
  }  
  
  try 
  {
    // Creazione controllata dell'array dei parametri
    const ArrPar = [
       { name: "CF", type: "hidden", label: "CF", value: CF },
       { name: "PWD", type: "hidden", label: "Password", value: pwdField.value }
    ];

    // Chiamata alla funzione globale in CMN_JS.js per generare il form invisibile
    const L_FRM = MakeForm(document, "FrmForce", "../SRVR/SRVR.php", "10_ForcePwd", "LOGIN", "LOGIN_F", ArrPar);

    if (L_FRM) 
    {
      document.body.appendChild(L_FRM);  
      L_FRM.submit(); 
    } 
    else 
    {
      throw new Error("Impossibile generare il form dinamico via MakeForm.");
    }
  }
  catch (err) 
  {
    // Sanato il bug originale inserendo esplicitamente l'azione corretta 'LOGIN_F'
    let ErrText = 'ERRORE: FILE:' + _FF_ + ' Act: LOGIN_F Errore: ' + err.name + ' - ' + err.message;
    alert(ErrText);
    console.error(ErrText);
  }
}
