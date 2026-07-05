<?php
// Superclasse per dati POST: ANAG + GEST + APP
  class clPost 
  {
    public clUtente $Utente; // ANAG + IMPIEGHI
    public?clGest $Gest; // NULL per LOGIN
    public?clApp $App; // NULL per LOGIN

    public function __construct($arrFlat) 
    {
      $this->Utente = new clUtente($arrFlat);
      $this->Gest = new clGest(); // Per ora ritorna NULL
      $this->App = new clApp(); // Per ora ritorna NULL
    }

    public function writeDatiPost(): array 
    {
      $arr = $this->Utente->writeDatiPost();
      if ($this->Gest)
      {
        $arr = array_merge($arr, $this->Gest->writeDatiPost());
      }
      if ($this->App)
      {
        $arr = array_merge($arr, $this->App->writeDatiPost());
      }
      return $arr;
    }
  }

  class clUtente
  {
    private $idUtente;
    private $nome;
    private $cognome;
    private $codiceFiscale;
    private $username;
    private $passwordHash;
    private $forzaCambioPassword;
    private $isManagementRoot;
    private $isMultisitoRoot;
    private $dataRegistrazione;
    private $abilitazioniSiti = [];
    private $abilMultisito = [];

    public function __construct($righeDb = null) 
    {
      var_dump($righeDb);
      $FROM = "clUtente";
      $THIS_FILE = basename(__FILE__,".php");
      $THIS_FUNCTION = $THIS_FILE."(".__FUNCTION__.")";

      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clUtente INIZIO", NULL);

        if (empty($righeDb) ||!is_array($righeDb)) 
        {
          throw new Exception("Array righeDb vuoto o non valido");
        }

        $r0 = $righeDb[0];
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Parsing ANAG da prima riga", NULL);

        $this->setIdUtente($r0['idUtente']?? null);
        $this->setNome($r0['nome']?? null);
        $this->setCognome($r0['cognome']?? null);
        $this->setCodiceFiscale($r0['codiceFiscale']?? null);
        $this->setUsername($r0['username']?? null);
        $this->setPasswordHash($r0['passwordHash']?? null);
        $this->setForzaCambioPassword(isset($r0['forzaCambioPassword'])? (bool)$r0['forzaCambioPassword'] : false);
        $this->setIsManagementRoot(isset($r0['isManagementRoot'])? (bool)$r0['isManagementRoot'] : false);
        $this->setIsMultisitoRoot(isset($r0['isMultisitoRoot'])? (bool)$r0['isMultisitoRoot'] : false);
        $this->setDataRegistrazione($r0['dataRegistrazione']?? null);

        if (is_null($this->idUtente)) 
        {
          throw new Exception("idUtente mancante in riga 0");
        }
        if (is_null($this->codiceFiscale)) 
        {
          throw new Exception("codiceFiscale mancante in riga 0");
        }

        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "ANAG parsata: idUtente=".$this->idUtente, NULL);
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Parsing IMPIEGHI, righe totali: ".count($righeDb), NULL);

        foreach ($righeDb as $idx => $riga) 
        {
          try 
          {
            $idSito = $riga['idSito']?? null;
            $fkImpAbil = $riga['fkImpiego']?? null;

            if (is_null($fkImpAbil) &&!is_null($riga['fkAbilitazione'])) 
            {
              $this->abilMultisito[] = 
              [
                'idAbilitazione' => $riga['fkAbilitazione']?? '',
                'descrAbil' => $riga['ruoloAziendale']?? '',
                'flagManagement' => (bool)($riga['flagManagement']?? false),
                'flagMultisito' => (bool)($riga['flagMultisito']?? false),
                'iconaInterfaccia' => $riga['iconaInterfaccia']?? ''
              ];
              continue;
            }

            if ($idSito!== null) 
            {
              if (!isset($this->abilitazioniSiti[$idSito])) 
              {
                $this->abilitazioniSiti[$idSito] = 
                [
                  'idImpiego' => $riga['idImpiego']?? null,
                  'impiegoInizio' => $riga['impiegoInizio']?? null,
                  'codiceSito' => $riga['codiceSito']?? '',
                  'indirizzo' => $riga['indirizzo']?? '',
                  'piantaAsset' => $riga['piantaAsset']?? '',
                  'ruoli' => []
                ];
              }

              if (!is_null($riga['idUtenteAbil'])) 
              {
                $this->abilitazioniSiti[$idSito]['ruoli'][$riga['idUtenteAbil']] = 
                [
                  'fkAbilitazione' => $riga['fkAbilitazione']?? '',
                  'AbilInizio' => $riga['AbilInizio']?? null,
                  'ruoloAziendale' => $riga['ruoloAziendale']?? '',
                  'flagManagement' => (bool)($riga['flagManagement']?? false),
                  'flagMultisito' => (bool)($riga['flagMultisito']?? false),
                  'iconaInterfaccia'=> $riga['iconaInterfaccia']?? ''
                ];
              }
            }
          } 
          catch (Exception | Error $e) 
          {
            WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), "Errore parsing riga $idx: ".$e->getMessage());
            throw new Exception("Errore parsing riga $idx: ".$e->getMessage());
          }
        }
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clUtente FINE OK", NULL);

      } 
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clUtente FALLITO", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html"; // Chi rileva emette SYS_ERR
        throw $e; // Rilancia per SRVR
      }
    }    
    // GETTER
    public function getIdUtente() { return $this->idUtente; }
    public function getNome() { return $this->nome; }
    public function getCognome() { return $this->cognome; }
    public function getCodiceFiscale() { return $this->codiceFiscale; }
    public function getUsername() { return $this->username; }
    public function getPasswordHash() { return $this->passwordHash; }
    public function getForzaCambioPassword() { return $this->forzaCambioPassword; }
    public function getIsManagementRoot() { return $this->isManagementRoot; }
    public function getIsMultisitoRoot() { return $this->isMultisitoRoot; }
    public function getDataRegistrazione() { return $this->dataRegistrazione; }
    public function getAbilitazioniSiti() { return $this->abilitazioniSiti; }
    public function getAbilMultisito() { return $this->abilMultisito; }

    // SETTER - aggiunti per standard SACS
    public function setIdUtente($v) { $this->idUtente = $v; }
    public function setNome($v) { $this->nome = $v; }
    public function setCognome($v) { $this->cognome = $v; }
    public function setCodiceFiscale($v) { $this->codiceFiscale = $v; }
    public function setUsername($v) { $this->username = $v; }
    public function setPasswordHash($v) { $this->passwordHash = $v; }
    public function setForzaCambioPassword($v) { $this->forzaCambioPassword = $v; }
    public function setIsManagementRoot($v) { $this->isManagementRoot = $v; }
    public function setIsMultisitoRoot($v) { $this->isMultisitoRoot = $v; }
    public function setDataRegistrazione($v) { $this->dataRegistrazione = $v; }
    
    /**
     * Factory Method: Ricostruisce l'istanza dai dati della POST a staffe
     */
    public static function istanziaDaPost($datiPost) 
    {
      $istanza = new self();
      if (isset($datiPost['utente']['anagrafica'])) 
      {
        $anag = $datiPost['utente']['anagrafica'];
        $istanza->idUtente            = $anag['idUtente'] ?? null;
        $istanza->nome                = $anag['nome'] ?? null;
        $istanza->cognome             = $anag['cognome'] ?? null;
        $istanza->username            = $anag['username'] ?? null;
        $istanza->passwordHash        = $anag['passwordHash'] ?? null;
        $istanza->forzaCambioPassword = (bool)($anag['forzaCambioPassword'] ?? false);
        $istanza->isManagementRoot    = (bool)($anag['isManagementRoot'] ?? false);
        $istanza->isMultisitoRoot     = (bool)($anag['isMultisitoRoot'] ?? false);
        $istanza->dataRegistrazione   = $anag['dataRegistrazione'] ?? null;
      }
      
      if (isset($datiPost['utente']['abilitazioniSiti']))  
      {
        $istanza->abilitazioniSiti = $datiPost['utente']['abilitazioniSiti'];
      }
      return $istanza;
    }

    /**
     * METODO DUMP: Restituisce i dati strutturati ad albero in forma di array nativo
     */
    public function Dump() 
    {
      return var_export($this, true);
    }

    public function writeDatiPost(): array 
    {
      $FROM = "clUtente";
      $THIS_FILE = basename(__FILE__,".php");
      $THIS_FUNCTION = $THIS_FILE."(".__FUNCTION__.")";

      try 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "writeDatiPost INIZIO", null);
        $arr = [];
        // ANAG - uso?? '' per evitare null
        $arr[] = ['name'=>'utente[idUtente]', 'value'=>$this->idUtente?? ''];
        $arr[] = ['name'=>'utente[nome]', 'value'=>$this->nome?? ''];
        $arr[] = ['name'=>'utente[cognome]', 'value'=>$this->cognome?? ''];
        $arr[] = ['name'=>'utente[codiceFiscale]', 'value'=>$this->codiceFiscale?? ''];
        $arr[] = ['name'=>'utente[forzaCambioPassword]', 'value'=>$this->forzaCambioPassword? 1 : 0];
        $arr[] = ['name'=>'utente[isManagementRoot]', 'value'=>$this->isManagementRoot? 1 : 0];
        $arr[] = ['name'=>'utente[isMultisitoRoot]', 'value'=>$this->isMultisitoRoot? 1 : 0];

        $i = 0;
        foreach($this->abilitazioniSiti as $idSito => $sito) 
        {
          $arr[] = ['name'=>"utente[IMPIEGHI][$i][idImpiego]", 'value'=>$sito['idImpiego']?? ''];
          $arr[] = ['name'=>"utente[IMPIEGHI][$i][idSito]", 'value'=>$idSito?? ''];
          $arr[] = ['name'=>"utente[IMPIEGHI][$i][codiceSito]", 'value'=>$sito['codiceSito']?? ''];
          $arr[] = ['name'=>"utente[IMPIEGHI][$i][indirizzo]", 'value'=>$sito['indirizzo']?? ''];
          $a = 0;
          foreach($sito['ruoli'] as $idAbil => $ruolo) 
          {
            $arr[] = ['name'=>"utente[IMPIEGHI][$i][ABILITAZIONI][$a][idAbilitazione]", 'value'=>$ruolo['fkAbilitazione']?? ''];
            $arr[] = ['name'=>"utente[IMPIEGHI][$i][ABILITAZIONI][$a][descrAbil]", 'value'=>$ruolo['ruoloAziendale']?? ''];
            $arr[] = ['name'=>"utente[IMPIEGHI][$i][ABILITAZIONI][$a][flagManagement]", 'value'=>$ruolo['flagManagement']? 1 : 0];
            $arr[] = ['name'=>"utente[IMPIEGHI][$i][ABILITAZIONI][$a][flagMultisito]", 'value'=>$ruolo['flagMultisito']? 1 : 0];
            $arr[] = ['name'=>"utente[IMPIEGHI][$i][ABILITAZIONI][$a][iconaInterfaccia]", 'value'=>$ruolo['iconaInterfaccia']?? ''];
            $a++;
          }
          $i++;
        }
        foreach($this->abilMultisito as $m => $abil) 
        {
          $arr[] = ['name'=>"utente[ABIL_MULTISITO][$m][idAbilitazione]", 'value'=>$abil['idAbilitazione']?? ''];
          $arr[] = ['name'=>"utente[ABIL_MULTISITO][$m][descrAbil]", 'value'=>$abil['descrAbil']?? ''];
          $arr[] = ['name'=>"utente[ABIL_MULTISITO][$m][flagManagement]", 'value'=>$abil['flagManagement']? 1 : 0];
          $arr[] = ['name'=>"utente[ABIL_MULTISITO][$m][flagMultisito]", 'value'=>$abil['flagMultisito']? 1 : 0];
          $arr[] = ['name'=>"utente[ABIL_MULTISITO][$m][iconaInterfaccia]", 'value'=>$abil['iconaInterfaccia']?? ''];
        }
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "writeDatiPost FINE OK, elementi: ".count($arr), null);
        return $arr;

      } 
      catch (Exception | Error $e) 
      {
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        require "../MSG/SYS_ERR.html";
        throw $e;
      }
    }

    /**
     * METODO PRINT: Utilizza printscalar di SRVR_LOG.php per tracciare i dati utente
    */
    public function print() 
    {
      $RetTxt="";
      $RetTxt=$RetTxt."UTENTE:\n";
      $RetTxt=$RetTxt."idUtente: " .  $this->idUtente."\n";
      $RetTxt=$RetTxt."nome: " .  $this->nome."\n";
      $RetTxt=$RetTxt."cognome: " .  $this->cognome."\n";
      $RetTxt=$RetTxt."username: " .  $this->username."\n";
      $RetTxt=$RetTxt."passwordHash" .  $this->passwordHash."\n";
      $RetTxt=$RetTxt."forzaCambioPassword: " . $this->forzaCambioPassword."\n";
      $RetTxt=$RetTxt."isManagementRoot: " .  $this->isManagementRoot."\n";
      $RetTxt=$RetTxt."isMultisitoRoot: " .  $this->isMultisitoRoot."\n";
      $RetTxt=$RetTxt."dataRegistrazione: " . $this->dataRegistrazione."\n";
      $RetTxt=$RetTxt."\t"."ABILITAZIONI PER SITO"."\n";
      $RetTxt=$RetTxt."\t"."SITO"."\n";
      $RetTxt=$RetTxt."\t\t"."ABILITAZIONE"."\n";
      $RetTxt=$RetTxt."\t"."ABILITAZIONI GLOBALI"."\n";    
      $RetTxt=$RetTxt."\t\t"."ABILITAZIONE"."\n";
        
      //print(abilitazioniSiti) da fare
      /* es:
       *     
       * foreach ($this->abilitazioniSiti as $idSito => $sito) 
       * {
       *   $RetTxt.= "\n SITO:". $sito['codiceSito']. " ID:". $idSito;
           foreach ($sito['ruoli'] as $idAbil => $ruolo) 
           {
            $RetTxt.= "\n  ABIL:". $ruolo['fkAbilitazione'];
            $RetTxt.= " RUOLO:". $ruolo['ruoloAziendale'];
            $RetTxt.= " M:". ($ruolo['flagManagement']? "1":"0");
            $RetTxt.= " MS:". ($ruolo['flagMultisito']? "1":"0");
           }
         }
    */
      
      return $RetTxt;      
    }
  }

  // Classi vuote per GEST e APP - ritornano NULL per ora
  class clGest 
  {
    public function writeDatiPost(): array { return []; }
  }
  class clApp 
  {
    public function writeDatiPost(): array { return []; }
  }


?>


