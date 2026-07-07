<?php
/**
 * SRVR_CLASS.php - Strutture Dati e Classi di Business SACS
 * Standard SACS: Architettura a Payload Diretto On-Demand legato alle macro-aree.
 * Blocco Try/Catch/Throw e singola uscita strutturata su ogni metodo.
 */

class clPost
{
    private $From;
    private $FrmName;
    private $Action;
    private $Sector;
    private $IDAction;
    private clUtente $Utente;
    private clGest $Gest;
    private clApp $App;
    private clPayload $Payload;

    public function __construct($arrInput)
    {
        $LOG_FROM = "clPost";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";

        try 
        {
            WriteLog("S", $LOG_FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clPost INIZIO", null);

            // Metadati fissi della busta
            $this->setFrmName($arrInput['FRMNAME'] ?? null);
            $this->setSector($arrInput['SECTOR'] ?? null);
            $this->setIDAction($arrInput['IDACTION'] ?? null);
            $this->setFrom($arrInput['FROM'] ?? null);
            $this->setAction($arrInput['ACTION'] ?? null);

            // Idratazione dell'oggetto Utente di Business (Recordset query a 25 campi)
            if (isset($arrInput['UTENTE']) && is_array($arrInput['UTENTE'])) 
            {
                $this->Utente = clUtente::istanziaDaPost($arrInput['UTENTE']);
            } 
            elseif (is_array($arrInput) && isset($arrInput[0]) && is_array($arrInput[0])) 
            {
                $this->Utente = new clUtente($arrInput); 
            } 
            else 
            {
                $this->Utente = new clUtente(); 
            }

            // Oggetti strutturati di supporto
            $this->Gest = clGest::istanziaDaPost($arrInput);
            $this->App = clApp::istanziaDaPost($arrInput);

            // Idratazione del Payload on-demand
            $this->Payload = clPayload::istanziaDaPost($arrInput);

            WriteLog("S", $LOG_FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clPost FINE OK", null);
        }
        catch (Exception | Error $e) 
        {
            WriteLog("E", $LOG_FROM, $THIS_FUNCTION, $THIS_FILE, "Costruttore clPost FALLITO", null);
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }
    }

    // --- GETTER & SETTER ---
    public function getFrom() { return $this->From; }
    public function setFrom($v) { $this->From = $v; }
    public function getFrmName() { return $this->FrmName; }
    public function setFrmName($v) { $this->FrmName = $v; }
    public function getAction() { return $this->Action; }
    public function setAction($v) { $this->Action = $v; }
    public function getSector() { return $this->Sector; }
    public function setSector($v) { $this->Sector = $v; }
    public function getIDAction() { return $this->IDAction; }
    public function setIDAction($v) { $this->IDAction = $v; }

    public function getUtente(): clUtente { return $this->Utente; }
    public function setUtente(clUtente $u) { $this->Utente = $u; }
    public function getGest(): clGest { return $this->Gest; }
    public function getApp(): clApp { return $this->App; }
    public function getPayload(): clPayload { return $this->Payload; }

    public function writeDatiPost(): array
    {
        $LOG_FROM = "clPost";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $arrResult = [];

        try 
        {
            $arrResult = [
                ['name' => 'FRMNAME',  'value' => $this->FrmName ?? ''],
                ['name' => 'SECTOR',   'value' => $this->Sector ?? ''],
                ['name' => 'IDACTION', 'value' => $this->IDAction ?? ''],
                ['name' => 'FROM',     'value' => $this->From ?? ''],
                ['name' => 'ACTION',   'value' => $this->Action ?? '']
            ];

            $arrResult = array_merge($arrResult, $this->Utente->writeDatiPost());
            $arrResult = array_merge($arrResult, $this->Gest->writeDatiPost());
            $arrResult = array_merge($arrResult, $this->App->writeDatiPost());
            $arrResult = array_merge($arrResult, $this->Payload->writeDatiPost());
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $arrResult;
    }

    public function toLogArray(): array
    {
        $LOG_FROM = "clPost";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $arrResult = [];

        try 
        {
            $arrResult = [
                "FRMNAME"  => $this->FrmName,
                "SECTOR"   => $this->Sector,
                "IDACTION" => $this->IDAction,
                "FROM"     => $this->From,
                "ACTION"   => $this->Action,
                "UTENTE"   => $this->Utente->toLogArray(),
                "GEST"     => $this->Gest->toLogArray(),
                "APP"      => $this->App->toLogArray(),
                "PAYLOAD"  => $this->Payload->toLogArray()
            ];
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $arrResult;
    }
}

/**
 * Gestore del Payload flessibile ordinato per macro-aree (UTENTE, GEST, APP)
 */
class clPayload
{
    private $utenteData = [];
    private $gestData = [];
    private $appData = [];

    public static function istanziaDaPost($arrInput)
    {
        $LOG_FROM = "clPayload";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $objInstance = null;

        try 
        {
            $objInstance = new self();
            if (isset($arrInput['PAYLOAD']) && is_array($arrInput['PAYLOAD'])) 
            {
                $p = $arrInput['PAYLOAD'];
                $objInstance->utenteData = $p['UTENTE'] ?? [];
                $objInstance->gestData   = $p['GEST'] ?? [];
                $objInstance->appData    = $p['APP'] ?? [];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $objInstance;
    }

    public function getUtenteParam($key, $default = null) 
    { 
        $LOG_FROM = "clPayload";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $mxResult = $default;

        try 
        {
            if (array_key_exists($key, $this->utenteData)) 
            {
                $mxResult = $this->utenteData[$key];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $mxResult;
    }

    public function getGestParam($key, $default = null)  
    { 
        $LOG_FROM = "clPayload";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $mxResult = $default;

        try 
        {
            if (array_key_exists($key, $this->gestData)) 
            {
                $mxResult = $this->gestData[$key];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $mxResult; 
    }

    public function getAppParam($key, $default = null)   
    { 
        $LOG_FROM = "clPayload";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $mxResult = $default;

        try 
        {
            if (array_key_exists($key, $this->appData)) 
            {
                $mxResult = $this->appData[$key];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $mxResult; 
    }

    public function writeDatiPost(): array
    {
        $LOG_FROM = "clPayload";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $arrResult = [];

        try 
        {
            foreach ($this->utenteData as $k => $v) {
                $arrResult[] = ['name' => "PAYLOAD[UTENTE][$k]", 'value' => $v];
            }
            foreach ($this->gestData as $k => $v) {
                $arrResult[] = ['name' => "PAYLOAD[GEST][$k]", 'value' => $v];
            }
            foreach ($this->appData as $k => $v) {
                $arrResult[] = ['name' => "PAYLOAD[APP][$k]", 'value' => $v];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $arrResult;
    }

    public function toLogArray(): array
    {
        return [
            "UTENTE" => $this->utenteData,
            "GEST"   => $this->gestData,
            "APP"    => $this->appData
        ];
    }
}

/**
 * Oggetto Anagrafico Utente - Mappa rigorosamente la query a 25 campi
 */
class clUtente
{
    private $idUtente = null;
    private $nome = null;
    private $cognome = null;
    private $codiceFiscale = null;
    private $username = null;
    private $passwordHash = null;
    private $forzaCambioPassword = false;
    private $isManagementRoot = false;
    private $isMultisitoRoot = false;
    private $dataRegistrazione = null;
    
    private $abilitazioniSiti = [];
    private $abilMultisito = [];

    public function __construct($righeDb = null)
    {
        $LOG_FROM = "clUtente";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";

        try 
        {
            if (empty($righeDb) || !is_array($righeDb) || (isset($righeDb[0]) && empty($righeDb[0]))) 
            {
                return; // Costruttore vuoto ammesso
            }

            $r0 = $righeDb[0];
            $this->idUtente = $r0['idUtente'] ?? null;
            $this->nome = $r0['nome'] ?? null;
            $this->cognome = $r0['cognome'] ?? null;
            $this->codiceFiscale = $r0['codiceFiscale'] ?? null;
            $this->username = $r0['username'] ?? null;
            $this->passwordHash = $r0['passwordHash'] ?? null;
            $this->forzaCambioPassword = isset($r0['forzaCambioPassword']) ? (bool)$r0['forzaCambioPassword'] : false;
            $this->isManagementRoot = isset($r0['isManagementRoot']) ? (bool)$r0['isManagementRoot'] : false;
            $this->isMultisitoRoot = isset($r0['isMultisitoRoot']) ? (bool)$r0['isMultisitoRoot'] : false;
            $this->dataRegistrazione = $r0['dataRegistrazione'] ?? null;

            foreach ($righeDb as $riga) 
            {
                $idSito = $riga['idSito'] ?? null;
                $fkImpAbil = $riga['fkImpiego'] ?? null;

                if (is_null($fkImpAbil) && !is_null($riga['fkAbilitazione'])) 
                {
                    $idx = 'ABILITAZIONE_' . count($this->abilMultisito);
                    $this->abilMultisito[$idx] = [
                        'idAbilitazione'   => $riga['fkAbilitazione'] ?? '',
                        'descrAbil'        => $riga['ruoloAziendale'] ?? '',
                        'flagManagement'   => $riga['flagManagement'] ?? '0',
                        'flagMultisito'    => $riga['flagMultisito'] ?? '0',
                        'iconaInterfaccia' => $riga['iconaInterfaccia'] ?? ''
                    ];
                    continue;
                }

                if ($idSito !== null) 
                {
                    if (!isset($this->abilitazioniSiti[$idSito])) 
                    {
                        $this->abilitazioniSiti[$idSito] = [
                            'idImpiego'     => $riga['idImpiego'] ?? null,
                            'impiegoInizio' => $riga['impiegoInizio'] ?? null,
                            'codiceSito'    => $riga['codiceSito'] ?? '',
                            'indirizzo'     => $riga['indirizzo'] ?? '',
                            'piantaAsset'    => $riga['piantaAsset'] ?? '',
                            'ruoli'         => []
                        ];
                    }
                    if (!is_null($riga['idUtenteAbil'])) 
                    {
                        $this->abilitazioniSiti[$idSito]['ruoli'][$riga['idUtenteAbil']] = [
                            'idAbilitazione'   => $riga['fkAbilitazione'] ?? '',
                            'descrAbil'        => $riga['ruoloAziendale'] ?? '',
                            'flagManagement'   => $riga['flagManagement'] ?? '0',
                            'flagMultisito'    => $riga['flagMultisito'] ?? '0',
                            'iconaInterfaccia' => $riga['iconaInterfaccia'] ?? ''
                        ];
                    }
                }
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }
    }

    public static function istanziaDaPost($arrInput)
    {
        $LOG_FROM = "clUtente";
        $THIS_FILE = basename(__FILE__, ".php");
        $THIS_FUNCTION = $THIS_FILE . "(" . __FUNCTION__ . ")";
        $objInstance = null;

        try 
        {
            $objInstance = new self();
            
            if (is_array($arrInput)) 
            {
                $objInstance->idUtente = $arrInput['idUtente'] ?? null;
                $objInstance->username = $arrInput['username'] ?? $arrInput['UID'] ?? null;
                $objInstance->nome = $arrInput['nome'] ?? null;
                $objInstance->cognome = $arrInput['cognome'] ?? null;
                $objInstance->forzaCambioPassword = isset($arrInput['forzaCambioPassword']) ? (bool)$arrInput['forzaCambioPassword'] : false;
                $objInstance->abilitazioniSiti = $arrInput['abilitazioniSiti'] ?? [];
                $objInstance->abilMultisito = $arrInput['abilMultisito'] ?? [];
            }
        }
        catch (Exception | Error $e) 
        {
            WriteErr($LOG_FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
            throw $e;
        }

        return $objInstance;
    }
    
    public function writeDatiPost(): array 
    { 
        return [
            ['name' => 'UTENTE[idUtente]', 'value' => $this->idUtente ?? ''],
            ['name' => 'UTENTE[username]', 'value' => $this->username ?? ''],
            ['name' => 'UTENTE[forzaCambioPassword]', 'value' => $this->forzaCambioPassword ? '1' : '0']
        ]; 
    }

    public function toLogArray(): array
    {
        return [
            "idUtente" => $this->idUtente, 
            "username" => $this->username, 
            "forzaCambioPassword" => $this->forzaCambioPassword,
            "abilitazioniSiti" => $this->abilitazioniSiti, 
            "abilMultisito" => $this->abilMultisito
        ];
    }
    
    // --- METODI ACCESSO PUBBLICI (Risolvono l'errore undefined method) ---
    public function getUsername() { return $this->username; }
    public function getPasswordHash() { return $this->passwordHash; }
    public function getForzaCambioPassword(): bool { return (bool)$this->forzaCambioPassword; }
}

class clGest { static function istanziaDaPost($d) { return new self(); } function writeDatiPost() { return []; } function toLogArray() { return []; } }
class clApp  { static function istanziaDaPost($d) { return new self(); } function writeDatiPost() { return []; } function toLogArray() { return []; } }
?>