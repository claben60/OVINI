<?php
  /*
   * DB_OPEN
   */
  function DB_Open($FROM) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    $pdo = null;
    try 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START PDO CREATION", NULL);
      $hostname = C_HOSTNAME;
      $dbname = C_DBNAME;
      $dbusr = C_DBUSR;
      $dbpwd = C_DBPWD;
      $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $dbusr, $dbpwd);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END PDO CREATION", NULL);
      return $pdo;  
    } 
    catch (Exception | Error | PDOException $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PDO CREATION ERROR", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - PDO CREATION ERROR");
    }
  }
  /*
   * DB_CLOSE
   */
  function DB_Close($FROM,$pdo) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    /*
     * Per chiudere la connessione sarà sufficiente distruggere l'oggetto creato,
     * ma bisognerà accertarsi di aver chiuso precedentemente tutte le istanze dell'oggetto stesso:
     */
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - START PDO CLOSE", NULL);
      unset($pdo); 
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - END PDO CLOSE", NULL);
    } 
    catch (Exception | Error | PDOException $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - PDO CLOSE ERROR", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - PDO PDO CLOSE ERROR");
    }
  }
  /*
   * BEGIN TRANSACTION
   */
  function DB_BeginTransaction($FROM,$pdo) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - STARTING TRANSACTION", NULL);
      $pdo->beginTransaction();
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - TRANSACTION STARTED", NULL);
    } 
    catch (Exception | Error | PDOException $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ERROR STARTING TRANSACTION", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - ERROR STARTING TRANSACTION");
    }
  }
  /*
   * COMMIT TRANSACTION
   */
  function DB_Commit($FROM,$pdo) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - STARTING COMMIT - Transaction successful", NULL);
      $pdo->commit();
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - COMMIT FINISHED", NULL);
    } 
    catch (Exception | Error | PDOException $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - COMMIT ERROR", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - COMMIT ERROR");
    }
  }
  /*
   * ROLLBACK TRANSACTION
   */
  function DB_Rollback($FROM,$pdo) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - STARTING ROLLBACK", NULL);
      $pdo->rollback();
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ROLLBACK FINISHED", NULL);
    } 
    catch (Exception | Error | PDOException $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - ROLLBACK ERROR", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - ROLLBACK ERROR");
    }
  }
  /*
   * QUERY EXEC (DISPATCHER DI TRANSAZIONI)
   */
  function QueryExec($FROM,$QryArr) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Running QueryExec", NULL);
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Found {$QryArr->get_NElements()} query", NULL);    
    WriteLog("Q", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Queries required:", $QryArr);
    /*
     * Estraggo le query
     */ 
    if($QryArr->get_NElements()==0)
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - No query to elabotrate", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, (__LINE__ - 3), "Attempted to execute an empty SqlQueryArr container (NElements is 0).");
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - No query to elabotrate");
    }
    /*
     * OPEN DATABASE
     */
    $pdo=null;
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Connecting to DB", NULL);
      $pdo=DB_Open($FROM);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Connection to DB done", NULL);
    }
    catch (Exception | Error $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_Open ", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - got error calling DB_Open");
    }
    if($QryArr->get_Transaction()==true)
    {
      /*
       * BEGIN TRANSACTION
       */
      try
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Starting transaction", NULL);
        DB_BeginTransaction($FROM,$pdo);
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Transaction started", NULL); 
      }
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_BeginTransaction ", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} - got error calling DB_BeginTransaction");
      }
    }
    /*
     * ELABORAZIONE QUERY
     */
    foreach($QryArr->Get_Arr() as $Qry)
    {
      switch ($Qry->get_Type())
      {
        /*
         * SELECT
         */ 
        case "S":
          try
          {
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Running DB_ExecSelect ", NULL);
            DB_ExecSelect($FROM,$pdo,$Qry);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_ExecSelect ended", NULL);
          }
          catch (Exception | Error $e) 
          {
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_ExecSelect ", NULL);
            WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw new Exception("{$THIS_FUNCTION} - got error calling DB_ExecSelect");
          }
          break;
        /*
         * INSERT, UPDATE, DELETE
         */ 
        case "I":
        case "U":
        case "D":
          try
          {
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - running DB_ExecIUD({$Qry->get_Type()})", NULL);
            DB_ExecIUD($FROM,$pdo,$Qry);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_ExecIUD({$Qry->get_Type()}) ended", NULL);
          }
          catch (Exception | Error | DB_ExecUpdate $e) 
          {
            $QryArr->set_RollBack(true);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_ExecIUD({$Qry->get_Type()}) ", NULL);
            WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw new Exception("{$THIS_FUNCTION} - got error calling DB_ExecIUD({$Qry->get_Type()})");
          }
          break;
        default:
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - query type not found ", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "{$THIS_FUNCTION} - query type not found ");
          require "../MSG/SYS_ERR.html";
          throw new Exception("{$THIS_FUNCTION} - query type not found");
      } // termine switch
    } //chiude il ciclo di elaborazioni delle query
    if($QryArr->get_Transaction()==true)
    {
      if($QryArr->get_RollBack()==true) 
      {
        /*
         * ROLLBACK
         */  
        try
        {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Running DB_Rollback", NULL);
          DB_Rollback($FROM,$pdo);
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_Rollback end", NULL);
        }
        catch (Exception | Error | DB_CloseException |  DB_RollbackException $e) 
        {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_Rollback", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
          throw new Exception("{$THIS_FUNCTION} - got error calling DB_Rollback");
        }
      }
      if($QryArr->get_RollBack()==false) 
      {
        /*
         * COMMIT
         */  
        try
        {
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Running DB_Commit", NULL);
          DB_Commit($FROM,$pdo);
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_Commit end", NULL);        
        }
        catch (Exception | Error | DB_OpenException $e) 
        {
          /* errore da altre funzioni. */
          WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_Commit", NULL);
          WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
          throw new Exception("{$THIS_FUNCTION} - got error calling DB_Commit");
        }
      }
    }
    /*
     * CLOSE DATABASE
     */
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - Running DB_Close", NULL);
      $pdo=DB_Close($FROM,$pdo);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_Close end", NULL);      
    }
    catch (Exception | Error | DB_OpenException $e) 
    {
      /* errore da altre funzioni. */
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - got error calling DB_Close", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      throw new Exception("{$THIS_FUNCTION} - got error calling DB_Close");
    }
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - QueryExec terminated", NULL);    
    WriteJrnl($FROM,$QryArr);
    return;
  }
  /*
   * ESEGUE LE SELECT
   */ 
  function DB_ExecSelect($FROM,$pdo,$Qry) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    /* 
     * SELECT - INIZIO 
     */
    try 
    {
      $Qry->set_RC(1); 
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - STARTING SELECT", NULL);    
      $stmt=$pdo->prepare(sprintf($Qry->get_SqlTxt()));
      $stmt->execute($Qry->get_ArrParms());
      $Qry->set_RC(0);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - SELECT DONE", NULL);    
    } 
    catch (Exception | Error | PDOException $e) 
    {
      $Qry->set_RC(1);
      $Qry->set_NRec(0);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - SELECT ABEND ", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - SELECT ABEND");
    }
    /*
     * SELECT - ESAME RISULTATI
     *
     * FETCH
     *
     * istruzione con cui facciamo scorrere i dati estratti dal database associando ad ogni riga
     * la variabile $row grazie al metodo fetch(),
     * PDO::FETCH_ASSOC ci dice che stiamo creando un array associativo usando come chiavi i
     * nomi dei campi;
     * PDO::FETCH_NUM che usa un indice numerico e rende quindi obbligatorio rispettare l'ordine
     * dei campi espresso nell'istruzione SELECT;
     * PDO::FETCH_BOTH che usa contemporaneamente un indice numerico ed  un array associativo 
     * che usa come chiavi i dei campi espresso nell'istruzione SELECT
     * 
     * The return value of FETCH on success depends on the fetch type. 
     * In all cases, false is returned on failure or if there are no more rows.
     * 
     */
    try
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - FETCH BEGIN ", NULL);
      $Qry->set_NRec(0);
      while ($row = $stmt->fetch(PDO::FETCH_BOTH)) 
      {
        $Qry->set_Rec($row);
      }
      
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - FETCH END ".sizeof($Qry->get_ArrRec())." recods avaliable", NULL);
    }
    catch (Exception | Error | PDOException $e) 
    {
      $Qry->set_RC(1);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - FETCH ABEND ", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - FETCH ABEND");
    }
    return;
  }
  /*
   * ESEGUE INSERT, UPDATE E DELETE
   */
  function DB_ExecIUD($FROM,$pdo,$Qry) 
  {
    $THIS_FILE=basename(__FILE__,".php");
    $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    try 
    {
      //echo __FUNCTION__;
      $NRec=0;
      $Qry->set_NRec(0);
      $Qry->set_RC(1);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - STARTING DB_ExecIUD(".$Qry->get_Type(). ")", NULL);
      $stmt=$pdo->prepare(sprintf($Qry->get_SqlTxt()));
      $NRec=$stmt->execute($Qry->get_ArrParms());
      $Qry->set_RC(0);
      $Qry->set_NRec($NRec);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_ExecIUD(".$Qry->get_Type(). ") DONE", NULL);
    } 
    catch (Exception | Error | PDOException $e) 
    {
      $Qry->set_RC(1);
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} - DB_ExecIUD(".$Qry->get_Type()." ABEND ", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - DB_ExecIUD(".$Qry->get_Type()." ABEND");
    }
    return;
  }
?>

