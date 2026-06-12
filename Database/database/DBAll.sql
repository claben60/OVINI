/*
 * DATABASE
 */
BEGIN;
 
DROP DATABASE IF EXISTS SACS;

CREATE DATABASE IF NOT EXISTS SACS 
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

COMMIT;

/*
 * diz_Utenti_CausaFine
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`diz_utenti_causafine`;

CREATE TABLE IF NOT EXISTS `sacs`.`diz_utenti_causafine` 
( 
   `ID_CausaFine`    SERIAL                  NOT NULL     PRIMARY KEY          COMMENT 'Calcolato: progr, Inizia da 1', 
   `Descrizione`     VARCHAR(40)             NOT NULL 
) ENGINE = InnoDB;

INSERT INTO `sacs`.`diz_utenti_causafine` (`Descrizione`) VALUES 
('Eta\'/Pensione'),
('Dimissioni volontarie'),
('Premorienza/Malattia'),
('Allontanamento');

COMMIT;

/*
 * diz_siti`
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`diz_siti`;

CREATE TABLE IF NOT EXISTS `sacs`.`diz_siti` 
( 
   `ID_Sito`       SERIAL              NOT NULL     PRIMARY KEY              COMMENT 'Calcolato: progr, Inizia da 1', 
   `Sito`          VARCHAR(7)          NOT NULL                              COMMENT 'Calcolato: SACS_+progr', 
   `Indirizzo`     VARCHAR(100)        NOT NULL,
   `Pianta`        VARCHAR(255)        NULL
) ENGINE = InnoDB;

INSERT INTO `sacs`.`diz_siti`
(
   `ID_sito`, 
   `Sito`,
   `Indirizzo`,
   `Pianta`
) 
VALUES 
(
   0,
   'SACS_00',
   'SOCIETA SACS - Città di Castello - Fraz. Coldipozzo - Voc. Palazzina sn',   
   ''
);

UPDATE `sacs`.`diz_siti` SET `ID_sito` = '0' WHERE `diz_siti`.`ID_sito` = 1;

INSERT INTO `sacs`.`diz_siti`
(
   `ID_sito`, 
   `Sito`,
   `Indirizzo`,
   `Pianta`
) 
VALUES 
(
   1,
   'SACS_01',
   'SACS_01 - Città di Castello - Fraz. Coldipozzo - Voc. Palazzina sn',   
   ''
);

INSERT INTO `sacs`.`diz_siti`
(
   `ID_sito`, 
   `Sito`,
   `Indirizzo`,
   `Pianta`
) 
VALUES 
(
   2,
   'SACS_02',
   'SACS_02 - Indirizzo prossimo podere',   
   ''
);

COMMIT;

/*
 * diz_soc_abilitazioni
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`diz_soc_abilitazioni`; 

CREATE TABLE IF NOT EXISTS `sacs`.`diz_soc_abilitazioni` 
( 
   `ID_Abil`           VARCHAR(3)          NOT NULL     PRIMARY KEY          COMMENT 'Z01=pecore, Z02=capre...', 
   `Descrizione`       VARCHAR(40)         NOT NULL,
   `Management`        BOOLEAN             NOT NULL DEFAULT false, 
   `Multisito`         BOOLEAN             NOT NULL DEFAULT false, 
   `Icona`             VARCHAR(255)        NULL 
) ENGINE = InnoDB;

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management` , 
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'G00',
   'SACS Gestione  di tutti i siti',
   true,
   true,   
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'M00',
   'SACS_00 Management',
   true,
   false,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'Z00',
   'Pecore',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'Z01',
   'Capre',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'A00',
   'Seminativi',
   false,
   false,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'V00',
   'Procacciatore',
   false,
   true,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
) 
VALUES 
(
   'S00',
   'Sicurezza',
   false,
   true,
   ''
);

INSERT INTO `sacs`.`diz_soc_abilitazioni`
(
   `ID_Abil` ,
   `Descrizione` ,
   `Management`,
   `Multisito`, 
   `Icona` 
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
 * Utenti
 */
BEGIN;

DROP TABLE IF EXISTS `sacs`.`utenti`;

CREATE TABLE IF NOT EXISTS `sacs`.`utenti` 
(
   `ID_Utente`     VARCHAR(31)         NOT NULL                PRIMARY KEY, 
   `Nome`          VARCHAR(32)         NOT NULL, 
   `Cognome`       VARCHAR(32)         NOT NULL, 
   `CF`            VARCHAR(16)         NOT NULL,
   `UID`           VARCHAR(32)         NULL, 
   `PWD`           VARCHAR(255)        NULL, 
   `PWD2CHANGE`    BOOLEAN             NOT NULL DEFAULT true, 
   `IsManagement`  BOOLEAN             NOT NULL DEFAULT false  COMMENT 'Se true, può gestire o assegnare altri utenti',
   `IsMultisito`   BOOLEAN             NOT NULL DEFAULT false  COMMENT 'Se true, opera o gestisce su scala globale (tutti i siti)',
   `DataReg`       DATE                NOT NULL,
   `DataFine`      DATE                NULL,
   `ID_CausaFine`  BIGINT UNSIGNED     NULL,
   CONSTRAINT `UQ_utenti_CF` UNIQUE (`CF`),
   CONSTRAINT `UQ_utenti_UID` UNIQUE (`UID`)
) ENGINE = InnoDB;

ALTER TABLE `sacs`.`utenti`   ADD CONSTRAINT 
  FOREIGN KEY (`ID_CausaFine`) 
  REFERENCES `sacs`.`diz_utenti_causafine` (`ID_CausaFine`); 
  
/*
 * Utente root 
 * Operativo: Crea nuovi siti
 *            Crea nuovi mamager G00
 *            Crea i manager per ogni sito (eventualmente se stsso)
 *            Gestisce i dizionari
 */
insert into`sacs`.`utenti` 
(
   `ID_Utente`,
   `Nome`,
   `Cognome`,
   `CF`,
   `UID`,
   `PWD`,
   `PWD2CHANGE`,
   `IsManagement`,
   `IsMultisito`,
   `DataReg`,
   `DataFine`,
   `ID_CausaFine`
)
values 
(
   'SACS_00_G00_FRMNML00A01C745Y_00',
   'animal',
   'farm',
   'FRMNML00A01C745Y',
   'root',
   'admin',
   true,
   true,
   true,
   '00010101',
   NULL,
   NULL
);

COMMIT;

/*
 * utenti_Impiego
 * LIVELLO 1: Chi lavora dove, e quando.
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`utenti_impiego`;
 
CREATE TABLE IF NOT EXISTS `sacs`.`utenti_impiego` 
(
   `ID_Impiego`    SERIAL              NOT NULL     PRIMARY KEY,
   `ID_Utente`     VARCHAR(31)         NOT NULL, 
   `ID_Sito`       BIGINT UNSIGNED     NOT NULL,
   `DataInizio`    DATE                NOT NULL,
   `DataFine`      DATE                NULL,
   
   -- Vincoli per evitare che lo stesso utente sia inserito due volte nello stesso sito nello stesso momento
   CONSTRAINT `FK_impiego_utente` FOREIGN KEY (`ID_Utente`) REFERENCES `sacs`.`utenti` (`ID_Utente`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `FK_impiego_sito` FOREIGN KEY (`ID_Sito`) REFERENCES `sacs`.`diz_siti` (`ID_Sito`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB;  

insert into `sacs`.`utenti_impiego` 
(
   `ID_Impiego`,
   `ID_Utente`,
   `ID_Sito`,
   `DataInizio`,
   `DataFine`
)
values 
(
   0,
   'SACS_00_G00_FRMNML00A01C745Y_00',
   0,
   '00010101',
   NULL
);

UPDATE `sacs`.`utenti_impiego` SET `ID_Impiego` = '0' WHERE `ID_Impiego` = 1;

COMMIT;

/*
 * utenti_abilitazioni
 * LIVELLO 2: Quali abilitazioni ha l'utente e in quale contesto
 */

BEGIN;

DROP TABLE IF EXISTS `sacs`.`utenti_abilitazioni`;
 
CREATE TABLE IF NOT EXISTS `sacs`.`utenti_abilitazioni` 
(
   `ID_UtenteAbil` SERIAL              NOT NULL     PRIMARY KEY,
   `ID_Utente`     VARCHAR(31)         NOT NULL,
   `ID_Abil`       VARCHAR(3)          NOT NULL,
   `ID_Impiego`    BIGINT UNSIGNED     NULL             COMMENT 'Valorizzato SOLO se l abilitazione è legata a uno specifico sito. Se NULL, l abilitazione è Multisito',
   `AbilInizio`    DATE                NOT NULL,
   `AbilFine`      DATE                NULL,
   
   CONSTRAINT `FK_abil_utente` FOREIGN KEY (`ID_Utente`) REFERENCES `sacs`.`utenti` (`ID_Utente`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `FK_abil_codice` FOREIGN KEY (`ID_Abil`) REFERENCES `sacs`.`diz_soc_abilitazioni` (`ID_Abil`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `FK_abil_impiego` FOREIGN KEY (`ID_Impiego`) REFERENCES `sacs`.`utenti_impiego` (`ID_Impiego`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB;

insert into `sacs`.`utenti_abilitazioni` 
(
   `ID_UtenteAbil`,
   `ID_Utente`,
   `ID_Abil`,
   `ID_Impiego`,
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

UPDATE `sacs`.`utenti_abilitazioni` SET `ID_UtenteAbil` = '0' WHERE `ID_UtenteAbil` = 1;

COMMIT;
