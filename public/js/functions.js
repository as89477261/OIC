/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


function resizeToFullscreen() {
	window.moveTo(0, 0);
	//alert(screen.availWidth);
	//alert(screen.availHeight);
	if (document.all) {
		top.window.resizeTo(screen.availWidth, screen.availHeight);
	} else if (document.layers || document.getElementById) {
		if (top.window.outerHeight < screen.availHeight
				|| top.window.outerWidth < screen.availWidth) {
			top.window.outerHeight = 1024;
			top.window.outerWidth = 700;
		}
	}
}