<?php
  /* 
   * ActLoadSite
   */ 
  function GEST_SEL_Fnct($FROM,$_post)
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
    $ArrRec=array();
    try 
    {
      $QryFnct=$_post["QRY_FNC"];
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$QryFnct} begins", NULL);
      $QryArr=new SqlQueryArr;
      $Qry = new SqlQuery();
      
      switch ($QryFnct) 
      {
        case "SEL_GEST_SITI":
          $Qry->set_SqlTxt(
            "select `ID_Sito`, `Sito`, `Indirizzo` from `siti` "
          );
          $Qry->set_Type("S");
          $Qry->set_NField(3);
          break;         
        case "SEL_GEST_USR":
          $Qry->set_SqlTxt(
            "select distinct `ID_Utente`, `Nome`, `Cognome`, `CF`, `UID`, `PWD`, `PWD2CHANGE`, `utenti`.`ID_Sito`, `siti`.`Sito`, `utenti`.`ID_Abil`, 
              `diz_soc_abilitazioni`.`Descrizione`, `diz_soc_abilitazioni`.`Management`
               FROM `utenti`,`siti`, `diz_soc_abilitazioni`
               WHERE
               `utenti`.`ID_Sito`=`siti`.`ID_sito` AND
               `utenti`.`ID_Abil`=`diz_soc_abilitazioni`.`ID_Abil`
               UNION distinct
               select distinct `ID_Utente`, `Nome`, `Cognome`, `CF`, `UID`, `PWD`, `PWD2CHANGE`, `ID_Sito`, NULL, `ID_Abil`, NULL, NULL
               FROM `utenti`
               WHERE
               `utenti`.`ID_Sito` IS NULL OR `utenti`.`ID_Sito`=\"\" AND
               `utenti`.`ID_Abil` IS NULL  OR `utenti`.`ID_Abil`=\"\"
               GROUP by `utenti`.`ID_Sito`"
          );
          $Qry->set_Type("S");
          $Qry->set_NField(12);
          break;     
        case "SEL_DIZ_SOC_ABIL":
          $Qry->set_SqlTxt(
            "select `ID_Abil`, `Descrizione`, `Icona` from `diz_soc_abilitazioni` "
          );
          $Qry->set_Type("S");
          $Qry->set_NField(3);
          break;    
        case "SEL_PROMEM":  
          $Qry->set_SqlTxt(
            "select `ID`, `FromFnct`, `DataOpen`, `Dest`, `Motivo`  FROM `pro_memoria` WHERE dest=\"M\" and ResData IS NULL"
          );
          $Qry->set_Type("S");
          $Qry->set_NField(5);
          break; 
        default:
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: function not found", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: function not found. Got:".$QryFnct);
          throw new Exception("{$THIS_FUNCTION} - error (function not found) writing text for QueryExec");
      }      
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
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - got error calling QueryExec");
    }
    /* NON TROVATO */
    if ($Qry->get_NRec() == 0) 
    {
      try 
      {
        switch ($QryFnct) 
        {
          case "SEL_GEST_SITI":
          case "SEL_GEST_USR":  
          case "SEL_DIZ_SOC_ABIL":  
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - NO RECORD RETURNED BY QUERY (Select)", NULL);
            DisplayErr($FROM, $_post["FIDA"]);
            break;
          case "SEL_PROMEM":
            $Qry->set_ArrRec(NULL);
            break;
          default:
            throw new Exception("{$THIS_FUNCTION} - QryFnct not found: got {$QryFnct}");
        }
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
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - {$QryFnct} ends", NULL);
    return $Qry->get_ArrRec();
  }  
?>