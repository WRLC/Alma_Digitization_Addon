<?php
// debug/error logging goes to web server error.log
#define('DEBUG_LOG', false);
define('DEBUG_LOG', true);

// settings for the premium sandboxes:
$izSettings = array (
    # indexed by Alma Institution Code
    '01WRLC_AMU' => array (
        'name' => 'American University PSB',
        'apikey' => 'l8xx490ac6d3da044e259775ccfa596c4960',
    ),
    '01WRLC_DOC' => array (
        'name' => 'University of the District of Columbia PSB',
        'apikey' => 'l8xx0c7b6e1ac14048a48a421c4f18972225',
    ),
    '01WRLC_GML' => array (
        'name' => 'George Mason University PSB',
        'apikey' => 'l8xx730fe0baed3d4576a50dbb763dc8ab9a',
    ),
    '01WRLC_GUNIV' => array (
        'name' => 'Georgetown University PSB',
        'apikey' => 'l8xxf5a6d76d039c4ef4bb7746fae34d5e3e',
    ),
    '01WRLC_GWA' => array (
        'name' => 'George Washington University PSB',
        'apikey' => 'l8xxe4f13def9deb42ddb84edec49e0e5f46',
    ),
);

$apiSettings = array (
    'defaultURL' => 'https://api-na.hosted.exlibrisgroup.com/',
);

// ILLiad Client Secret - should only be shared with authorized ILLiad clients
// from https://passwordsgenerator.net/ - 16 lowercase & number characters
define('ILLIAD_CLIENT_SECRET', 'frhq7sm5cwrnhc4d');
?>
