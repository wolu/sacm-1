var ICEcoder = {

// ==============
// INIT
// ==============

	// Define settings
	filesW: 250,			// Width of the files pane
	minFilesW: 14,			// Min width of the files pane
	maxFilesW: 250,			// Max width of the files pane
	selectedTab: 0,			// The tab that's currently selected
	changedContent: [],		// Binary array to indicate which tabs have changed
	canSwitchTabs: true,		// Stops switching of tabs when trying to close
	openFiles: [],			// Array of open file URLs
	openFileMDTs: [],		// Array of open file modification datetimes
	cMInstances: [],		// List of CodeMirror instance no's
	nextcMInstance: 1,		// Next available CodeMirror instance no
	selectedFiles: [],		// Array of selected files
	findMode: false,		// States if we're in find/replace mode
	lockedNav: true, 		// Nav is locked or not
	htmlTagArray: [],		// Array storing the nest of tags
	codeAssist: true,		// Assist user with their coding
	mouseDown: false,		// If the mouse is down or not
	draggingFilesW: false,		// If we're dragging the file manager width or not
	draggingTab: false,		// If we're dragging a tab
	tabLeftPos: [],			// Left position of tabs inside content area
	serverQueueItems: [],		// Array of URLs to call in order
	previewWindow: false,		// Target variable for the preview window
	pluginIntervalRefs: [],		// Array of plugin interval refs
	overPopup: false,		// Indicates if we're over a popup or not
	ready: false,			// Indicates if ICEcoder is ready for action

	// Set our aliases
	initAliases: function() {
		var aliasArray = ["header","files","account","fmLock","filesFrame","editor","tabsBar","findBar","content","footer","nestValid","nestDisplay","charDisplay"];

		// Create our ID aliases
		for (var i=0;i<aliasArray.length;i++) {
			ICEcoder[aliasArray[i]] = top.document.getElementById(aliasArray[i]);
		}
	},

	// On load, set the layout and get the nest location
	init: function() {
		var screenIcon, sISrc;

		// Set layout & the nest location
		ICEcoder.setLayout();

		// Hide the loading screen & auto open last files?
		top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));
		if (top.ICEcoder.openLastFiles) {top.ICEcoder.autoOpenFiles()};

		setInterval(ICEcoder.updateNestingIndicator,30);

		// Setup fullscreen detection and change icon
		screenIcon = top.document.getElementById('screenMode');
		sISrc = ["images/restored-screen.gif","images/full-screen.gif"];
		document.addEventListener("fullscreenchange", function () {screenIcon.src = document.fullscreen ? sISrc[0] : sISrc[1];}, false);
		document.addEventListener("mozfullscreenchange", function () {screenIcon.src = document.mozFullScreen ? sISrc[0] : sISrc[1];}, false);
		document.addEventListener("webkitfullscreenchange", function () {screenIcon.src = document.webkitIsFullScreen ? sISrc[0] : sISrc[1];}, false);

		top.ICEcoder.ready = true;
	},

// ==============
// LAYOUT
// ==============

	// Set our layout according to the browser size
	setLayout: function(dontSetEditor) {
		var winW, winH, headerH, footerH, accountH, tabsBarH, findBarH;

		// Determin width & height available
		winW = window.innerWidth ? window.innerWidth : document.body.clientWidth;
		winH = window.innerHeight ? window.innerHeight : document.body.clientHeight;

		// Apply sizes to various elements of the page
		headerH = 40, footerH = 30, accountH = 50, tabsBarH = 21, findBarH = 28;
		this.header.style.width = this.tabsBar.style.width = this.findBar.style.width = winW + "px";
		this.files.style.width = this.editor.style.left = this.filesW + "px";
		this.account.style.height = this.accountH + "px";
		this.fmLock.style.marginLeft = (this.filesW-42) + "px";
		this.filesFrame.style.height = (winH-headerH-accountH-footerH) + "px";
		this.nestValid.style.left = (this.filesW+10) + "px";
		this.nestDisplay.style.left = (this.filesW+17) + "px";
		top.ICEcoder.setTabWidths();

		// If we need to set the editor sizes
		if (!dontSetEditor) {
			this.editor.style.width = ICEcoder.content.style.width = (winW-this.filesW) + "px";
			ICEcoder.content.style.height = (winH-headerH-footerH-tabsBarH-findBarH) + "px";
			
			// Resize the CodeMirror instances to match the window size
			setTimeout(function(){
				for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
				top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setSize("100%",top.ICEcoder.content.style.height);
			}},4);
		}
	},

	// Set the width of the file manager on demand
	changeFilesW: function(expandContract) {

		if (!ICEcoder.lockedNav||(ICEcoder.lockedNav && ICEcoder.filesW==ICEcoder.minFilesW)) {
			if ("undefined" != typeof ICEcoder.changeFilesInt) {clearInterval(ICEcoder.changeFilesInt)};
			ICEcoder.changeFilesInt = setInterval(function() {ICEcoder.changeFilesWStep(expandContract)},10);
		}
	},

	// Expand/contract the file manager in half-steps
	changeFilesWStep: function (expandContract) {
		if (expandContract=="expand") {
			ICEcoder.filesW < ICEcoder.maxFilesW-1 ? ICEcoder.filesW += Math.ceil((ICEcoder.maxFilesW-ICEcoder.filesW)/2) : ICEcoder.filesW = ICEcoder.maxFilesW;
		} else {
			ICEcoder.filesW > ICEcoder.minFilesW+1 ? ICEcoder.filesW -= Math.ceil((ICEcoder.filesW-ICEcoder.minFilesW)/2) : ICEcoder.filesW = ICEcoder.minFilesW;
		}
		if ((expandContract=="expand" && ICEcoder.filesW == ICEcoder.maxFilesW)||(expandContract=="contract" && ICEcoder.filesW == ICEcoder.minFilesW)) {
			clearInterval(ICEcoder.changeFilesInt);
		}
		// Redo the layout to match
		ICEcoder.setLayout();
	},

	// Can click-drag file manager width?
	canResizeFilesW: function() {
		// If we have the cursor set we must be able!
		if (top.document.body.style.cursor == "w-resize") {
			// If our mouse is down and we're within a 250-400px range
			if (top.ICEcoder.mouseDown) {
				top.ICEcoder.filesW = top.ICEcoder.maxFilesW = top.ICEcoder.mouseX >=250 && top.ICEcoder.mouseX <= 400
					? top.ICEcoder.mouseX : top.ICEcoder.mouseX <250 ? 250 : 400;
				// Set various widths based on the new width
				top.ICEcoder.files.style.width = top.ICEcoder.account.style.width = top.ICEcoder.filesFrame.style.width = top.ICEcoder.filesW + "px";
				top.ICEcoder.setLayout();
				top.ICEcoder.draggingFilesW = true;
			}
		} else {
			top.ICEcoder.draggingFilesW = false;
		}
	},

	// Lock & unlock the file manager navigation on demand
	lockUnlockNav: function() {
		var lockIcon;

		lockIcon = top.document.getElementById('fmLock');
		ICEcoder.lockedNav = ICEcoder.lockedNav ? false : true;
		lockIcon.style.backgroundPosition = ICEcoder.lockedNav ? "-64px -16px" : "-80px -16px";
	},

// ==============
// EDITOR
// ==============

	// Clean up our loaded code
	contentCleanUp: function() {
		var fileName, cM, content;

		// If it's not a JS, CoffeeScript Ruby, CSS or LESS file, replace our temp /textarea value
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (["js","coffee","rb","css","less"].indexOf(fileName.split(".")[1])<0) {
			cM = ICEcoder.getcMInstance();
			content = cM.getValue();
			content = content.replace(/<ICEcoder:\/:textarea>/g,'<\/textarea>');

			// Then set the content in the editor & clear the history
			cM.setValue(content);
			cM.clearHistory();
		}
	},

	// Move current line up/down
	moveLine: function(dir) {
		var cM, line, swapLineNo, swapLine;

		cM = top.ICEcoder.getcMInstance();
		line = cM.getCursor().line;
		if (dir=="up" && line>0) {swapLineNo = line-1}
		if (dir=="down" && line<cM.lineCount()-1) {swapLineNo = line+1}
		if (!isNaN(swapLineNo)) {
			swapLine = cM.getLine(swapLineNo);
			cM.setLine(swapLineNo,cM.getLine(line));
			cM.setLine(line,swapLine);
			ICEcoder.highlightLine(swapLineNo);
		}
	},

	// Highlight specified line
	highlightLine: function(line) {
		var cM;

		cM = top.ICEcoder.getcMInstance();
		cM.setSelection({line:line,ch:0}, {line:line,ch:cM.lineInfo(line).text.length});
	},

	// Go to a specific line number
	goToLine: function(lineNo) {
		var cM;

		cM = ICEcoder.getcMInstance();
		cM.setCursor(lineNo ? lineNo-1 : document.getElementById('goToLineNo').value-1);
		cM.focus();
		return false;
	},

	// Switch the CodeMirror mode on demand
	switchMode: function(mode) {
		var cM, fileName;

		cM = ICEcoder.getcMInstance();
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (mode) {
			cM.setOption("mode",mode);
		} else if (fileName) {
			fileName.indexOf('.js')>0	? cM.setOption("mode","javascript")
			: fileName.indexOf('.coffee')>0	? cM.setOption("mode","coffeescript")
			: fileName.indexOf('.rb')>0	? cM.setOption("mode","ruby")
			: fileName.indexOf('.css')>0	? cM.setOption("mode","css")
			: fileName.indexOf('.less')>0	? cM.setOption("mode","less")
			: fileName.indexOf('.md')>0	? cM.setOption("mode","markdown")
			: cM.setOption("mode","application/x-httpd-php");
		}
	},

	// Comment/uncomment line or selected range on keypress
	lineCommentToggle: function() {
		var cM, cursorPos, linePos, lineContent, lCLen, adjustCursor, startLine, endLine;

		cM = ICEcoder.getcMInstance();
		cursorPos = cM.getCursor().ch;
		linePos = cM.getCursor().line;
		lineContent = cM.getLine(linePos);
		lCLen = lineContent.length;
		adjustCursor = 2;

		if (["JavaScript","CoffeeScript","PHP","Ruby","CSS"].indexOf(ICEcoder.caretLocType)>-1) {
			if (cM.somethingSelected()) {
				if (ICEcoder.caretLocType=="Ruby") {
					startLine = cM.getCursor(true).line;
					endLine = cM.getCursor().line;
					for (var i=startLine; i<=endLine; i++) {
						cM.setLine(i, cM.getLine(i).slice(0,1)!="#"
						? "#" + cM.getLine(i)
						: cM.getLine(i).slice(1,cM.getLine(i).length));
					}
				} else {
					cM.replaceSelection(cM.getSelection().slice(0,2)!="/*"
					? "/*" + cM.getSelection() + "*/"
					: cM.getSelection().slice(2,cM.getSelection().length-2));
				}
			} else {
				if (["CoffeeScript","CSS"].indexOf(ICEcoder.caretLocType)>-1) {
					cM.setLine(linePos, lineContent.slice(0,2)!="/*"
					? "/*" + lineContent + "*/"
					: lineContent.slice(2,lCLen).slice(0,lCLen-4));
					if (lineContent.slice(0,2)=="/*") {adjustCursor = -adjustCursor};
				} else if (ICEcoder.caretLocType=="Ruby") {
					cM.setLine(linePos, lineContent.slice(0,1)!="#"
					? "#" + lineContent
					: lineContent.slice(1,lCLen));
					if (lineContent.slice(0,1)=="#") {adjustCursor = -adjustCursor};
				} else {
					cM.setLine(linePos, lineContent.slice(0,2)!="//"
					? "//" + lineContent
					: lineContent.slice(2,lCLen));
					if (lineContent.slice(0,2)=="//") {adjustCursor = -adjustCursor};
				}
			}
		} else {
			if (cM.somethingSelected()) {
				cM.replaceSelection(cM.getSelection().slice(0,4)!="<\!--"
				? "<\!--" + cM.getSelection() + "//-->"
				: cM.getSelection().slice(4,cM.getSelection().length-5));
			} else {
				cM.setLine(linePos, lineContent.slice(0,4)!="<\!--"
				? "<\!--" + lineContent + "//-->"
				: lineContent.slice(4,lCLen).slice(0,lCLen-9));
				adjustCursor = lineContent.slice(0,4)=="<\!--" ? -4 : 4;
			}
		}
		if (!cM.somethingSelected()) {cM.setCursor(linePos, cursorPos+adjustCursor)};
	},

	// Highlight or hide block upon roll over/out of nest positions
	highlightBlock: function(nestPos,hide) {
		var cM, searchPos, cursor, cursorTemp, startPos, endPos;

		cM = top.ICEcoder.getcMInstance();
		// Hiding the block
		if (hide) {
			// Set cursor back to orig pos if we haven't clicked, or redo nest display if we have
			top.ICEcoder.dontUpdateNest
			? cM.setCursor(top.ICEcoder.cursorOrigLine,top.ICEcoder.cursorOrigCh)
			: top.ICEcoder.getNestLocation('updateNestDisplay');
			top.ICEcoder.dontUpdateNest = false;
		} else {
			// Showing the block, set orig cursor position
			top.ICEcoder.cursorOrigCh = cM.getCursor().ch;
			top.ICEcoder.cursorOrigLine = cM.getCursor().line;
			top.ICEcoder.dontUpdateNest = true;
			// Set a cursor position object to begin with
			searchPos = new Object();
			searchPos.ch = cM.getCursor().ch;
			searchPos.line = cM.getCursor().line;
			// Then find our cursor position for our target nest depth
			for (var i=top.ICEcoder.htmlTagArray.length-1;i>=nestPos;i--) {
				cursor = cM.getSearchCursor("<"+top.ICEcoder.htmlTagArray[i],searchPos);
				cursor.findPrevious();
				searchPos.ch = cursor.from().ch;
				searchPos.line = cursor.from().line;
				if (i==nestPos) {
					cursorTemp = cM.getSearchCursor(">",searchPos);
					cursorTemp.findNext();
					cM.setCursor(cursorTemp.from().line, cursorTemp.from().ch+1);
					top.ICEcoder.getNestLocation();
					if (ICEcoder.htmlTagArray.length-1 != nestPos) {
						i++;
					}
				}
			}
			// Once we've found our tag
			if (cursor.from()) {
				// Set our vars to match the start position
				startPos = new Object();
				top.ICEcoder.startPosLine = startPos.line = cursor.from().line;
				top.ICEcoder.startPosCh = startPos.ch = cursor.from().ch;
				// Now set an end position object that matches this start tag
				endPos = new Object();
				endPos.line = top.ICEcoder.content.contentWindow.CodeMirror.tagRangeFinder(cM,startPos) || startPos.line;
				endPos.line = endPos.line.to ? endPos.line.to.line : endPos.line;
				endPos.ch = cM.getLine(endPos.line).indexOf("</"+top.ICEcoder.htmlTagArray[nestPos]+">")+top.ICEcoder.htmlTagArray[nestPos].length+3;
				// Set the selection or escape out of not selecting
				!top.ICEcoder.dontSelect ? cM.setSelection(startPos,endPos) : top.ICEcoder.dontSelect = false;
				cM.scrollIntoView(startPos);
			}
		}
	},
	// Set our cursor position upon mouse click of the nest position
	setPosition: function(nestPos,line,tag) {
		var cM, ch, chPos;

		cM = ICEcoder.getcMInstance();
		// Set our ch position just after the tag, and refocus on the editor
		ch = cM.getLine(line).indexOf(">",cM.getLine(line).indexOf("<"+tag))+1;
		cM.setCursor(line,ch);
		cM.focus();
		// Now update nest display to this nest depth & without any HTML tags to kill further interactivity
		chPos = 0;
		for (var i=0;i<=nestPos;i++) {
			chPos = ICEcoder.nestDisplay.innerHTML.indexOf("&gt;",chPos+1);
		}
		ICEcoder.nestDisplay.innerHTML = ICEcoder.nestDisplay.innerHTML.substr(0,chPos).replace(/<(?:.|\n)*?>/gm, '');
		top.ICEcoder.dontUpdateNest = false;
		top.ICEcoder.dontSelect = true;
	},

	// Wrap our selected text/cursor with tags
	tagWrapper: function(tag) {
		var cM, tagStart, tagEnd, startLine, endLine;

		cM = ICEcoder.getcMInstance();
		tagStart = tag;
		tagEnd = tag;
		if (tag=='div') {
			startLine = cM.getCursor('start').line;
			endLine = cM.getCursor().line;
			cM.operation(function() {
				cM.replaceSelection("<div>\n"+cM.getSelection()+"\n</div>");
				for (var i=startLine+1; i<=endLine+1; i++) {
					cM.indentLine(i);
				}
				cM.indentLine(endLine+2,'prev');
				cM.indentLine(endLine+2,'subtract');
			});
		} else {
			if (tag=='a') {tagStart='a href=""';}
			cM.replaceSelection("<"+tagStart+">"+cM.getSelection()+"</"+tagEnd+">");
		}
		if (tag=='a') {cM.setCursor({line:cM.getCursor('start').line,ch:cM.getCursor('start').ch+9})}
	},

	// Add a line break at end of current or specified line
	addLineBreakAtEnd: function(line) {
		var cM;

		cM = ICEcoder.getcMInstance();
		if (!line) {line = cM.getCursor().line};
		cM.setLine(line,cM.getLine(line)+"<br>");
	},

	// Duplicate line
	duplicateLine: function(line) {
		var cM, ch;

		cM = ICEcoder.getcMInstance();
		if (!line) {line = cM.getCursor().line};
		ch = cM.getCursor().ch;
		cM.setLine(line,cM.getLine(line)+"\n"+cM.getLine(line));
		cM.setCursor(line+1,ch);
	},

	// Remove line
	removeLine: function(line) {
		var cM, ch;

		cM = ICEcoder.getcMInstance();
		if (!line) {line = cM.getCursor().line};
		ch = cM.getCursor().ch;
		cM.removeLine(line);
		cM.setCursor(line-1,ch);
	},

	// Jump to and highlight the function definition current token
	jumpToDefinition: function() {
		var cM, tokenString, defVars;

		cM = ICEcoder.getcMInstance();
		tokenString = cM.getTokenAt(cM.getCursor()).string;

		if (cM.somethingSelected() && top.ICEcoder.origCurorPos) {
			cM.setCursor(top.ICEcoder.origCurorPos);
		} else {
			top.ICEcoder.origCurorPos = cM.getCursor();
			defVars = ["var "+tokenString, "function "+tokenString, tokenString+"=function", tokenString+"=new function", tokenString+":", "def "+tokenString, "class "+tokenString];
			for (var i=0; i<defVars.length; i++) {
				if (top.ICEcoder.findReplace(defVars[i],false,false)) {
					i=defVars.length;
				}
			}
		}
	},

// ==============
// FILES
// ==============

	// Open/close dirs on demand
	openCloseDir: function(dir,load) {
		var node, d;

		dir.onclick = function() {top.ICEcoder.openCloseDir(this,false)};
		node = dir.parentNode;
		if (node.nextSibling) {node = node.nextSibling};
		if (node && node.tagName=="UL") {
			d = node.style.display=="none";
			d ? load = true : node.style.display = "none";
			dir.parentNode.className = dir.className = d ? "pft-directory dirOpen" : "pft-directory";
		}
		if (load) {
			top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/get-branch.php?location="+dir.childNodes[1].id;
		}
		return false;
	},

	// Note which files or folders we are over on mouseover/mouseout
	overFileFolder: function(type, link) {
		ICEcoder.thisFileFolderType=type;
		ICEcoder.thisFileFolderLink=link;
	},

	// Select file or folder on demand
	selectFileFolder: function(evt) {
		var tgtFile, shortURL;

		// If we've clicked somewhere other than a file/folder
		if (top.ICEcoder.thisFileFolderLink=="") {
			if (!evt.ctrlKey) {
				top.ICEcoder.deselectAllFiles();
			}
		} else if (top.ICEcoder.thisFileFolderLink) {
			// Get file URL, with pipes instead of slashes & target DOM elem
			shortURL = top.ICEcoder.thisFileFolderLink.replace(/\//g,"|");
			tgtFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);

			// If we have the CTRL key down
			if (evt.ctrlKey) {
				// Deselect or select file
				if (top.ICEcoder.selectedFiles.indexOf(shortURL)>-1) {
					ICEcoder.selectDeselectFile('deselect',tgtFile);
					top.ICEcoder.selectedFiles.splice(top.ICEcoder.selectedFiles.indexOf(shortURL),1);
				} else {
					ICEcoder.selectDeselectFile('select',tgtFile);
					top.ICEcoder.selectedFiles.push(shortURL);
				}
			// We are single clicking
			} else {
				top.ICEcoder.deselectAllFiles();

				// Add our URL and highlight the file
				ICEcoder.selectDeselectFile('select',tgtFile);
				top.ICEcoder.selectedFiles.push(shortURL);
			}
		}
		// Adjust the file & replace select dropdown values accordingly
		document.findAndReplace.target[2].innerHTML = !top.ICEcoder.selectedFiles[0] ? "all files" : "selected files";
		document.findAndReplace.target[3].innerHTML = !top.ICEcoder.selectedFiles[0] ? "all filenames" : "selected filenames";

		// Finally, show or grey out the relevant file manager icons
		top.ICEcoder.fMIconVis('fMOpen',top.ICEcoder.selectedFiles.length == 1 ? 1 : 0.3);
		top.ICEcoder.fMIconVis('fMNewFile',top.ICEcoder.selectedFiles.length == 1 && top.ICEcoder.thisFileFolderType == "folder" ? 1 : 0.3);
		top.ICEcoder.fMIconVis('fMNewFolder',top.ICEcoder.selectedFiles.length == 1 && top.ICEcoder.thisFileFolderType == "folder" ? 1 : 0.3);
		top.ICEcoder.fMIconVis('fMDelete',top.ICEcoder.selectedFiles.length > 0 ? 1 : 0.3);
		top.ICEcoder.fMIconVis('fMRename',top.ICEcoder.selectedFiles.length == 1 ? 1 : 0.3);
		// Hide the file menu incase it's showing
		top.document.getElementById('fileMenu').style.display = "none";
	},

	// Deselect all files
	deselectAllFiles: function() {
		var tgtFile;

		for (var i=0;i<top.ICEcoder.selectedFiles.length;i++) {
			tgtFile = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
			ICEcoder.selectDeselectFile('deselect',tgtFile);
		}
		top.ICEcoder.selectedFiles.length = 0;
	},

	// Select or deselect file
	selectDeselectFile: function(action,file) {
		var isOpen;

		if (file) {
			isOpen = top.ICEcoder.openFiles.indexOf(file.id.replace(/\|/g,"/")) > -1 ? true : false;

			if (top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] == file.id.replace(/\|/g,"/")) {
				file.style.backgroundColor="#49d";
			} else {
				file.style.backgroundColor = action=="select"
				? "#888" : file.style.backgroundColor = isOpen
				? "rgba(255,255,255,0.15)" : "transparent";
			}
			file.style.color= action=="select" ? "#fff" : "#eee";
		}
	},

	// Create a new file (start & instant save)
	newFile: function() {
		top.ICEcoder.newTab();
		top.ICEcoder.saveFile();
	},

	// Create a new folder
	newFolder: function() {
		var shortURL, newFolder;

		shortURL = top.ICEcoder.rightClickedFile.replace(/\|/g,"/");
		newFolder = top.ICEcoder.getInput('Enter New Folder Name at '+shortURL,'');
		if (newFolder) {
			newFolder = (shortURL + "/" + newFolder).replace(/\/\//,"/");
			top.ICEcoder.serverQueue("add","lib/file-control.php?action=newFolder&file="+newFolder.replace(/\//g,"|"));
			top.ICEcoder.serverMessage('<b>Creating Folder</b><br>'+newFolder);
		}
	},

	// Open a file
	openFile: function(fileLink) {
		var shortURL, canOpenFile;

		if (fileLink) {
			top.ICEcoder.thisFileFolderLink=fileLink;
			top.ICEcoder.thisFileFolderType="file";
		}
		if (top.ICEcoder.isOpen(top.ICEcoder.thisFileFolderLink)!==false) {
			top.ICEcoder.switchTab(top.ICEcoder.isOpen(top.ICEcoder.thisFileFolderLink)+1);
		} else if (top.ICEcoder.thisFileFolderLink!="" && top.ICEcoder.thisFileFolderType=="file") {

			// work out a shortened URL for the file
			shortURL = top.ICEcoder.thisFileFolderLink.replace(/\|/g,"/");
			// No reason why we can't open a file (so far)
			canOpenFile = true;
			// Limit to 100 files open at a time
			if (top.ICEcoder.openFiles.length<100) {
				// check if we've already got it in our array
				if (top.ICEcoder.openFiles.indexOf(shortURL)>-1 && shortURL!="/[NEW]") {
					// we have, so instead, switch to that tab
					canOpenFile = false;
					top.ICEcoder.switchTab(i+1);
				}
			} else {
				// show a message because we have 100 files open
				top.ICEcoder.message('Sorry, you can only have 100 files open at a time!');
				canOpenFile = false;
			}

			// if we're still OK to open it...
			if (canOpenFile) {
				top.ICEcoder.shortURL = shortURL;

				if (shortURL!="/[NEW]") {
					top.ICEcoder.thisFileFolderLink = top.ICEcoder.thisFileFolderLink.replace(/\//g,"|");
					top.ICEcoder.serverQueue("add","lib/file-control.php?action=load&file="+top.ICEcoder.thisFileFolderLink);
					top.ICEcoder.serverMessage('<b>Opening File</b><br>'+top.ICEcoder.shortURL);
				} else {
					top.ICEcoder.createNewTab();
				}
				top.ICEcoder.fMIconVis('fMView',1);
			}
		}
	},

	// Show file prompt to open file
	openPrompt: function() {
		var fileLink;

		if(fileLink = top.ICEcoder.getInput('Enter relative file path prefixed with /','')) {
			top.ICEcoder.openFile(fileLink);
		}
	},

	// Save a file
	saveFile: function(saveAs) {
		var saveType;

		saveType = saveAs ? "saveAs" : "save";
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=save&file="+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(top.iceRoot,"").replace(/\//g,"|")+"&fileMDT="+ICEcoder.openFileMDTs[ICEcoder.selectedTab-1]+"&saveType="+saveType);
		top.ICEcoder.serverMessage('<b>Saving</b><br>'+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(top.iceRoot,""));
	},

	// Prompt a rename dialog
	renameFile: function(oldName,newName) {
		var shortURL, fileName, i;

		if (!oldName) {
			shortURL = top.ICEcoder.rightClickedFile.replace(/\|/g,"/");
			oldName = top.ICEcoder.rightClickedFile.replace(/\|/g,"/");
		} else {
			shortURL = oldName.replace(/\|/g,"/");
		}
		if (!newName) {
			newName = top.ICEcoder.getInput('Please enter the new name for',shortURL);
		}
		if (newName) {
			i = top.ICEcoder.openFiles.indexOf(shortURL.replace(/\|/g,"/"));
			if(i>-1) {
				// rename array item and the tab
				top.ICEcoder.openFiles[i] = newName;
				closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
				fileName = top.ICEcoder.openFiles[i];
				top.document.getElementById('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
				top.document.getElementById('tab'+(i+1)).title = newName;
			}
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=rename&file="+newName+"&oldFileName="+oldName.replace(/\|/g,"/"));
		top.ICEcoder.serverMessage('<b>Renaming to</b><br>'+newName);

		top.ICEcoder.setPreviousFiles();
		}
	},

	// Delete a file
	deleteFile: function() {
		var delFiles, selectedFilesList;

		if (top.ICEcoder.selectedFiles.length>0) {
			delFiles = top.ICEcoder.ask('Delete:\n\n'+top.ICEcoder.selectedFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n")+'?');
		}
		if (delFiles) {
			selectedFilesList = "";
			for (var i=0;i<top.ICEcoder.selectedFiles.length;i++) {
				selectedFilesList += top.ICEcoder.selectedFiles[i];
				if (i<top.ICEcoder.selectedFiles.length-1) {selectedFilesList+=";"};
			}
			top.ICEcoder.serverQueue("add","lib/file-control.php?action=delete&file="+selectedFilesList);
			top.ICEcoder.serverMessage('<b>Deleting File</b><br>'+top.ICEcoder.selectedFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n"));
		};
	},

	// Copy a file
	copyFile: function(selFile) {
		top.ICEcoder.copiedFile = selFile;
		top.document.getElementById('fmMenuPasteOption').style.display = "block";
		top.ICEcoder.hideFileMenu();
	},

	// Paste a file
	pasteFile: function(location) {
		if (top.ICEcoder.copiedFile) {
			top.ICEcoder.serverQueue("add","lib/file-control.php?action=paste&file="+top.ICEcoder.copiedFile+"&location="+location);
			top.ICEcoder.serverMessage('<b>Pasting File</b><br>'+top.ICEcoder.copiedFile.toString().replace(/\|/g,"/").replace(/,/g,"\n"));
		} else {
			top.ICEcoder.message("Nothing to paste, copy a file/folder first!");
		}
	},

	// Upload file(s) - select & submit
	uploadFilesSelect: function(location) {
		top.document.getElementById('uploadDir').value = location;
		top.document.getElementById("fileInput").click();
	},
	uploadFilesSubmit: function(obj) {
		if (top.document.getElementById('fileInput').value!="") {
			top.ICEcoder.showHide('show',top.document.getElementById('loadingMask'));
			document.getElementById('uploadFilesForm').submit();
			event.preventDefault();
		}
	},

	// Show menu on right clicking in file manager
	showMenu: function(evt) {
		var menuType, folderMenuItems;

		if (	top.ICEcoder.selectedFiles.length == 0 ||
			top.ICEcoder.selectedFiles.indexOf(top.ICEcoder.rightClickedFile.replace(/\//g,"|")) == -1) {
			top.ICEcoder.selectFileFolder(evt);
		}

		if ("undefined" != typeof top.ICEcoder.thisFileFolderLink && top.ICEcoder.thisFileFolderLink!="") {
			menuType = top.ICEcoder.selectedFiles[0].indexOf(".")>-1 ? "file" : "folder";
			folderMenuItems = top.document.getElementById('folderMenuItems');
			folderMenuItems.style.display = menuType == "folder" && top.ICEcoder.selectedFiles.length == 1 ? "block" : "none";
			singleFileMenuItems.style.display = top.ICEcoder.selectedFiles.length > 1 ? "none" : "block";
			document.getElementById('fileMenu').style.display = "inline-block";
			document.getElementById('fileMenu').style.left = (top.ICEcoder.mouseX+20) + "px";
			document.getElementById('fileMenu').style.top = (top.ICEcoder.mouseY-top.ICEcoder.filesFrame.contentWindow.document.body.scrollTop-10) + "px";
		}
		return false;
	},

	// Continue to show the file manager
	showFileMenu: function() {
		document.getElementById('fileMenu').style.display='inline-block';
	},

	// Hide the file manager
	hideFileMenu: function() {
		document.getElementById('fileMenu').style.display='none';
	},

	// Update the file manager tree list on demand
	updateFileManagerList: function(action,location,file,perms,oldName,uploaded) {
		var actionElemType, cssStyle, perms, targetElem, locNest, newText, innerLI, newUL, newLI, elemType, nameLI, shortURL, newMouseOver;

		// Adding files
		if (action=="add" && !document.getElementById('filesFrame').contentWindow.document.getElementById(location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file)) {
			// Is this is a file or folder and based on that, set the CSS styling & link
			actionElemType = file.indexOf(".")>-1 ? "file" : "folder";
			cssStyle = actionElemType=="file" ? "pft-file ext-" + file.substr(file.indexOf(".")+1) : "pft-directory";
			perms = actionElemType=="file" ? 664 : 705;

			// Identify our target element & the first child element in it's location
			if (!location) {location="/"}
			location = location.replace(top.iceRoot,"");
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|"));
			locNest = targetElem.parentNode.parentNode.nextSibling;
			newText = document.createTextNode("\n");
			innerLI = '<a nohref title="'+location.replace(/\/$/, "")+"/"+file+'" onMouseOver="top.ICEcoder.overFileFolder(\''+actionElemType+'\',\''+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'\')" onMouseOut="top.ICEcoder.overFileFolder(\''+actionElemType+'\',\'\')" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'">'+file+'</span> <span style="color: #888; font-size: 8px" id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'_perms">'+perms+'</span></a>';

			// If we don't have at least 3 DOM items in here, it's an empty folder
			if(locNest.childNodes.length<3) {
				// We now need to begin a new UL list
				newUL = document.createElement("ul");
				locNest = targetElem.parentNode.parentNode;
				locNest.parentNode.insertBefore(newUL,locNest.nextSibling);

				// Now we can add the first LI for this file/folder we're adding
				newLI = document.createElement("li");
				newLI.className = cssStyle;
				newLI.innerHTML = innerLI
				locNest.nextSibling.appendChild(newLI);
				locNest.nextSibling.appendChild(newText);

			// There are items in that location, so add our new item in the right position
			} else {
				for (var i=0;i<=locNest.childNodes.length-1;i++) {
					if (locNest.childNodes[i].className) {
						// Identify if the item we're considering is a file or folder
						elemType = locNest.childNodes[i].className.indexOf('directory')>0 ? "folder" : "file";
					
						// Get the name of the item
						nameLI = locNest.childNodes[i].getElementsByTagName('span')[0].innerHTML;

						// If it's of the same type & the name is greater, or we're adding a folder and it's a file or if we're at the end of the list
						if ((elemType==actionElemType && nameLI > file) || (actionElemType=="folder" && elemType=="file") || i==locNest.childNodes.length-1) {
							newLI = document.createElement("li");
							newLI.className = cssStyle;
							newLI.innerHTML = innerLI;
							// Append or insert depending on which of the above if statements is true
							if (i==locNest.childNodes.length-1) {
								locNest.appendChild(newLI);
								locNest.appendChild(newText);
							} else {
								locNest.insertBefore(newLI,locNest.childNodes[i]);
								locNest.insertBefore(newText,locNest.childNodes[i+1]);
							}
							break;
						}
					}
				}
			}
			// If we added a new file, we've saved it under a new filename, so set that
			if (actionElemType=="file" && !uploaded) {
				top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]=location+file;
			}
		}

		// Renaming files
		if (action=="rename") {
			// Get short URL of our right clicked file and get target elem based on this
			shortURL = oldName.replace(/\//g,"|");
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(shortURL);
			// Set the name to be as per our new file/folder name
			targetElem.innerHTML = file;
			// Finally, update the ID of the target & set a new mouseover function for the parent too
			targetElem.id = location.replace(/\//g,"|") + "|" + file;
			newMouseOver = targetElem.parentNode.onmouseover.toString().replace(shortURL.substring(shortURL.lastIndexOf("|")+1),file).split('\'');
			eval("targetElem.parentNode.onmouseover = function() { top.ICEcoder.overFileFolder('"+newMouseOver[1]+"','"+newMouseOver[3]+"');}");
			eval("targetElem.parentNode.title = newMouseOver[3];");
			targetElemPerms = document.getElementById('filesFrame').contentWindow.document.getElementById(shortURL+"_perms");
			targetElemPerms.id = location.replace(/\//g,"|") + "|" + file + "_perms";
		}

		// Chmod on files
		if (action=="chmod") {
			// Get short URL for our file and get our target elem based on this
			shortURL = top.ICEcoder.rightClickedFile.replace(/\|/g,"/");
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(shortURL.replace(/\//g,"|")+"_perms");
			// Set the new perms
			targetElem.innerHTML = perms;
			}

		// Deleting files
		if (action=="delete") {
			// Simply get our target and make it dissapear
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file).parentNode.parentNode;
			targetElem.parentNode.removeChild(targetElem);
		}
	},

	// Carry out actions when clicking icons above file manager
	fMIcon: function(action) {

		if (action=="save" && ICEcoder.openFiles.length>0) {
			top.ICEcoder.saveFile();
		}

		if (ICEcoder.selectedFiles.length==1) {
			top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink=top.ICEcoder.selectedFiles[0].replace('|','/');

			if (action=="open" && ICEcoder.selectedFiles[0].indexOf(".")>-1) {
				top.ICEcoder.thisFileFolderType='file';
				top.ICEcoder.openFile();
			}
			else if (action=="newFile")	 {top.ICEcoder.newFile();}
			else if (action=="newFolder") {top.ICEcoder.newFolder();}
			else if (action=="rename")	 {top.ICEcoder.renameFile(top.ICEcoder.rightClickedFile);}
		}

		if (action=="delete" && ICEcoder.selectedFiles.length>0) {
			top.ICEcoder.deleteFile();
		}

		if (action=="view" && ICEcoder.openFiles.length>0) {
			window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
		}
	},

	// Refresh file manager
	refreshFileManager: function() {
		var pB;

		pB = top.document.getElementById('progressBar').style;

		pB.webkitAnimation = pB.mozAnimation = '';
		setTimeout(function () {
			pB.webkitAnimation = pB.mozAnimation = 'fullexpand 10s ease-out';
		}, 4);
		top.ICEcoder.showHide('show',top.document.getElementById('loadingMask'));
		top.ICEcoder.filesFrame.src="files.php";
		top.ICEcoder.filesFrame.style.opacity="0";
		top.ICEcoder.filesFrame.onload = function() {
			top.ICEcoder.filesFrame.style.opacity="1";
			top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));
		}
	},

// ==============
// FIND & REPLACE
// ==============

	// Update find & replace options based on user selection
	findReplaceOptions: function() {
		top.document.getElementById('rText').style.display =
		top.document.getElementById('replace').style.display =
		top.document.getElementById('rTarget').style.display =
		document.findAndReplace.connector.value=="and"
			? "inline-block" : "none";
	},

	// Find & replace text according to user selections
	findReplace: function(findString,resultsOnly,buttonClick) {
		var find, replace, results, cM, content, lineCount, numChars, charsToCursor, charCount, cursor, replaceQS, targetQS;

		// Determine our find & replace strings and the length of them
		find		= findString.toLowerCase();
		replace		= top.document.getElementById('replace').value;
		results		= top.document.getElementById('results');

		// If we have something to find in currrent document
		cM = ICEcoder.getcMInstance();
		if (cM && find.length>0 && document.findAndReplace.target.value=="this document") {
			content = cM.getValue().toLowerCase();
			// Find & replace the next instance, or all?
			if (document.findAndReplace.connector.value=="and") {
				if (document.findAndReplace.replaceAction.value=="replace" && cM.getSelection().toLowerCase()==find) {
					cM.replaceSelection(replace);
				} else if (document.findAndReplace.replaceAction.value=="replace all" && buttonClick) {
					var rExp = new RegExp(find,"gi");
					cM.setValue(cM.getValue().replace(rExp,replace));
				}
			}

			// Get the content again, as it might of changed
			content = cM.getValue().toLowerCase();
			if (!top.ICEcoder.findMode||find!=top.ICEcoder.lastsearch) {
				ICEcoder.results = [];

				for (var i=0;i<content.length;i++) {
					if (content.substr(i,find.length)==find && i!= ICEcoder.findResult) {
						ICEcoder.results.push(i);
					}
				}

				// Also remember the last search term made
				ICEcoder.lastsearch = find;
			}

			// If we have results
			if (ICEcoder.results.length>0) {
				// Show results only
				if (resultsOnly) {
					results.innerHTML = ICEcoder.results.length + " results";
				// We need to take action instead
				} else {
					lineCount=1;
					numChars=0;

					// Count the no of chars & lines previous to our cursor's line
					for (var i=0;i<content.length;i++) {
						if (content.indexOf('\n',i)==i && lineCount<=cM.getCursor().line) {
							lineCount++;
							numChars=i;
						}
					}

					charsToCursor = numChars+cM.getCursor().ch+1;
					ICEcoder.findResult = 0;
					for (var i=0;i<ICEcoder.results.length;i++) {
						if (ICEcoder.results[i]<charsToCursor) {
							ICEcoder.findResult++;
						}
					}

					if (ICEcoder.findResult>ICEcoder.results.length-1) {ICEcoder.findResult=0};
					results.innerHTML = "Highlighted result "+(ICEcoder.findResult+1)+" of "+ICEcoder.results.length+" results";

					lineCount=0;
					for (var i=0;i<ICEcoder.results[ICEcoder.findResult];i++) {
						if (content.indexOf('\n',i)==i) {
							lineCount++;
							numChars=i;
						}
					}

					charCount = ICEcoder.results[ICEcoder.findResult] - numChars - 1;
					if (ICEcoder.results[ICEcoder.findResult]<=cM.lineInfo(0).text.length) {
						charCount++;
					}

					cursor = cM.getSearchCursor(find,cM.getCursor(),true);
					cursor.findNext();
					if (!cursor.from()) {
						cursor = cM.getSearchCursor(find,{line:0,ch:0},true);
						cursor.findNext();
					}
					// Finally, highlight our selection
					cM.setSelection(cursor.from(), cursor.to());
					cM.focus();
					top.ICEcoder.findMode = true;
				}
				return true;
			} else {
				results.innerHTML = "No results";
				return false;
			}
		} else {
			// Show the relevant multiple results popup
			if (find != "" && buttonClick) {
				replaceQS = "";
				targetQS = "";
				filesQS = "";
				if (document.findAndReplace.connector.value=="and") {
					replaceQS = "&replace="+replace;
				}
				if (document.findAndReplace.target.value.indexOf("file")>=0) {
					targetQS = "&target="+document.findAndReplace.target.value.replace(/ /g,"-");
				}
				if (document.findAndReplace.target.value=="selected files") {
					filesQS = "&selectedFiles=";
					for(i=0;i<top.ICEcoder.selectedFiles.length;i++) {
						filesQS += top.ICEcoder.selectedFiles[i]+":";
					}
					filesQS = filesQS.replace(/\:$/,"");
				}
				find = find.replace(/\'/g, '\&#39;');
				find != encodeURIComponent(find) ? find = 'ICEcoder:'+encodeURIComponent(find) : find;
				top.ICEcoder.showHide('show',top.document.getElementById('loadingMask'));
				top.document.getElementById('mediaContainer').innerHTML = '<iframe src="lib/multiple-results.php?find='+find+replaceQS+targetQS+filesQS+'" class="whiteGlow" style="width: 700px; height: 500px"></iframe>';
			}
		}
	},

	// Replace text in a file
	replaceInFile: function(fileRef,find,replace) {
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=replaceText&fileRef="+fileRef.replace(/\//g,"|")+"&find="+find+"&replace="+replace);
		top.ICEcoder.serverMessage('<b>Replacing text in</b><br>'+fileRef);
	},

// ==============
// INFO & DISPLAY
// ==============

	// Work out the nesting depth location on demand and update our display if required
	getNestLocation: function(updateNestDisplay) {
		var cM, nestCheck, state, cx, startPos, fileName, events;

		cM = ICEcoder.getcMInstance();
		if (cM) {
			nestCheck = cM.getValue();

			// Set up array to store nest data
			state = cM.getTokenAt(cM.getCursor()).state;
			if ("undefined" != typeof state.curState) {
				ICEcoder.htmlTagArray = [];
				for (cx = state.curState.htmlState.context; cx; cx = cx.prev) {
					if ("undefined" != typeof cx.tagName) {
						ICEcoder.htmlTagArray.unshift(cx.tagName);
					}
				}
			}
			ICEcoder.tagString = ICEcoder.htmlTagArray[ICEcoder.htmlTagArray.length-1];
			if (ICEcoder.caretLocType=="JavaScript") {
				ICEcoder.tagString = "script";
			}

			// Now we've built up our nest depth array, if we're due to show it in the display
			if (updateNestDisplay && !top.ICEcoder.dontUpdateNest) {
				// Clear the display
				ICEcoder.nestDisplay.innerHTML = "";
				if ("undefined" != typeof ICEcoder.openFiles[ICEcoder.selectedTab-1]) {
					fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
					if (["js","coffee","rb","css","less"].indexOf(fileName.split(".")[1])<0 &&
						(nestCheck.indexOf("include(")==-1)&&(nestCheck.indexOf("include_once(")==-1)&&
						(nestCheck.indexOf("<html")>-1||nestCheck.indexOf("<body")>-1)) {

						// Then for all the array items, output as the nest display
						for (var i=0;i<ICEcoder.htmlTagArray.length;i++) {
							events = 'onMouseover="top.ICEcoder.highlightBlock('+i+')" onMouseout="top.ICEcoder.highlightBlock('+i+',\'hide\')" onClick="top.ICEcoder.setPosition('+i+',top.ICEcoder.startPosLine,\''+ICEcoder.htmlTagArray[i]+'\')"';
							if (i==0) {ICEcoder.nestDisplay.innerHTML += '<div '+events+' style="display: inline-block; width: 7px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif)"></div>'};
							ICEcoder.nestDisplay.innerHTML += '<a '+events+' style="display: inline-block; cursor: pointer; background: #333; padding: 7px 2px 7px 7px; margin-top: -5px; height: 30px">'+ICEcoder.htmlTagArray[i]+'</a>';
							ICEcoder.nestDisplay.innerHTML += i<ICEcoder.htmlTagArray.length-1
							? '<div '+events+' style="display: inline-block; width: 8px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif); background-position: -7px 0; cursor: pointer"></div>'
							: '<div '+events+' style="display: inline-block; width: 7px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif); background-position: -15px 0; cursor: pointer"></div>';
						}
						if ("undefined" != typeof state.curState && ICEcoder.htmlTagArray.length > 0) {
							ICEcoder.nestDisplay.innerHTML += '<a style="display: inline-block; cursor: default; padding: 7px 2px 7px 7px; margin-top: -5px; height: 30px; color: #666">'+(state.curState.tagName ? state.curState.tagName : 'content')+'</a>';
						}
					}
				}
			}
		}
	},

	// Indicate if the nesting structure of the code is OK
	updateNestingIndicator: function () {
		var cM, nestOK, fileName;

		cM = ICEcoder.getcMInstance();
		nestOK = true;
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (cM && fileName && ["js","coffee","rb","css","less"].indexOf(fileName.split(".")[1])==-1) {
			nestOK = cM.getTokenAt({line:cM.lineCount(),ch:cM.lineInfo(cM.lineCount()-1).text.length}).className != "error" ? true : false;
		}
		ICEcoder.nestValid.style.background = nestOK ? "#0b0" : "#f00";
		ICEcoder.nestValid.title = nestOK ? "Nesting OK" : "Nesting Broken";
	},

	// Get the caret position
	getCaretPosition: function() {
		var cM, content, line, ch, chPos, chCount;

		cM = ICEcoder.getcMInstance();
		content = cM.getValue();
		line = cM.getCursor().line;
		ch = cM.getCursor().ch;
		chPos = 0;
		for (var i=0;i<line;i++) {
			chCount = content.indexOf("\n",chPos);
			chPos=chCount+1;
		}
		ICEcoder.caretPos=(chPos+ch-1);
		ICEcoder.getNestLocation('yes');
	},

	// Update the code type, line & character display
	updateCharDisplay: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		ICEcoder.caretLocationType();
		ICEcoder.charDisplay.innerHTML = ICEcoder.caretLocType + ", Line: " + (cM.getCursor().line+1) + ", Char: " + cM.getCursor().ch;
	},

	// Determine which area of the document we're in
	caretLocationType: function() {
		var cM, caretLocType, caretChunk, fileName;

		cM = ICEcoder.getcMInstance();
		caretLocType = "Unknown";
		caretChunk = cM.getValue().substr(0,ICEcoder.caretPos+1);
		if (caretChunk.lastIndexOf("<script")>caretChunk.lastIndexOf("</script>")&&caretLocType=="Unknown") {caretLocType = "JavaScript"} 
		else if (caretChunk.lastIndexOf("<?")>caretChunk.lastIndexOf("?>")&&caretLocType=="Unknown") {caretLocType = "PHP"} 
		else if (caretChunk.lastIndexOf("<%")>caretChunk.lastIndexOf("%>")&&caretLocType=="Unknown") {caretLocType = "Ruby"} 
		else if (caretChunk.lastIndexOf("<")>caretChunk.lastIndexOf(">")&&caretLocType=="Unknown") {caretLocType = "HTML"} 
		else if (caretLocType=="Unknown") {caretLocType = "Content"};

		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (fileName.indexOf(".js")>0) {caretLocType="JavaScript"} 
		else if (fileName.indexOf(".coffee")>0) {caretLocType="CoffeeScript"} 
		else if (fileName.indexOf(".rb")>0) {caretLocType="Ruby"} 
		else if (fileName.indexOf(".css")>0) {caretLocType="CSS"} 
		else if (fileName.indexOf(".less")>0) {caretLocType="LESS"} 
		else if (fileName.indexOf(".md")>0) {caretLocType="Markdown"};

		ICEcoder.caretLocType = caretLocType;
	},

	// Alter array indicating which files have changed
	redoChangedContent: function(evt) {
		var cM, key;
		
		cM = ICEcoder.getcMInstance();
		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;
		// Exclude a few keys...
		// Escape (27), Caps Lock (20), Shift, CTRL, Alt, Pause/Break (16-19), Left, Up, Right, Down (37-40), Num Lock, Scroll Lock (144-145), 
		// Insert, Delete (45,46), Page Up, Page Down, End, Home (33-36), Left Win Key, Right Win Key (91-92), F1-F12 (112-123)
		if (!evt.ctrlKey && key!=27 && key!=20 && (key<16||key>19) && (key<37||key>40) && (key!=144||key!=145) && (key!=45||key!=46) && (key<33||key>36) && (key!=91||key!=92) && (key<112||key>123)) {
			ICEcoder.changedContent[ICEcoder.selectedTab-1] = cM.historySize().undo > 0 ? 1 : 0;
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
		}
	},

	// Show & hide target element
	showHide: function(doVis,elem) {
		elem.style.visibility = doVis=="show" ? 'visible' : 'hidden';
	},


	// Determine the CodeMirror instance we're using
	getcMInstance: function(newTab) {
		return top.ICEcoder.content.contentWindow[
			newTab=="new"||(newTab!="new" && ICEcoder.openFiles.length>0)
			? 'cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]
			: 'cM1'
		];
	},

	// Get the mouse position
	getMouseXY: function(e,area) {
		var tempX, tempY, scrollTop;

		top.ICEcoder.mouseX = e.pageX ? e.pageX : e.clientX + document.body.scrollLeft;
		top.ICEcoder.mouseY = e.pageY ? e.pageY : e.clientY + document.body.scrollTop;
		
		if (area!="top") {
			top.ICEcoder.mouseY += 40 + 50;
		}
		if (area=="editor") {
			top.ICEcoder.mouseX += top.ICEcoder.filesW;
		}
		top.ICEcoder.dragCursorTest();
		if (top.ICEcoder.mouseY>62) {top.ICEcoder.setTabWidths();};
	},

	// Test if we need to show a drag cursor or not
	dragCursorTest: function() {
		var winH, cursorName, diffX, zone;

		diffX = top.ICEcoder.mouseX - top.ICEcoder.diffStartX;
		if (top.ICEcoder.draggingTab!==false && top.ICEcoder.diffStartX && (diffX <= -10 || diffX >= 10)) {
			if (top.ICEcoder.mouseX > parseInt(top.ICEcoder.files.style.width,10)) {
				top.ICEcoder.tabDragMouseX = top.ICEcoder.mouseX - parseInt(top.ICEcoder.files.style.width,10) - top.ICEcoder.tabDragMouseXStart;
				top.ICEcoder.tabDragMove();
			}
		}

		if (top.ICEcoder.ready) {
			winH = window.innerWidth ? window.innerHeight : document.body.clientHeight;
			if (!top.ICEcoder.mouseDown) {top.ICEcoder.draggingFilesW = false};

			cursorName = (!ICEcoder.draggingTab && ((top.ICEcoder.mouseX > top.ICEcoder.filesW-7 && top.ICEcoder.mouseX < top.ICEcoder.filesW+7 && top.ICEcoder.mouseY > 40 && top.ICEcoder.mouseY < (winH-30)) || top.ICEcoder.draggingFilesW))
				? "w-resize"
				: "auto";
			if (top.ICEcoder.content.contentWindow.document && top.ICEcoder.filesFrame.contentWindow) {
				top.document.body.style.cursor = cursorName;
				if (zone = top.ICEcoder.content.contentWindow.document.body) {zone.style.cursor = cursorName};
				if (zone = top.ICEcoder.filesFrame.contentWindow.document.body) {zone.style.cursor = cursorName};
			}
		}
	},

	// Show or hide a server message
	serverMessage: function(message) {
		var serverMessage;

		serverMessage =	document.getElementById('serverMessage');
		if (message) {
			serverMessage.innerHTML = message;
			serverMessage.style.display = "inline-block";
		} else {
			setTimeout(function() {serverMessage.style.display = "none"},200);
		}
		serverMessage.style.opacity = message ? 1 : 0;
	},

	// Show a CSS color block next to our text cursor
	cssColorPreview: function() {
		var cM, string, rx, match, oldBlock, newBlock;

		cM = ICEcoder.getcMInstance();
		string = cM.getLine(cM.getCursor().line);
		rx = /(#[\da-f]{3}(?:[\da-f]{3})?\b|\b(?:rgb|hsl)a?\([\s\d%,.-]+\)|\b[a-z]+\b)/gi;

		while((match = rx.exec(string)) && cM.getCursor().ch > match.index+match[0].length);

		oldBlock = top.document.getElementById('content').contentWindow.document.getElementById('cssColor');
		if (oldBlock) {oldBlock.parentNode.removeChild(oldBlock)};
		if (top.ICEcoder.codeAssist && top.ICEcoder.caretLocType=="CSS") {
			newBlock = top.document.createElement("div");
			newBlock.id = "cssColor";
			newBlock.style.position = "absolute";
			newBlock.style.display = "block";
			newBlock.style.width = newBlock.style.height = "20px";
			newBlock.style.zIndex = "1000";
			newBlock.style.background = match ? match[0] : '';
			newBlock.style.cursor = "pointer";
			newBlock.onclick = function() {top.ICEcoder.showColorPicker(match[0])};
			if (newBlock.style.backgroundColor=="") {newBlock.style.display = "none"};
			top.document.getElementById('header').appendChild(newBlock);
			cM.addWidget(cM.getCursor(), top.document.getElementById('cssColor'), true);
		}
	},

	// Show color picker
	showColorPicker: function(color) {
		top.document.getElementById('blackMask').style.visibility = "visible";
		top.document.getElementById('mediaContainer').innerHTML = '<div id="picker" class="picker"></div><br><br><input type="text" id="color" name="color" value="#123456" style="border: 0; width: 70px; padding-left: 5px"><input type="button" onClick="top.ICEcoder.insertColorValue()" value="insert &gt;" style="background: #888; color: #fff; height: 18px; width: 70px; border: 0; margin-left: 5px; cursor: pointer">';
		farbtastic('picker','color');
		if (color) {
			top.document.getElementById('picker').farbtastic.setColor(color);
		}
	},

	// Draw a canvas image based on actual img node image src
	drawCanvasImage: function (imgThis) {
		var canvas = document.getElementById('canvasPicker').getContext('2d');
		var img = new Image();
		img.src = imgThis.src;
		img.onload = function() {
			document.getElementById('canvasPicker').width = imgThis.width;
			document.getElementById('canvasPicker').height = imgThis.height;
			canvas.drawImage(img,0,0,imgThis.width,imgThis.height);
		}

		// Show pointer colors on mouse move over canvas
		document.getElementById('canvasPicker').onmousemove = function(event) {
			// get mouse x & y
			var x = event.pageX - this.offsetLeft;
			var y = event.pageY - this.offsetTop;
			// get image data & then RGB values
			var imgData = canvas.getImageData(x, y, 1, 1).data;
			var R = imgData[0];
			var G = imgData[1];
			var B = imgData[2];
			var rgb = R+','+G+','+B;
	 		// Get hex from RGB value
			var hex = top.ICEcoder.rgbToHex(R,G,B);
			// set the values & BG colours of the input boxes
			document.getElementById('rgbMouseXY').value = rgb;
			document.getElementById('hexMouseXY').value = '#' + hex;
			document.getElementById('hexMouseXY').style.backgroundColor = document.getElementById('rgbMouseXY').style.backgroundColor = '#' + hex;
		};
		// Set pointer colors on clicking canvas
		document.getElementById('canvasPicker').onclick = function() {
			document.getElementById('rgb').value = document.getElementById('rgbMouseXY').value;
	  		document.getElementById('hex').value = document.getElementById('hexMouseXY').value;
			document.getElementById('hex').style.backgroundColor = document.getElementById('rgb').style.backgroundColor = document.getElementById('hex').value;
		}
	},

	// Convert RGB values to Hex
	rgbToHex: function(R,G,B) {
		return top.ICEcoder.toHex(R)+top.ICEcoder.toHex(G)+top.ICEcoder.toHex(B);
	},

	// Return numbers as hex equivalent
	toHex: function(n) {
		n = parseInt(n,10);
		if (isNaN(n)) return "00";
		n = Math.max(0,Math.min(n,255));
		return "0123456789ABCDEF".charAt((n-n%16)/16)  + "0123456789ABCDEF".charAt(n%16);
	},

	// Insert new color value
	insertColorValue: function(color) {
		var cM, cursor;

		cM = ICEcoder.getcMInstance();
		cursor = cM.getTokenAt(cM.getCursor());
		cM.replaceRange(top.document.getElementById('color').value,{line:cM.getCursor().line,ch:cursor.start},{line:cM.getCursor().line,ch:cursor.end});
	},

	// Change opacity of the file manager icons
	fMIconVis: function(icon, vis) {
		var i;

		if (i = top.document.getElementById(icon)) {
			i.style.opacity = vis;
		}
	},

	// Check if a file is already open
	isOpen: function(file) {
		var i;

		file = file.replace(/\|/g, "/").replace(top.docRoot+top.iceRoot,"");
		i = top.ICEcoder.openFiles.indexOf(file);
		return i!=-1 ? i : false;
	},

	// Show JS Hint errors
	updateHints: function() {
		var cM;

		if ("undefined" != typeof JSHINT && top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1].indexOf('.js')>-1) {
			cM = ICEcoder.getcMInstance();
			cM.operation(function(){
				var widgets = top.ICEcoder['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]+'widgets'];
				for (var i=0; i<widgets.length; ++i) {
					cM.removeLineWidget(widgets[i]);
				}
				widgets.length = 0;

				JSHINT(cM.getValue());
				for (var i=0; i<JSHINT.errors.length; ++i) {
					var err = JSHINT.errors[i];
					if (!err) continue;
					var msg = document.createElement("div");
					var icon = msg.appendChild(document.createElement("span"));
					icon.innerHTML = "!!";
					icon.className = "lint-error-icon";
					msg.appendChild(document.createTextNode(err.reason));
					msg.className = "lint-error";
					widgets.push(cM.addLineWidget(err.line-1, msg, {coverGutter: false, noHScroll: true}));
				}
			});
			var info = cM.getScrollInfo();
			var after = cM.charCoords({line: cM.getCursor().line+1, ch: 0}, "local").top;
			if (info.top + info.clientHeight < after) {
				cM.scrollTo(null, after - info.clientHeight+3);
			}
		}
	},

// ==============
// SYSTEM
// ==============

	// Start running plugin intervals according to given specifics
	startPluginIntervals: function(plugRef,plugURL,plugTarget,plugTimer) {
		top.ICEcoder['plugTimer'+plugRef] = 
		// This window instances
			["_parent","_top","_self",""].indexOf(plugTarget) > -1
			? top.ICEcoder['plugTimer'+plugRef] = setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60)
		// fileControl iframe instances
			: plugTarget.indexOf("fileControl") == 0
			? top.ICEcoder['plugTimer'+plugRef] = setInterval(function() {
				top.ICEcoder.serverQueue("add",plugURL);top.ICEcoder.serverMessage(plugTarget.split(":")[1]);
				},plugTimer*1000*60)
		// _blank or named target window instances
			: top.ICEcoder['plugTimer'+plugRef] = setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);

		// push the plugin ref into our array
		top.ICEcoder.pluginIntervalRefs.push(plugRef);
	},

	// Turning on/off the Code Assist
	codeAssistToggle: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		top.ICEcoder.codeAssist = !top.ICEcoder.codeAssist;
		top.ICEcoder.cssColorPreview();
		cM.focus();

		if (!top.ICEcoder.codeAssist) {
			for (i=0;i<top.ICEcoder.cMInstances.length;i++) {
				cM = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[i]];
				cM.operation(function(){
					var widgets = top.ICEcoder['cM'+top.ICEcoder.cMInstances[i]+'widgets'];
					for (var j=0; j<widgets.length; ++j) {
						cM.removeLineWidget(widgets[j]);
					}
					widgets.length = 0;
				});
			}
		} else {
			top.ICEcoder.updateHints();
		}
	},

	// Queue items up for processing in turn
	serverQueue: function(action,item) {
		var cM,nextSaveID,txtArea,topSaveID,element;

		cM = ICEcoder.getcMInstance();
		// Firstly, work out how many saves we have to carry out
		nextSaveID=0;
		for (var i=0;i<ICEcoder.serverQueueItems.length;i++) {
			if (ICEcoder.serverQueueItems[i].indexOf('action=save')>0) {
				nextSaveID++;
			}
		}
		nextSaveID++;

		// Add to end of array or remove from beginning on demand, plus add or remove if necessary
		if (action=="add") {
			ICEcoder.serverQueueItems.push(item);
			if (item.indexOf('action=save')>0) {
				txtArea = document.createElement('textarea');
				txtArea.setAttribute('id', 'saveTemp'+nextSaveID);
				document.body.appendChild(txtArea);
				document.getElementById('saveTemp'+nextSaveID).value = cM.getValue();
			}
		} else if (action=="del") {
			if (ICEcoder.serverQueueItems[0] && ICEcoder.serverQueueItems[0].indexOf('action=save')>0) {
				topSaveID = nextSaveID-1;
				for (var i=1;i<topSaveID;i++) {
					document.getElementById('saveTemp'+i).value = document.getElementById('saveTemp'+(i+1)).value;
				}
				element = document.getElementById('saveTemp'+topSaveID);
				element.parentNode.removeChild(element);
			}
			ICEcoder.serverQueueItems.splice(0,1);
		}

		// If we've just removed from the array and there's another action queued up, or we're triggering for the first time
		// then do the next requested process, stored at array pos 0
		if (action=="del" && ICEcoder.serverQueueItems.length>=1 || ICEcoder.serverQueueItems.length==1) {
			setTimeout(function() {top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href=ICEcoder.serverQueueItems[0]},1);
		}
	},

	// Cancel all actions on pressing Esc in non content areas
	cancelAllActions: function() {
		// Stop whatever the parent may be loading and clear tasks other than the current one
		window.stop();
		if (ICEcoder.serverQueueItems.length>0) {
			ICEcoder.serverQueueItems.splice(1,ICEcoder.serverQueueItems.length);
		}
		top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));
		top.ICEcoder.serverMessage('<b style="color: #d00">Cancelled tasks</b>');
		setTimeout(function() {top.ICEcoder.serverMessage();},2000);
	},

	// Set the current previousFiles in the settings file
	setPreviousFiles: function() {
		var previousFiles;

		previousFiles = top.ICEcoder.openFiles.join(',').replace(/\//g,"|").replace(/(\|\[NEW\])|(,\|\[NEW\])/g,"").replace(/(^,)|(,$)/g,"");
		if (previousFiles=="") {previousFiles="CLEAR"};
		// Then send through to the settings page to update setting
		top.ICEcoder.serverQueue("add","lib/settings.php?saveFiles="+previousFiles);
	},

	// Opens the last files we had open
	autoOpenFiles: function() {
		if (top.ICEcoder.previousFiles.length>0 && top.ICEcoder.ask('Open previous files?\n\n'+top.ICEcoder.previousFiles.length+' files:\n'+top.ICEcoder.previousFiles.join('\n').replace(/\|/g,"/").replace(new RegExp(top.docRoot+top.iceRoot,'gi'),""))) {
			for (var i=0;i<top.ICEcoder.previousFiles.length;i++) {
				top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink=top.ICEcoder.previousFiles[i].replace('|','/');
				top.ICEcoder.thisFileFolderType='file';
				top.ICEcoder.openFile();
			}
		}
	},

	// Show the settings screen
	settingsScreen: function(hide) {
		if (!hide) {
			top.document.getElementById('mediaContainer').innerHTML = '<iframe src="lib/settings-screen.php" class="whiteGlow" style="width: 970px; height: 600px"></iframe>';
		}
		top.ICEcoder.showHide(hide?'hide':'show',top.document.getElementById('blackMask'));
	},

	// Show the help screen
	helpScreen: function() {
		top.document.getElementById('mediaContainer').innerHTML = '<iframe src="lib/help.php" class="whiteGlow" style="width: 800px; height: 470px"></iframe>';
		top.ICEcoder.showHide('show',top.document.getElementById('blackMask'));
	},

	// Show the ICEcoder manual, loaded remotely
	showManual: function(version) {
		top.document.getElementById('mediaContainer').innerHTML = '<iframe src="http://icecoder.net/manual?version='+version+'" class="whiteGlow" style="width: 500px; height: 500px"></iframe>';
		top.ICEcoder.showHide('show',top.document.getElementById('blackMask'));
	},

	// Show the properties screen
	propertiesScreen: function(fileName) {
		top.document.getElementById('mediaContainer').innerHTML = '<iframe src="lib/properties.php?fileName='+fileName.replace(/\//g,"|")+'" class="whiteGlow" style="width: 660px; height: 330px"></iframe>';
		top.ICEcoder.showHide('show',top.document.getElementById('blackMask'));
	},

	// Update the settings used when we make a change to them
	useNewSettings: function(themeURL,codeAssist,lockedNav,visibleTabs,fontSize,lineWrapping,indentWithTabs,indentSize,refreshFM) {
		var styleNode, strCSS, cMCSS, activeLineBG;

		// Add new stylesheet for selected theme
		top.ICEcoder.theme = themeURL.slice(themeURL.lastIndexOf("/")+1,themeURL.lastIndexOf("."));
		if (top.ICEcoder.theme=="editor") {top.ICEcoder.theme="icecoder"};
		styleNode = document.createElement('link');
		styleNode.setAttribute('rel', 'stylesheet');
		styleNode.setAttribute('type', 'text/css');
		styleNode.setAttribute('href', themeURL);
		top.ICEcoder.content.contentWindow.document.getElementsByTagName('head')[0].appendChild(styleNode);
		activeLineBG = ["eclipse","elegant","neat"].indexOf(top.ICEcoder.theme)>-1 ? "#ccc": "#000";
		top.ICEcoder.switchTab(top.ICEcoder.selectedTab);

		// Check/uncheck Code Assist setting
		top.document.getElementById('codeAssist').checked = codeAssist;

		// Unlock/lock the file manager
		if (lockedNav != top.ICEcoder.lockedNav) {top.ICEcoder.lockUnlockNav()};
		if (!lockedNav) {
			ICEcoder.changeFilesW('contract'); 
			top.document.getElementById('fileMenu').style.display='none';
		}

		cMCSS = ICEcoder.content.contentWindow.document.styleSheets[3];
		strCSS = cMCSS.rules ? 'rules' : 'cssRules';
		cMCSS[strCSS][0].style['fontSize'] = fontSize;
		cMCSS[strCSS][5].style['content'] = visibleTabs ? '"\\21e5"' : '" "';
		cMCSS[strCSS][2].style.cssText = "background: " + activeLineBG + " !important";

		top.ICEcoder.lineWrapping = lineWrapping;
		top.ICEcoder.indentWithTabs = indentWithTabs;
		top.ICEcoder.indentSize = indentSize;
		for (var i=0;i<ICEcoder.cMInstances.length;i++) {
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("lineWrapping", top.ICEcoder.lineWrapping);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentWithTabs", top.ICEcoder.indentWithTabs);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentUnit", top.ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("tabSize", top.ICEcoder.indentSize);
		}

		// Finally, refresh the file manager if we need to
		if (refreshFM) {top.ICEcoder.refreshFileManager()};
	},

	// Update and show/hide found results display?
	updateResultsDisplay: function(showHide) {
		ICEcoder.findReplace(top.document.getElementById('find').value,true,false);
		document.getElementById('results').style.display = showHide=="show" ? 'inline-block' : 'none';
	},

	// Toggle full screen on/off
	fullScreenSwitcher: function() {
		// Future use
		if ("undefined" != typeof document.cancelFullScreen) {
			document.fullScreen ? document.cancelFullScreen() : document.body.requestFullScreen();
		// Moz specific
		} else if ("undefined" != typeof document.mozCancelFullScreen) {
			document.mozFullScreen ? document.mozCancelFullScreen() : document.body.mozRequestFullScreen();
		// Chrome specific
		} else if ("undefined" != typeof document.webkitCancelFullScreen) {
			document.webkitIsFullScreen ? document.webkitCancelFullScreen() : document.body.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	},

	// Pass target file/folder to Zip It!
	zipIt: function(tgt) {
		tgt=tgt.replace(/\//g,"|");
		top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href="plugins/zip-it/index.php?zip="+tgt;
	},

	// Change permissions on a file/folder
	chmod: function(file,perms) {
		top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=perms&file="+file.replace(top.iceRoot,"")+"&perms="+perms);
		top.ICEcoder.serverMessage('<b>chMod '+perms+' on </b><br>'+file.replace(top.iceRoot,""));
	},

	// Open/show the preview window
	openPreviewWindow: function() {
		var cM, filepath, filename, fileExt;

		filepath = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
		filename = filepath.substr(filepath.lastIndexOf("/")+1);
		fileExt = filename.substr(filename.lastIndexOf(".")+1);
		cM = ICEcoder.getcMInstance();

		top.ICEcoder.previewWindow = window.open(filepath,"previewWindow");
		if (["md"].indexOf(fileExt) > -1) {
			top.ICEcoder.previewWindow.onload = function() {top.ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(cM.getValue())};
		}
	},

	// Open a new terminal window
	openTerminal: function() {
		top.ICEcoder.demoMode ? top.ICEcoder.message('Sorry, you need to be logged in to use the terminal') : window.open('terminal');
	},

	// Logout of ICEcoder
	logout: function() {
		window.location = window.location + "?logout";
	},

	// Show a message
	message: function(msg) {
		alert(msg);
	},

	// Ask for confirmation
	ask: function(question) {
		return confirm(question);
	},

	// Get the users input
	getInput: function(question,defaultValue) {
		return prompt(question,defaultValue);
	},

	// Show a data screen message
	dataMessage: function(message) {
		var dM;

		dM = top.ICEcoder.content.contentWindow.document.getElementById('dataMessage');
		dM.style.display = "block";
		dM.innerHTML = message;
	},

// ==============
// TABS
// ==============

	// Change tabs by switching visibility of instances
	switchTab: function(newTab,noFocus) {
		var cM;

		// Identify tab that's currently selected & get the instance
		ICEcoder.selectedTab = newTab;
		cM = ICEcoder.getcMInstance();

		if (cM) {
			// Switch mode to HTML, PHP, CSS etc
			ICEcoder.switchMode();

			// Set all cM instances to be hidden, then make our selected instance visible
			for (var i=0;i<ICEcoder.cMInstances.length;i++) {
				ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].getWrapperElement().style.display = "none";
			}
			cM.setOption('theme',top.ICEcoder.theme);
			cM.getWrapperElement().style.display = "block";

			// Focus on & refresh our selected instance
			if (!noFocus) {setTimeout(function() {cM.focus();},4);}
			cM.refresh();

			// Highlight the selected tab
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Redo our find display
			top.ICEcoder.findMode = false;
			ICEcoder.findReplace(top.document.getElementById('find').value,true,false);

			// Rerun JS Hint
			if (top.ICEcoder.codeAssist) {top.ICEcoder.updateHints()};

			// Finally, update the cursor display
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
		}
	},

	// Starts a new file by setting a few vars & creating a new cM instance
	newTab: function() {
		var cM;

		ICEcoder.cMInstances.push(ICEcoder.nextcMInstance);
		ICEcoder.selectedTab = ICEcoder.cMInstances.length;
		ICEcoder.showHide('show',ICEcoder.content);
		ICEcoder.content.contentWindow.createNewCMInstance(ICEcoder.nextcMInstance);
		ICEcoder.setLayout();

		ICEcoder.thisFileFolderType='file';
		ICEcoder.thisFileFolderLink='/[NEW]';
		ICEcoder.openFile();

		cM = ICEcoder.getcMInstance('new');
		ICEcoder.switchTab(ICEcoder.openFiles.length);

		cM.removeLineClass(ICEcoder['cMActiveLine'+ICEcoder.selectedTab], "background");
		ICEcoder['cMActiveLine'+ICEcoder.selectedTab] = cM.addLineClass(0, "background", "cm-s-activeLine");
		ICEcoder.nextcMInstance++;
	},

	// Create a new tab for a file
	createNewTab: function() {
		var closeTabLink, fileName;

		// Push new file into array
		top.ICEcoder.openFiles.push(top.ICEcoder.shortURL);

		// Setup a new tab
		closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
		top.document.getElementById('tab'+(top.ICEcoder.openFiles.length)).style.display = "inline-block";
		fileName = top.ICEcoder.openFiles[top.ICEcoder.openFiles.length-1];
		top.document.getElementById('tab'+(top.ICEcoder.openFiles.length)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		top.document.getElementById('tab'+(top.ICEcoder.openFiles.length)).title = "/" + top.ICEcoder.openFiles[top.ICEcoder.openFiles.length-1].replace(/\//,"");

		// Set the widths
		top.ICEcoder.setTabWidths();

		// Highlight it and state it's selected
		top.ICEcoder.redoTabHighlight(top.ICEcoder.openFiles.length);
		top.ICEcoder.selectedTab=top.ICEcoder.openFiles.length;

		// Add a new value ready to indicate if this content has been changed
		top.ICEcoder.changedContent.push(0);

		top.ICEcoder.setPreviousFiles();
	},

	// Cycle to next tab
	nextTab: function() {
		var goToTab;

		goToTab = top.ICEcoder.selectedTab+1 <= top.ICEcoder.openFiles.length ? top.ICEcoder.selectedTab+1 : 1;
		top.ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Cycle to next tab
	previousTab: function() {
		var goToTab;

		goToTab = top.ICEcoder.selectedTab-1 >= 1 ? top.ICEcoder.selectedTab-1 : top.ICEcoder.openFiles.length;
		top.ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Create a new tab for a file
	renameTab: function(tabNum,newName) {
		var closeTabLink, fileName;

		// Push new file into array
		top.ICEcoder.openFiles[tabNum-1] = newName;

		// Setup a new tab
		closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
		fileName = top.ICEcoder.openFiles[tabNum-1];
		top.document.getElementById('tab'+tabNum).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		top.document.getElementById('tab'+tabNum).title = "/" + top.ICEcoder.openFiles[tabNum-1].replace(/\//,"");
	},

	// Reset all tabs to be without a highlight and then highlight the selected
	redoTabHighlight: function(selectedTab) {
		var bgVPos, tColor, fileLink;

		for(var i=1;i<=ICEcoder.changedContent.length;i++) {
			if (document.getElementById('tab'+i).childNodes[0]) {
				document.getElementById('tab'+i).childNodes[0].childNodes[0].style.backgroundColor = ICEcoder.changedContent[i-1]==1
				? "#b00" : "transparent";
			}
			tColor = i==selectedTab ? "#000" : "#fff";
			if ("undefined" != typeof top.ICEcoder.openFiles[i-1] && top.ICEcoder.openFiles[i-1] != "/[NEW]") {
				fileLink = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.openFiles[i-1].replace(/\//g,"|"));
				if (fileLink) {fileLink.style.backgroundColor = i==selectedTab ? "#49d" : "rgba(255,255,255,0.15)";};
			}
			document.getElementById('tab'+i).style.color = tColor;
			bgVPos = i==selectedTab ? -22 : 0;
			document.getElementById('tab'+i).style.backgroundPosition = "0 "+bgVPos+"px";
		}
		top.ICEcoder.fMIconVis('fMSave',ICEcoder.changedContent[selectedTab-1]==1 ? 1 : 0.3);
	},

	// Close the tab upon request
	closeTab: function(closeTabNum, dontSetPV) {
		var cM, okToRemove, closeFileName;

		cM = ICEcoder.getcMInstance();
		okToRemove = true;
		if (ICEcoder.changedContent[closeTabNum-1]==1) {
			okToRemove = top.ICEcoder.ask('You have made changes.\n\nAre you sure you want to close without saving?');
		}

		if (okToRemove) {
			// Get the filename of tab we're closing
			closeFileName = top.ICEcoder.openFiles[closeTabNum-1];

			// recursively copy over all tabs & data from the tab to the right, if there is one
			for (var i=closeTabNum;i<ICEcoder.openFiles.length;i++) {
				top.document.getElementById('tab'+i).innerHTML = top.document.getElementById('tab'+(i+1)).innerHTML;
				top.document.getElementById('tab'+i).title = top.document.getElementById('tab'+(i+1)).title;
				ICEcoder.openFiles[i-1] = ICEcoder.openFiles[i];
				ICEcoder.openFileMDTs[i-1] = ICEcoder.openFileMDTs[i];
			}
			// hide the instance we're closing by setting the hide class and removing from the array
			ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[closeTabNum-1]].getWrapperElement().style.display = "none";
			top.ICEcoder.cMInstances.splice(closeTabNum-1,1);
			// clear the rightmost tab (or only one left in a 1 tab scenario) & remove from the array
			top.document.getElementById('tab'+ICEcoder.openFiles.length).style.display = "none";
			top.document.getElementById('tab'+ICEcoder.openFiles.length).innerHTML = "";
			top.document.getElementById('tab'+ICEcoder.openFiles.length).title = "";
			ICEcoder.openFiles.pop();
			ICEcoder.openFileMDTs.pop();
			// If we're closing the selected tab, determin the new selectedTab number, reduced by 1 if we have some tabs, 0 for a reset state
			if (ICEcoder.selectedTab==closeTabNum) {
				ICEcoder.openFiles.length>0 ? ICEcoder.selectedTab-=1 : ICEcoder.selectedTab = 0;
			}
			if (ICEcoder.openFiles.length>0 && ICEcoder.selectedTab==0) {ICEcoder.selectedTab=1};

			// grey out the view icon
			if (ICEcoder.openFiles.length==0) {
				top.ICEcoder.fMIconVis('fMView',0.3);
			} else {
				// Switch the mode & the tab
				ICEcoder.switchMode();
				ICEcoder.switchTab(ICEcoder.selectedTab);
			}
			// Highlight the selected tab after splicing the change state out of the array
			top.ICEcoder.changedContent.splice(closeTabNum-1,1);
			top.ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Update the nesting indicator
			top.ICEcoder.getNestLocation('update');

			// Remove any highlighting from the file manager
			top.ICEcoder.selectDeselectFile('deselect',top.ICEcoder.filesFrame.contentWindow.document.getElementById(closeFileName.replace(/\//g,"|")));

			if (!dontSetPV) {
				top.ICEcoder.setPreviousFiles();
			}
		}
		// Lastly, stop it from trying to also switch tab
		top.ICEcoder.canSwitchTabs=false;
		// and set the widths
		top.ICEcoder.setTabWidths('posOnlyNewTab');
		setTimeout(function() {top.ICEcoder.canSwitchTabs=true;},100);
	},

	// Close all tabs
	closeAllTabs: function() {
		if (ICEcoder.ask("Close all tabs?")) {
			for (var i=top.ICEcoder.cMInstances.length; i>0; i--) {
				top.ICEcoder.closeTab(i, i>1? true:false);
			}
		}
	},

	// Set the tabs width
	setTabWidths: function(posOnlyNewTab) {
		var availWidth, avgWidth, tabWidth, lastLeft, lastWidth;

		availWidth = parseInt(top.ICEcoder.content.style.width,10)-41-24-10; // - left margin - new tab - right margin
		avgWidth = (availWidth/top.ICEcoder.openFiles.length)-18;
		tabWidth = -18; // Incl 18px offset
		lastLeft = 41;
		lastWidth = 0;
		top.ICEcoder.tabLeftPos = [];
		for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
			if (posOnlyNewTab) {i=top.ICEcoder.openFiles.length};
			tabWidth = top.ICEcoder.openFiles.length*(150+18) > availWidth ? parseInt(avgWidth*i,10) - parseInt(avgWidth*(i-1),10) : 150;
			lastLeft = i==0 ? 41 : parseInt(top.document.getElementById('tab'+(i)).style.left,10);
			lastWidth = i==0 ? 0 : parseInt(top.document.getElementById('tab'+(i)).style.width,10)+18;
			if (!posOnlyNewTab) {
				top.document.getElementById('tab'+(i+1)).style.left = (lastLeft+lastWidth) + "px";
				top.document.getElementById('tab'+(i+1)).style.width = tabWidth + "px";
			} else {
				tabWidth = -18;
			}
			top.ICEcoder.tabLeftPos.push(lastLeft+lastWidth);
		}
		top.document.getElementById('newTab').style.left = (lastLeft+lastWidth+tabWidth+18) + "px";
	},

	// Tab dragging start
	tabDragStart: function(tab) {
		top.ICEcoder.draggingTab = tab;
		top.ICEcoder.diffStartX = top.ICEcoder.mouseX;
		top.ICEcoder.tabDragMouseXStart = (top.ICEcoder.mouseX - (parseInt(top.ICEcoder.files.style.width,10)+41+18)) % 150;
		top.document.getElementById('tab'+tab).style.zIndex = 2;
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			top.document.getElementById('tab'+i).className = i!==tab
			? "tab tabSlide"
			: "tab tabDrag";
		}
	},

	// Tab dragging
	tabDragMove: function() {
		var lastTabWidth, thisLeft, dragTabNo, tabWidth;

		lastTabWidth = parseInt(top.document.getElementById('tab'+top.ICEcoder.openFiles.length).style.width,10)+18;

		top.ICEcoder.thisLeft = thisLeft = top.ICEcoder.tabDragMouseX >= 41
		? top.ICEcoder.tabDragMouseX <= parseInt(top.document.getElementById('newTab').style.left,10) - lastTabWidth
		? top.ICEcoder.tabDragMouseX : (parseInt(top.document.getElementById('newTab').style.left,10) - lastTabWidth) : 41;

		top.document.getElementById('tab'+top.ICEcoder.draggingTab).style.left = thisLeft + "px";

		top.ICEcoder.dragTabNo = dragTabNo = top.ICEcoder.draggingTab;
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			top.document.getElementById('tab'+i).style.opacity = i == top.ICEcoder.draggingTab ? 1 : 0.5;
			tabWidth = top.ICEcoder.tabLeftPos[i] ? top.ICEcoder.tabLeftPos[i] - top.ICEcoder.tabLeftPos[i-1] : tabWidth;
			if (i!=top.ICEcoder.draggingTab) {
				if (i < top.ICEcoder.draggingTab) {
					top.document.getElementById('tab'+i).style.left = thisLeft <= top.ICEcoder.tabLeftPos[i-1]
					? top.ICEcoder.tabLeftPos[i-1]+tabWidth
					: top.ICEcoder.tabLeftPos[i-1];
				} else {
					top.document.getElementById('tab'+i).style.left = thisLeft >= top.ICEcoder.tabLeftPos[i-1]
					? top.ICEcoder.tabLeftPos[i-1]-tabWidth
					: top.ICEcoder.tabLeftPos[i-1];
				}
			}
		}
	},

	// Tab dragging end
	tabDragEnd: function() {
		var swapWith, tempArray;

		top.ICEcoder.setTabWidths();
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			if (top.ICEcoder.thisLeft >= top.ICEcoder.tabLeftPos[i-1]) {
				swapWith = top.ICEcoder.thisLeft == top.ICEcoder.tabLeftPos[0] ? 1 : top.ICEcoder.dragTabNo > i ? i+1 : i;
			}
			top.document.getElementById('tab'+i).className = "tab";
			top.document.getElementById('tab'+i).style.opacity = 1;
			if (i!=top.ICEcoder.dragTabNo) {
				top.document.getElementById('tab'+i).style.zIndex = 1;
			} else {
				setTimeout(function() {
					top.document.getElementById('tab'+i).style.zIndex = 1;
				},150);
			}
		}
		if (top.ICEcoder.thisLeft && top.ICEcoder.thisLeft!==false) {
			tempArray = [];
			for (var i=1;i<=top.ICEcoder.openFiles.length;i++) {
				tempArray.push(i);
			}
			tempArray.splice(top.ICEcoder.dragTabNo-1,1);
			tempArray.splice(swapWith-1,0,top.ICEcoder.dragTabNo);
			ICEcoder.sortTabs(tempArray);
		}
		top.ICEcoder.setTabWidths();
		top.ICEcoder.draggingTab = false;
		top.ICEcoder.thisLeft = false;
	},

	// Sort tabs into new order
	sortTabs: function(newOrder) {
		var a, b, changedContent = [], openFiles = [], openFileMDTs = [], cMInstances = [], selectedTabWillBe;

		a = [ICEcoder.changedContent, ICEcoder.openFiles, ICEcoder.openFileMDTs, ICEcoder.cMInstances];
		b = [changedContent, openFiles, openFileMDTs, cMInstances];
		for (var i=0;i<a.length;i++) {
			for (var j=0;j<a[i].length;j++) {
				b[i].push(a[i][newOrder[j]-1]);
			}
			a[i] = b[i];
		}
		for (var i=0;i<newOrder.length;i++) {
			document.getElementById('tab'+newOrder[i]).id = "tab" + (i+1) + ".temp";
			if (top.ICEcoder.selectedTab == newOrder[i]) {
				selectedTabWillBe = (i+1);
			}
		}
		for (var i=0;i<newOrder.length;i++) {
			document.getElementById('tab'+(i+1)+'.temp').id = "tab"+(i+1);
		}
		if (top.document.getElementById('tab'+selectedTabWillBe)) {
			top.document.getElementById('tab'+selectedTabWillBe).className = "tab tabSlide";
		}
		ICEcoder.changedContent = a[0];
		ICEcoder.openFiles = a[1];
		ICEcoder.openFileMDTs = a[2];
		ICEcoder.cMInstances = a[3];
		top.ICEcoder.setTabWidths();
		top.ICEcoder.switchTab(selectedTabWillBe);
	},

	// Alphabetize tabs
	alphaTabs: function() {
		var currentArray, currentArrayFull, alphaArray, nextValue, nextPos;
		
		currentArray = [];
		currentArrayFull = [];
		alphaArray = [];
		for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
			currentArray.push(top.ICEcoder.openFiles[i].slice(top.ICEcoder.openFiles[i].lastIndexOf('/')+1));
			currentArrayFull.push(top.ICEcoder.openFiles[i]);
			top.document.getElementById('tab'+(i+1)).className = "tab tabSlide";
		}
		while (currentArray.length>0) {
			nextValue = currentArray[0];
			nextValueFull = currentArrayFull[0];
			nextPos = 0;
			for (var i=0;i<currentArray.length;i++) {
				if (currentArray[i] < nextValue) {
					nextValue  = currentArray[i];
					nextValueFull  = top.ICEcoder.openFiles[top.ICEcoder.openFiles.indexOf(currentArrayFull[i])];
					nextPos = i;
				}
			}
			alphaArray.push((top.ICEcoder.openFiles.indexOf(nextValueFull)+1));
			currentArray.splice(nextPos,1);
			currentArrayFull.splice(nextPos,1);
		}
		top.ICEcoder.sortTabs(alphaArray);
	},

// ==============
// UI
// ==============

	// Detect keys/combos plus identify our area and set the vars, perform actions
	interceptKeys: function(area, evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// DEL (Delete file)
		if (key==46 && area == "files") {
			top.ICEcoder.deleteFile();
	        	return false;
		};

		// Alt key down?
		if (evt.altKey) {

			// + CTRL + key (tag wrapper or add line break at end)
			if (evt.ctrlKey && area == "content") {
				if (key==68) {top.ICEcoder.tagWrapper('div'); return false;}
				else if (key==83) {top.ICEcoder.tagWrapper('span'); return false;}
				else if (key==80) {top.ICEcoder.tagWrapper('p'); return false;}
				else if (key==65) {top.ICEcoder.tagWrapper('a'); return false;}
				else if (key==66) {top.ICEcoder.tagWrapper('b'); return false;}
				else if (key==73) {top.ICEcoder.tagWrapper('i'); return false;}
				else if (key==71) {top.ICEcoder.tagWrapper('strong'); return false;}
				else if (key==69) {top.ICEcoder.tagWrapper('em'); return false;}
				else if (key==49) {top.ICEcoder.tagWrapper('h1'); return false;}
				else if (key==50) {top.ICEcoder.tagWrapper('h2'); return false;}
				else if (key==51) {top.ICEcoder.tagWrapper('h3'); return false;}
				else if (key==52) {top.ICEcoder.tagWrapper('h4'); return false;}
				else if (key==53) {top.ICEcoder.tagWrapper('h5'); return false;}
				else if (key==54) {top.ICEcoder.tagWrapper('h6'); return false;}
				else if (key==55) {top.ICEcoder.tagWrapper('h7'); return false;}
				else if (key==13) {top.ICEcoder.addLineBreakAtEnd(); return false;}
				else {return key;}
			} else {return key;}

		} else {

			// CTRL+F (Find)
			if(key==70 && evt.ctrlKey) {
				top.document.getElementById('find').focus();
	        		return false;

			// CTRL+G (Go to line)
			} else if(key==71 && evt.ctrlKey) {
				top.document.getElementById('goToLineNo').focus();
	        		return false;

			// CTRL+I (Get info)
			} else if(key==73 && evt.ctrlKey && area == "content") {
				var searchPrefix = top.ICEcoder.caretLocType.toLowerCase()+" ";
				if (top.ICEcoder.caretLocType=="CSS"||top.ICEcoder.caretLocType=="PHP") {
					window.open("http://voke.fm/"+searchPrefix+top.ICEcoder.getcMInstance().getSelection());
				} else {
					if (top.ICEcoder.caretLocType=="Content") {
						searchPrefix = "";
					}
					window.open("http://www.google.com/#output=search&q="+searchPrefix+top.ICEcoder.getcMInstance().getSelection());
				}
	        		return false;

			// CTRL+right arrow (Next tab)
			} else if(key==39 && evt.ctrlKey && area!="content") {
				top.ICEcoder.nextTab();
	        		return false;

			// CTRL+left arrow (Previous tab)
			} else if(key==37 && evt.ctrlKey && area!="content") {
				top.ICEcoder.previousTab();
	        		return false;

			// CTRL+up arrow (Move line up)
			} else if(key==38 && evt.ctrlKey && area=="content") {
				top.ICEcoder.moveLine('up');
	        		return false;

			// CTRL+down arrow (Move line down)
			} else if(key==40 && evt.ctrlKey && area=="content") {
				top.ICEcoder.moveLine('down');
	        		return false;

			// CTRL+numeric plus (New tab)
			} else if(key==107 && evt.ctrlKey) {
				area=="content"
				? top.ICEcoder.duplicateLine()
				: top.ICEcoder.newTab();
	        		return false;

			// CTRL+numeric minus (Close tab)
			} else if(key==109 && evt.ctrlKey) {
				area=="content"
				? top.ICEcoder.removeLine()
				: top.ICEcoder.closeTab(top.ICEcoder.selectedTab);
	        		return false;

			// CTRL+S (Save), CTRL+Shift+S (Save As)
			} else if(key==83 && evt.ctrlKey) {
				if(evt.shiftKey) {
					top.ICEcoder.saveFile('saveAs');
				} else {
					top.ICEcoder.saveFile();
				}
	        		return false;

			// CTRL+Enter (Open Webpage)
			} else if(key==13 && evt.ctrlKey && top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] != "/[NEW]") {
				window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
	        		return false;

			// CTRL+O (Open Prompt)
			} else if(key==79 && evt.ctrlKey) {
				top.ICEcoder.openPrompt();
	        		return false;

			// CTRL+Space (Show snippet)
			} else if(key==32 && evt.ctrlKey && area=="content") {
				top.ICEcoder.addSnippet();
	        		return false;

			// CTRL+J (Jump to definition)
			} else if(key==74 && evt.ctrlKey && area=="content") {
				top.ICEcoder.jumpToDefinition();
	        		return false;

			// ESC in content area (Comment/Uncomment line)
       			} else if(key==27 && area == "content") {
				top.ICEcoder.lineCommentToggle();
	        		return false;

			// ESC not in content area (Cancel all actions)
	       		} else if(key==27 && area != "content") {
				top.ICEcoder.cancelAllActions();
		        	return false;

			// Any other key
			} else {
	        		return key;
        		}
        	}
	},

	// Reset the state of keys back to the normal state
	resetKeys: function(evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;
	}, 

	// Add snippet panel below line
	addSnippet: function() {
		var cM, lineNo, whiteSpace, content;

		cM = ICEcoder.getcMInstance();
		lineNo = cM.getCursor().line;
		whiteSpace = cM.getLine(lineNo).length - cM.getLine(lineNo).replace(/^\s\s*/, '').length;
		content = cM.getLine(lineNo).slice(whiteSpace);
		if (content.slice(0,8)=="function") {
			top.ICEcoder.doSnippet('function','function VAR() {\nINDENT\tCURSOR\nINDENT}');
		} else if (content.slice(0,2)=="if") {
			top.ICEcoder.doSnippet('if','if (CURSOR) {\nINDENT\t\nINDENT}');
		} else if (content.slice(0,3)=="for") {
			top.ICEcoder.doSnippet('for','for (var i=0; i<CURSOR; i++) {\nINDENT\t\nINDENT}');
		}
	},

	// Action a snippet
	doSnippet: function(tgtString,replaceString) {
		var cM, lineNo, lineContents, remainder, strPos, replacedLine, whiteSpace, curPos, sPos, lineNoCount;

		cM = top.ICEcoder.getcMInstance();
		lineNo = cM.getCursor().line;
		lineContents = cM.getLine(lineNo);
		if (lineContents.indexOf(tgtString)>-1) {
			remainder = cM.getLine(lineNo);
			strPos = remainder.indexOf(tgtString);
			remainder = remainder.slice(remainder.indexOf(tgtString)+tgtString.length);
			replaceString = replaceString.replace(/VAR/g,remainder);
			replacedLine = cM.getLine(lineNo).slice(0,strPos);
			whiteSpace = cM.getLine(lineNo).length - cM.getLine(lineNo).replace(/^\s\s*/, '').length;
			whiteSpace = cM.getLine(lineNo).slice(0,whiteSpace);
			replaceString = replaceString.replace(/INDENT/g,whiteSpace);
			replacedLine += replaceString;
			curPos = replacedLine.indexOf("CURSOR");
			sPos = 0;
			lineNoCount = lineNo;
			for (i=0;i<replacedLine.length;i++) {
				if (replacedLine.indexOf("\n",sPos)<replacedLine.indexOf("CURSOR")) {
					sPos = replacedLine.indexOf("\n",sPos)+1;
					lineNoCount = lineNoCount+1;
				}
			}
			cM.setLine(lineNo, replacedLine.replace("CURSOR",""));
			cM.setCursor(lineNoCount,curPos);
			cM.focus();
		}
	}
};