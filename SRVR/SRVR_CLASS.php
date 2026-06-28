<?php
  class Utente 
  {
    // --- PROPRIETÀ PRIVATE (Sezione Anagrafica) ---
    private $idUtente;
    private $nome;
    private $cognome;
    private $username;
    private $passwordHash;
    private $forzaCambioPassword;
    private $isManagementRoot;
    private $isMultisitoRoot;
    private $dataRegistrazione;

    // --- Struttura ad albero per i siti e ruoli ---
    private $abilitazioniSiti = []; 

    /**
     * Costruttore: riceve l'array piatto SQL e modella la struttura ad albero
     */
    public function __construct($righeDb = null) 
    {
      if (!empty($righeDb) && is_array($righeDb)) 
      {
        $primaRiga = $righeDb[0];

        $this->idUtente            = $primaRiga['idUtente'] ?? null;
        $this->nome                = $primaRiga['nome'] ?? null;
        $this->cognome             = $primaRiga['cognome'] ?? null;
        $this->username            = $primaRiga['username'] ?? null;
        $this->passwordHash        = $primaRiga['passwordHash'] ?? null;
        $this->forzaCambioPassword = isset($primaRiga['forzaCambioPassword']) ? (bool)$primaRiga['forzaCambioPassword'] : false;
        $this->isManagementRoot    = isset($primaRiga['isManagementRoot']) ? (bool)$primaRiga['isManagementRoot'] : false;
        $this->isMultisitoRoot     = isset($primaRiga['isMultisitoRoot']) ? (bool)$primaRiga['isMultisitoRoot'] : false;
        $this->dataRegistrazione   = $primaRiga['dataRegistrazione'] ?? null;

        foreach ($righeDb as $riga) 
        {
          $idSito = $riga['idSito'] ?? null;
          if ($idSito !== null) 
          {
            if (!isset($this->abilitazioniSiti[$idSito])) 
            {
              $this->abilitazioniSiti[$idSito] = 
              [
                'idImpiego'     => $riga['idImpiego'] ?? null,
                'impiegoInizio' => $riga['impiegoInizio'] ?? null,
                'codiceSito'    => $riga['codiceSito'] ?? '',
                'indirizzo'     => $riga['indirizzo'] ?? '',
                'piantaAsset'   => $riga['piantaAsset'] ?? '',
                'ruoli'         => [] 
              ];
          }

          $idUtenteAbil = $riga['idUtenteAbil'] ?? null;
          if ($idUtenteAbil !== null) 
          {
            $this->abilitazioniSiti[$idSito]['ruoli'][$idUtenteAbil] = 
            [
              'fkAbilitazione'  => $riga['fkAbilitazione'] ?? '',
              'AbilInizio'      => $riga['AbilInizio'] ?? null,
              'ruoloAziendale'  => $riga['ruoloAziendale'] ?? '',
              'flagManagement'  => isset($riga['flagManagement']) ? (bool)$riga['flagManagement'] : false,
              'flagMultisito'   => isset($riga['flagMultisito']) ? (bool)$riga['flagMultisito'] : false,
              'iconaInterfaccia'=> $riga['iconaInterfaccia'] ?? ''
            ];
          }
        }
      }
    }
  }

      // --- METODI GETTER ---
      public function getIdUtente() { return $this->idUtente; }
      public function getNome() { return $this->nome; }
      public function getCognome() { return $this->cognome; }
      public function getUsername() { return $this->username; }
      public function getPasswordHash() { return $this->passwordHash; }
      public function getForzaCambioPassword() { return $this->forzaCambioPassword; }
      public function getIsManagementRoot() { return $this->isManagementRoot; }
      public function getIsMultisitoRoot() { return $this->isMultisitoRoot; }
      public function getAbilitazioniSiti() { return $this->abilitazioniSiti; }

      /**
       * Factory Method: Ricostruisce l'istanza dai dati della POST a staffe
       */
      public static function istanziaDaPost($datiPost) {
          $istanza = new self();
          if (isset($datiPost['utente']['anagrafica'])) {
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
          if (isset($datiPost['utente']['abilitazioniSiti'])) {
              $istanza->abilitazioniSiti = $datiPost['utente']['abilitazioniSiti'];
          }
          return $istanza;
      }

    /**
     * METODO DUMP: Restituisce i dati strutturati ad albero in forma di array nativo
     */
    public function Dump() 
    {
      return 
      [
        'anagrafica' => 
        [
          'idUtente'            => $this->idUtente,
          'nome'                => $this->nome,
          'cognome'             => $this->cognome,
          'username'            => $this->username,
          'passwordHash'        => $this->passwordHash,
          'forzaCambioPassword' => $this->forzaCambioPassword,
          'isManagementRoot'    => $this->isManagementRoot,
          'isMultisitoRoot'     => $this->isMultisitoRoot,
          'dataRegistrazione'   => $this->dataRegistrazione
        ],
        'abilitazioniSiti' => $this->abilitazioniSiti
      ];
    }

    /**
     * METODO PRINT: Utilizza printscalar di SRVR_LOG.php per tracciare i dati utente
    */
    public function PrintUser($logObj) 
    {
      if (function_exists('printscalar')) 
      {
        printscalar($logObj, "ID Utente: " . $this->idUtente);
        printscalar($logObj, "Username: " . $this->username);
        printscalar($logObj, "Nominativo: " . $this->cognome . " " . $this->nome);
        printscalar($logObj, "Forza Password: " . ($this->forzaCambioPassword ? "SI" : "NO"));
        printscalar($logObj, "Siti Associati: " . count($this->abilitazioniSiti));
      }
    }
  }
?>