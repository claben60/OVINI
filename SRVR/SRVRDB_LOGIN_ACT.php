<?php
  /*
   * NOTA: Nelle azioni richiamate dalle funzioni di questo file e' eseguita l' istruzione:
   *           $stmt=$pdo->prepare(sprintf($Qry->get_SqlTxt()));
   * Se si usano wildchars, specie %, dovrebbero essere scritti con i caratteri di escape per 
   * printf, (nel caso %%) perche' altrimenti printf li considera parametri.
   * [SISTEMATO]: Rimossi i wildcard letterali complessi dentro le query per evitare conflitti.
   */ 

  /*
   * ACTLOGIN
   */
  function ActLogin($FROM, $objPost)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
    // Chiediamo puntualmente al Payload le credenziali dell'UTENTE inviate dal client
    $usrID = $objPost->getPayload()->getUtenteParam('username'); 
    $pwd   = $objPost->getPayload()->getUtenteParam('password'); 

    if (empty($usrID) || empty($pwd) || $usrID===NULL || $pwd===NULL) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - username or password not valid", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__-3, "username or password not valid");
      throw new Exception("{$THIS_FUNCTION} - username or password not valid");
    }
      
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActLogin begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "SELECT
          u.`idUtente`, 
          u.`nome`, 
          u.`cognome`, 
          u.`codiceFiscale`, 
          u.`username`,
          u.`passwordHash`, 
          u.`forzaCambioPassword`, 
          u.`isManagementRoot`, 
          u.`isMultisitoRoot`,
          u.`dataRegistrazione`,
          ui.`idImpiego`, 
          ui.`dataInizio` AS `impiegoInizio`, 
          ui.`fkSito`,
          ds.`idSito`, 
          ds.`codiceSito`, 
          ds.`indirizzo`, 
          ds.`piantaAsset`,
          ua.`idUtenteAbil`, 
          ua.`fkAbilitazione`, 
          ua.`fkImpiego`, 
          ua.`AbilInizio`,
          dsa.`descrAbil` AS `ruoloAziendale`, 
          dsa.`flagManagement`, 
          dsa.`flagMultisito`, 
          dsa.`iconaInterfaccia`
        FROM 
          `sacs`.`Utenti` u
          LEFT JOIN `sacs`.`UtentiImpiego` ui ON u.`idUtente` = ui.`fkUtente` AND ui.`dataCessazione` IS NULL
          LEFT JOIN `sacs`.`DizSiti` ds ON ui.`fkSito` = ds.`idSito`
          LEFT JOIN `sacs`.`UtentiAbilitazioni` ua ON u.`idUtente` = ua.`fkUtente` AND ua.`AbilFine` IS NULL
          LEFT JOIN `sacs`.`DizSocAbilitazioni` dsa ON ua.`fkAbilitazione` = dsa.`idAbilitazione`
        WHERE 
          (u.`dataCessazione` IS NULL OR u.`dataCessazione` > CURDATE()) 
          AND u.`username` = ?"
      );  
      $Qry->set_Parm($usrID);
      $Qry->set_Type("S");
      $Qry->set_NField(25);
      $QryArr->push_Query($Qry);
      $QryArr->set_Transaction(false);
      $QryArr->set_RollBack(false);

      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Select)", NULL);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Select)", NULL);
      QueryExec($FROM, $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Select)", NULL);
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} - got error calling QueryExec");
    }
    
    $RC = true;  
    $arrFlat = $Qry->get_ArrRec();

    /* NON TROVATO */
    if (count($arrFlat) == 0) 
    {
      $RC=false;
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - NO RECORD RETURNED BY QUERY (Select)", NULL);
      try 
      {
        DisplayErr($FROM, $objPost->getPayload()->getUtenteParam('idAction'));
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
      }
    }

    /* TROVATO */
    if (count($arrFlat) > 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QUERY EXECUTION RETURNED ".$Qry->get_Nrec()." RECORDS", NULL);
      if($RC==true)
      {
        try
        {
          $utenteObj = new clUtente($Qry->get_ArrRec());
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - utenteObj created", NULL);
        }
        catch (Exception | Error $e) 
        {
          $RC=false;
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - utenteObj cannot be created", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
          require "../MSG/SYS_ERR.html";
          throw new Exception("{$THIS_FUNCTION} - utenteObj cannot be created");        
        }
      }

      // Verifica password
      if($RC==true)
      {
        try
        {
          if (!password_verify($pwd, $utenteObj->getPasswordHash())) 
          {
            $RC=false;
            DisplayErr($FROM, $objPost->getPayload()->getUtenteParam('idAction')); 
          }
        }
        catch (Exception | Error $e) 
        {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
          require "../MSG/SYS_ERR.html";
          throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
        }
      }

      if($RC==true)
      {
        try
        {        
          $ArrPar = $utenteObj->writeDatiPost();
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ArrPar created", NULL);
        }
        catch (Exception | Error $e) 
        {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Unable to create ArrPar.", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
          require "../MSG/SYS_ERR.html";
          throw new Exception("{$THIS_FUNCTION} - Unable to create ArrPar.");        
        }
      }

      if($RC==true)
      {
        if ($utenteObj->getForzaCambioPassword() === true) 
        { 
            try 
            {
              @require "../LOGIN/10_ForcePwd.php";
            }
            catch (Exception | Error $e) 
            {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - 10_ForcePwd not found", NULL);
              WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
              require "../MSG/SYS_ERR.html";
              throw new Exception("{$THIS_FUNCTION} - 10_ForcePwd not found");        
            }
            try
            {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PASSWORD CHANGE NEEDED", NULL);
              ForcePwd($FROM, $Qry->get_ArrRec());
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PASSWORD CHANGE DONE", NULL);
            } 
            catch (Exception | Error $e) 
            {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling ForcePWD", NULL);
              WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
              throw new Exception("{$THIS_FUNCTION} got error calling ForcePWD");   
            }
        }
        else 
        {
            try 
            {
              @require "../LOGIN/20_Choose.php";
            }
            catch (Exception | Error $e) 
            {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} 20_Choose.php not found", NULL);
              WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
              require "../MSG/SYS_ERR.html";
              throw new Exception("{$THIS_FUNCTION} 20_Choose.php not found");   
            }
            try
            {
              /* 
               * NOTA Nella Choose le azioni si incominciano ad attribuire all' utente per mezzo della 
               * $FROM="ID_Utente"
               */ 
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} USER CHOOSE BEGIN", NULL);
              Choose($FROM, $Qry->get_ArrRec());
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} USER CHOOSE END", NULL);
            } 
            catch (Exception | Error $e) 
            {
              WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling Choose", NULL);
              WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
              require "../MSG/SYS_ERR.html";
              throw new Exception("{$THIS_FUNCTION} got error calling Choose");         
            }
        }
      }
    }
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActLogin ends", NULL);
    return;
  }

  /*
   * FORCE PASSWORD
   */ 
  function ActForcePWD($FROM, $objPost)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
    $CF  = $objPost->getPayload()->getUtenteParam('CF');
    $PWD = $objPost->getPayload()->getUtenteParam('PWD');
    $idAction = $objPost->getPayload()->getUtenteParam('IDACTION');

    if (empty($CF) || empty($PWD))
    {
      WriteLog("F", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error reading CF or PWD in post.", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__-3, "{$THIS_FUNCTION} got error reading CF or PWD in post.");  
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} got error reading CF or PWD in post.");     
    }
    
    $hashPWD = password_hash($PWD, PASSWORD_BCRYPT);
    
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActForcePWD begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "UPDATE `sacs`.`Utenti` SET `passwordHash`=?, `forzaCambioPassword`=? 
         WHERE `codiceFiscale`=? AND (`dataCessazione` IS NULL OR `dataCessazione` > CURDATE())"
      );
      $Qry->set_Parm($hashPWD);
      $Qry->set_Parm(0);      
      $Qry->set_Parm($CF);
      $Qry->set_Type("U");
      $Qry->set_NField(2);
      $QryArr->push_Query($Qry);
      $QryArr->set_Transaction(true);
      $QryArr->set_RollBack(false);      
      WriteLog("Q", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Update)", $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Update)", NULL);
      QueryExec($FROM, $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Update)", NULL);
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} got error calling QueryExec");         
    }

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$Qry->get_NRec()} record modified by update", NULL);
    try 
    {
      DisplayMSG($FROM, $idAction, $Qry->get_ArrParms(), $Qry->get_ArrRec());
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling DisplayMSG", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} got error calling DisplayMSG");         
    }
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActForcePWD end", NULL);
    return;
  }

  /*
   * RECUPERO UID
   */ 
  function ActRecuperoUID($FROM, $objPost)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";

    $usrName    = $objPost->getPayload()->getUtenteParam('USRNAME');
    $usrCognome = $objPost->getPayload()->getUtenteParam('USRCOGNOME');
    $usrCf      = $objPost->getPayload()->getUtenteParam('USRCF');
    $fida       = $objPost->getPayload()->getUtenteParam('FIDA');

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoUID begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "SELECT `idUtente`, `nome`, `cognome`, `codiceFiscale`, `username`, `passwordHash`, `forzaCambioPassword`
         FROM `sacs`.`Utenti`
         WHERE `nome`=? AND `cognome`=? AND `codiceFiscale`=? AND (`dataCessazione` IS NULL OR `dataCessazione` > CURDATE())"
      );
      $Qry->set_Parm($usrName);
      $Qry->set_Parm($usrCognome);
      $Qry->set_Parm($usrCf);
      $Qry->set_Type("S");
      $Qry->set_NField(7);
      $QryArr->push_Query($Qry);
      $QryArr->set_Transaction(false);
      $QryArr->set_RollBack(false);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Select)", NULL);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Select)", NULL);
      QueryExec($FROM, $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Select)", NULL);
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} got error calling QueryExec");         
    }   

    /* NON TROVATO */
    if ($Qry->get_NRec() == 0) 
    {
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - NO RECORD RETURNED BY QUERY (Select)", NULL);
        DisplayErr($FROM, $fida);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
      }
    }

    /* TROVATO */
    if ($Qry->get_NRec() > 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QUERY EXECUTION RETURNED ".$Qry->get_Nrec()." RECORDS", NULL);
      try 
      {
        DisplayMSG($FROM, $fida, $Qry->get_ArrParms(), $Qry->get_ArrRec());
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling DisplayMSG", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} got error calling DisplayMSG");      
      }
    }
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoUID ends", NULL);
    return;
  }

  /*
   * RECUPERO PWD
   */ 
  function ActRecuperoPWD($FROM, $objPost)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";

    $usrName    = $objPost->getPayload()->getUtenteParam('USRNAME');
    $usrCognome = $objPost->getPayload()->getUtenteParam('USRCOGNOME');
    $usrCf      = $objPost->getPayload()->getUtenteParam('USRCF');
    $fida       = $objPost->getPayload()->getUtenteParam('FIDA');

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoPWD begins", NULL);
    try 
    {
      $FROM=$FROM."->".__FUNCTION__;
      $THIS_FILE=basename(__FILE__);
      
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "SELECT `idUtente`, `nome`, `cognome`, `codiceFiscale`, `username`, `passwordHash`, `forzaCambioPassword`
         FROM `sacs`.`Utenti`
         WHERE `nome`=? AND `cognome`=? AND `codiceFiscale`=? AND (`dataCessazione` IS NULL OR `dataCessazione` > CURDATE())"
      );
      $Qry->set_Parm($usrName);
      $Qry->set_Parm($usrCognome);
      $Qry->set_Parm($usrCf);
      $Qry->set_Type("S");
      $Qry->set_NField(7);
      $QryArr->push_Query($Qry);
      $QryArr->set_Transaction(false);
      $QryArr->set_RollBack(false);
      WriteLog("Q", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Select)", $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Select)", NULL);
      QueryExec($FROM, $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Select)", NULL);
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} got error calling QueryExec");      
    }
    
    /* NON TROVATO */
    if ($Qry->get_NRec() == 0) 
    {
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - NO RECORD RETURNED BY QUERY (Select)", NULL);
        DisplayErr($FROM, $fida);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
      }
    }

    /* TROVATO */
    if ($Qry->get_NRec() > 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QUERY EXECUTION RETURNED ".$Qry->get_Nrec()." RECORDS", NULL);
      try 
      {
        $QryArrUpd=new SqlQueryArr;
        $QryUpd = new SqlQuery();
        $QryUpd->set_SqlTxt(
          "UPDATE `sacs`.`Utenti` SET `forzaCambioPassword`=? 
           WHERE `codiceFiscale`=? AND (`dataCessazione` IS NULL OR `dataCessazione` > CURDATE())"
        );
        $QryUpd->set_Parm(1);
        $QryUpd->set_Parm($usrCf);
        $QryUpd->set_Type("U");
        $QryUpd->set_NField(1);
        $QryArrUpd->push_Query($QryUpd);
        $QryArrUpd->set_Transaction(true);
        $QryArrUpd->set_RollBack(false);      
        WriteLog("Q", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Update)", $QryArrUpd);
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Update)", NULL);
        QueryExec($FROM, $QryArrUpd);
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Update)", NULL);
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling QueryExec", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} got error calling QueryExec");  
      }

      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$QryUpd->get_NRec()} record modified by update", NULL);
      try 
      {
        DisplayMSG($FROM, $fida, $Qry->get_ArrParms(), $Qry->get_ArrRec());
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling DisplayMSG", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} got error calling DisplayMSG");  
      }
    }
    unset($QryArr);
    unset($QryArrUpd);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoPWD end", NULL);    
    return;
  }

  /*
   * REGISTRAZIONE
   */ 
  function ActRegistrazione($FROM, $objPost)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";

    $usrName    = $objPost->getPayload()->getUtenteParam('USRNAME');
    $usrCognome = $objPost->getPayload()->getUtenteParam('USRCOGNOME');
    $usrCf      = $objPost->getPayload()->getUtenteParam('USRCF');
    $usrId      = $objPost->getPayload()->getUtenteParam('USR_ID');
    $pwd        = $objPost->getPayload()->getUtenteParam('PWD');
    $fida       = $objPost->getPayload()->getUtenteParam('FIDA');

    // Messa in sicurezza della password inviata in registrazione con algoritmo BCRYPT standard
    $hashPWD = password_hash($pwd, PASSWORD_BCRYPT);

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRegistrazione begins", NULL);    
    try 
    {
      $FROM=$FROM."->".__FUNCTION__;
      $THIS_FILE=basename(__FILE__);
      $QryArr=new SqlQueryArr;
      
      // Generiamo un ID temporaneo univoco lato codice basato sul timestamp, 
      // così evitiamo sotto-query con stringhe "TMP%%" che rompono lo sprintf interno
      $New_ID_Utente = "TMP" . bin2hex(random_bytes(4));

      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "INSERT INTO `sacs`.`Utenti`
         (
           `idUtente`, `nome`, `cognome`, `codiceFiscale`, `username`, `passwordHash`, `dataRegistrazione`
         )
         VALUES 
         (
           ?, ?, ?, ?, ?, ?, CURDATE()
         )"
      );
      $Qry->set_Parm($New_ID_Utente);
      $Qry->set_Parm($usrName);
      $Qry->set_Parm($usrCognome);
      $Qry->set_Parm($usrCf);
      $Qry->set_Parm($usrId);
      $Qry->set_Parm($hashPWD); 
      $Qry->set_Type("I");
      $Qry->set_NField(7);
      $QryArr->push_Query($Qry);
      
      $PMTxt="====NUOVA REGISTRAZIONE====<br> Nome: {$usrName}<br>Cognome: {$usrCognome}<br>CF: {$usrCf}<br>UID: {$usrId}<br>PWD: [HASHED]<br>Necessario assegnare SITO ed ABILITAZIONE.<br> ";
      $QryPM = new SqlQuery();
      $QryPM->set_SqlTxt(  
        "INSERT INTO `sacs`.`Promemoria` 
            (
              `idUtente`, `nota`, `dataInserimento`, `letto`
            ) 
            VALUES 
            (
              ?, ?, CURDATE(), 0
            )"
        );
      $QryPM->set_Parm($New_ID_Utente);
      $PM_Nota = "Assegnare Ruoli e Sedi per nuovo utente registrato (" . $usrId . ")";
      $QryPM->set_Parm($PM_Nota);
      $QryPM->set_Type("I");
      $QryPM->set_NField(2);
      $QryArr->push_Query($QryPM);
      
      $QryArr->set_Transaction(true);
      $QryArr->set_RollBack(false);      
      WriteLog("Q", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PREPARED QUERY (Insert)", $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START QUERY EXECUTION (Insert)", NULL);
      QueryExec($FROM, $QryArr);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END QUERY EXECUTION (Insert)", NULL);
    } 
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} got error calling QueryExec");  
    }

    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$Qry->get_NRec()} record added by insert", NULL);
    if ($Qry->get_NRec() == 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error inserting values 0 record added", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__-3, "got error inserting values 0 record added");
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} got error inserting values 0 record added");  
    }

    if ($Qry->get_NRec() == 1) 
    {
      try
      {
        DisplayMSG($FROM, $fida, $Qry->get_ArrParms(), $Qry->get_ArrRec());
      }
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling DisplayMSG", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} got error calling DisplayMSG");        
      }
    }
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRegistrazione ends", NULL);    
    return;
  }
?>