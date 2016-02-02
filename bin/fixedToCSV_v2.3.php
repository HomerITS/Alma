<?php
//file_get_contents('ssh2.sftp://libhomer:VUNvbm4gRGFpcnkg@glue3.uits.uconn.edu:22/home/libhomer/dropbox');
require_once('includes/fixedToCSVModel.php');
try
{
    /*** the fixed width file to convert ***/
    $today = date('m_d_y');
    echo '/data/websites/MINES_Survey/proxyfiles/data/patload.' . $today . '.txt';
    //$file = new fixed2CSV( '/data/websites/MINES_Survey/proxyfiles/data/patload.' . $today);
    $file = new fixed2CSV( '/usr/local/data/patload.' . $today . ".sif");

    /*** The start position=>width of each field ***/
    // The below $file->fields = array() implements the magic method __set method
    // to set the array.  Not sure why not create function to set array?  Would be more efficient, but not as cool?
    $csvHeaderRecord =
          /* 01 */   "\"patron_id\"," .                // Not used, always x values - length 1
          /* 02 */   "\"patron_barcode_id_1\"," .      // Not used, always 0 values - length 10
          /* 03 */   "\"patron_barcode_1\"," .         // Ask Janice, has values but don't see any library card info in XSLT
          /* 04 */   "\"user_group\"," .               // "v1.0-userDetails/patron_group_1" - minOccurs="0"  patron_group_1
          /* 05 */   "\"status\"," .                   // "v1.0-userDetails/status" - minOccurs="1" - barcode_status_1 - *** verify w/Janice
          /* 06 */   "\"barcode_modified_date_1\"," .  // Not used, Only populated when "expired" and value the same as patron expiration date
          /* 07 */   "\"patron_barcode_id_2\"," .      // Not used, always 0 values - length 10
          /* 08 */   "\"patron_barcode_2\"," .         // Not used, always NULL - length 25
          /* 09 */   "\"patron_group_2\"," .           // Not used, always 0 values - length 10
          /* 10 */   "\"barcode_status_2\"," .         // Not used, always 0 value - length 1
          /* 11 */   "\"barcode_modified_date_2\"," .  // Not used, always NULL values - length 10
          /* 12 */   "\"patron_barcode_id_3\"," .      // Not used, always 0 values - length 10
          /* 13 */   "\"patron_barcode_3\"," .         // Not used, always NULL values - length 25
          /* 14 */   "\"patron_group_3\"," .           // Not used, always NULL values - length 10
          /* 15 */   "\"barcode_status_3\"," .         // Not used, always 0 value - length 1
          /* 16 */   "\"barcode_modified_date_3\"," .  // Not used, always NULL values - length 10
          /* 17 */   "\"creationDate\"," .             // owneredEntity - minOccurs="0" - registration_date *** verify w/Janice 
          /* 18 */   "\"expiry_date\"," .              // "v1.0-userDetails/expiryDate" - minOccurs="0" - patron_expiration_date
          /* 19 */   "\"patron_purge_date\"," .        // Not used, seems to contain invalid data -- always 12014.12.31 in SIF
          /* 20 */   "\"voyager_date\"," .             // Not used, always NULL values - length 10
          /* 21 */   "\"voyager_updated\"," .          // Not used, always NULL values - length 10
          /* 22 */   "\"circulation_happening_location_code\"," .  // Not used, always NULL values - length 10
          /* 23 */   "\"userIdentifiers\"," .          // // "v1.0-userIdentifiers/userIdentifier" - minOccurs="0" - institution_ID *** per Janice, strip @uconn.edu.
                     // below is a 9-digit value in the SSN position in the Voyager patron SIF.
          /* 24 */   "\"primary_id\"," .               // "v1.0-userDetails/userName" - minOccurs="0" - ssn
          /* 25 */   "\"statistic_category\"," .      // "v1.0-userStatisticalCategoriesList/userCategory/statisticalCategory" - minOccurs="0" - statistical_category_1.
          /* 26 */   "\"statistical_category_2\"," .   // Not used, always NULL values - length 3
          /* 27 */   "\"statistical_category_3\"," .   // Not used, always NULL values - length 3
          /* 28 */   "\"statistical_category_4\"," .   // Not used, always NULL values - length 3
          /* 29 */   "\"statistical_category_5\"," .   // Not used, always NULL values - length 3
          /* 30 */   "\"statistical_category_6\"," .   // Not used, always NULL values - length 3
          /* 31 */   "\"statistical_category_7\"," .   // Not used, always NULL values - length 3
          /* 32 */   "\"statistical_category_8\"," .   // Not used, always NULL values - length 3
          /* 33 */   "\"statistical_category_9\"," .   // Not used, always NULL values - length 3
          /* 34 */   "\"statistical_category_10\"," .  // Not used, always NULL values - length 3
          /* 35 */   "\"name_type\"," .                // Not used, but can be tested for personal(1) or organization(2)
          /* 36 */   "\"last_name\"," .                // "v1.0-userDetails/lastName" - minOccurs="0" - surname[SIF]
          /* 37 */   "\"first_name\"," .               // "v1.0-userDetails/firstName" - minOccurs="0" - first_name
          /* 38 */   "\"middle_name\"," .              // "v1.0-userDetails/middleName" - minOccurs="0"- middle_name
          /* 39 */   "\"title\"," .                    // This should be append 
          /* 40 */   "\"historical_charges\"," .       // Not used, always 0 value - length 10
          /* 41 */   "\"claims_returned_count\"," .    // Not used, always 0 value - length 5
          /* 42 */   "\"self_shelved_count\"," .       // Not used, always 0 value - length 5
          /* 43 */   "\"lost_items_count\"," .         // Not used, always 0 value - length 5
          /* 44 */   "\"late_media_returns\"," .       // Not used, always 0 value - length 5
          /* 45 */   "\"historical_bookings\"," .      // Not used, always 0 value - length 5
          /* 46 */   "\"canceled_bookings\"," .        // Not used, always 0 value - length 5
          /* 47 */   "\"unclaimed_bookings\"," .       // Not used, always 0 value - length 5
          /* 48 */   "\"historical_callslips\"," .     // Not used, always 0 value - length 5
          /* 49 */   "\"historical_distributions\"," . // Not used, always 0 value - length 5
          /* 50 */   "\"historical_short_loans\"," .   // Not used, always 0 value - length 5
          /* 51 */   "\"unclaimed_short_loans\"," .    // Not used, always 0 value - length 5
          /* 52 */   "\"address_count\"," .            // Not used, but can be test how many address blocks (1-9)
            
            
          /* 53 */   "\"address_id_01\"," .            // Not used, always 0 value - length 10
          /* 54 */   "\"address_type_01\"," .          // userAddress - add attribute name="preferred" if value is 1
          /* 55 */   "\"address_status_code_01\"," .
          /* 56 */   "\"startDate_01\"," .             // userAddressList/userAddress - minOccurs="0" -address_begin_date_01
          /* 57 */   "\"endDate_01\"," .               // userAddressList/userAddress - minOccurs="0" - address_end_date_01
          /* 58 */   "\"line1_01\"," .                 // userAddressList/userAddress - minOccurs="0" - address_line_1_01
          /* 59 */   "\"line2_01\"," .                 // userAddressList/userAddress - minOccurs="0" - address_line_2_01   
          /* 60 */   "\"line3_01\"," .                 // userAddressList/userAddress - minOccurs="0" - address_line_3_01
          /* 61 */   "\"line4_01\"," .                 // userAddressList/userAddress - minOccurs="0" - address_line_4_01
          /* 62 */   "\"line5_01\"," .                 // userAddressList/userAddress - minOccurs="0" - address_line_5_01
          /* 63 */   "\"city_01\"," .                  // userAddressList/userAddress - minOccurs="0" - city_01
          /* 64 */   "\"stateProvince_01\"," .         // userAddressList/userAddress - minOccurs="0" - state_province_code_01
          /* 65 */   "\"postalCode_01\"," .            // userAddressList/userAddress - minOccurs="0" - zipcode_postal_code_01
          /* 66 */   "\"country_01\"," .               // Not used, always NULL values - length 20
          /* 67 */   "\"phone_01\"," .                 // userAddressList/userPhone - minOccurs="0" - phone_primary_01
                                                       // need to add userPhone/types/userPhoneTypes
          /* 68 */   "\"mobil_01\"," .                 // Not used, always NULL values - length 25
          /* 69 */   "\"fax_01\"," .                   // Not used, always NULL values - length 25
          /* 70 */   "\"other_01\"," .                 // Not used, always NULL values - length 25
          /* 71 */   "\"date_added_updated_01\"," .    // Not used, always NULL values - length 10
            
            
          /* 72 */   "\"address_id_02\"," .
          /* 73 */   "\"address_type_02\"," .
          /* 74 */   "\"address_status_code_02\"," .
          /* 75 */   "\"startDate_02\"," .
          /* 76 */   "\"endDate_02\"," .
          /* 77 */   "\"line1_02\"," .
          /* 77 */   "\"line2_02\"," .
          /* 78 */   "\"line3_02\"," .
          /* 80 */   "\"line4_02\"," .
          /* 81 */   "\"line5_02\"," .
          /* 82 */   "\"city_02\"," .
          /* 83 */   "\"stateProvince_02\"," .
          /* 84 */   "\"postalCode_02\"," .
          /* 85 */   "\"country_02\"," .
          /* 86 */   "\"phone_02\"," .
          /* 87 */   "\"mobil_02\"," .
          /* 88 */   "\"fax_02\"," .
          /* 89 */   "\"other_02\"," .
          /* 90 */   "\"date_added_updated_02\"," .
            
            
          /* 91 */   "\"address_id_03\"," .
          /* 92 */   "\"address_type_03\"," .
          /* 93 */   "\"address_status_code_03\"," .
          /* 94 */   "\"startDate_03\"," .
          /* 95 */   "\"endDate_03\"," .
          /* 96 */   "\"line1_03\"," .
          /* 97 */   "\"line2_03\"," .
          /* 98 */   "\"line3_03\"," .
          /* 99 */   "\"line4_03\"," .
          /* 100 */  "\"line5_03\"," .
          /* 101*/   "\"city_03\"," .
          /* 102 */  "\"stateProvince_03\"," .
          /* 103 */  "\"postalCode_03\"," .
          /* 104 */  "\"country_03\"," .
          /* 105 */  "\"phone_03\"," .
          /* 106 */  "\"mobil_03\"," .
          /* 107 */  "\"fax_03\"," .
          /* 108 */  "\"other_03\"," .
          /* 109 */  "\"date_added_updated_03\"";

    
    $outfile = new SplFileObject('/data/websites/MINES_Survey/proxyfiles/data/csvoutput.txt', "w");
    //echo "csvHeaderRecord-><br />".$csvHeaderRecord."<br/><br/>";
    $written = $outfile->fwrite($csvHeaderRecord."\n");
    
    $file->fields = array(
        1=>10,    // patron id
        11=>10,   // patron barcode id 1
        21=>25,   // patron barcode 1
        46=>10,   // patron group 1
        56=>1,    // barcode status 1
        57=>10,   // barcode modified date 1
        67=>10,   // patron barcode id 2
        77=>25,   // patron barcode 2
        102=>10,  // patron group 2
        112=>1,   // barcode status 2
        113=>10,  // barcode modified date 2
        123=>10,  // patron barcode
        133=>25,  // patron barcode 3
        158=>10,  // patron group 3
        168=>1,   // barcode status 3
        169=>10,  // barcode modified date 3
        179=>10,  // registration date
        189=>10,  // patron expiration date
        199=>10,  // patron purge date
        209=>10,  // voyager date
        219=>10,  // voyager updated
        229=>10,  // circulation happening location code
        239=>30,  // institution ID
        269=>11,  // ssn
        280=>3,   // statistical category 1
        283=>3,   // statistical category 2
        286=>3,   // statistical category 3
        289=>3,   // statistical category 4
        292=>3,   // statistical category 5
        295=>3,   // statistical category 6
        298=>3,   // statistical category 7
        301=>3,   // statistical category 8
        304=>3,   // statistical category 9
        307=>3,   // statistical category 10
        310=>1,   // name type
        311=>30,  // surname
        341=>20,  // first name
        361=>20,  // middle name
        381=>10,  // title
        391=>10,  // historical charges
        401=>5,   // claims returned count
        406=>5,   // self-shelved count
        411=>5,   // lost items count
        416=>5,   // late media returns
        421=>5,   // historical bookings
        426=>5,   // canceled bookings
        431=>5,   // unclaimed bookings
        436=>5,   // historical callslips
        441=>5,   // historical distributions
        446=>5,   // historical short loans
        451=>5,   // unclaimed short loans
        
        /* Count of how many address field should be expected.  
         * Between 1 and 9 address fields are expected.
         */
        456=>1,   // address count
        /* Address fields 1 */
        
        457=>10,  // address id
        467=>1,   // address type
        468=>1,   // address status code
        469=>10,  // address begin date
        479=>10,  // address end date
        489=>50,  // address line 1
        539=>40,  // address line 2
        579=>40,  // address line 3
        619=>40,  // address line 4
        659=>40,  // address line 5
        699=>40,  // city
        739=>7,   // state (province) code
        746=>10,  // zipcode/postal code
        756=>20,  // country
        776=>25,  // phone (primary)
        801=>25,  // phone (mobile)
        826=>25,  // phone (fax)
        851=>25,  // phone (other)
        876=>10,  // date added/updated
        
        /* Address fields 2 */
        886=>10,   // address id
        896=>1,    // address type
        897=>1,    // address status code
        898=>10,   // address begin date
        908=>10,   // address end date
        918=>50,   // address line 1
        968=>40,   // address line 2
        1008=>40,  // address line 3
        1048=>40,  // address line 4
        1088=>40,  // address line 5
        1128=>40,  // city
        1168=>7,   // state (province) code
        1175=>10,  // zipcode/postal code
        1185=>20,  // country
        1205=>25,  // phone (primary)
        1230=>25,  // phone (mobile)
        1255=>25,  // phone (fax)
        1280=>25,  // phone (other)
        1305=>10,  // date added/updated
        
        /* Address fields 3 */
        1315=>10,  // address id
        1325=>1,   // address type
        1326=>1,   // address status code
        1327=>10,  // address begin date
        1337=>10,  // address end date
        1347=>50,  // address line 1
        1397=>40,  // address line 2
        1437=>40,  // address line 3
        1477=>40,  // address line 4
        1517=>40,  // address line 5
        1557=>40,  // city
        1597=>7,   // state (province) code
        1604=>10,  // zipcode/postal code
        1614=>20,  // country
        1634=>25,  // phone (primary)
        1659=>25,  // phone (mobile)
        1684=>25,  // phone (fax)
        1709=>25,  // phone (other)
        1734=>10   // date added/updated

        );

    
    //echo "processing line<br />";
    /*** output the converted lines ***/
    //$outfile = new SplFileObject('../proxyfiles/data/csvoutput.txt', "w");
    foreach( $file as $line )
    {
        //echo "line-><br />".$line."<br/><br/>";
        $line = stripInvalidXml($line);
        $written = $outfile->fwrite($line);
    }

    /*** a new instance ***/
    //$new = new fixed2CSV( '../proxyfiles/data/patload_20150126.txt' );

    /*** get only first and third fields ***/
    //$new->fields = array(0=>10, 25=>20);

    /*** output only the first and third fields ***/
    //foreach( $new as $line )
    //{
    //    echo $line;
    //}
  $file = null; // close out file.
  include 'VoyagerCSVtoXML_v2.3.php';
}

catch( Exception $e )
{
    echo $e->getMessage();
}

function stripInvalidXml( $value ) {
    $ret = "";
    $current;
    if (empty( $value )) :
        return $ret;
    endif;
    $length = strlen( $value );
    for ($i=0; $i < $length; $i++) :
        
        $current = ord( $value{ $i });
        if ((( $current >= 32 ) && ( $current <= 126 ))  ||
            (( $current >= 161 ) && ( $current <= 255 )) ||
             ( $current == 10) ) :
            //echo "keeping current=" . $current . "<br />";
            $ret .= chr( $current );
        else :
            //$ret .= " ";
            $space = ' ';
            $ret .= chr( $space );
        endif;
        
    endfor;
    $converted_utf8_value = utf8_encode ( $ret );
    // return $ret;
    return $converted_utf8_value;
} // function stripInvalidXml

?>