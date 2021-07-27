
<?php

/*
The MIT License (MIT)

Copyright (c) Fri Jan 05 2018 Micky De Pauw

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORTOR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * deze functie geeft u het gemeentenummer 
 * (primary key) 
 * voor een postcode, een gemeentenaam
 * of een combinatie postcode-gemeentenaam
 * 
 * @param  [string] $_postcode     [postcode]
 * @param  [string] $_gemeenteNaam [gemeente naam]
 * @return [integer] [primary key uit t_gemeente]
 *                                
 *-- deze functie wordt verder uitgewerkt in de 
 *   volgende lessen
 */

function PK_t_gemeente($_postcode, $_gemeenteNaam)
{
	
// functie initalisatie	
  global $_PDO; // gebruik de globale var $_pdo ipv een lokale var
	
  $_prev_par = false;
	
  $_query = "SELECT D_Gemeente FROM t_gemeente"; 
	/* eerste deel van de query 
	   we hebben enkel d_gemeente nodig
	   dus selecteren we enkel deze kolom */

  if ($_postcode!= "")  // is postcode ingegeven ?
  {		  
    $_query.= " WHERE ";  // we vervolledigen  de query met WHERE
					
    $_query.="D_Postnummer = '$_postcode'";
		// we vervolledigen  de query met de kolom en de ingegeven waarde
    $_prev_par = true;	// er is nu een kolom toegevoegd aan de query
  }	
  
  
  if ($_gemeenteNaam != "") //is de gemeente naam ingegeven ?
  {	
    if ($_prev_par) // is er al een kolom (postcode) toegevoegd aan de query
    {
      $_query.= " AND "; // vervolledig met AND
		  }	
		  else
		  {  // nee
			   $_query.= " WHERE "; // vervolledig met  WHERE
		  }	
		
		  $_query.="D_GemeenteNaam ='$_gemeenteNaam'";
		// we vervolledigen  de query met de kolom en de ingegeven waarde
		  $_prev_par = true;	// er is nu minstens één kolom toegevoegd aan de query
	 }
	
	if ($_prev_par) // was er minstens één input (postcode of naam)
	{
		 $_result = $_PDO -> query("$_query"); 

   if ( $_result -> rowCount() > 0)
   {

     while($_row = $_result -> fetch(PDO::FETCH_ASSOC)) 
     {
       $_return = $_row['D_Gemeente'];
     }
   }
   else
   {
      $_return=0; // FALSE
	   }
  }
	 return $_return;
}
?>