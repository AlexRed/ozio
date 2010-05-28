function setFlashWidth(divid, newW){
	document.getElementById(divid).style.width = newW+"px";
}
function setFlashHeight(divid, newH){
	window.scrollTo(0,0);

	var el = document.getElementById(divid);  
	if(el.currentStyle) {  
		//alert("IE");
		el.style.height = newH+"px";  
	}
	else {
		//alert("FF");
		el.style.setProperty("height", (newH+"px"), null);  
	}
}
function setFlashHeight2(divid, newH){
	document.getElementById(divid).style.height = newH+"px";		
}
function setFlashSize(divid, newW, newH){
	setFlashWidth(divid, newW);
	setFlashHeight(divid, newH);
}
function canResizeFlash(){
	var ua = navigator.userAgent.toLowerCase();
	var opera = ua.indexOf("opera");
	if( document.getElementById ){
		if(opera == -1) return true;
		else if(parseInt(ua.substr(opera+6, 1)) >= 7) return true;
	}
	return false;
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  }
	else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  }
	else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}