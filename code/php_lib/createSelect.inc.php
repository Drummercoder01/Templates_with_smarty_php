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
 * Deze functie maakt vanuit de gegeven input een correcte SQL select querie
 * 
 * gewoon alle velden voor alle gevonden records
 *
 * @param  [string] $_table [tabel naam]
 * @param  [Array]  $_value [lijst met waarden]
 * @param  [Array]  $_colum [lijst met data velden]
 * @return [string] [SQL selectie querie]
 * *********************************
 * vb:
 *   Function call
 *     createSelect("t_klanten", 
 *                  array($_naam, $_voornaam, $_telefoon),
 *                  array('d_naam', 'd_voornaam', 'd_tel'));
 *
 *   Return value
 *     "SELECT * FROM t_klanten WHERE 
 *          d_naam = $_naam 
 *          AND 
 *          d_voornaam = $_voornaam
 *          AND 
 *          d_tel = $_telefoon;"
 */

function createSelect($_table, $_value, $_colum)
{

  $_prev_par= false;
  $_query = "SELECT * FROM $_table";

  foreach ($_value as $_key => $_value)
  {
  if ($_value != "" && $_value != "0")
    {
      if ($_prev_par)
      {
        $_query.= " AND ";
      }
      else
      {
        $_query.= " WHERE ";
      }

      $_query.= $_colum[$_key]." = '$_value'";
      $_prev_par = true;
    }
  }
  $_query.=";";

  return $_query;
}

?>
