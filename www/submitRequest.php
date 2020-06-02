<?php
    require_once 'utils/config.php';
    require_once 'utils/utils.php';

    // check shared secret credential
    $illiadCS = $_POST["illiadCS"];
    if (isset($_POST["illiadCS"])) {
        if (ILLIAD_CLIENT_SECRET != $_GET["illiadCS"]) {
            http_response_code(403);
            exit;
        }
    } else {
        http_response_code(400);
        exit;
    }

    // Loads variables from form
    $apiKey = $izSettings[$instCode]['apikey'];
    $instCode = $_POST["instCode"];
    $usrId = $_POST["usrId"];
    $itemId = preg_replace("/[^0-9]/", "", $_POST["itemId"]); // Strips accidental non-numeric characters from itemId
    $mmsId = preg_replace("/[^0-9]/", "", $_POST["mmsId"]); // Strips accidental non-numeric characters from mmsId
    $aTitle = $_POST["aTitle"];
    $aAuthor = $_POST["aAuthor"];
    $start = $_POST["start"];
    $end = $_POST["end"];
    $regionalURL = $_POST["regionalURL"];

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
        'copyrights_declaration_signed_by_patron' => true,
    );
    $requestJSON = json_encode($requestArray);

    $fetch_url = $regionalURL . "almaws/v1/users/" . $usrId . "/requests?apiKey=" . $apiKey . "&mms_id=" . $mmsId . "&item_pid=" . $itemId;

    $apiResponse = apiPost($apiKey,$fetch_url,$requestJSON);
    $responseArray = json_decode($apiResponse, true);
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
