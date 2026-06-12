<!DOCTYPE html>
<?php
//vedere: https://codepen.io/paulobrien/pen/LBrMxa 

function GestContainer($FROM, $_post)
{
  $THIS_FILE=basename(__FILE__,".php");
  $THIS_FUNCTION=$THIS_FILE."(".__FUNCTION__ .")";
    
  /*
   * DETERMINO IL TIPO DI CONTAINER DI APPLICAZIONE CARICARE - INIZIO
   * Puo' essere:
   *      G00=Managent genrale
   *      Mnn=Management di un sito
   *      Znn/Ann=Zootecnia/Agricoltura per il sito SACS_nn, comprensivo di parte contabile
   */ 
  WriteLog("F", $FROM, $THIS_FUNCTION."[post]", $THIS_FILE, "GestContainer POST PARAMETERS", $_post);
  // lettura dei parametri di post
  try
  {
    $ABIL=$_post["ABIL"];
    $FROM=$_post["FROM"]; // e' lo stesso di $FROM=$_POST["ID_USR"];
    $SITE=$_post["SITE"];
    $MnuAct=$_post["MNU_ACT"];
    if($MnuAct=="BEGIN")
    {
      $_post["MNU_ACT"]="SITI";
      $_post["QRY_FNC"]="GEST_SOC_SITI";
    }

    $MnuAct=$_post["MNU_ACT"];
  }
  catch (Exception | Error $e) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} error reading post parameters", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    throw new Exception("{$THIS_FUNCTION} error reading post parameters");      
  }
  
  $RetArrRec=array();
  
  switch (substr($ABIL,0,1))
  {
    case "G":
      /* 
       * COSTRUZIONE DEL TITOLO
       */  
      $HDRTXT = "";
      /*
       * PREPARAZIONE DELLE LISTE
       */
      try
      {
        switch ($MnuAct)
        {
          case "SITI":  
            $HDRTXT = "{$SITE} <br> GESTIONE DI TUTTI I SITI <br> Siti";
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading site list begin", NULL);
            $RetArr=GEST_SEL_Fnct($FROM,$_post);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading site list end", NULL);
            break;
          case "USR":
            $HDRTXT = "{$SITE} <br> GESTIONE DI TUTTI I SITI <br> Utenti";
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading user list begin", NULL);
            $RetArr=GEST_SEL_Fnct($FROM,$_post);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading user list end", NULL);
            break;
          case "DIZ_ABIL":
            $HDRTXT = "{$SITE} <br> GESTIONE DI TUTTI I SITI <br> Utenti";
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading abilitations dictionary begin", NULL);
            $RetArr=GEST_SEL_Fnct($FROM,$_post);
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Loading abilitations dictionary end", NULL);
            break;
          default:  
            WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: MnuAct not found", NULL);
            WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: MnuAct not found. got:".$MnuAct);
            throw new Exception("{$THIS_FUNCTION} - MnuAct not found. got:".$MnuAct);
        }
        break;
      }
      catch (Exception | Error $e) 
      {
        WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} Error calling actions GEST_SEL ", NULL);
        WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
        throw new Exception("{$THIS_FUNCTION} Error calling actions GEST_SEL ");      
      }
    case "M":
      /* 
       * COSTRUZIONE DEL TITOLO
       */  
      $HDRTXT = "{$SITE} <br> GESTIONE DI UN SITO";
      break;
    case "Z":
      /* 
       * COSTRUZIONE DEL TITOLO
       */  
      $HDRTXT = "{$SITE} <br> GESTIONE ANIMALI DI UN SITO";
      break;
    case "A":
      /* 
       * COSTRUZIONE DEL TITOLO
       */  
      $HDRTXT = "{$SITE} <br> GESTIONE AGRICOLTURA DI UN SITO";
      break;
    default:
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: abil not found", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, __LINE__, "Error: abil not found. got:".substr($ABIL,0,1));
      require "../MSG/SYS_ERR.html";
      throw new Exception("{$THIS_FUNCTION} - Error: abil not found");
  }
  /*
  try 
  {
    @require "../MSG/DISPLAY_COMMON.php";
  } 
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, "GestContainer.php", "File DISPLAY_COMMON.php not found - Program ends", "");
    $ErrTxt = "FROM:{$FROM} - GestContainer.php() - " .   
              " + File DISPLAY_COMMON.php not found. - " .
              "Exception/Error: " . $e->getMessage() . " line: ". $e->getLine() . " +";   
    WriteErr($ErrTxt);
    /* Non trova una risorsa e finisce */
/*    require "../MSG/SYS_ERR.html";
    die();
  }
  */
  
?>

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Management of a sheep farm, especially taking care that there are no inbreeding animals." />
    <meta name="keywords" content="Sheeps, goats, inbreeding animals " />      
    <title><?php echo $HDRTXT ?></title>
    <!--link rel="stylesheet" href="../GEST/100_GestContainer.css">
    <link rel="stylesheet" href="../GEST/200_GestMenu.css">
    <link rel="stylesheet" href="../GEST/300_GestLista.css">
    <link rel="stylesheet" href="../GEST/300_GestAdd.css"-->
    <style>
<?php
    try 
    {
      @require '../GEST/100_GestContainer.css';
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 100_GestContainer.css avaliable", NULL);
      @require '../GEST/300_GestLista.css';
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 300_GestLista.css avaliable", NULL);
      @require '../GEST/200_GestMenu.css';  
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 200_GestMenu.css avaliable", NULL);
      @require '../GEST/400_GestAdd.css';  
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 400_GestAdd.css avaliable", NULL);
      @require '../GEST/500_GestPM.css';  
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "File 400_GestAdd.css avaliable", NULL);
    }
    catch(Exception $e) 
    {
      WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "{$THIS_FUNCTION} a .css file of GEST is unavaliable", NULL);
      WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
      require "../MSG/SYS_ERR.html";      
      throw new Exception("{$THIS_FUNCTION} a .css file of GEST is unavaliable");        
    }   
?>  
    </style>    
      <script type="text/javascript" src="../COMMON/CMN_JS.js"></script>
      <script type="text/javascript" src="../GEST/GestCommon.js"></script>
      <script type="text/javascript" src="../GEST/300_GestLista_SA.js"></script>
      <script type="text/javascript" src="../GEST/200_GestMenu.js"></script>
      <script type="text/javascript" src="../GEST/400_GestAdd.js"></script>
      <script type="text/javascript" src="../GEST/500_GestPM.js"></script>
  </head> 
<!--      
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?//php echo $HDRTXT ?></title>
    <link rel="stylesheet" href="../LOGIN/20_Choose.css">  
    <style>
-->
<?php
  /*      
  try 
  {
    require "100_GestContainer.css";
  } 
  catch (Exception $e) 
  {
    //catch exception
    echo "Message: " . $e->getMessage();
    die();
  }
      
  try 
  {
    require "GestContainerIcons.css";
  } 
  catch (Exception $e)
  {
    //catch exception
    echo "Message: " . $e->getMessage();
    die();
  }

/*  try 
  {
    require '110_Lst.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }
     
  try 
  {
    require '150_Mnu.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }      
            
  try 
  {
    require '115_Add.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }      
                 
  try 
  {
    require 'Dspl.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 
      
  try 
  {
    require '125_AddResumePg.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }

  try 
  {
    require '500_Insert.css';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 
  */

?>  
<!--
    .ID_DivFltr
    {
      position: fixed;
      top:1.01vh;
      left:20%;            
      width:80%;
      z-index: 1;
      margin: auto;
      overflow-x: hidden;
      overflow-y: auto;    
      font-size: 14px;
      background-color: red;
      border:solid;      
      transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    }
    
    .ID_DivFltrPg
    {
      position: fixed;
      top:1.01vh;
      left:20%;            
      width:80%;
      z-index: 1;
      margin: auto;
      overflow-x: hidden;
      overflow-y: auto;    
      font-size: 14px;
      background-color: orange;
      border:solid;      
      transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    }
    </style>
    <script>
<?php
/*      
  try 
  {
    require "100_Common.js";
  } 
  catch (Exception $e) 
  {
    //catch exception
    echo "Message: " . $e->getMessage();
    die();
  }

  try 
  {
    require '110_Lst.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }  
           
  try 
  {
    require '150_Mnu.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }         

  try 
  {
    require '115_Add.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }
      
  /*try 
  {
    require 'Dspl.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } */     
      
  /*try 
  {
    require '125_AddResumePg.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 

  try 
  {
    require '500_Insert.js';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 
  */    
?>
  
      
      function Fltr(Act)
      {
        document.getElementById("ID_DivFltr").style.transition="0.5s"; /* 0.5 second transition effect to slide in the sidenav */
        if (Act=='O')
        {
          document.getElementById("ID_DivFltr").style.left = "0%";    
        }
        if (Act=='C')
        {
          document.getElementById("ID_DivFltr").style.left = "100%";    
        }
      }
        
      function FltrPg(Act)
      {
        document.getElementById("ID_DivFltrPg").style.transition="0.5s"; /* 0.5 second transition effect to slide in the sidenav */
        if (Act=='O')
        {
          document.getElementById("ID_DivFltrPg").style.left = "0%";
        }
        if (Act=='C')
        {
          document.getElementById("ID_DivFltrPg").style.left = "100%"; 
        }  
      }


        
        
    </script>
  </head>
-->
  <body onload="GestContainerInitializePage();FormatMnu('<?php echo $FROM?>','<?php echo $SITE?>','<?php echo $ABIL?>','<?php echo $MnuAct?>',false)">
  <!--body-->
    <div ID="ID_DIVCNT">
<?php

  try 
  {
     @require '../GEST/200_GestMenu.php';
  }
  //catch exception
  catch (Exception | Error $e) 
  {
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: 200_GestMenu not found", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    throw new Exception("{$THIS_FUNCTION} - 200_GestMenu not found");    
  }    
  
  try 
  {
    @require '../GEST/300_GestLista_SA.php';
  }
  //catch exception
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: 300_GestLista_SA not found", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    throw new Exception("{$THIS_FUNCTION} - 300_GestLista_SA not found");    
  }
  
  try 
  {
    @require '../GEST/400_GestAdd.php';
  }
  //catch exception
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: 400_GestAdd not found", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    throw new Exception("{$THIS_FUNCTION} - 400_GestAdd not found");    
  }

  try 
  {
    @require '../GEST/500_GestPM.php';
  }
  //catch exception
  catch (Exception | Error $e) 
  {
    //catch exception
    WriteLog("S", $FROM, $THIS_FUNCTION, $THIS_FILE, "Error: 500_GestPM not found", NULL);
    WriteErr($FROM, $THIS_FUNCTION, $THIS_FILE, $e->getLine(), $e->getMessage());
    require "../MSG/SYS_ERR.html";
    throw new Exception("{$THIS_FUNCTION} - 500_GestPM not found");    
  }

  /*  
  try 
  {
    require '115_Add.php';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 
    
  /*try 
  {
    require 'Dspl.html';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }*/ 
 
/*  try 
  {
    require '125_AddResumePg.php';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  } 
    
  try 
  {
    require '500_Insert.php';
  }
  //catch exception
  catch(Exception $e) 
  {
    echo 'Message: ' .$e->getMessage();
    die;
  }
  */
?>
    <!--section>
      <div id="ID_DivFltr" class="ID_DivFltr">
        <a href="#" onclick="Fltr('C')">Close</a>
        <a href="#" onclick="FltrPg('O')">Dtl</a>
      </div>
    </section>
    <section>
      <div id="ID_DivFltrPg" class="ID_DivFltrPg">
        <a href="#" onclick="FltrPg('C')">Close</a>
      </div>
    </section-->
    </div>
    <script type="text/javascript" src="../GEST/300_BtnAdd.js"></script>
  </body>
</html>
<?php
}
?>
