<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 *  Initialize the XML parser
 * 
 */
$GLOBALS['f'] = fopen("/usr/local/data/Alma/working/bursarOut.sif", "w"); 
$parser=xml_parser_create();
$current_tag = NULL;
// Function to use at the start of an element
function start($parser,$element_name,$element_attrs) {
  $GLOBALS['element_name'] = $element_name;
  //echo "Element being process is '$element_name'<br />";
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
    //echo "Stop processing element_name='.$element_name'<br />";
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
  //echo "element_name='".$GLOBALS['element_name']."=$data'<br />";
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
    case "XB:ITEMDUEDATE":              $GLOBALS['ITEMDUEDATE']           = "Due Date: " . date('Y-m-d h:m', strtotime($data));
        break;   
    case "XB:FINEFEETYPE":              $GLOBALS['FINEFEETYPE']          = $data;
        break;
    case "XB:PATRONNAME":               $GLOBALS['PATRONNAME']           = "Patron: $data";
        break;
    case "XB:LIBRARYID":                $GLOBALS['LIBRARYID']            = "Library ID: $data";
        break;
    case "XB:LASTTRANSACTIONDATE":      $GLOBALS['LASTTRANSACTIONDATE']  = date('Y.m.d', strtotime($data));
        break;
    case "XB:ITEMTITLE":                $GLOBALS['ITEMTITLE']            = "Title: $data";
        break;
    case "XB:ITEMCALLNUMEBR":           $GLOBALS['ITEMCALLNUMBER']       = "Call No.: $data";
        break;
    case "XB:ITEMLIBRARY":              $GLOBALS['ITEMLIBRARY']          = "Library: $data";
        break;
    case "XB:ITEMLOCATION":             $GLOBALS['ITEMLOCATION']         = "Item Location: $data";
        break;
    case "XB:ITEMBARCODE":              $GLOBALS['ITEMBARCODE']          = "Barcode: $data";
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
  $LASTTRANSACTIONDATE = str_pad($GLOBALS['LASTTRANSACTIONDATE'], 20, ' ');
  $PatronName   = str_pad($GLOBALS['PATRONNAME'], 50, ' ');
  $VALUE        = str_pad($GLOBALS['VALUE'], 9, ' ');
  $SUM          = str_pad(substr($SUM,1), 16, ' ');
  $feeType      = "+"; // Set default.  Most records are collections
  $FINEFEETYPE = str_pad("xx", 10, ' ');
  // Equate new value to old SIF value for PeopleSoft processing
  switch($GLOBALS['FINEFEETYPE']) {
    case "OVERDUEFINE":
        $FINEFEETYPE = str_pad("F1", 10, ' ');
        break;
    case "RECALLEDOVERDUEFINE":
        $FINEFEETYPE = str_pad("F1", 10, ' ');
        break;
    case "LOSTITEMREPLACEMENTFEE":
        $FINEFEETYPE = str_pad("F2", 10, ' ');
        break;
    case "LOSTITEMPROCESSFEE":
        $FINEFEETYPE = str_pad("F3", 10, ' ');
        break;
    case "CREDIT":
        // Ex Libres uses the FINEFEETYPE to post a credit [dumb].
        if ($GLOBALS['SUM']=="95") { $FINEFEETYPE = str_pad("F2", 10, ' '); }
         elseif ($GLOBALS['SUM']=="15") { $FINEFEETYPE = str_pad("F3", 10, ' '); }
          else { $FINEFEETYPE = str_pad("F1", 10, ' '); }
        $feeType     = "-"; // Set fee type to credit
        break;
    default :
        echo "Unknown finefeetype='" . $GLOBALS['FINEFEETYPE'] . "'\n";
        if ($GLOBALS['SUM']=="95") { 
            $FINEFEETYPE = str_pad("F2", 10, ' ');
        }
        elseif ($GLOBALS['SUM']=="15") { 
            $FINEFEETYPE = str_pad("F3", 10, ' '); 
        }
        else { 
            $FINEFEETYPE = str_pad("F1", 10, ' ');
        }
        break;
    }
      $output = $VALUE . /* $GLOBALS['INSTITUTIONID'] . */ $PatronName . $FINEFEETYPE . $LASTTRANSACTIONDATE . $feeType . $SUM
                . $GLOBALS['ITEMTITLE'] . ", " . $GLOBALS['ITEMCALLNUMBER'] . ", "
                . $GLOBALS['ITEMBARCODE'] . ", " . $GLOBALS['ITEMDUEDATE'] . ", " . $GLOBALS['ITEMLIBRARY'] . ", "
                . $GLOBALS['ITEMLOCATION'] . "\r\n";
  fwrite($GLOBALS['f'], $output); 
  $output = Null;
  
}
function get_numeric($val) {
    if (ctype_digit($val));
       else {
           $val = substr($val,1);
           echo "val=$val \n";
       }
    return $val;
}

// Specify element handler
xml_set_element_handler($parser,"start","stop");

// Specify data handler
xml_set_character_data_handler($parser,"char");

// Open XML file
// if running locally for testing uncomment below line
// $fp=fopen("../proxyfiles/data/ALMA/AlmaBURSAR.xml","r");
// if running as hosted service on Lamp Server use below line
$fp=fopen("/usr/local/data/Alma/working/bursarOut.xml","r");

// Read data
while ($data=fread($fp,4096)) {
  xml_parse($parser,$data,feof($fp)) or 
  die (sprintf("XML Error: %s at line %d", 
  xml_error_string(xml_get_error_code($parser)),
  xml_get_current_line_number($parser)));
}

// Free the XML parser
xml_parser_free($parser);
fclose($f); 
?>


