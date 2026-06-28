<?php
  class SqlQuery 
  {
    // Properties
    
    private $Type;
    private $SqlTxt="";
    private $ArrParms=array();   
    private $RC=1;
    private $NField=0;    
    private $NRec=0;
    private $ArrRec=array();

    // Methods

    function set_Type($Type)
    {
      $this->Type = $Type;  
    } 

    function get_Type() 
    {
      return $this->Type;
    }    

    function set_SqlTxt($SqlTxt) 
    {
      $this->SqlTxt = $SqlTxt;
    }
    function get_SqlTxt() 
    {
      return $this->SqlTxt;
    }  

    function set_ArrParms($ArrParms) 
    {
      $this->ArrParms=$ArrParms;
    }    
    
    function get_ArrParms() 
    {
      return $this->ArrParms;
    }    

    function set_Parm($Value) 
    {
      array_push($this->ArrParms, $Value);
    }
    
    function get_Parm($Idx) 
    {
      return $this->ArrParms[$Idx];
    }   

    function set_RC($RC) 
    {
      $this->RC = $RC;
    }
    function get_RC() 
    {
      return $this->RC;
    }            

    function set_NField($NField) 
    {
      $this->NField = $NField;
    }
    function get_NField() 
    {
      return $this->NField;
    }      

    function set_NRec($NRec) 
    {
      $this->NRec = $NRec;
    }
    function get_NRec() 
    {
      return $this->NRec;
    }      

    function set_ArrRec($ArrRec) 
    {
      $this->ArrRec=$ArrRec;
    }
    function get_ArrRec() 
    {
      return $this->ArrRec;
    }    

    function set_Rec($Rec) 
    {
      array_push($this->ArrRec, $Rec);
      $this->set_NRec(count($this->ArrRec));
    }
    
    function get_Rec($Idx) 
    {
      return $this->ArrRec[$Idx];
    }    

    function DumpParms()
    {  
      $PrmsTxt="";
      foreach ($this->get_ArrParms() as $key => $value) 
      {
        $PrmsTxt=$PrmsTxt."{$key} => {$value}"."\n";
      } 
      return $PrmsTxt;
    }

    function print()
    {
      $RetTxt="QUERY:"."\n";
      $RetTxt=$RetTxt."  type        :".$this->get_Type()."\n";
      $RetTxt=$RetTxt."  RC          :".$this->get_RC()."\n";
      $RetTxt=$RetTxt."  N.field     :".$this->get_NField()."\n";
      $RetTxt=$RetTxt."  N Rec       :".$this->get_NRec()."\n";
      $RetTxt=$RetTxt."  SQL text    :".$this->get_SqlTxt()."\n"; 
      $RetTxt=$RetTxt."PARMS:"."\n";
      $RetTxt=$RetTxt.$this->DumpParms();
      return $RetTxt;
    }    
  }

  class SqlQueryArr
  {
    /*
     * A short array syntax exists which replaces array() with []
     */ 
    private $QueryArr = array();
    private $NElements=0;
    private $Transaction=false; 
    private $RollBack=false; 
    
    function get_Arr()
    {
      return $this->QueryArr;
    }

    function set_Arr($Arr)
    {
      $this->QueryArr=$Arr;
    }

    function push_Query(SqlQuery $Qry)
    {
      $NRec=array_push($this->QueryArr,$Qry);
      $this->set_NElements(count($this->QueryArr));
    } 

    function get_Query()
    {
      /*
       * array_shift() sposta il primo valore dell'array arraye lo restituisce, accorciandolo arraydi un elemento e spostando tutto verso il basso.
       */ 
      $RetQry = array_shift($this->QueryArr);
      return $RetQry;
    }    
    
    function get_NElements()
    {
      return $this->NElements;
    }
    
    function set_NElements($NElems)
    {
      $this->NElements=$NElems;
    }
    
    function set_Transaction($TF) 
    {
      $this->Transaction = $TF;
    }
    
    function get_Transaction() 
    {
      return $this->Transaction;
    }   

    function set_RollBack($TF) 
    {
      $this->RollBack = $TF;
    }
    
    function get_RollBack() 
    {
      return $this->RollBack;
    }   
    
    function print()
    {
      $PrintText="TRANSAZIONE:\n";
      $PrintText=$PrintText."NQuery:".$this->get_NElements()."; ";
      $PrintText=$PrintText."Transaction:".(int)$this->get_Transaction()."; ";
      $PrintText=$PrintText."RollBack:".(int)$this->get_RollBack()."; ";
      if($this->get_NElements() == 0)
      {
         $PrintText=$PrintText."\n"."NO QUERY (NQuery={$this->get_NElements()})"."\n";
      }
      else
      {
        foreach( $this->QueryArr as $Qry )
        {
          $PrintText=$PrintText."\n".$Qry->print();
        }
      }
      return $PrintText; 
    }
  }
?>