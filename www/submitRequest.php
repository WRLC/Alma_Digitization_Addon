<?php
    require_once 'utils/config.php';
    require_once 'utils/jsonapi.php';

    // check shared secret credential
    if (isset($_POST["illiadCS"])) {
        if (ILLIAD_CLIENT_SECRET != $_POST["illiadCS"]) {
            http_response_code(403);
            exit;
        }
    } else {
        http_response_code(400);
        exit;
    }

    // Loads variables from form
    $instCode = $_POST["instCode"];
    $apiKey = $izSettings[$instCode]['apikey'];
    $usrId = $_POST["usrId"];
    $itemId = preg_replace("/[^0-9]/", "", $_POST["itemId"]); // Strips accidental non-numeric characters from itemId
    $mmsId = preg_replace("/[^0-9]/", "", $_POST["mmsId"]); // Strips accidental non-numeric characters from mmsId
    $aTitle = $_POST["aTitle"];
    $aAuthor = $_POST["aAuthor"];
    $start = $_POST["start"];
    $end = $_POST["end"];
    $comment = '';
    $regionalURL = $_POST["regionalURL"];

    // Page number validation
    if ($start == 0 AND $end == 0) {
        $comment = 'Page range is unknown';
        $start = 1; $end = 1;
    } else if ($start == 0 OR $end == 0 OR $start > $end) {
        $comment = "Original request had invalid page range: $start - $end";
        $start = 1; $end = 1;
    }

    $emptyResponse = array (
        "request_id" => '',
        "title" => '',
        "chapter_or_article_title" => '',
        "chapter_or_article_author" => '',
    );

    $requestArray = array (
        'user_primary_id' => $usrId,
        'request_id' => '',
        'request_type' => 'DIGITIZATION',
        'request_sub_type' => array (
            'desc' => 'Digitization Request',
            'value' => 'PATRON_DIGITIZATION',
        ),
        'item_id' => $itemId,
        'target_destination' => array (
            'desc' => 'string',
            'value' => 'DIGI_DEPT_INST',
        ),
        'partial_digitization' => true,
        'chapter_or_article_title' => $aTitle,
        'chapter_or_article_author' =>$aAuthor,
        'required_pages_range' => array (
            0 => array (
                'from_page' => $start,
                'to_page' => $end,
            ),
        ),
        'comment' => $comment,
        'copyrights_declaration_signed_by_patron' => true,
    );
    $requestJSON = json_encode($requestArray);

    $fetch_url = $regionalURL . "almaws/v1/users/$usrId/requests?mms_id=$mmsId&item_pid=$itemId&apiKey=";
    DEBUG_LOG? error_log( "ADDON DEBUG: POST $fetch_url\n$requestJSON" ) : true;
    $fetch_url .= $apiKey;

    // Initialize cURL session
    $curl = getCurlSession( $fetch_url );
    // Set cURL options for request API
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
		'accept: application/json',
        'Content-Length: ' . strlen($requestJSON))
    );
    // execute cURL command to create request
#    $apiResponse = apiPost($apiKey,"$fetch_url&apiKey=$apiKey",$requestJSON);
    list ($code, $apiResponse) = sendCurlRequest( $curl, $requestJSON );
    DEBUG_LOG? error_log( "ADDON DEBUG: RESPONSE - $code\n$apiResponse" ) : true;

    // Close cURL session
    curl_close($curl);

    if ($code == 200) {
        // Success!
        $responseArray = json_decode($apiResponse, true);
        $error_rpt = '';
    } else {
        // Error response, log and report it
        $error_rpt = "<pre>\n**************** HTTP Error Response: $code ****************\n";
        if (substr( $apiResponse, 0,5 ) == '<?xml') {
            // XML is returned regardless for some errors, like invalid apiKey
            $errxml = simplexml_load_string($apiResponse);
            #var_dump( $errxml );
            if (isset($errxml->errorsExist) && $errxml->errorsExist == 'true') {
                foreach ($errxml->errorList->error as $error) {
                    $errmsg = $error->errorCode." - ".$error->errorMessage;
                    error_log( "ADDON ERROR: $errmsg" );
                    $error_rpt .= $errmsg."\n";
                }
            } else {
                error_log( "ADDON ERROR: API response $code\n$apiResponse" );
                $error_rpt .= "check web error log for messages\n";
            }
        } else if ($errjson = json_decode($apiResponse, true)) {
            // ToDo: handle data errors (like 401129) better
            //      401129 - No items can fulfill the submitted request.
            // https://developers.exlibrisgroup.com/alma/apis/docs/users/UE9TVCAvYWxtYXdzL3YxL3VzZXJzL3t1c2VyX2lkfS9yZXF1ZXN0cw==/#errorCodes
            if (isset($errjson["errorsExist"]) && $errjson["errorsExist"] == 'true') {
                foreach ($errjson["errorList"]["error"] as $error) {
                    $errmsg = $error["errorCode"]." - ".$error["errorMessage"];
                    error_log( "ADDON ERROR: $errmsg" );
                    $error_rpt .= $errmsg."\n";
                }
            } else {
                error_log( "ADDON ERROR: API response $code\n$apiResponse" );
                $error_rpt .= "check web error log for messages\n";
            }
        } else {
            list ($err, $msg) = jsonError( json_last_error() );
            error_log( "ADDON ERROR: JSON error $err $msg" );
            error_log( "ADDON ERROR: API response $code\n$apiResponse" );
            $error_rpt .= "check web error log for messages\n";
        }
        $error_rpt .= "**********************************************************";
        $error_rpt .= "        </pre>\n";
        $responseArray = $emptyResponse;
    }
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Scan Request Submitting</title>
		<script src="utils/utils.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="default.css">	
		<script>
			$( "#requestForm" ).controlgroup({
			"direction": "vertical",
			});
		</script>
	</head>
	<body>
        <?php echo $error_rpt; ?>
		<div class="widget" id="form" width="100%">
			<form method="POST" name="htmlForm" action="submitRequest.php" id="requestForm">
					
					<legend id="requestFormHeader" class="ui-widget-header">The following request has been submitted:</legend>
					<br>
					
					<label for="reqId">Request ID:</label>
					<input type="text" id="reqId" name="reqId" value="<?php echo $responseArray["request_id"]; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<br><br>
					
					<label for="bTitle">Book Title:</label>
					<input type="text" id="bTitle" name="bTitle" value="<?php echo $responseArray["title"]; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<br><br>
					
					<label for="aTitle">Chapter Title:</label>
					<input type="text" id="aTitle" name="aTitle" value="<?php echo $responseArray["chapter_or_article_title"]; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<br><br>
					
					<label for="aAuthor">Chapter Author:</label>
					<input type="text" id="aAuthor" name="aAuthor" value="<?php echo $responseArray["chapter_or_article_author"]; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<br><br>
			</form>
		</div>
	</body>
</html>
