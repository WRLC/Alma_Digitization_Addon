<?php
	require 'utils/utils.php';
	
	$instCode = $_GET["instCode"];
	$instName = getInstitutionName($instCode);
	
	$usrId = $_GET["usrId"];
	$itemId = preg_replace("/[^0-9]/", "", $_GET["itemId"]); // Strips accidental non-numeric characters from itemId
	$mmsId = preg_replace("/[^0-9]/", "", $_GET["mmsId"]); // Strips accidental non-numeric characters from mmsId
	
	$aTitle = $_GET["aTitle"];
	$aAuthor = $_GET["aAuthor"];
	
	$pageRange = $_GET["pageRange"]; //TODO: Implement Validation for Page Range Splitting
	
	$regionalURL = $_GET["regionalURL"];
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Scan Request Submitting</title>
		<script src="/utils/utils.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="default.css">		
		<script>
			$( function() {
			buildUI();
			updatePageRange("<?php echo $pageRange;?>");
			addEventListeners();
			pageValidate("#start");
			pageValidate("#end");
			});
		</script>
	</head>
	
	<body>
		
		<div class="widget" id="form" width="100%">
			<form method="POST" name="htmlForm" action="submitRequest.php" onsubmit="return validateForm()" id="requestForm">
					
					<legend id="requestFormHeader" class="ui-widget-header">Request Form</legend>
					
					<label for="instCode">Institution Code:</label>
					<input type="text" id="instCode" name="instCode" value="<?php echo $instCode; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<span class="error" id="instCodeValidation"></span>
					<br><br>
					
					<label for="instName">Institution Name:</label>
					<input type="text" id="instName" name="instName" value="<?php echo $instName; ?>" class="text ui-widget-content ui-corner-all" readonly>
					<span class="error" id="instNameValidation"></span>
					<br><br>
					
					<label for="usrId">User ID:</label>
					<input type="text" id="usrId" name="usrId" class="text ui-widget-content ui-corner-all" value="<?php echo $usrId; ?>" readonly>
					<span class="error" id="usrIdValidation"></span>
					<br><br>
					
					<label for="itemId">Item ID:</label>
					<input type="text" id="itemId" name="itemId" value="<?php echo $itemId; ?>" class="text ui-widget-content ui-corner-all">
					<span class="error" id="itemIdValidation"></span>
					<br><br>
					
					<label for="mmsId">MMS ID:</label>
					<input type="text" id="mmsId" name="mmsId" value="<?php echo $mmsId; ?>" class="text ui-widget-content ui-corner-all">
					<span class="error" id="mmsIdValidation"></span>
					<br><br>
					
					<label for="aTitle">Chapter Title:</label>
					<input type="text" id="aTitle" name="aTitle" value="<?php echo $aTitle; ?>" class="text ui-widget-content ui-corner-all">
					<span class="error" id="aTitleValidation"></span>
					<br><br>
					
					<label for="aAuthor">Chapter Author:</label>
					<input type="text" id="aAuthor" name="aAuthor" value="<?php echo $aAuthor; ?>" class="text ui-widget-content ui-corner-all">
					<span class="error" id="aAuthorValidation"></span>
					<br><br>
					
					<label for="start">Start Page:</label>
					<input type="text" id="start" name="start" value="" class="text ui-widget-content ui-corner-all">
					<span class="error" id="startValidation"></span>
					<br><br>
					
					<label for="end">End Page:</label>
					<input type="text" id="end" name="end" value="" class="text ui-widget-content ui-corner-all">
					<span class="error" id="endValidation"></span>
					<br><br>
					
					<input id="regionalURL" name="regionalURL" type="hidden" value="<?php echo $regionalURL ?>">
				<button id="send">Submit Request</button>
				
			</form>
		</div>
		
		<div id="loadingDialog" title="Submitting Request" hidden>
			<p>Request submitting to <?php echo $instName; ?>'s Alma</p>
			<p><div id="progressbar"></div></p>
		</div>
		
		<div id="contentHolder"></div>
	</body>
</html>											