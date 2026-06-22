<?php
  /*
   * NOTA: Nelle azioni richiamate dalle funzioni di questo file e' eseguita l' istruzione:
   *              $stmt=$pdo->prepare(sprintf($Qry->get_SqlTxt()));
   * Se si usano wildchars, specie %, dovrebbero essere scritti con i cqaratteri di escape per 
   * printf, (nel caso %%) perche' altrimenti printf li considera parametri
   */ 
  /*
   * ACTLOGIN
   */
  function ActLogin($FROM, $_post)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    /*
     * NOTA:For most databases, PDOStatement::rowCount() does not return the number of rows 
     *      affected by a SELECT statement. 
     *      Instead, use PDO::query() to issue a SELECT COUNT(*) statement with the same 
     *      predicates as your intended SELECT statement, then use PDOStatement::fetchColumn() 
     *      to retrieve the number of matching rows.
     */ 
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActLogin begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      /*$Qry->set_SqlTxt(
        "SELECT `utenti`.`ID_Utente`, `utenti`.`Nome`, `utenti`.`Cognome`, `CF`, `UID`, `PWD`, `PWD2CHANGE`, `sito`, 
         `diz_soc_abilitazioni`.`Management`,  `diz_soc_abilitazioni`.`ID_Abil`, `Descrizione`, `Multisito`, `Icona` ,
         `DataReg`, `utenti`.`DataFine`, `IDCausaFine` 
          FROM `utenti`, `siti`, `diz_soc_abilitazioni`, `utenti_impiego` 
          WHERE 
          `utenti`.`ID_Sito`= `siti`.`ID_Sito` AND
          `utenti`.`ID_Abil`= `diz_soc_abilitazioni`.`ID_Abil` AND
          `utenti`.`ID_Utente`= `utenti_impiego`.`ID_Utente` AND
          `utenti`.`ID_Sito`=`utenti_impiego`.`ID_Sito` AND
          `utenti`.`ID_Abil`=`utenti_impiego`.`ID_Abil` AND
          `utenti_impiego`.`DataFine` IS NULL AND
          `utenti`.`DataFine` IS NULL AND
          UID=? and PWD=? order by sito"
      /*$Qry->set_SqlTxt(
      );*/
      $Qry->set_SqlTxt(
        "SELECT 
          u.`idUtente`, 
          u.`nome`, 
          u.`cognome`, 
          u.`username`, 
          u.`passwordHash`, 
          u.`forzaCambioPassword`, 
          u.`isManagementRoot`, 
          u.`isMultisitoRoot`, 
          u.`dataRegistrazione`,
          ui.`idImpiego`,
          ui.`dataInizio` AS `impiegoInizio`,
          ds.`idSito`, 
          ds.`codiceSito`, 
          ds.`indirizzo`, 
          ds.`piantaAsset`,
          ua.`idUtenteAbil`,
          ua.`fkAbilitazione`,               -- VARCHAR(3) (Es. Z01, Z02)
          ua.`AbilInizio`,
          dsa.`descrAbil` AS `ruoloAziendale`, 
          dsa.`flagManagement`, 
          dsa.`flagMultisito`, 
          dsa.`iconaInterfaccia`
        FROM `sacs`.`Utenti` u
        INNER JOIN `sacs`.`UtentiImpiego` ui ON u.`idUtente` = ui.`fkUtente`
        INNER JOIN `sacs`.`DizSiti` ds ON ui.`fkSito` = ds.`idSito`
        INNER JOIN `sacs`.`UtentiAbilitazioni` ua ON u.`idUtente` = ua.`fkUtente`
        INNER JOIN `sacs`.`DizSocAbilitazioni` dsa ON ua.`fkAbilitazione` = dsa.`idAbilitazione`
        WHERE u.`dataRegistrazione` IS NOT NULL AND
              u.`dataCessazione` IS NULL AND
              u.`fkCausafine` IS NULL AND
              ui.`dataInizio` IS NOT NULL AND
              ui.`dataCessazione` IS NULL AND
              ua.`AbilFine` IS NULL AND -- Verifica che l'abilitazione non sia scaduta
              (ua.`fkImpiego` = ui.`idImpiego` OR ua.`fkImpiego` IS NULL) AND -- Logica per ruoli Locali o Globali
              u.`username` = ?
              ORDER BY ds.`codiceSito` ASC, ua.`fkImpiego` ASC;" 
      );  
      $Qry->set_Parm($_post["UID"]);
      $Qry->set_Type("S");
      $Qry->set_NField(22);
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
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling QueryExec", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} - got error calling QueryExec");
    }
    /* NON TROVATO */
    if ($Qry->get_NRec() == 0) 
    {
      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - NO RECORD RETURNED BY QUERY (Select)", NULL);
        DisplayErr($FROM, $_post["IDACTION"]);
      } 
      catch (Exception | Error $e) 
      {
        //catch exception
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
      }
    }
    /* 
     * TROVATO 
     */
    if ($Qry->get_NRec() > 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QUERY EXECUTION RETURNED ".$Qry->get_Nrec()." RECORDS", NULL);
      if ($Qry->get_Rec(0)["PWD2CHANGE"] == true) 
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
          throw new Exception("{$THIS_FUNCTION} got error calling Choose");         }
      }
    }
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActLogin ends", NULL);
    return;
  }
  /*
   * FORCE PASSWORD
   */ 
  function ActForcePWD($FROM, $_post)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActForcePWD begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "UPDATE `utenti` SET `PWD`=?,`PWD2CHANGE`=? 
          WHERE 
          CF=?"
      );
      $Qry->set_Parm($_post["PWD"]);
      $Qry->set_Parm(0);
      $Qry->set_Parm($_post["CF"]);
      $Qry->set_Type("U");
      $Qry->set_NField(1);
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
    /* get_NRec() torna il numero delle righe modificate
     * Quindi non distingue tra record non trovato o record trovato, ma non modificato.
     */
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$Qry->get_NRec()} record modified by update", NULL);
    /*
     * MESSAGGIO DI PASSWORD AGGIORNATA
     */
    try 
    {
      DisplayMSG($FROM, $_post["IDACTION"], $Qry->get_ArrParms(), $Qry->get_ArrRec());
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
  function ActRecuperoUID($FROM, $_post)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoUID begins", NULL);
    try 
    {
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "select `ID_Utente`,`Nome`,`Cognome`,`CF`,`UID`,`PWD`,`PWD2CHANGE`
          from `utenti`
          WHERE 
          Nome=? and Cognome=? and CF=?"
      );
      $Qry->set_Parm($_post["USRNAME"]);
      $Qry->set_Parm($_post["USRCOGNOME"]);
      $Qry->set_Parm($_post["USRCF"]);
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
        DisplayErr($FROM, $_post["FIDA"]);
      } 
      catch (Exception | Error $e) 
      {
        //catch exception
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DisplayErr", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.
        html";
        throw new Exception("{$THIS_FUNCTION} - got error calling DisplayErr");
      }
    }
    /* TROVATO */
    if ($Qry->get_NRec() > 0) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QUERY EXECUTION RETURNED ".$Qry->get_Nrec()." RECORDS", NULL);
      try 
      {
        DisplayMSG($FROM, $_post["FIDA"], $Qry->get_ArrParms(), $Qry->get_ArrRec());
      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error calling DisplayMSG", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} got error calling DisplayMSG");      
      }
    }
    /* TROVATO - FINE */
    unset($QryArr);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoUID ends", NULL);
    return;
  }
  /*
   * RECUPERO PWD
   */ 
  function ActRecuperoPWD($FROM, $_post)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoPWD begins", NULL);
    try 
    {
      $FROM=$FROM."->".__FUNCTION__;
      $THIS_FILE=basename(__FILE__);
      
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "select `ID_Utente`,`Nome`,`Cognome`,`CF`,`UID`,`PWD`,`PWD2CHANGE`
          from `utenti`
          WHERE 
          Nome=? and Cognome=? and CF=?"
      );
      $Qry->set_Parm($_post["USRNAME"]);
      $Qry->set_Parm($_post["USRCOGNOME"]);
      $Qry->set_Parm($_post["USRCF"]);
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
        DisplayErr($FROM, $_post["FIDA"]);
      } 
      catch (Exception | Error $e) 
      {
        //catch exception
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
          "UPDATE `utenti` SET `PWD2CHANGE`=? 
            WHERE 
            CF=?"
        );
        $QryUpd->set_Parm(1);
        $QryUpd->set_Parm($_post["USRCF"]);
        $QryUpd->set_Type("U");
        $QryUpd->set_NField(1);
        $QryArrUpd->push_Query($QryUpd);
        $QryArrUpd->set_Transaction(true);
        $QryArrUpd->set_RollBack(false);      
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
      /* get_NRec() torna il numero delle righe modificate
       * Quindi non distingue tra record non trovato o record trovato, ma non modificato.
       */
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$Qry->get_NRec()} record modified by update", NULL);
      try 
      {
        /*
         * MESSAGGIO DI PASSWORD AGGIORNATA
         */ 
        DisplayMSG($FROM, $_post["FIDA"], $Qry->get_ArrParms(), $Qry->get_ArrRec());
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
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRecuperoPWD begins", NULL);    
    return;
  }
  /*
   * REGISTRAZIONE
   */ 
  function ActRegistrazione($FROM, $_post)
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ActRegistrazione begins", NULL);    
    try 
    {
      $FROM=$FROM."->".__FUNCTION__;
      $THIS_FILE=basename(__FILE__);
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      $Qry->set_SqlTxt(
        "INSERT INTO `utenti`
         (
           `ID_Utente`, `Nome`, `Cognome`, `CF`, `UID`, `PWD`, `PWD2CHANGE`, `ID_Sito`, `ID_Abil`,  `DataReg`, `DataFine`, `IDCausaFine`
         )
         VALUES 
         (
           (select concat(\"TMP\",count(*)+1) from `utenti` B WHERE ID_Utente LIKE \"TMP%%\"), 
                ?, ?, ?, ?, ?, 1, NULL, NULL, CURDATE() +0, NULL, 0
         )"
      );
      $Qry->set_Parm($_post["USRNAME"]);
      $Qry->set_Parm($_post["USRCOGNOME"]);
      $Qry->set_Parm($_post["USRCF"]);
      $Qry->set_Parm($_post["USR_ID"]);
      $Qry->set_Parm($_post["PWD"]);
      $Qry->set_Type("I");
      $Qry->set_NField(13);
      $QryArr->push_Query($Qry);
      $PMTxt="====NUOVA REGISTRAZIONE====<br> Nome: {$_post["USRNAME"]}<br>Cognome: {$_post["USRCOGNOME"]}<br>CF: {$_post["USRCF"]}<br>UID: {$_post["USR_ID"]}<br>PWD: {$_post["PWD"]}<br>Necessario assegnare SITO ed ABILITAZIONE.<br> ";
      $QryPM = new SqlQuery();
      $QryPM->set_SqlTxt(  
        "INSERT INTO `pro_memoria` 
            (
              `ID`, `FromFnct`, `DataOpen`, `Dest`, `Motivo`, `ResBy`, `ResData`
            ) 
            VALUES 
            (
              (
                SELECT 
                  CASE 
                    WHEN MIN(ID) IS NULL THEN 1
                    WHEN MIN(ID) > 1 THEN MIN(ID) - 1
                    WHEN MIN(ID) = 1 THEN MAX(ID) + 1
                  END 
                  AS m 
                FROM `pro_memoria` B),?,?,?,?,NULL,NULL
            )"
        );
      $QryPM->set_Parm($FROM);
      $QryPM->set_Parm(date("Ymd"));
      $QryPM->set_Parm("M");
      $QryPM->set_Parm($PMTxt);
      $QryPM->set_Type("I");
      $QryPM->set_NField(3);
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
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} got error inserting values 0 record added");  
    }
    if ($Qry->get_NRec() > 1) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} got error inserting values. More than 1 record added", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} got error inserting values. More than 1 record added");  
    }
    /*
     * MESSAGGIO DI REGISTRAZIONE AVVENUTA
     */
    if ($Qry->get_NRec() == 1) 
    {
      try
      {
        DisplayMSG($FROM, $_post["FIDA"], $Qry->get_ArrParms(), $Qry->get_ArrRec());
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