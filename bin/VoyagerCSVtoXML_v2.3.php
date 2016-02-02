<?php
//error_reporting(E_ALL | E_STRICT | E_WARNING);
//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//ini_set('display_errors', true);
ini_set('auto_detect_line_endings', true);
//include('includes/headerIncludeXMLconvert.php');
//$outputFilename = "/data/websites/MINES_Survey/proxyfiles/data/ALMA.xml";
$outputFilename = "/usr/local/data/working/ALMA_Patron";
//else $outputFilename = '/var/log/AppLogs/vPC/kualiCustomerXML';
$fileName    = $outputFilename;
//$fileName    = $outputFilename . date('Ymd-gis') . ".xml";
echo "fileName=$fileName";
$processFile = True;

$inputFilename = '/data/websites/MINES_Survey/proxyfiles/data/csvoutput.txt';
if ($processFile) {
  // Define whether elements are required.
  // TRUE or FALSE was determined by the xml schema.
  
  // https://developers.exlibrisgroup.com/blog/Working-with-the-code-tables-API
  $hdrReq['record_type']            = FALSE;  // Added for ALMA V2.0 -- ["Need API" - codeTable=AddNewUserOptions; ]
  $hdrReq['user_group']             = FALSE;  // Mod for ALMA V2.0   -- ["Need API" - codeTable=UserGroups; ]
  $hdrReq['status']                 = TRUE;   // Mod for ALMA V2.0   -- ["Need API" - codeTable=ContentStructureStatus; ]
  $hdrReq['creationDate']           = FALSE;
  $hdrReq['expiryDate']             = FALSE;
  $hdrReq['userIdentifiers']        = TRUE;
  $hdrReq['primary_id']             = FALSE; // Mod for ALMA V2.0
  $hdrReq['statistic_category']     = TRUE;  // Mod for ALMA V2.0   -- ["Need API" - codeTable=UserStatCategories; ]
  
  $hdrReq['name_type']              = FALSE;
  $hdrReq['last_name']              = FALSE;  // Mod for ALMA V2.0
  $hdrReq['first_name']             = FALSE;  // Mod for ALMA V2.0
  $hdrReq['middle_name']            = TRUE;   // Mod for ALMA V2.0
  $hdrReq['full_name']              = FALSE;  // Added for ALMA V2.0
  $hdrReq['title']                  = FALSE;  // Added for ALMA V2.0 -  I belive it gets added to end of full_name?
  
  $hdrReq['address_count']          = FALSE;  // Count of Address Records to expect
  //
  // Use to determine incoming Address Type [ 1=permanent; 2=temporary; 3=email
  $hdrReq['address_type_01']        = FALSE;
  $hdrReq['address_status_code_01'] = FALSE; // Should always be "N".  Just test and handle if not "N".
  $hdrReq['startDate_01']           = FALSE;
  $hdrReq['endDate_01']             = TRUE;
  $hdrReq['line1_01']               = FALSE;
  $hdrReq['line2_01']               = FALSE;
  $hdrReq['line3_01']               = FALSE;
  $hdrReq['line4_01']               = TRUE;
  $hdrReq['line5_01']               = FALSE;
  $hdrReq['city_01']                = FALSE;
  $hdrReq['stateProvince_01']       = FALSE;
  $hdrReq['postalCode_01']          = FALSE;
  $hdrReq['country_01']             = FALSE;
  $hdrReq['phone_01']               = FALSE;
  $hdrReq['mobile_01']              = FALSE; // not used
  $hdrReq['fax_01']                 = FALSE; // not used
  $hdrReq['other_01']               = FALSE; // not used
    // Use to determine incoming Address Type [ 1=permanent; 2=temporary; 3=email
  $hdrReq['address_type_02']        = FALSE;
  $hdrReq['address_status_code_02'] = FALSE; // Should always be "N".  Just test and handle if not "N".
  $hdrReq['startDate_02']           = FALSE;
  $hdrReq['endDate_02']             = TRUE;
  $hdrReq['line1_02']               = FALSE;
  $hdrReq['line2_02']               = FALSE;
  $hdrReq['line3_02']               = FALSE;
  $hdrReq['line4_02']               = TRUE;
  $hdrReq['line5_02']               = FALSE;
  $hdrReq['city_02']                = FALSE;
  $hdrReq['stateProvince_02']       = FALSE;
  $hdrReq['postalCode_02']          = FALSE;
  $hdrReq['country_02']             = FALSE;
  $hdrReq['phone_02']               = FALSE;
  $hdrReq['mobile_02']              = FALSE; // not used
  $hdrReq['fax_02']                 = FALSE; // not used
  $hdrReq['other_02']               = FALSE; // not used
    // Use to determine incoming Address Type [ 1=permanent; 2=temporary; 3=email
  $hdrReq['address_type_03']        = FALSE;
  $hdrReq['address_status_code_03'] = FALSE; // Should always be "N".  Just test and handle if not "N".
  $hdrReq['startDate_03']           = FALSE;
  $hdrReq['endDate_03']             = TRUE;
  $hdrReq['line1_03']               = FALSE;
  $hdrReq['line2_03']               = FALSE;
  $hdrReq['line3_03']               = FALSE;
  $hdrReq['line4_03']               = TRUE;
  $hdrReq['line5_03']               = FALSE;
  $hdrReq['city_03']                = FALSE;
  $hdrReq['stateProvince_03']       = FALSE;
  $hdrReq['postalCode_03']          = FALSE;
  $hdrReq['country_03']             = FALSE;
  $hdrReq['phone_03']               = FALSE;
  $hdrReq['mobile_03']              = FALSE; // not used
  $hdrReq['fax_03']                 = FALSE; // not used
  $hdrReq['other_03']               = FALSE; // not used
 
  
  
  // Open csv to read
  
  $inputFile  = fopen($inputFilename, 'rt');
  
  // Get the headers of the file
  $headers = fgetcsv($inputFile);
  //echo "headers=";
  //print_r($headers);
  //echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
  
  // Create a new dom document with pretty formatting
  $xmlDoc = new DomDocument('1.0', 'UTF-8');
  $xmlDoc->formatOutput = true;
  // create root node
  $root = $xmlDoc->createElementNS('http://com/exlibris/digitool/repository/extsystem/xmlbeans','users');
  $xmlDoc->appendChild($root);
  $root->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
  
  
  $counter    = 0;
  $errCtr     = 0;
  $todayDate  = date('Ymd');
  // Loop through each row creating a <row> node with the correct data
  while (($row = fgetcsv($inputFile)) !== FALSE /* && $counter < 10 */) {
    $userGroup = "";
    $status                 = NULL;
    $creationDate           = NULL;
    $expiryDate             = NULL;
    $userIdentifiers        = NULL;
    $primary_id             = NULL;
    $statisticalCategory    = NULL;
    $name_type              = NULL;
    $lastName               = NULL;
    $firstName              = NULL;
    $middleName             = NULL;
    $address_count          = NULL;
    $address_type_01        = NULL;
    $address_status_code_01 = NULL;
    $startDate_01           = NULL;
    $endDate_01             = NULL;
    $line1_01               = NULL;
    $line1_02               = NULL;
    $line1_03               = NULL;
    $line1_04               = NULL;
    $line1_05               = NULL;
    $city_01                = NULL;
    $stateProvince_01       = NULL;
    $postalCode_01          = NULL;
    $country_01             = NULL;
    $phone_01               = NULL;
    $mobile_01              = NULL;
    $fax_01                 = NULL;
    $other_01               = NULL;
    $processUserDetail      = FALSE;
    $i                      = 0;
    $counter++;
    // Loop thru each cs
    foreach ($headers as $i => $header) {
        
        // Initilize variables
        

      // Test if the incoming Header is defined in Header table.
        try {	  	
  	    if (isset($hdrReq[$header]));
  	     else throw new Exception();
  	} // try
        catch(Exception $e) {
            // echo "An unknown field [ $header ] has been detected.";
            //exitRoutine("An unknown field [ $header ] has been detected.  Please check input file validity and try again!",$output, 'error');
        } // catch
      // create xml output
      
      //echo "<br />header=$header<br/>";
      
      if ($header=='user_group')              { $user_group = $row[$i]; }  
      if ($header=='patron_barcode_1')        { $patron_barcode_1 = $row[$i]; }
      if ($header=='status')                  { $status = $row[$i]; }
      if ($header=='creationDate')            { $creationDate = $row[$i]; }
      if ($header=='expiry_date')             { $expiry_date = $row[$i]; }  
      if ($header=='userIdentifiers')         { $userIdentifiers = $row[$i]; }
      if ($header=='primary_id')              { $primary_id = $row[$i]; }
      if ($header=='statisticalCategory')     { $statisticalCategory = $row[$i]; }  
      if ($header=='name_type')               { $name_type = $row[$i]; }
      if ($header=='last_name')               { $last_name = $row[$i]; }
      if ($header=='first_name')              { $first_name = $row[$i]; }
      if ($header=='middle_name')             { $middle_name = $row[$i]; }
      if ($header=='address_count')           { $address_count = $row[$i]; }
      
      
      if ($header=='address_type_01')        { $address_type_01 = $row[$i]; }  
      if ($header=='address_status_code_01') { $address_status_code_01 = $row[$i]; }
      if ($header=='startDate_01')           { $startDate_01 = $row[$i]; }
      if ($header=='endDate_01')             { $endDate_01 = $row[$i]; }  
      if ($header=='line1_01')               { $line1_01 = $row[$i]; }
      if ($header=='line2_01')               { $line2_01 = $row[$i]; }
      if ($header=='line3_01')               { $line3_01 = $row[$i]; }
      if ($header=='line4_01')               { $line4_01 = $row[$i]; }
      if ($header=='line5_01')               { $line5_01 = $row[$i]; }
      if ($header=='city_01')                { $city_01  = $row[$i]; }
      if ($header=='stateProvince_01')       { $stateProvince_01 = $row[$i]; }
      if ($header=='postalCode_01')          { $postalCode_01 = $row[$i]; } 
      if ($header=='country_01')             { $country_01 = $row[$i]; }
      if ($header=='phone_01')               { $phone_01 = $row[$i]; }
      if ($header=='mobile_01')              { $mobile_01 = $row[$i]; }  
      if ($header=='fax_01')                 { $fax_01 = $row[$i]; }
      if ($header=='other_01')               { $other_01 = $row[$i]; }
      
      if ($header=='address_type_02')        { $address_type_02 = $row[$i];}  
      if ($header=='address_status_code_02') { $address_status_code_02 = $row[$i];}
      if ($header=='startDate_02')           { $startDate_02 = $row[$i];}
      if ($header=='endDate_02')             { $endDate_02 = $row[$i];}  
      if ($header=='line1_02')               { $line1_02 = $row[$i];}
      if ($header=='line2_02')               { $line2_02 = $row[$i];}
      if ($header=='line3_02')               { $line3_02 = $row[$i];}
      if ($header=='line4_02')               { $line4_02 = $row[$i];}
      if ($header=='line5_02')               { $line5_02 = $row[$i];}
      if ($header=='city_02')                { $city_02  = $row[$i];}
      if ($header=='stateProvince_02')       { $stateProvince_02 = $row[$i];}
      if ($header=='postalCode_02')          { $postalCode_02 = $row[$i];} 
      if ($header=='country_02')             { $country_02 = $row[$i];}
      if ($header=='phone_02')               { $phone_02 = $row[$i];}
      if ($header=='mobile_02')              { $mobile_02 = $row[$i];}  
      if ($header=='fax_02')                 { $fax_02 = $row[$i];}
      if ($header=='other_02')               { $other_02 = $row[$i];}
      
      if ($header=='address_type_03')        { $address_type_03 = $row[$i]; }  
      if ($header=='address_status_code_03') { $address_status_code_03 = $row[$i]; }
      if ($header=='startDate_03')           { $startDate_03 = $row[$i]; }
      if ($header=='endDate_03')             { $endDate_03 = $row[$i]; }  
      if ($header=='line1_03')               { $line1_03 = $row[$i]; }
      if ($header=='line2_03')               { $line2_03 = $row[$i]; }
      if ($header=='line3_03')               { $line3_03 = $row[$i]; }
      if ($header=='line4_03')               { $line4_03 = $row[$i]; }
      if ($header=='line5_03')               { $line5_03 = $row[$i]; }
      if ($header=='city_03')                { $city_03  = $row[$i]; }
      if ($header=='stateProvince_03')       { $stateProvince_03 = $row[$i]; }
      if ($header=='postalCode_03')          { $postalCode_03 = $row[$i]; } 
      if ($header=='country_03')             { $country_03 = $row[$i]; }
      if ($header=='phone_03')               { $phone_03 = $row[$i]; }
      if ($header=='mobile_03')              { $mobile_03 = $row[$i]; }  
      if ($header=='fax_03')                 { $fax_03 = $row[$i]; }
      if ($header=='other_03')               { $other_03 = $row[$i]; }
    }
    
    //Begin building XML 
    $UR = $root->appendChild($xmlDoc->createElement('user')); // Parent Element
    
    $rectypeATTR = $UR->appendChild($xmlDoc->createElement("record_type","PUBLIC"));
    $x = $xmlDoc->createAttribute('desc');
    $x->value = "Public";
    $rectypeATTR->appendChild($x);
    $UR->appendChild($rectypeATTR);
    
    if (isset($primary_id)  && $primary_id != "NULL")   {$UR->appendChild($xmlDoc->createElement("primary_id",$primary_id));}
    
    if (isset($first_name) && $first_name != "NULL")   {
        $UR->appendChild($xmlDoc->createElement("first_name",$first_name));
        $full_name = $first_name;
    }
    if (isset($middle_name) && $middle_name != "NULL") {
        $UR->appendChild($xmlDoc->createElement("middle_name",$middle_name));
        if (isset($full_name)) { $full_name .= " $middle_name"; }
        else $full_name = $middle_name;
    }
    if (isset($last_name) && $last_name != "NULL") {
        $UR->appendChild($xmlDoc->createElement("last_name",$last_name));
        if (isset($full_name)) { $full_name .= " $last_name"; }
        else $full_name = $last_name;
    }
    if (isset($full_name)) { 
        $UR->appendChild($xmlDoc->createElement("full_name",$full_name));
    }   

    if (isset($user_group) && $user_group != "NULL")   {
        $createATTR = $UR->appendChild($xmlDoc->createElement("user_group",$user_group));
        $x = $xmlDoc->createAttribute('desc');
        switch ($user_group) {
            case "ADJFACUC":
                $x->value = "Adjunct Faculty UC";
                break;
            case "AFFIL":
                $x->value = "Affiliated";
                break;
            case "BILL":
                $x->value = "BLC Patrons";
                break;
            case "BLC - ?":
                $x->value = "BLC Patrons - ?";
                break;
            case "COMM":
                $x->value = "Community Borrower";
                break;
            case "EMERITUS":
                $x->value = "Emeritus Faculty";
                break;
            case "FACHEALTH":
                $x->value = "Faculty Health";
                break;
            case "FACLAW":
                $x->value = "Faculty Law";
                break;
            case "FACUC":
                $x->value = "Faculty UC";
                break;
            case "GRADHEALTH":
                $x->value = "Grad Health";
                break;
            case "GRADLAW":
                $x->value = "Grad Law";
                break;
            case "GRADUC":
                $x->value = "Grad UC";
                break;
            case "GRADUNKOWN":
                $x->value = "Grad Unknown";
                break;
            case "HSINSTRUCT":
                $x->value = "HS Coop Instructor";
                break;
            case "ILLCULP":
                $x->value = "ILL Culpeper";
                break;
            case "JREF":
                $x->value = "JREF";
                break;
            case "PROX":
                $x->value = "Proxy";
                break;
            case "RET":
                $x->value = "Retiree";
                break;
            case "STAFFLAW":
                $x->value = "Staff Law";
                break;
            case "STAFFUC":
                $x->value = "Staff UC";
                break;
            case "STAFHEALTH":
                $x->value = "Staff Health";
                break;
            case "TEMP":
                $x->value = "Special Payroll";
                break;
            case "UGHS":
                $x->value = "Honors Student";
                break;
            case "UND":
                $x->value = "Undergrad";
                break;
            case "UNDNOESCR":
                $x->value = "Undergrad No Escrow";
                break;
            case "UNDUNKOWN":
                $x->value = "Undergrad Unknown";
                break;
            case "WCL":
                $x->value = "WorldCat Local (test)";
                break;
            case "XPPDP1":
                $x->value = "XPPDP1";
                break;
            case "XPPDP2":
                $x->value = "XPPDP2";
                break;
            case "XPPILL1":
                $x->value = "XPPILL1";
                break;
            case "XPPILL2":
                $x->value = "XPPILL2";
                break;
            case "XPPMISS":
                $x->value = "XPPMISS";
                break;
            case "XPPNOTE":
                $x->value = "XPPNOTE";
                break;
            case "XPPRES":
                $x->value = "XPPRES";
                break;
            case "XPPSYST":
                $x->value = "XPPSYST";
                break;
            default:
                echo "";
                $x->value = "Unknown";
        }
        $createATTR->appendChild($x);
        $UR->appendChild($createATTR);
    }
    
    $createATTR = $UR->appendChild($xmlDoc->createElement("preferred_language","en"));
    $x = $xmlDoc->createAttribute('desc');
    $x->value = "English";
    $createATTR->appendChild($x);
    $UR->appendChild($createATTR);
    
    if (isset($expiry_date) && $expiry_date != "NULL") { $UR->appendChild($xmlDoc->createElement("expiry_date",validate($expiry_date,'formatdate'))); }
    
    $createATTR = $UR->appendChild($xmlDoc->createElement("account_type","EXTERNAL"));
    $x = $xmlDoc->createAttribute('desc');
    $x->value = "External";
    $createATTR->appendChild($x);
    $UR->appendChild($createATTR);
    
    $UR->appendChild($xmlDoc->createElement("external_id","SIS"));
    
    if (isset($status) && $status != "NULL") {
        switch ($status) {
            case "1":
                $status_code = "ACTIVE";
                $status_desc = "Active";
            break;
            case "4":
                $status_code = "INACTIVE";
                $status_desc = "Inactive";
            break;
            default:  // I decided to make default active if Status is not in Patron record.
                $status_code = "ACTIVE";
                $status_desc = "Active";
        }
    }
    if (isset($status) && $status != "NULL") { 
        $createATTR = $UR->appendChild($xmlDoc->createElement("status",$status_code));
        $x = $xmlDoc->createAttribute('desc');
        $x->value = $status_desc;
        $createATTR->appendChild($x);
        $UR->appendChild($createATTR);
    }
    
    //$OE->appendChild($xmlDoc->createElement("creationDate",$todayDate));
    //$OE->appendChild($xmlDoc->createElement("modificationDate",$todayDate));
    
    if ((isset($address_type_01) && $address_type_01 != "NULL") | (isset($address_type_02) && $address_type_02 != "NULL") | (isset($address_type_03) && $address_type_03 != "NULL"))
        { 
        $CI  = $UR->appendChild($xmlDoc->createElement('contact_info')); // Contact Parent Element
        // Assemble userAddressList
        if (isset($address_type_01) && $address_type_01 != "NULL") { 
            $UAS   = $CI->appendChild($xmlDoc->createElement('addresses')); // addresses Parent Element
            $UA   = $UAS->appendChild($xmlDoc->createElement('address')); // Parent Element
            if ($address_type_01 == "1") {
                $x = $xmlDoc->createAttribute('preferred');
                $x->value = "true";
                $UA->appendChild($x);
                $x = $xmlDoc->createAttribute('segment_type');
                $x->value = "External";
                $UA->appendChild($x);
            }
           
            if (isset($line1_01) && $line1_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("line1",$line1_01));}
            if (isset($line2_01) && $line2_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("line2",$line2_01));}
            if (isset($line3_01) && $line3_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("line3",$line3_01));}
            if (isset($line4_01) && $line4_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("line4",$line4_01));}
            if (isset($line5_01) && $line5_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("line5",$line5_01));}
            if (isset($city_01) && $city_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("city",$city_01));}
            if (isset($stateProvince_01) && $stateProvince_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("state_province",$stateProvince_01));}
            // @comment dneary 20150409 - Country seems to always be empty for "Address 01"
            //if (isset($country_01) && $country_01 != "NULL") {$UA->appendChild($xmlDoc->createElement("country",$country_01));}
            if (isset($postalCode_01) && $postalCode_01 != "NULL") {
                $postalCode_01 = validate($postalCode_01,'zipcode');
                if ($postalCode_01 != "error") {$UA->appendChild($xmlDoc->createElement("postal_code",$postalCode_01));}
            }
            if (isset($startDate_01) && $startDate_01 != "NULL") { $UA->appendChild($xmlDoc->createElement("start_date",validate($startDate_01,'formatdate'))); }
            if (isset($endDate_01) && $endDate_01 != "NULL")     { $UA->appendChild($xmlDoc->createElement("end_date",validate($endDate_01,'formatdate'))); }
        
            // Documentation comment in v2.0 XSD states address_types element is required.  Of course it doesn't have a minOccur="1"???
            $UTS = $UA->appendChild($xmlDoc->createElement('address_types')); // Parent Element
            if (isset($address_type_01) && $address_type_01 != "NULL") {
                if ($address_type_01 == "1") {
                    $createATTR = $UTS->appendChild($xmlDoc->createElement("address_type","home"));
                    $x = $xmlDoc->createAttribute('desc');
                    $x->value = "Home";
                }
                if ($address_type_01 == "2") {
                    $createATTR = $UTS->appendChild($xmlDoc->createElement("address_type","school"));
                    $x = $xmlDoc->createAttribute('desc');
                    $x->value = "School";
                }
                $createATTR->appendChild($x);
                $UTS->appendChild($createATTR);
            }
        }
        if (isset($address_type_02) && $address_type_02 != "NULL" && $address_type_02 != "3") { 
            $UA   = $UAS->appendChild($xmlDoc->createElement('address')); // Parent Element
            if ($address_type_02 == "1") {
                $x = $xmlDoc->createAttribute('preferred');
                $x->value = "true";
                $UA->appendChild($x);
                $x = $xmlDoc->createAttribute('segment_type');
                $x->value = "External";
                $UA->appendChild($x);
            }
            if (isset($line1_02) && $line1_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("line1",$line1_02));}
            if (isset($line2_02) && $line2_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("line2",$line2_02));}
            if (isset($line3_02) && $line3_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("line3",$line3_02));}
            if (isset($line4_02) && $line4_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("line4",$line4_02));}
            if (isset($line5_02) && $line5_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("line5",$line5_02));}
            if (isset($city_02) && $city_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("city",$city_02));}
            if (isset($stateProvince_02) && $stateProvince_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("state_province",$stateProvince_02));}
            // @comment dneary 20150409 - Country contains "non country data" for "Address 02"
            // if (isset($country_02) && $country_02 != "NULL") {$UA->appendChild($xmlDoc->createElement("country",$country_02));}
            if (isset($postalCode_02) && $postalCode_02 != "NULL") {
                $postalCode_02 = validate($postalCode_02,'zipcode');
                if ($postalCode_02 != "error") {$UA->appendChild($xmlDoc->createElement("postal_code",$postalCode_02));}
            }
        
            if (isset($startDat_02) && $startDate_02 != "NULL") { $UA->appendChild($xmlDoc->createElement("startDate",validate($startDate_02,'formatdate'))); }
            if (isset($endDate_02) && $endDate_02 != "NULL")     { $UA->appendChild($xmlDoc->createElement("end_date",validate($endDate_02,'formatdate'))); }
        
            // Documentation comment in v2.0 XSD states address_types element is required.  Of course it doesn't have a minOccur="1"???
            $UTS = $UA->appendChild($xmlDoc->createElement('address_types')); // Parent Element
            if (isset($address_type_02) && $address_type_02 != "NULL") {
                if ($address_type_02 == "1") {
                    $createATTR = $UTS->appendChild($xmlDoc->createElement("address_type","home"));
                    $x = $xmlDoc->createAttribute('desc');
                    $x->value = "Home";
                }
                if ($address_type_02 == "2") {
                    $createATTR = $UTS->appendChild($xmlDoc->createElement("address_type","school"));
                    $x = $xmlDoc->createAttribute('desc');
                    $x->value = "School";
                }
                $createATTR->appendChild($x);
                $UTS->appendChild($createATTR);
            }
        }
        
        // Assemble userEmail
        if ((isset($address_type_02) && $address_type_02 == "3") OR (isset($address_type_03) && $address_type_03 == "3")) {
            $UES = $CI->appendChild($xmlDoc->createElement('emails')); // emails Parent/Child Element
            $UE  = $UES->appendChild($xmlDoc->createElement('email')); // email Parent/Child Element
            $x = $xmlDoc->createAttribute('preferred');
            $x->value = "true";
            $UE->appendChild($x);
            $x = $xmlDoc->createAttribute('segment_type');
            $x->value = "External";
            $UE->appendChild($x);
            if (isset($address_type_02) && $address_type_02 == "3") {
                if (isset($line1_02) && $line1_02 != "NULL")   {$UE->appendChild($xmlDoc->createElement("email_address",$line1_02));}
            }
            if (isset($address_type_03) && $address_type_03 == "3") {
                if (isset($line1_03) && $line1_03 != "NULL")   {$UE->appendChild($xmlDoc->createElement("email_address",$line1_03));}
            }
            $TYUE = $UE->appendChild($xmlDoc->createElement('email_types')); // Parent Elementdescription
            $createATTR = $TYUE->appendChild($xmlDoc->createElement("email_type","school"));
            $x = $xmlDoc->createAttribute('desc');
            $x->value = "School";
            $createATTR->appendChild($x);
            $TYUE->appendChild($createATTR);
        }
    
        // assemble User Phone
        if ((isset($phone_01) && $phone_01 != "NULL")  OR (isset($phone_02) && $phone_02 != "NULL") OR (isset($phone_03) && $phone_03 != "NULL")) {
            $UPS = $CI->appendChild($xmlDoc->createElement('phones')); // phones Parent/Child Element
        
            if ((isset($phone_01) && $phone_01 != "NULL")) {
                $UP = $UPS->appendChild($xmlDoc->createElement('phone')); // phone Parent/Child Element
                $x = $xmlDoc->createAttribute('preferred');
                $x->value = "true";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('preferred_sms');
                $x->value = "false";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('segment_type');
                $x->value = "External";
                $UP->appendChild($x);
                $UP->appendChild($xmlDoc->createElement('phone_number',$phone_01));
                $TUP = $UP->appendChild($xmlDoc->createElement('phone_types')); // Parent/Child Element
                $createATTR = $TUP->appendChild($xmlDoc->createElement('phone_type','home'));
                $x = $xmlDoc->createAttribute('desc');
                $x->value = "Home";
                $createATTR->appendChild($x);
                $TUP->appendChild($createATTR);
            }
        
            if ((isset($address_type_02) && $address_type_02 == "2")) {
                $UP = $UPS->appendChild($xmlDoc->createElement('phone')); // phone Parent/Child Element
                $x = $xmlDoc->createAttribute('preferred');
                $x->value = "true";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('preferred_sms');
                $x->value = "false";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('segment_type');
                $x->value = "External";
                $UP->appendChild($x);
                $UP->appendChild($xmlDoc->createElement('phone_number',$phone_02));
                $TUP = $UP->appendChild($xmlDoc->createElement('phone_types')); // Parent/Child Element
                $createATTR = $TUP->appendChild($xmlDoc->createElement('phone_type','home'));
                $x = $xmlDoc->createAttribute('desc');
                $x->value = "Home";
                $createATTR->appendChild($x);
                $TUP->appendChild($createATTR);
            }
        
            if ((isset($address_type_03) && $address_type_03 == "2")) {
                $UP = $UPS->appendChild($xmlDoc->createElement('phone')); // phone Parent/Child Element
                $x = $xmlDoc->createAttribute('preferred');
                $x->value = "true";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('preferred_sms');
                $x->value = "false";
                $UP->appendChild($x);
                $x = $xmlDoc->createAttribute('segment_type');
                $x->value = "External";
                $UP->appendChild($x);
                $UP->appendChild($xmlDoc->createElement('phone_number',$phone_03));
                $TUP = $UP->appendChild($xmlDoc->createElement('phone_types')); // Parent/Child Element
                $createATTR = $TUP->appendChild($xmlDoc->createElement('phone_type','home'));
                $x = $xmlDoc->createAttribute('desc');
                $x->value = "Home";
                $createATTR->appendChild($x);
                $TUP->appendChild($createATTR);
            }
        }
    } 
    
    // Assemble userIdentifiers
    if ((isset($userIdentifiers) && $userIdentifiers != "NULL") | (isset($patron_barcode_1) && $patron_barcode_1 != "NULL")) {
        $UIS  = $UR->appendChild($xmlDoc->createElement('user_identifiers')); // Parent Element
      // Barcode Identifier
        if (isset($patron_barcode_1) && $patron_barcode_1 != "NULL") {
            $UI = $UIS->appendChild($xmlDoc->createElement('user_identifier')); // Parent Element
            $x = $xmlDoc->createAttribute('segment_type');
            $x->value = "External";
            $UI->appendChild($x);
            
            $createATTR = $UI->appendChild($xmlDoc->createElement("id_type","BARCODE"));
            $x = $xmlDoc->createAttribute('desc');
            $x->value = "Barcode";
            $createATTR->appendChild($x);
            $UI->appendChild($createATTR);
            
            $UI->appendChild($xmlDoc->createElement("value","$patron_barcode_1"));
            $UI->appendChild($xmlDoc->createElement("status","ACTIVE"));
            
        }
        /*
        elseif ($patron_barcode_1 = "NULL") {
            $UI = $UIS->appendChild($xmlDoc->createElement('user_identifier'));
            $x = $xmlDoc->createAttribute('segment_type');
            $x->value = "Internal";
            $UI->appendChild($x);
            
            $createATTR = $UI->appendChild($xmlDoc->createElement("id_type","BARCODE"));
            $x = $xmlDoc->createAttribute('desc');
            $x->value = "Barcode";
            $createATTR->appendChild($x);
            $UI->appendChild($createATTR);
            $UI->appendChild($xmlDoc->createElement("value"));
        }
         * 
         */
            
        if (isset($userIdentifiers) && $userIdentifiers != "NULL") {
            $createATTR = $UI = $UIS->appendChild($xmlDoc->createElement('user_identifier')); // Parent Element
            $x = $xmlDoc->createAttribute('segment_type');
            $x->value = "External";
            $UI->appendChild($x);
            
            $createATTR = $UI->appendChild($xmlDoc->createElement("id_type","INST_ID"));
            $x = $xmlDoc->createAttribute('desc');
            $x->value = "NetID";
            $createATTR->appendChild($x);
            $UI->appendChild($createATTR);
            $UI->appendChild($xmlDoc->createElement("value",validate($userIdentifiers,'userIdentifiers')));
        }
    }    

    if (isset($statisticalCategory) && $statisticalCategory != "NULL") {
        $USCL  = $UR->appendChild($xmlDoc->createElement('user_statistics')); // Parent/Child Element
        $UC    = $USCL->appendChild($xmlDoc->createElement('user_statistic')); // Parent/Child Element
        $UC->appendChild($xmlDoc->createElement("statistic_category",$statisticalCategory));
        $x = $xmlDoc->createAttribute('desc');
        $x->value = $statisticalCategory;  // Need correct code from Ex Libres
        $UI->appendChild($x);
    }
   } // while (($row = fgetcsv($inputFile)) !== FALSE) {

    $xmlString  = $xmlDoc->savexml();
    //$fileName .= date('Ymd-gis') . ".xml";
    $fileName .= "." . date('m_d_y') . ".xml";
    echo "fileName=$fileName<br />";
    //echo 'target path =' . $target_path;
    try {	  	
        if (!fopen($fileName, "w")) {
            throw new Exception("Cannot Create, or Append to, specified file.  Contact programming support!");
	  	
	}
	$xmlHandle = fopen($fileName, "w");
    } // try
    catch(Exception $e) {
  	exitRoutine($e->getMessage(),$output,'error');
    //echo 'Message: ' .$e->getMessage();
  } // catch
  
  //$xmlHandle = fopen($fileName, "w");

  $fwrite = TRUE;
	//fwrite($xmlHandle, $xmlString);
	
  try   {	  	
	  setOutputFile($xmlHandle, $xmlString);
	   //$output = exitRoutine("File has been converted successfully and output has been placed on c: drive.",$output,'');
	 } // try
    catch(Exception $e) {
  	exitRoutine($e->getMessage(),$output,'error');
    //echo 'Message: ' .$e->getMessage();
  } // catch
	  
  fclose($xmlHandle);
  echo "Total input records  =$counter <br />";
  echo "Total errors records =$errCtr <br />";
} // if ($processFile) {



// validate handles verifying and manipulation of fields being processing
function validate($data, $type) {
    
  if ($type=="zipcode") {
    if (strlen($data)<=0) {
    	$data = "error";
    }
    elseif (strlen($data)==5 AND is_numeric($data));
    elseif (strlen($data)==9 AND is_numeric($data)) {
        $data = substr($data,0,5) . '-' . substr($data,5,4);
    }
    elseif (strlen($data)>5 AND is_numeric(substr($data,0,5))) {
        $data = substr($data,0,5);
    }
    else {
    	$data = "error";
    }
  }
  if ($type=="userIdentifiers") {
    $pos = strpos($data, '@');
    if ($pos > 0) $data = substr($data,0,$pos);
  }
  
  if ($type=="formatdate") {
      // . (dot) indicates to strtotime a European d-m-y format is to be used.  This is not desired in this case.
      $cdata = str_replace('.','-',$data);
      $data  = date('Y-m-d', strtotime($cdata));
  }
      
  // echo "return data=$data<br />";
  return $data;
} // function validate() {

function setOutputFile($xmlHandle, $xmlString) {
	if (!$fwrite = fwrite($xmlHandle, $xmlString)) throw new Exception("Error creating output file!  Notify support!");
	return($fwrite);	
} // function setOutputFile() {

function createDownload($fn) {
	
}

function exitRoutine($message,$output,$type='normal') {
	
  if ($type == "error") $class = "failure";
    else                $class = "success";
  
  
  if ($type == "error") {

    echo 'Error Detected; Program is stopping';
    die();
  }
} // 

?>
