/*
 * DATABASE
 */
BEGIN;
 
DROP DATABASE IF EXISTS SACS;

CREATE DATABASE IF NOT EXISTS SACS 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

COMMIT;

/*
 * DizUtentiCausaFine
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`DizUtentiCausaFine`;

CREATE TABLE IF NOT EXISTS `sacs`.`DizUtentiCausaFine`
( 
   `idCausaFine`     SERIAL                  NOT NULL     PRIMARY KEY          COMMENT 'Calcolato: progr, Inizia da 1', 
   `descrizioneFine`     VARCHAR(40)             NOT NULL 
) ENGINE = InnoDB;

INSERT INTO `sacs`.`DizUtentiCausaFine` (`descrizioneFine`) VALUES 
('Eta\'/Pensione'),
('Dimissioni volontarie'),
('Premorienza/Malattia'),
('Allontanamento');

COMMIT;

/*
 * DizSiti - Anagrafica dei Luoghi di Lavoro
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`DizSiti`;

CREATE TABLE IF NOT EXISTS `sacs`.`DizSiti` 
( 
   `idSito`        SERIAL              NOT NULL     PRIMARY KEY              COMMENT 'Calcolato: progr, Inizia da 1. Chiave numerica per le JOIN ad alta velocità ', 
   `codiceSito`    VARCHAR(7)          NOT NULL                              COMMENT 'Calcolato: SACS_+progr, usato per ricerche e interfaccia.', 
   `indirizzo`     VARCHAR(100)        NOT NULL,
   `piantaAsset`   VARCHAR(255)        NULL                                  COMMENT 'Percorso o identificativo della planimetria.'
) ENGINE = InnoDB;

INSERT INTO `sacs`.`DizSiti`
(
   `idSito`, 
   `codiceSito`,
   `indirizzo`,
   `piantaAsset`
) 
VALUES 
(
   0,
   'SACS_00',
   'SOCIETA SACS - Città di Castello - Fraz. Coldipozzo - Voc. Palazzina sn',   
   ''
);

UPDATE `sacs`.`DizSiti` SET `idSito` = '0' WHERE `DizSiti`.`idSito` = 1;

INSERT INTO `sacs`.`DizSiti`
(
   `idSito`, 
   `codiceSito`,
   `indirizzo`,
   `piantaAsset`
) 
VALUES 
(
   1,
   'SACS_01',
   'SACS_01 - Città di Castello - Fraz. Coldipozzo - Voc. Palazzina sn',   
   ''
);

INSERT INTO `sacs`.`DizSiti`
(
   `idSito`, 
   `codiceSito`,
   `indirizzo`,
   `piantaAsset`
) 
VALUES 
(
   2,
   'SACS_02',
   'SACS_02 - indirizzo prossimo podere',   
   ''
);

COMMIT;

/*
 * DizSocAbilitazioni - Abilitazioni nelle societa' e multisocietarie
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`DizSocAbilitazioni`; 

CREATE TABLE IF NOT EXISTS `sacs`.`DizSocAbilitazioni` 
( 
   `idAbilitazione`    VARCHAR(3)          NOT NULL     PRIMARY KEY          COMMENT 'Z01=pecore, Z02=capre...', 
   `descrAbil`         VARCHAR(40)         NOT NULL,
   `flagManagement`    BOOLEAN             NOT NULL DEFAULT false, 
   `flagMultisito`     BOOLEAN             NOT NULL DEFAULT false, 
   `iconaInterfaccia`  VARCHAR(255)        NULL 
) ENGINE = InnoDB;

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement` , 
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'G00',
   'SACS Gestione  di tutti i siti',
   true,
   true,   
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'M00',
   'SACS_00 flagManagement',
   true,
   false,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'Z00',
   'Pecore',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'Z01',
   'Capre',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'A00',
   'Seminativi',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'V00',
   'Procacciatore',
   false,
   true,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'S00',
   'Sicurezza',
   false,
   true,
   ''
);

INSERT INTO `sacs`.`DizSocAbilitazioni`
(
   `idAbilitazione` ,
   `descrAbil` ,
   `flagManagement`,
   `flagMultisito`, 
   `iconaInterfaccia` 
) 
VALUES 
(
   'R00',
   'Ricerca',
   false,
   true,
   ''
);

COMMIT;

/*
 * Utenti - Anagrafica Personale e Profilo Contrattuale
 */
BEGIN;

DROP TABLE IF EXISTS `sacs`.`Utenti`;

CREATE TABLE IF NOT EXISTS `sacs`.`Utenti` 
(
   `idUtente`            VARCHAR(31)         NOT NULL                PRIMARY KEY, 
   `nome`                VARCHAR(32)         NOT NULL, 
   `cognome`             VARCHAR(32)         NOT NULL, 
   `codiceFiscale`       VARCHAR(16)         NOT NULL,
   `username`            VARCHAR(32)         NULL, 
   `passwordHash`        VARCHAR(255)        NULL, 
   `forzaCambioPassword` BOOLEAN             NOT NULL DEFAULT true, 
   `isManagementRoot`    BOOLEAN             NOT NULL DEFAULT false  COMMENT 'Se true, può gestire o assegnare altri Utenti. Profilo contrattuale/potenziale di gestione dell\'utente.',
   `isMultisitoRoot`     BOOLEAN             NOT NULL DEFAULT false  COMMENT 'Se true, opera o gestisce su scala globale (tutti i siti). Profilo contrattuale/potenziale multisito dell\'utente',
   `dataRegistrazione`   DATE                NOT NULL,
   `dataCessazione`      DATE                NULL,
   `fkCausaFine`         BIGINT UNSIGNED     NULL                    COMMENT 'Il prefisso fk esplicita il vincolo con la tabella delle cause.',
   CONSTRAINT `UQ_utenti_CF` UNIQUE (`codiceFiscale`),
   CONSTRAINT `UQ_utenti_UID` UNIQUE (`username`)
) ENGINE = InnoDB;

  ALTER TABLE `sacs`.`Utenti`   ADD CONSTRAINT 
  FOREIGN KEY (`fkCausaFine`) 
  REFERENCES `sacs`.`DizUtentiCausaFine`(`idCausaFine`); 
  
/*
 * Utente root 
 * Operativo: Crea nuovi siti
 *            Crea nuovi mamager G00
 *            Crea i manager per ogni sito (eventualmente se stsso)
 *            Gestisce i dizionari
 */
insert into`sacs`.`Utenti` 
(
   `idUtente`,
   `nome`,
   `cognome`,
   `codiceFiscale`,
   `username`,
   `passwordHash`,
   `forzaCambioPassword`,
   `isManagementRoot`,
   `isMultisitoRoot`,
   `dataRegistrazione`,
   `dataCessazione`,
   `fkCausaFine`
)
values 
(
   'SACS_00_G00_FRMNML00A01C745Y_00',
   'animal',
   'farm',
   'FRMNML00A01C745Y',
   'root',
   '$2y$10$ahpjK0n2g/qQnToJTZFb6uLj7rn817SM966T0DHPvNxyT/5zJocYO',
   true,
   true,
   true,
   '00010101',
   NULL,
   NULL
);

COMMIT;

/*
 * UtentiImpiego - Assegnazione Operativa nei Siti
 * LIVELLO 1: Chi lavora dove, e quando.
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`UtentiImpiego`;
 
CREATE TABLE IF NOT EXISTS `sacs`.`UtentiImpiego` 
(
   `idImpiego`       SERIAL              NOT NULL     PRIMARY KEY,
   `fkUtente`        VARCHAR(31)         NOT NULL, 
   `fkSito`          BIGINT UNSIGNED     NOT NULL,
   `dataInizio`      DATE                NOT NULL,
   `dataCessazione`  DATE                NULL,
   
   -- Vincoli per evitare che lo stesso utente sia inserito due volte nello stesso sito nello stesso momento
   CONSTRAINT `FK_impiego_utente` FOREIGN KEY (`fkUtente`) REFERENCES `sacs`.`Utenti` (`idUtente`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `FK_impiego_sito` FOREIGN KEY (`fkSito`) REFERENCES `sacs`.`DizSiti` (`idSito`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;  

insert into `sacs`.`UtentiImpiego` 
(
   `idImpiego`,
   `fkUtente`,
   `fkSito`,
   `dataInizio`,
   `dataCessazione`
)
values 
(
   0,
   'SACS_00_G00_FRMNML00A01C745Y_00',
   0,
   '00010101',
   NULL
);

UPDATE `sacs`.`UtentiImpiego` SET `idImpiego` = '0' WHERE `idImpiego` = 1;

COMMIT;

/*
 * UtentiAbilitazioni. Assegnazione dei Profili nelle societa
 * LIVELLO 2: Quali abilitazioni ha l'utente e in quale contesto
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`UtentiAbilitazioni`;
 
CREATE TABLE IF NOT EXISTS `sacs`.`UtentiAbilitazioni` 
(
   `idUtenteAbil`   SERIAL              NOT NULL     PRIMARY KEY,
   `fkUtente`       VARCHAR(31)         NOT NULL         COMMENT 'Collegato a Utenti.idUtente.',
   `fkAbilitazione` VARCHAR(3)          NOT NULL         COMMENT 'Collegato a DizSocAbilitazioni.idAbilitazione.',
   `fkImpiego`      BIGINT UNSIGNED     NULL             COMMENT 'Collegato a UtentiImpiego.idImpiego. Valorizzato SOLO se l abilitazione è legata a uno specifico sito. Se NULL, l abilitazione è Multisito',
   `AbilInizio`     DATE                NOT NULL,
   `AbilFine`       DATE                NULL,
   
   CONSTRAINT `FK_abil_utente` FOREIGN KEY (`fkUtente`) REFERENCES `sacs`.`Utenti` (`idUtente`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `FK_abil_codice` FOREIGN KEY (`fkAbilitazione`) REFERENCES `sacs`.`DizSocAbilitazioni` (`idAbilitazione`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `FK_abil_impiego` FOREIGN KEY (`fkImpiego`) REFERENCES `sacs`.`UtentiImpiego` (`idImpiego`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB;

insert into `sacs`.`UtentiAbilitazioni` 
(
   `idUtenteAbil`,
   `fkUtente`,
   `fkAbilitazione`,
   `fkImpiego`,
   `AbilInizio`,
   `AbilFine`
)
values 
(
   0,
   'SACS_00_G00_FRMNML00A01C745Y_00',
   'G00',
   NULL,
   '00010101',
   NULL
);

UPDATE `sacs`.`UtentiAbilitazioni` SET `idUtenteAbil` = '0' WHERE `idUtenteAbil` = 1;

COMMIT;

