
function doIframe(){
	o = document.getElementsByTagName('iframe');
	for(i=0;i<o.length;i++){
		if (/\bautoHeight\b/.test(o[i].className)){
			setHeight(o[i]);
			addVambEve(o[i],'load', doIframe);
		}
	}
}

function setHeight(e){
	if(e.contentDocument){
		e.height = e.contentDocument.body.offsetHeight + 1;
	} else {
		e.height = e.contentWindow.document.body.scrollHeight;
	}
}

function addVambEve(obj, evType, fn){
	if(obj.addEventListener)
	{
	obj.addEventListener(evType, fn,false);
	return true;
	} else if (obj.attachEvent){
	var r = obj.attachEvent("on"+evType, fn);
	return r;
	} else {
	return false;
	}
}

if (document.getElementById && document.createTextNode){
 addVambEve(window,'load', doIframe);	
}
