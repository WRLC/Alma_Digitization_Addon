<?php
// debug/error logging goes to web server error.log. Test
#define('DEBUG_LOG', false);
define('DEBUG_LOG', true);

// settings for each Institution Zone
$izSettings = array (
    # indexed by Alma Institution Code
    '01WRLC_AMU' => array (
        'name' => 'American University',
        'apikey' => 'l8xxa322d4a380fa45d78d5b534c91f82df4',
    ),
    '01WRLC_CAA' => array (
        'name' => 'Catholic University',
        'apikey' => 'l8xxf7072ac0374a45e7af395871a47e5287',
    ),
    '01WRLC_DOC' => array (
        'name' => 'University of DC',
        'apikey' => 'l8xx3c28924347e646a38de9d8b555b7b3b2',
    ),
    '01WRLC_GAL' => array (
        'name' => 'Gallaudet University',
        'apikey' => 'l8xx2327b288d18846938ad7ee3acb2aca03',
    ),
    '01WRLC_GML' => array (
        'name' => 'George Mason University',
        'apikey' => 'l8xx730fe0baed3d4576a50dbb763dc8ab9a',
    ),
    '01WRLC_GUNIV' => array (
        'name' => 'Georgetown University',
        'apikey' => 'l8xxf5a6d76d039c4ef4bb7746fae34d5e3e',
    ),
    '01WRLC_GWA' => array (
        'name' => 'George Washington University',
        'apikey' => 'l8xxbd5a789eb4b743e88ee1a6708f91b887',
    ),
    '01WRLC_GWAHLTH' => array (
        'name' => 'GW Health Himmelfarb Library',
        'apikey' => 'l8xx856756ebabd64eec96c5c9a005edd6e9',
    ),
    '01WRLC_HOW' => array (
        'name' => 'Howard University',
        'apikey' => 'l8xx30e722eb3096478f8632a76267aaf272',
    ),
    '01WRLC_MAR' => array (
        'name' => 'Marymount University',
        'apikey' => 'l8xx082516ed236d4bc582c2a3f1b0bc3f9d',
    ),
);

$apiSettings = array (
    'defaultURL' => 'https://api-na.hosted.exlibrisgroup.com/',
);

// ILLiad Client Secret - should only be shared with authorized ILLiad clients
//      no URL encoding/decoding is done, avoid special & non-ASCII characters
// from https://passwordsgenerator.net/ - 16 lowercase & number characters
define('ILLIAD_CLIENT_SECRET', 'jphbghg2s6jcqb4h');
?>
