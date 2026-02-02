<?php
// debug/error logging goes to web server error.log
#define('DEBUG_LOG', false);
define('DEBUG_LOG', true);

// settings for each Institution Zone
$izSettings = array (
    # indexed by Alma Institution Code
    '01WRLC_AMU' => array (
        'name' => 'American University',
        'apikey' => $_ENV['AU_API_KEY'],
    ),
    '01WRLC_CAA' => array (
        'name' => 'Catholic University',
        'apikey' => $_ENV['CU_API_KEY'],
    ),
    '01WRLC_DOC' => array (
        'name' => 'University of DC',
        'apikey' => $_ENV['DC_API_KEY'],
    ),
    '01WRLC_GAL' => array (
        'name' => 'Gallaudet University',
        'apikey' => $_ENV['GA_API_KEY'],
    ),
    '01WRLC_GML' => array (
        'name' => 'George Mason University',
        'apikey' => $_ENV['GM_API_KEY'],
    ),
    '01WRLC_GUNIV' => array (
        'name' => 'Georgetown University',
        'apikey' => $_ENV['GT_API_KEY'],
    ),
    '01WRLC_GWA' => array (
        'name' => 'George Washington University',
        'apikey' => $_ENV['GW_API_KEY'],
    ),
    '01WRLC_GWAHLTH' => array (
        'name' => 'GW Health Himmelfarb Library',
        'apikey' => $_ENV['GWHH_API_KEY'],
    ),
    '01WRLC_HOW' => array (
        'name' => 'Howard University',
        'apikey' => $_ENV['HU_API_KEY'],
    ),
    '01WRLC_MAR' => array (
        'name' => 'Marymount University',
        'apikey' => $_ENV['MU_API_KEY'],
    ),
);

$apiSettings = array (
    'defaultURL' => 'https://api-na.hosted.exlibrisgroup.com/',
);

// ILLiad Client Secret - should only be shared with authorized ILLiad clients
//      no URL encoding/decoding is done, avoid special & non-ASCII characters
// from https://passwordsgenerator.net/ - 16 lowercase & number characters
define('ILLIAD_CLIENT_SECRET', $_ENV['ILLIAD_SECRET']);
?>