<?php include("settings.php");?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> settings screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="settings-screen.css">
<link rel="stylesheet" href="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<script src="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 409px; height: 240px; font-size: <?php echo $ICEcoder["fontSize"];?>}
.CodeMirror-scroll {overflow: hidden}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($ICEcoder["visibleTabs"]) {echo '\21e5';};?>";}
</style>

<link rel="stylesheet" href="editor.css">
<?php
$themeArray = array();
$handle = opendir('../'.$ICEcoder["codeMirrorDir"].'/theme/');
while (false !== ($file = readdir($handle))) {
	if ($file !== "." && $file != "..") {
		array_push($themeArray,basename($file,".css"));
	}
}
sort($themeArray);
for ($i=0;$i<count($themeArray);$i++) {
	echo '<link rel="stylesheet" href="../'.$ICEcoder["codeMirrorDir"].'/theme/'.$themeArray[$i].'.css">'.PHP_EOL;
}
?>
</head>

<body class="settings">

<div class="infoPane">
	<a href="http://icecoder.net" target="_blank"><img src="../images/ice-coder.png" class="logo"></a>
	<div class="version">v <?php echo $ICEcoder["versionNo"];?></div>

	<p>
	website:<br>
	<a href="http://icecoder.net" target="_blank">http://icecoder.net</a>
	<br><br>

	git:<br>
	<a href="http://github.com/mattpass/ICEcoder" target="_blank">http://github.com/mattpass/ICEcoder</a>
	<br><br>

	codemirror dir:<br>
	<?php echo $ICEcoder["codeMirrorDir"]; ?>
	<br><br>

	codemirror version:<br>
	<script>
	document.write(CodeMirror.version);
	</script>
	<br><br>

	file manager root:<br>
	<?php echo $ICEcoder['root'] == "" ? "/" : $ICEcoder['root'];?>
	<br><br><br><br>

	<div style="font-size: 10px; line-height: 12px">ICE coder by Matt Pass (<a href="http://www.twitter.com/mattpass" style="font-size: 10px" target="_blank">@mattpass</a>)<br><br>
		Free to use it for your own purposes, commercial or not, just let me know of any cool uses or customisations. :)<br><br>
		No warranty or liability accepted for anything, all responsibility of use is your own.<br><br>

		Thanks go to the following people who have inspired me to create this and in the odd case, provided feedback or code:<br>
		<?php
			$peopleArray = array("marijnjh", "maettig", "a_harris88", "emmetio", "prinzhorn", "wimtibackx", "jakubvrana", "davidwalshblog");
			for ($i=0;$i<count($peopleArray)-1;$i++) {
				echo '<a href="http://www.twitter.com/'.$peopleArray[$i].'" style="font-size: 10px" target="_blank">@'.$peopleArray[$i].'</a>';
				if ($i<count($peopleArray)-2) {
					echo ", ";
				}
			}			
		?>
		<br><br>
		...plus a whole load of people on Github. Thanks for your contributions!
	</div>
	</p>
</div>

<form name="settings" action="settings.php" method="POST">
<div class="settingsColumn1">
<h1>settings</h1>
<h2>functionality</h2>
<input type="checkbox" onclick="showButton()" name="checkUpdates" value="true"<?php if($ICEcoder["checkUpdates"]) {echo ' checked';};?>> check for updates on load<br>
<input type="checkbox" onclick="showButton()" name="openLastFiles" value="true"<?php if($ICEcoder["openLastFiles"]) {echo ' checked';};?>> auto open last files on login<br>
<br>
when finding in files, exclude:<br>
<input type="text" onkeydown="showButton()" name="findFilesExclude" value="<?php for($i=0;$i<=count($ICEcoder["findFilesExclude"])-1;$i++) {echo $ICEcoder["findFilesExclude"][$i]; if ($i<count($ICEcoder["findFilesExclude"])-1) {echo ', ';};}; ?>"><br>
<br>

<h2>assisting</h2>
<input type="checkbox" onclick="showButton()" name="codeAssist" value="true"<?php if($ICEcoder["codeAssist"]) {echo ' checked';};?>> code assist<br>
<input type="checkbox" onclick="showButton();showHideTabs()" name="visibleTabs" value="true"<?php if($ICEcoder["visibleTabs"]) {echo ' checked';};?>> visible tabs<br>
<input type="checkbox" onclick="showButton()" name="lockedNav" value="true"<?php if($ICEcoder["lockedNav"]) {echo ' checked';};?>> locked nav<br>
<br>

<h2>security</h2>
new password <span style="font-size: 10px; color: #888">8 chars</span><br>
<input type="password" name="accountPassword" onkeydown="showButton()"><br>
confirm password<br>
<input type="password" name="confirmPassword" onkeydown="showButton()"><br>
<br>
banned files/folders<br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="bannedFiles" value="<?php for($i=0;$i<=count($ICEcoder["bannedFiles"])-1;$i++) {echo $ICEcoder["bannedFiles"][$i]; if ($i<count($ICEcoder["bannedFiles"])-1) {echo ', ';};}; ?>"><br>
banned paths<br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="bannedPaths" value="<?php for($i=0;$i<=count($ICEcoder["bannedPaths"])-1;$i++) {echo $ICEcoder["bannedPaths"][$i]; if ($i<count($ICEcoder["bannedPaths"])-1) {echo ', ';};}; ?>"><br>
<input type="hidden" name="changedFileSettings" value="false">
ip addresses<br>
<input type="text" onkeydown="showButton()" name="allowedIPs" value="<?php for($i=0;$i<=count($ICEcoder["allowedIPs"])-1;$i++) {echo $ICEcoder["allowedIPs"][$i]; if ($i<count($ICEcoder["allowedIPs"])-1) {echo ', ';};}; ?>"><br>
</div>

<div class="settingsColumn2">
<h2>plugins</h2>
plugins array <span style="font-size: 10px; color: #888">name, img src, style, url, target, setInterval (mins)</span><br>
<textarea name="plugins" class="plugins" onkeydown="showButton()"><?php
for($i=0;$i<count($ICEcoder["plugins"]);$i++) {
	echo '"'.implode('",'.PHP_EOL.'"', $ICEcoder["plugins"][$i]).'"';
	if ($i<count($ICEcoder["plugins"])-1) {
		echo PHP_EOL."====================".PHP_EOL;
	}
}
?></textarea>
<br><br>

<h2>style</h2>
theme<br>
<select onchange="selectTheme();showButton()" id="select" name="theme">
    <option<?php if ($ICEcoder["theme"]=="default") {echo ' selected';}; ?>>default</option>
<?php
for ($i=0;$i<count($themeArray);$i++) {
	$optionSelected = $ICEcoder["theme"]==$themeArray[$i] ? ' selected' : '';
	echo '<option'.$optionSelected.'>'.$themeArray[$i].'</option>'.PHP_EOL;
}
?>
</select>

<span style="position: absolute; margin: -15px 0 0 10px">
	line wrapping<br>
	<select onchange="showButton()" name="lineWrapping">
		<option value="true"<?php if($ICEcoder["lineWrapping"]) {echo " selected";};?>>yes</option>
		<option value="false"<?php if(!$ICEcoder["lineWrapping"]) {echo " selected";};?>>no</option>
	</select>
</span>

<span style="position: absolute; margin: -15px 0 0 100px">
	indent type<br>
	<select onchange="showButton()" name="indentWithTabs">
		<option value="true"<?php if($ICEcoder["indentWithTabs"]) {echo " selected";};?>>tabs</option>
		<option value="false"<?php if(!$ICEcoder["indentWithTabs"]) {echo " selected";};?>>spaces</option>
	</select>
</span>

<span style="position: absolute; margin: -15px 0 0 190px">
	indent size <br>
	<input type="text" name="indentSize" id="indentSize" style="width: 30px" onkeydown="showButton()" onkeyup="changeIndentSize()" value="<?php echo $ICEcoder["indentSize"];?>">
</span>

<span style="position: absolute; margin: -15px 0 0 267px">
	font size <br>
	<input type="text" name="fontSize" id="fontSize" style="width: 44px" onkeydown="showButton()" onkeyup="changeFontSize()" value="<?php echo $ICEcoder["fontSize"];?>">
</span>
<br><br>

<textarea id="code" name="code">
function findSequence(goal) {
	function find(start,history) {
		if (start==goal)
			return history;
		else if (start>goal)
			return null;
		else
			return find(start+5,"("+history+"+5)") ||
			find(start*3,"("+history+"*3)");
	}
	return find(1,"1");
}</textarea>
<br>

<span style="position: absolute; top: 510px">
	<h2>file manager</h2>
	root <span style="font-size: 10px; color: #888">slash prefixed</span><br>
	<input type="text" name="root" style="width: 200px" onkeydown="document.settings.changedFileSettings.value='true';showButton()" value="<?php echo $ICEcoder["root"];?>">
</span>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	lineNumbers: true,
	readOnly: "nocursor",
	indentUnit: top.ICEcoder.indentSize,
	tabSize: top.ICEcoder.indentSize,
	mode: "javascript",
	theme: "<?php echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];?>"
	});

var input = document.getElementById("select");
function selectTheme() {
	var theme = input.options[input.selectedIndex].innerHTML;
	if (theme=="default") {theme = "icecoder"};
	editor.setOption("theme", theme);
}

function changeIndentSize() {
	var indentSize = document.getElementById("indentSize").value;
	editor.setOption("indentUnit", indentSize);
	editor.setOption("tabSize", indentSize);
}

function changeFontSize() {
	cMCSS = document.styleSheets[2];
	cMCSS.rules ? strCSS = 'rules' : strCSS = 'cssRules';
	cMCSS[strCSS][0].style['fontSize'] = document.getElementById("fontSize").value;
}

var showButton = function() {
	document.getElementById('updateButton').style.opacity = 1;
}

var showHideTabs = function() {
	cMCSS = document.styleSheets[2];
	cMCSS.rules ? strCSS = 'rules' : strCSS = 'cssRules';
	document.settings.visibleTabs.checked ? cMCSS[strCSS][2].style['content'] = '"\\21e5"' : cMCSS[strCSS][2].style['content'] = '" "';
}

var validatePasswords = function() {
	if (document.settings.accountPassword.value != 0 && document.settings.accountPassword.value.length<8) {
		top.ICEcoder.message('Please use at least 8 chars in the password');
	} else {
		if (document.settings.accountPassword.value != document.settings.confirmPassword.value) {
			top.ICEcoder.message('Sorry, your passwords don\'t match')
		} else {
			document.settings.submit();
		}
	}
}
</script>

<div class="update" id="updateButton" onClick="validatePasswords()">update</div>

</div>

</form>

</body>

</html>