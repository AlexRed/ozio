var $viewerSelected = "fancybox";
var $url=document.URL.split("?", 2);

/*if ($url.length == 2) {
	var $queryParams = $url[1].split("&");
	for ($queryParam in $queryParams) {
		var $split = $queryParams[$queryParam].split("=", 2);
		if ($split[0] === "viewer") {
			$viewerSelected = $split[1];
		}
	}
}*/

//$(document).ready(function() {
jQuery(document).ready(function ($) {
	var $viewerShow, $viewerJs, $viewerName;
	$('#FancyboxCode').hide();
	switch($viewerSelected)
	{
		case "fancybox":
			$viewerName = "FancyBox";
			$viewerCss  = "js/jquery.fancybox/jquery.fancybox.css";
			$viewerJs   = "js/jquery.fancybox/jquery.fancybox.pack.js";
			$('#FancyboxCode').show();
			break;
		case "slimbox":
			$viewerName = "SlimBox2";
			$viewerCss  = "js/jquery.slimbox2/jquery.slimbox2.css";
			$viewerJs   = "js/jquery.slimbox2/jquery.slimbox2.js";
			break;
		case "colorbox":
			$viewerName = "ColorBox";
			$viewerCss  = "js/jquery.colorbox/colorbox.css";
			$viewerJs   = "js/jquery.colorbox/jquery.colorbox-min.js";
			break;
		default:
			alert("Unknown viewer selected");
			$viewerName = "unknown";
			$viewerCss  = "unknown";
			$viewerJs   = "unknown";
			break;
	}

	$("#jqueryVersion").text($().jquery);
	$("#viewername").text($viewerName);
	$("#viewernameCss").text($viewerCss);
	$("#viewernameJs").text($viewerJs);
	//$("a#inline").fancybox({closeClick: false});

	var settings = {
	// Ignora i comandi tramite parametri GET ?par=...
     useQueryParameters: false,

		username: '116764043164650913090',
		//username: 'demis.palma',

		// Filtro sugli album utente
	  //albums: ["Fuerteventura", "LeDuneDiCorralejoDopoLUraganoNadine27Settembre2012"],

        //popupPlugin: "slimbox",

		 mode: 'album',
		 album: "Fuerteventura",

		popupPlugin: $viewerSelected
	};
	$("#container").pwi(settings);

});
