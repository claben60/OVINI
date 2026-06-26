<?php
  class clUtente 
  {
    // --- SEZIONE ANAGRAFICA (Tabella u) ---
    public $idUtente;
    public $nome;
    public $cognome;
    public $username;
    public $passwordHash;
    public $forzaCambioPassword;
    public $isManagementRoot;
    public $isMultisitoRoot;
    public $dataRegistrazione;
    
    // --- SEZIONE ABILITAZIONI E IMPIEGHI (Tabelle ui, ds, ua, dsa) ---
    // Struttura ad albero indicizzata per idSito
    public $abilitazioniSiti = []; 

    /**
     * Costruttore: Mappa le righe piatte duplicate generate dalla tua query SQL
     */
    public function __construct($righeDb = null) 
    {
      if (!empty($righeDb) && is_array($righeDb)) 
      {
        // Estraiamo l'anagrafica dalla prima riga (i dati anagrafici sono identici in tutti i record)
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

        // Cicliamo tutte le righe per estrarre i dati dei siti e i relativi ruoli
        foreach ($righeDb as $riga) 
        {
          $idSito = $riga['idSito'] ?? null;
          if ($idSito !== null) 
          {
            // Se il sito non è ancora presente nel nostro oggetto, creiamo la struttura base del sito
            if (!isset($this->abilitazioniSiti[$idSito])) 
            {
              $this->abilitazioniSiti[$idSito] = 
              [
                // Dati del Sito (ui + ds)
                'idImpiego'       => $riga['idImpiego'] ?? null,
                'impiegoInizio'   => $riga['impiegoInizio'] ?? null,
                'codiceSito'      => $riga['codiceSito'] ?? '',
                'indirizzo'       => $riga['indirizzo'] ?? '',
                'piantaAsset'     => $riga['piantaAsset'] ?? '',
                // Sotto-array per contenere i ruoli/abilitazioni associati a QUESTO specifico sito
                'ruoli'           => [] 
              ];
            }

            // Inseriamo l'abilitazione specifica (ua + dsa) dentro la lista dei ruoli di questo sito
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

    /**
     * Metodo Factory: Ricostruisce l'oggetto Utente partendo dai dati ad albero della POST a staffe
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
  }
?>