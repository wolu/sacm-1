<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; if (!top.ICEcoder.overCloseLink) {top.ICEcoder.tabDragEnd()}" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; return top.ICEcoder.showMenu(event)" onClick="top.ICEcoder.selectFileFolder(event)">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> file manager</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/ice-coder.min.js" type="text/javascript"></script>
</head>

<body onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div title="Refresh" onClick="top.ICEcoder.refreshFileManager()" class="refresh"></div>

<ul class="fileManager">
<li class="pft-directory dirOpen"><a nohref title="/" onMouseOver="top.ICEcoder.overFileFolder('folder','/')" onMouseOut="top.ICEcoder.overFileFolder('folder','')" onClick="top.ICEcoder.openCloseDir(this)" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="|">/ <?php echo $iceRoot == "" ? "[ROOT]" : trim($iceRoot,"/");?></span> <span style="color: #888; font-size: 8px" id="|_perms"><?php echo $serverType=="Linux" ? substr(sprintf('%o', fileperms($docRoot.$iceRoot)), -3) : "";?></span></a></li><?php include("lib/get-branch.php");?>
</ul>

<iframe name="fileControl" style="display: none"></iframe>

<iframe name="testControl" style="display: none"></iframe>
		
</body>
	
</html>