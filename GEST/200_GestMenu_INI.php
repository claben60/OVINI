<?php

  /*
   *     gestione dinamica del menu 
   */   
  

  $MnuItemSOC=
  [
    "ACT" => "SOC",
    "NOME" => "GESTIONE SOCETA&#39",
    "REFRESH" => "false",
    "LIV" => 0,
    "QUERY_RIC_ACT" => NULL,
    "QUERY_ADD_ACT" => NULL,
    "QUERY_UPT_ACT" => NULL,
    "QUERY_DEL_ACT" => NULL,    
  ];
  
  $MnuItemSITI=
  [
    "ACT" => "SITI",
    "NOME" => "Siti",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_SITI",
    "QUERY_ADD_ACT" => "ADD_SOC_SITI",
    "QUERY_UPT_ACT" => "UPD_SITI",
    "QUERY_DEL_ACT" => "DEL_SITI",    
  ];
    
  $MnuItemUTENTI=
  [
    "ACT" => "USR",
    "NOME" => "Utenti",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_USR",
    "QUERY_ADD_ACT" => "ADD_SOC_USR",
    "QUERY_UPT_ACT" => "UPD_USR",
    "QUERY_DEL_ACT" => "DEL_USR",    
  ];    

  $MnuItemDIZ_SOC=
  [
    "ACT" => "DIZ_SOC",
    "NOME" => "DIZIONARI( SOC. )",
    "REFRESH" => "false",
    "LIV" => 1,
    "QUERY_RIC_ACT" => NULL,
    "QUERY_ADD_ACT" => NULL,
    "QUERY_UPT_ACT" => NULL,
    "QUERY_DEL_ACT" => NULL,    
  ];

  $MnuItemDIZ_ABILITAZIONI=
  [
    "ACT" => "DIZ_ABIL",
    "NOME" => "Abilitazioni( SOC. )",
    "REFRESH" => "true",
    "LIV" => 2,
    "QUERY_RIC_ACT" => "SEL_DIZ_SOC_ABIL",
    "QUERY_ADD_ACT" => "ADD_DIZ_ABIL",
    "QUERY_UPT_ACT" => "UPD_DIZ_ABIL",
    "QUERY_DEL_ACT" => "DEL_DIZ_ABIL",    
  ];    

  $MnuItemGEST=
  [
    "ACT" => "GEST",
    "NOME" => "GESTIONALE",
    "REFRESH" => "false",
    "LIV" => 0,
    "QUERY_RIC_ACT" => NULL,
    "QUERY_ADD_ACT" => NULL,
    "QUERY_UPT_ACT" => NULL,
    "QUERY_DEL_ACT" => NULL,    
  ];

  $MnuItemGEST_ACQUISTI=
  [
    "ACT" => "GEST_ACQ",
    "NOME" => "Acquisti",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_ACQ",
    "QUERY_ADD_ACT" => "ADD_GEST_ACQ",
    "QUERY_UPT_ACT" => "UPD_GEST_ACQ",
    "QUERY_DEL_ACT" => "DEL_GEST_ACQ",    
  ];       

  $MnuItemGEST_VENDITE=
  [
    "ACT" => "GEST_VEN",
    "NOME" => "Vendite",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_VEN",
    "QUERY_ADD_ACT" => "ADD_GEST_VEN",
    "QUERY_UPT_ACT" => "UPD_GEST_VEN",
    "QUERY_DEL_ACT" => "DEL_GEST_VEN",    
  ];       
    
  $MnuItemGEST_PRINOT=
  [
    "ACT" => "GEST_PRINOT",
    "NOME" => "Prima nota",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_PRINOT",
    "QUERY_ADD_ACT" => "ADD_GEST_PRINOT",
    "QUERY_UPT_ACT" => "UPD_GEST_PRINOT",
    "QUERY_DEL_ACT" => "DEL_GEST_PRINOT",    
  ];           
    
  $MnuItemGEST_STAT=
  [
    "ACT" => "GEST_STAT",
    "NOME" => "Statistiche",
    "REFRESH" => "true",
    "LIV" => 1,
    "QUERY_RIC_ACT" => "SEL_GEST_STAT",
    "QUERY_ADD_ACT" => "ADD_GEST_STAT",
    "QUERY_UPT_ACT" => "UPD_GEST_STAT",
    "QUERY_DEL_ACT" => "DEL_GEST_STAT",    
  ];               
    
  $MnuItemGEST_DIZ_APP=
  [
    "ACT" => "GEST_DIZ_APP",
    "NOME" => "DIZIONARI( APP. )",
    "REFRESH" => "false",
    "LIV" => 1,
  ];

  $MnuItemDIZ_FORNITORI=
  [
    "ACT" => "GEST_DIZ_FORN",
    "NOME" => "Fornitori",
    "REFRESH" => "true",
    "LIV" => 2,
    "QUERY_RIC_ACT" => "SEL_GEST_DIZ_FORN",
    "QUERY_ADD_ACT" => "ADD_GEST_DIZ_FORN",
    "QUERY_UPT_ACT" => "UPD_GEST_DIZ_FORN",
    "QUERY_DEL_ACT" => "DEL_GEST_DIZ_FORN",    
  ];    

  $MnuItemDIZ_CLIENTI=
  [
    "ACT" => "GEST_DIZ_CLI",
    "NOME" => "Clienti",
    "REFRESH" => "true",
    "LIV" => 2,
    "QUERY_RIC_ACT" => "SEL_GEST_DIZ_CLI",
    "QUERY_ADD_ACT" => "ADD_GEST_DIZ_CLI",
    "QUERY_UPT_ACT" => "UPD_GEST_DIZ_CLI",
    "QUERY_DEL_ACT" => "DEL_GEST_DIZ_CLI",    
  ];    
    
  $MnuItemPROMEM=
  [
    "ACT" => "PROMEM",
    "NOME" => "PRO MEMORIA",
    "REFRESH" => "true",
    "LIV" => 0,
    "QUERY_RIC_ACT" => "SEL_PROMEM",
    "QUERY_ADD_ACT" => "ADD_PROMEM",
    "QUERY_UPT_ACT" => "UPD_PROMEM",
    "QUERY_DEL_ACT" => "DEL_PROMEM",    
  ];    

  $MnuItemGEST_FILE=
  [
    "ACT" => "GEST_FILE",
    "NOME" => "GESTIONE FILE",
    "REFRESH" => "true",
    "LIV" => 0,
    "QUERY_RIC_ACT" => "SEL_FILE",
    "QUERY_ADD_ACT" => "ADD_FILE",
    "QUERY_UPT_ACT" => "UPD_FILE",
    "QUERY_DEL_ACT" => "DEL_FILE",    
  ];     
    
  $MnuItemGEST_SALVATAGGI=
  [
    "ACT" => "GEST_SALVA",
    "NOME" => "SALVATAGGI",
    "REFRESH" => "true",
    "LIV" => 0,
    "QUERY_RIC_ACT" => "SEL_SALV",
    "QUERY_ADD_ACT" => "ADD_SALV",
    "QUERY_UPT_ACT" => "UPD_SALV",
    "QUERY_DEL_ACT" => "DEL_SALV",    
  ];         
    
  $MnuItemCONTACT=
  [
    "ACT" => "CNCT",
    "NOME" => "Contatti",
    "REFRESH" => "true",
    "LIV" => 0,
    "QUERY_RIC_ACT" => NULL,
    "QUERY_ADD_ACT" => NULL,
    "QUERY_UPT_ACT" => NULL,
    "QUERY_DEL_ACT" => NULL,    
  ];         

  $MnuItemABOUT=
  [
    "ACT" => "ABOUT",
    "NOME" => "About",
    "REFRESH" => "true",
    "LIV" => 0,
    "QUERY_RIC_ACT" => NULL,
    "QUERY_ADD_ACT" => NULL,
    "QUERY_UPT_ACT" => NULL,
    "QUERY_DEL_ACT" => NULL,    
  ];  
    
  $MnuItemsArr=  
  [
    "SOC" => $MnuItemSOC,
    "SITI" => $MnuItemSITI,
    "UTENTI" => $MnuItemUTENTI,
    "DIZ_SOC" => $MnuItemDIZ_SOC,
    "DIZ_ABILITAZIONI" => $MnuItemDIZ_ABILITAZIONI,
    "GEST" => $MnuItemGEST,
    "GEST_ACQUISTI" => $MnuItemGEST_ACQUISTI,
    "GEST_VENDITE" => $MnuItemGEST_VENDITE,
    "GEST_PRINOT" => $MnuItemGEST_PRINOT,
    "GEST_STAT" => $MnuItemGEST_STAT,
    "GEST_DIZ_APP" => $MnuItemGEST_DIZ_APP,
    "DIZ_FORNITORI" => $MnuItemDIZ_FORNITORI,
    "DIZ_CLIENTI" => $MnuItemDIZ_CLIENTI,
    "PROMEM" => $MnuItemPROMEM,
    "GEST_FILE" => $MnuItemGEST_FILE,
    "GEST_SALVATAGGI" => $MnuItemGEST_SALVATAGGI,
    "CONTACT" => $MnuItemCONTACT,
    "ABOUT" => $MnuItemABOUT,
  ];
?>  