<?php
if (!isset($ICEcoder['root'])) {
	include("settings.php");
}

if (!$_SESSION['loggedIn']) {
	header("Location: ../");
}

// If we're just getting a branch, get that and set as the finalArray
$scanDir = $docRoot.$iceRoot;
$location = "";
if (isset($_GET['location'])) {
	echo '<div id="branch">';
	$location = str_replace("|","/",$_GET['location']);
}

$dirArray = $filesArray = $finalArray = array();
$finalArray = scanDir($scanDir.$location);
foreach($finalArray as $entry) {
	$canAdd = true;
	for ($i=0;$i<count($_SESSION['bannedFiles']);$i++) {
		if(strpos($entry,$_SESSION['bannedFiles'][$i])!==false) {$canAdd = false;}
	}
	if ($entry != "." && $entry != ".." && $canAdd) {
		is_dir($docRoot.$iceRoot.$location."/".$entry)
		? array_push($dirArray,$location."/".$entry)
		: array_push($filesArray,$location."/".$entry);
	}
}
natcasesort($dirArray);
natcasesort($filesArray);
$finalArray = array_merge($dirArray,$filesArray);

for ($i=0;$i<count($finalArray);$i++) {
	$fileFolderName = str_replace("\\","/",$finalArray[$i]);
	$type = is_dir($docRoot.$iceRoot.$fileFolderName) ? "folder" : "file";
	if ($type=="file") {
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
	}
	if ($i==0) {echo "<ul style=\"display: block\">\n";}
	if ($i==count($finalArray)-1 && isset($_GET['location'])) {
		echo "</ul>\n";
	}
	$type == "folder" ? $class = 'pft-directory' : $class = 'pft-file '.strtolower($ext);
	$loadParam = $type == "folder" ? "true" : "false";
	echo "<li class=\"".$class."\"><a nohref title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type','".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."')\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" onClick=\"if(!event.ctrlKey) {top.ICEcoder.openCloseDir(this,$loadParam)}\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".basename($fileFolderName)."</span> ";
	echo '<span style="color: #888; font-size: 8px" id="'.str_replace($docRoot,"",str_replace("/","|",$fileFolderName)).'_perms">';
	echo $serverType=="Linux" ? substr(sprintf('%o', fileperms($docRoot.$iceRoot.$fileFolderName)), -3) : '';
	echo "</span></a></li>\n";
}

if (isset($_GET['location'])) {
?>
	</div>
	<script>
	targetElem = top.ICEcoder.filesFrame.contentWindow.document.getElementById('<?php echo $_GET['location'];?>');
	newUL = document.createElement("ul");
	newUL.style = "display: block";
	locNest = targetElem.parentNode.parentNode;
	if(locNest.nextSibling && locNest.nextSibling.tagName=="UL") {
		x = locNest.nextSibling;
		x.parentNode.removeChild(x);
	}
	newUL.innerHTML = document.getElementById('branch').innerHTML.slice(28).slice(0,-7);
	locNest.parentNode.insertBefore(newUL,locNest.nextSibling);
	</script>
<?php
;};
?>