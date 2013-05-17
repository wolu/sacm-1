<?php
// -----------------------------------------------
// Zip-It! for ICEcoder v1.0 by Matt Pass
// Will backup requested files/folders in ICEcoder
// and remove old backups older than $keepLastDays
// -----------------------------------------------
include("../../lib/settings.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>Zip It! for ICEcoder</title>
</head>
<body>
<?php
$saveLocation = '../../backups/';
$_GET['zip']=="|" ? $fileName = "root" : $fileName = str_replace("|","_",strClean($_GET['zip']));
$fileName .= '-'.time().'.zip';
$keepLastDays = 7;

if (!is_dir($saveLocation)) {mkdir($saveLocation, 0777);}
Class zipIt {
	public function zipFilesUp($zipDir,$zipFile,$keepLastDays,$docRoot) {
		$zipName = $zipDir.$zipFile;
		$zipFiles = array();
		$_GET['zip']=="|" ? $zipTgt = "" : $zipTgt = str_replace("|","/",strClean($_GET['zip']));
		if (strpos($_GET['zip'],"/")!==0) {$zipTgt = "/".trim($zipTgt,"/");};
		$addItem = $docRoot.$zipTgt;

		if (is_dir($addItem)) {
			$dirStack = array($addItem);
			while (!empty($dirStack)) {
				$currentDir = array_pop($dirStack);
				$dir = dir($currentDir);
				while (false !== ($node = $dir->read())) {
					if ($node == '.' || $node == '..') {continue;}
					if (is_dir($currentDir.$node) && !strpos($currentDir.$node,"_coder") && !strpos($currentDir.$node,"ICEcoder")) { 
						array_push($dirStack,$currentDir.$node.'/'); 
					}
					if (is_file($currentDir.$node)) {$zipFiles[] = $currentDir.$node;} 
				}
			}
		} else {
			if(file_exists($addItem)) {$zipFiles[] = $addItem;}
		}
		if ($backupsDir = opendir($zipDir)) {
			$keepTime = $keepLastDays*60*60*24;
			while (false !== ($backup = readdir($backupsDir))) {
				if ($backup != "." && $backup != "..") {
					if ((time()-filemtime($zipDir.$backup)) > $keepTime) {
						chmod($zipDir.$backup, 0777);
						unlink($zipDir.$backup) or DIE("couldn't delete $zipDir$backup<br>");
					}
				}
			}
			closedir($backupsDir);
		}
		if(count($zipFiles)) {
			$zip = new ZipArchive();
	    		if($zip->open($zipName,ZIPARCHIVE::CREATE)!== true) {return false;}
			$excludeFilesFolders = isset($_GET['exclude']) ? explode("*",strClean($_GET['exclude'])) : array();
			foreach($zipFiles as $file) {
				$canAdd=true;
				for ($i=0;$i<count($excludeFilesFolders);$i++) {
					if($excludeFilesFolders[$i] && strpos($file,$excludeFilesFolders[$i])!==false) {$canAdd=false;};
				}
				if ($canAdd==true) {
					$zip->addFile($file,str_replace($docRoot."/","",$file));
				}
			}
			$zip->close();
			chmod($zipName, 0777);
			return file_exists($zipName);
		} else {
			return false;
		}
	}
}
if($_SESSION['loggedIn']) {
	$doZip = new zipIt();
	echo '<script>top.ICEcoder.serverMessage("<b>Zipping Files</b>");</script>';
	$addToZip = $doZip->zipFilesUp($saveLocation,$fileName,$keepLastDays,$docRoot);
	echo '<script>';
	echo !$addToZip
		? 'top.ICEcoder.message("Could not zip files up!");'
		: 'setTimeout(function(){top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);},500);';
	echo '</script>';
}
?>
</body>
</html>