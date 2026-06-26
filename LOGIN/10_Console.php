<?php
  // LOGIN_Console.php
    $FROM = "LOGIN_CONSOLE";
    $ACT = "TRANSIT";

    $THIS_FILE = basename(__FILE__, ".php");
    $THIS_FUNCTION = $THIS_FILE . "(main)";

/*
 * Controllo di integrità del payload proveniente dal database
 */
if (!isset($datiGrezziDatabase) || !is_array($datiGrezziDatabase) || count($datiGrezziDatabase) === 0) {
    // Log dell'errore di sistema
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "ERRORE CRITICO: Payload grezzo database mancante o corrotto.", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Integrita payload fallita nella console virtuale.");
    
    // Mostra la pagina di errore di sistema irreversibile
    require "../MSG/SYS_ERR.html";
    die();
}

try {
    // Scrittura log di successo per tracciabilità di transito
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Integrita payload verificata. Record trovati: " . count($datiGrezziDatabase), NULL);

    $primo = $datiGrezziDatabase[0];

    // 1. Isolamento dell'anagrafica fissa
    $anagrafica = [
        'idUtente'            => $primo['idUtente'],
        'nome'                => $primo['nome'],
        'cognome'             => $primo['cognome'],
        'username'            => $primo['username'],
        'forzaCambioPassword' => $primo['forzaCambioPassword'] ? 1 : 0,
        'isManagementRoot'    => $primo['isManagementRoot'] ? 1 : 0,
        'isMultisitoRoot'     => $primo['isMultisitoRoot'] ? 1 : 0
    ];

    // 2. Isolamento degli array paralleli per le staffe []
    $arraySiti = [
        'idImpiego'        => [],
        'idSito'           => [],
        'codiceSito'       => [],
        'indirizzo'        => [],
        'piantaAsset'      => [],
        'idUtenteAbil'     => [],
        'fkAbilitazione'   => [],
        'ruoloAziendale'   => [],
        'isManagerQui'     => [],
        'isMultisitoQui'   => [],
        'iconaInterfaccia' => []
    ];

    foreach ($datiGrezziDatabase as $row) {
        $arraySiti['idImpiego'][]        = $row['idImpiego'];
        $arraySiti['idSito'][]           = $row['idSito'];
        $arraySiti['codiceSito'][]       = $row['codiceSito'];
        $arraySiti['indirizzo'][]        = $row['indirizzo'];
        $arraySiti['piantaAsset'][]      = $row['piantaAsset'];
        $arraySiti['idUtenteAbil'][]     = $row['idUtenteAbil'];
        $arraySiti['fkAbilitazione'][]   = $row['fkAbilitazione'];
        $arraySiti['ruoloAziendale'][]   = $row['ruoloAziendale'];
        $arraySiti['iconaInterfaccia'][] = $row['iconaInterfaccia'];
        $arraySiti['isManagerQui'][]     = ($row['isManagementRoot'] && $row['flagManagement']) ? 1 : 0;
        $arraySiti['isMultisitoQui'][]    = ($row['isMultisitoRoot'] && $row['flagMultisito']) ? 1 : 0;
    }

} catch (Exception | Error $e) {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Eccezione durante l'organizzazione dei vettori dati", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    die();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>SACS - Transito Consolle</title>
    <!-- Inclusione della libreria globale client contenente MakeForm -->
    <script src="../cmn/cmn_js.js"></script>
</head>
<body>
    <script>
    (function() {
        // Conforme allo standard: tracciamento errore della funzione chiamata
        const _FF_ = 'LOGIN_Console(InviaAChoose)';
        try {
            const actionParams = {
                FROM: 'LOGIN_CONSOLE',
                SECTOR: 'CHOOSE',
                ACTION: 'RENDER_INTERFACE'
            };

            // Unione nativa dei vettori anagrafica e sotto-array a staffe
            const payloadData = Object.assign({}, 
                <?php echo json_encode($anagrafica); ?>, 
                {
                    "idImpiego[]":        <?php echo json_encode($arraySiti['idImpiego']); ?>,
                    "idSito[]":           <?php echo json_encode($arraySiti['idSito']); ?>,
                    "codiceSito[]":       <?php echo json_encode($arraySiti['codiceSito']); ?>,
                    "indirizzo[]":        <?php echo json_encode($arraySiti['indirizzo']); ?>,
                    "piantaAsset[]":      <?php echo json_encode($arraySiti['piantaAsset']); ?>,
                    "idUtenteAbil[]":     <?php echo json_encode($arraySiti['idUtenteAbil']); ?>,
                    "fkAbilitazione[]":   <?php echo json_encode($arraySiti['fkAbilitazione']); ?>,
                    "ruoloAziendale[]":   <?php echo json_encode($arraySiti['ruoloAziendale']); ?>,
                    "isManagerQui[]":     <?php echo json_encode($arraySiti['isManagerQui']); ?>,
                    "isMultisitoQui[]":    <?php echo json_encode($arraySiti['isMultisitoQui']); ?>,
                    "iconaInterfaccia[]": <?php echo json_encode($arraySiti['iconaInterfaccia']); ?>
                }
            );

            // Esegue DIRETTAMENTE ed ESCLUSIVAMENTE la sottomissione POST verso 20_Choose.php
            MakeForm('../20_Choose/20_Choose.php', actionParams, payloadData);

        } catch(err) {
            // Log su console client dell'errore propagato da MakeForm
            console.error('ERRORE: FILE:' + _FF_ + ' ' + err.message);
        }
    })();
    </script>
</body>
</html>
