<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 *  Initialize the XML parser
 * 
 */
$GLOBALS['f'] = fopen("/usr/local/data/Alma/working/NONbursarOut.csv", "w");
///$GLOBALS['f'] = fopen("NONbursarOut.csv", "w"); 
$parser=xml_parser_create();
$current_tag = NULL;
// Function to use at the start of an element
function start($parser,$element_name,$element_attrs) {
  $GLOBALS['element_name'] = $element_name;
  switch($element_name) {
        case "XB:USER":
            $GLOBALS['USER']        = True;
            break;
        case "XB:FINEFEELIST":
            $GLOBALS['FINEFEELIST'] = True;
            break;
        case "XB:USERFINEFEE":
            $GLOBALS['USERFINEFEE'] = True;
            break;
  }
}

// Function to use at the end of an element
function stop($parser,$element_name) {
    switch($element_name) {
        case "XB:USER": 
            $GLOBALS['USER']        = False;
            break;
        case "XB:FINEFEELIST": 
            $GLOBALS['FINEFEELIST'] = False;
            break;
        // record can be written. 
        // SIF has only 1 charge per record in output file.
        // 1 userExportedFineFeesList Element may contain multiple fines for a User.     
        case "XB:USERFINEFEE":
            writeoutput();
            break;
        case "XB:USEREXPORTEDFINEFEESLIST":
            $GLOBALS['ITEMDUEDATE']          = Null;
            $GLOBALS['INSTITUTIONID']        = Null;
            $GLOBALS['FEE_INSTITUTIONID']    = Null;
            $GLOBALS['PATRONNAME']           = Null;
            $GLOBALS['LIBRARYID']            = Null;
            $GLOBALS['LASTTRANSACTIONDATE']  = Null;
            $GLOBALS['ITEMTITLE']            = Null;
            $GLOBALS['ITEMCALLNUMBER']       = Null;
            $GLOBALS['ITEMLIBRARY']          = Null;
            $GLOBALS['ITEMLOCATION']         = Null;
            $GLOBALS['ITEMBARCODE']          = Null;
            $GLOBALS['SUM']                  = Null;
            break;
    }
}

// Function to use when finding character data
function char($parser,$data) {
  switch($GLOBALS['element_name']) {
    case "XB:VALUE":
        if ($GLOBALS['USER']) { $GLOBALS['VALUE'] = $data; }
        break;
    case "XB:INSTITUTIONID": 
        if ($GLOBALS['USER']) {
            $GLOBALS['INSTITUTIONID'] = $data;
        }
        if (isset($GLOBALS['FINEFEELIST']) AND $GLOBALS['FINEFEELIST']) {
            $GLOBALS['FEE_INSTITUTIONID'] = $data;
        }
        break;
    case "XB:ITEMDUEDATE":              $GLOBALS['ITEMDUEDATE']          = date('Y-m-d h:m', strtotime($data));
        break;   
    case "XB:FINEFEETYPE":              $GLOBALS['FINEFEETYPE']          = $data;
        break;
    case "XB:PATRONNAME":               $GLOBALS['PATRONNAME']           = $data;
        break;
    case "XB:LIBRARYID":                $GLOBALS['LIBRARYID']            = $data;
        break;
    case "XB:LASTTRANSACTIONDATE":      $GLOBALS['LASTTRANSACTIONDATE']  = date('Y.m.d', strtotime($data));
        break;
    case "XB:ITEMTITLE":
        // @Comment - xml_parser_create was creating multiple title when certain utf8 characters are present.
        if ( isset( $GLOBALS['ITEMTITLE'] )) :
            $GLOBALS['ITEMTITLE']            .= $data;
        else :
            $GLOBALS['ITEMTITLE']             = $data;
        endif;
        break;
    case "XB:ITEMCALLNUMEBR":           $GLOBALS['ITEMCALLNUMBER']       = $data;
        break;
    case "XB:ITEMLIBRARY":              $GLOBALS['ITEMLIBRARY']          = $data;
        break;
    case "XB:ITEMLOCATION":             $GLOBALS['ITEMLOCATION']         = $data;
        break;
    case "XB:ITEMBARCODE":              $GLOBALS['ITEMBARCODE']          = $data;
        break;
    case "XB:SUM":                      $GLOBALS['SUM']                  = $data;
        break;
    case "XB:CURRENCY":                 
        if ($data != "USD") { 
            echo "Non USD Currency Error detected, Currency detected is $data";
            die("Fix XML and rerun.. contact your administrator if this error is invalid.");
        }
        break;
        
  }
  
}

function writeoutput() {

  setlocale(LC_MONETARY, 'en_US');
  // Windows does not have this function.
  $GLOBALS['SUM'] = get_numeric($GLOBALS['SUM']);
  $SUM = money_format('%.2n',$GLOBALS['SUM']);
  $LASTTRANSACTIONDATE = $GLOBALS['LASTTRANSACTIONDATE'];
  $PatronName   = $GLOBALS['PATRONNAME'];
  $VALUE        = $GLOBALS['VALUE'];
  $SUM          = substr($SUM,1);
  $feeType      = "+"; // Set default.  Most records are collections
  $FINEFEETYPE = "xx";
  // Equate new value to old SIF value for PeopleSoft processing
  switch($GLOBALS['FINEFEETYPE']) {
    case "OVERDUEFINE":
        $FINEFEETYPE = "F1";
        break;
    case "RECALLEDOVERDUEFINE":
        $FINEFEETYPE = "F1";
        break;
    case "LOSTITEMREPLACEMENTFEE":
        $FINEFEETYPE = "F2";
        break;
    case "LOSTITEMPROCESSFEE":
        $FINEFEETYPE = "F3";
        break;
    case "CREDIT":
        // Ex Libres uses the FINEFEETYPE to post a credit [dumb].
        if ($GLOBALS['SUM']=="95") { $FINEFEETYPE = "F2"; }
         elseif ($GLOBALS['SUM']=="15") { $FINEFEETYPE = "F3"; }
          else { $FINEFEETYPE = "F1"; }
        $feeType     = "-"; // Set fee type to credit
        break;
    default :
        echo "new finefeetype='" . $GLOBALS['FINEFEETYPE'] . "'\n";
        break;
    }
  
  $output = "\"" . $VALUE . "\"" . ",\"" . $PatronName . "\"" . ",\"" .$FINEFEETYPE . "\"" . ",\"" . $LASTTRANSACTIONDATE . "\"" .  ",\""  . $feeType . "\"" .  ",\"" . $SUM
          . "\"" .  ",\"" . $GLOBALS['ITEMTITLE'] . "\"" .  ",\"" . $GLOBALS['ITEMCALLNUMBER'] . "\"" .
           ",\"" . $GLOBALS['ITEMBARCODE'] .  "\"" .  ",\"" . $GLOBALS['ITEMDUEDATE'] . "\"" . ",\"" .  $GLOBALS['ITEMLIBRARY'] .  "\"" .
           ",\"" . $GLOBALS['ITEMLOCATION'] .  "\"\r\n";
  
  // @@Comment - Clear Globals after write.

  $GLOBALS['ITEMTITLE']             = Null;
  fwrite($GLOBALS['f'], $output); 
  $output = Null;
  
}

function get_numeric($val) {
    if (ctype_digit($val));
       else {
           $val = substr($val,1);
       }
    return $val;
}
function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value)) 
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        
        $current = ord($value{$i});
        if ((( $current >= 32 ) && ( $current <= 126 )) ||
              ( $current >= 10 ))
        {
                $ret .= chr($current);
        }
        else
        {
            echo "Skipping current=" . $current . "<br />";
            $ret .= " ";
        }
    }
    return $ret;
}

 $output = "\"Patron_ID\"" . ",\"Patron_Name\"" . ",\"Fine_type\"" . ",\"Last_Transaction_Date\"" .  ",\"Fee_type\"" .  ",\"Sum\"" .
           ",\"Item_Title\"" .  ",\"Item_call_number\"" . ",\"Item_barcode\"" .  ",\"Item_due_date\"" . ",\"Item_library\"" . ",\"Item_location\"\r\n";
 fwrite($GLOBALS['f'], $output); 
 $output = Null;
// Specify element handler
xml_set_element_handler($parser,"start","stop");

// Specify data handler
xml_set_character_data_handler($parser,"char");

// Open XML file
// if running locally for testing uncomment below line
// $fp=fopen("../proxyfiles/data/ALMA/AlmaBURSAR.xml","r");
// if running as hosted service on Lamp Server use below line
//$fp=fopen("BURSARNON-TEST.xml","r");
$fp=fopen("/usr/local/data/Alma/working/NONbursarOut.xml","r");

// Read data
while ($data=fread($fp,4096)) {
  $data = stripInvalidXml($data);
  xml_parse($parser,$data,feof($fp)) or 
  die (sprintf("XML Error: %s at line %d", 
  xml_error_string(xml_get_error_code($parser)),
  xml_get_current_line_number($parser)));
}

// Free the XML parser
xml_parser_free($parser);
fclose($f); 
?>


