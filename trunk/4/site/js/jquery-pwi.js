(function ($)
{
	var elem, opts = {};
	$.fn.pwi = function (opts)
	{
		var $self, settings = {}, strings = {};
		opts = $.extend(true, {}, $.fn.pwi.defaults, opts);
		if (opts.popupPlugin == "")
		{
			// Detect the popup plugin type
			if ($.fn.fancybox)
			{
				opts.popupPlugin = "fancybox";
			}
			else if ($.fn.colorbox)
			{
				opts.popupPlugin = "colorbox";
			}
			else if ($.fn.slimbox)
			{
				opts.popupPlugin = "slimbox";
			}
		}

		if (opts.popupExt == "")
		{
			if (opts.popupPlugin === "fancybox")
			{
				opts.popupExt = function (photos, rel)
				{
					rel = typeof(rel) != "undefined" ? rel : "lb";
					if (rel === "lb")
					{     // Settings for normal photos
						photos.fancybox(opts.fancybox_config.config_photos);
					}
					else if (rel === "yt")
					{     // Settings for youtube videos
						photos.fancybox(opts.fancybox_config.config_youtube);
					}
					else if (rel === "map")
					{    // Settings for maps
						photos.fancybox(opts.fancybox_config.config_maps);
					}
					else if (rel === "map_overview")
					{    // Settings for overview-map
						photos.fancybox(opts.fancybox_config.config_map_overview);
					}
				};
			}
			else if (opts.popupPlugin === "colorbox")
			{
				opts.popupExt = function (photos, rel)
				{
					rel = typeof(rel) != "undefined" ? rel : "lb";
					if (rel === "lb")
					{     // Settings for normal photos
						photos.colorbox(opts.colorbox_config.config_photos);
					}
					else if (rel === "yt")
					{     // Settings for youtube videos
						photos.colorbox(opts.colorbox_config.config_youtube);
					}
					else if (rel === "map")
					{    // Settings for maps
						photos.colorbox(opts.colorbox_config.config_maps);
					}
					else if (rel === "map_overview")
					{    // Settings for overview-map
						photos.colorbox(opts.colorbox_config.config_map_overview);
					}
				};
			}
		}

		elem = this;
		function _initialize()
		{
			settings = opts;
			ts = new Date().getTime();
			settings.id = ts;
			strings = $.fn.pwi.strings;
			$self = $("<div id='pwi_" + ts + "'/>").appendTo(elem);
			$self.addClass('pwi_container');
			return _start();
		}

		function _start()
		{

			if (settings.username === '')
			{
				alert('Make sure you specify at least your username.' + '\n' +
					'See http://pwi.googlecode.com for more info');
				return;
			}

			if (settings.useQueryParameters)
			{
				var $url = document.URL.split("?", 2);
				if ($url.length == 2)
				{
					var $queryParams = $url[1].split("&");
					var $queryActive = false;
					var $page = 1;
					for ($queryParam in $queryParams)
					{
						var $split = $queryParams[$queryParam].split("=", 2);
						if ($split.length == 2)
						{
							switch ($split[0])
							{
								case 'pwi_album_selected':
									settings.mode = 'album';
									settings.album = $split[1];
									$queryActive = true;
									break;
								case 'pwi_albumpage':
									$page = $split[1];
									break;
								case 'pwi_showpermalink':
									settings.showPermaLink = true;
									break;
							}
						}
					}
					if ($queryActive)
					{
						settings.page = $page;
						settings.showPermaLink = false;
					}
				}
			}

			switch (settings.mode)
			{
				case 'latest':
					getLatest();
					break;
				case 'album':
				case 'keyword':
					getAlbum();
					break;
				case 'album_cover':
					GetAlbumCover();
					break;
				case 'album_data':
					GetAlbumData();
					break;
				case 'user_albums':
					GetUserAlbums();
					break;
				default:
					getAlbums();
					break;
			}
		}

		// Function:        formatDate
		// Description:     Format date to <day>-<month>-<year>
		// Parameters:      $dt: String containing a numeric date/time
		// Return:          Date string
		// Todo: compatibilita' con anno a 2 cifre, ma 12 viene interpretato 1912
		function formatDate($dt)
		{
			var $today = new Date(Number($dt)),
				$year = $today.getUTCFullYear();
			if ($year < 1000)
			{
				$year += 1900;
			}
			return ($today.getUTCDate() + "-" + ($today.getUTCMonth() + 1) + "-" + $year);
		}

		// Function:        formatDateTime
		// Description:     Format date to <day>-<month>-<year> <hours>:<minutes>
		//                  Time is only shown when not equal to 00:00
		// Parameters:      $dt: String containing a numeric date/time
		// Return:          Date/Time string
		// Todo: compatibilita' con anno a 2 cifre, ma 12 viene interpretato 1912
		function formatDateTime($dt)
		{
			var $today = new Date(Number($dt));
			$year = $today.getUTCFullYear();
			if ($year < 1000)
			{
				$year += 1900;
			}
			if ($today == "Invalid Date")
			{
				return $dt;
			}
			else
			{
				if (($today.getUTCHours() == 0) && ($today.getUTCMinutes() == 0) &&
					($today.getUTCSeconds() == 0))
				{
					return ($today.getUTCDate() + "-" + ($today.getUTCMonth() + 1) + "-" + $year);
				}
				else
				{
					return ($today.getUTCDate() + "-" + ($today.getUTCMonth() + 1) + "-" + $year +
						" " + $today.getUTCHours() + ":" + ($today.getUTCMinutes() < 10 ? "0" +
						$today.getUTCMinutes() : $today.getUTCMinutes()));
				}
			}
		}

		// Function:        sortData
		// Description:     Sort array according to sortMode
		// Parameters:      j: array containing all photo or album records
		//                  sortMode: mode to sort; name or date; ascending or descending
		// Return:          Sorted array
		function sortData(entries, sortMode)
		{
			if (sortMode === "none")
				return;

			function ascDateSort(a, b)
			{
				return Number(a.gphoto$timestamp.$t) - Number(b.gphoto$timestamp.$t);
			}

			function descDateSort(a, b)
			{
				return Number(b.gphoto$timestamp.$t) - Number(a.gphoto$timestamp.$t);
			}

			function ascNameSort(a, b)
			{
				var nameA = a.title.$t.toLowerCase();
				var nameB = b.title.$t.toLowerCase();
				if (nameA < nameB)
				{
					return -1
				}
				if (nameA > nameB)
				{
					return 1
				}
				return 0;
			}

			function descNameSort(a, b)
			{
				var nameA = a.title.$t.toLowerCase();
				var nameB = b.title.$t.toLowerCase();
				if (nameA > nameB)
				{
					return -1
				}
				if (nameA < nameB)
				{
					return 1
				}
				return 0;
			}

			switch (sortMode)
			{
				case "ASC_DATE":
					entries.sort(ascDateSort);
					break;
				case "DESC_DATE":
					entries.sort(descDateSort);
					break;
				case "ASC_NAME":
					entries.sort(ascNameSort);
					break;
				case "DESC_NAME":
					entries.sort(descNameSort);
					break;
			}
		}


		// Function:        alignPictures
		// Description:     Align all pictures horizontally and vertically
		// Parameters:      divName: Name of the div containing the pictures
		// Return:          none
		function alignPictures(divName)
		{
			// Now make sure all divs have the same width and heigth
			var divHeigth = 0;
			var divWidth = 0;
			$(divName).each(function (index, element)
			{
				if (element.clientHeight > divHeigth)
				{
					divHeigth = element.clientHeight;
				}
				if (element.clientWidth > divWidth)
				{
					divWidth = element.clientWidth;
				}
			});
			$(divName).css('height', (divHeigth + 2) + 'px');
			if (settings.thumbAlign)
			{
				$(divName).css('width', (divWidth + 2) + 'px');
			}
		}


		// Function:        photo
		// Description:     Create a photo-div
		// Parameters:      j: element containing the photo data
		//                  hidden: photo should not be shown, but included in HTML
		//                  username: processed username
		// Return:          HTML containing the photo-div
		function photo(j, hidden, username)
		{
			var $html, $d = "", $c = "", $youtubeId = "", $caption;
			if (j.summary)
			{
				var $matched = j.summary.$t.match(/\[youtube\s*:\s*(.*)\s*\](.*)/);
				if ($matched)
				{ // Found youtube video entry
					$youtubeId = $matched[1];
					$c = $matched[2].replace(/[\r\n\t\s]+/g, ' ');
					$caption = $matched[2].replace(/[\n]/g, '<br/>');
				}
				else
				{
					$c = j.summary.$t.replace(/[\r\n\t\s]+/g, ' ');
					$caption = j.summary.$t.replace(/[\n]/g, '<br/>');
				}
			}
			if (settings.showPhotoDate)
			{
				if ((j.exif$tags) && (j.exif$tags.exif$time))
				{
					$d = formatDateTime(j.exif$tags.exif$time.$t) + " ";
				}
			}
			$d += $c.replace(new RegExp("'", "g"), "&#39;");
			var $thumbnail0 = j.media$group.media$thumbnail[0];
			var $thumbnail1 = j.media$group.media$thumbnail[1];

			if (hidden)
			{
				$html = $("<div class='pwi_photo' style='display: none'/>");
				if ($youtubeId == "")
				{
					$html.append("<a href='" + $thumbnail1.url + "' rel='lb-" +
						username + "' title='" + $d + "'></a>");
				}
			}
			else
			{
				$html = $("<div class='pwi_photo' style='cursor: pointer;'/>");
				if (($youtubeId == "") || (settings.popupPlugin === "slimbox"))
				{
					$html.append("<a href='" + $thumbnail1.url + "' rel='lb-" +
						username + "' title='" + $d +
						($youtubeId == "" ? "" : " (" + settings.labels.videoNotSupported + ")") +
						"'><img src='" + $thumbnail0.url + "' height='" + $thumbnail0.height +
						"' width='" + $thumbnail0.width + "'/></a>");
				}
				else
				{
					$html.append("<a class='" + (settings.popupPlugin === "fancybox" ?
						"fancybox.iframe" : "iframe") +
						"' href='http://www.youtube.com/embed/" + $youtubeId +
						"?autoplay=1&rel=0&hd=1&autohide=1' rel='yt-" + username +
						"' title='" + $d + "'><img id='main' src='" + $thumbnail0.url +
						"' height='" + $thumbnail0.height +
						"' width='" + $thumbnail0.width + "'/>" +
						"<img id='video' src='" + settings.videoBorder +
						"' height='" + $thumbnail0.height + "' /></a>");
				}
				if ((settings.showPhotoLocation) || (settings.showPhotoCaption))
				{
					$html.append("<br/>");
					if ((settings.popupPlugin !== "slimbox") && (settings.showPhotoLocation) &&
						(settings.mapIconLocation != "") &&
						(j.georss$where) && (j.georss$where.gml$Point) && (j.georss$where.gml$Point.gml$pos))
					{
						var $locationLink = $("<a class='" +
							(settings.popupPlugin === "fancybox" ? "fancybox.iframe" : "iframe") +
							"' href='http://maps.google.com/?output=embed&t=h&z=15&q=" +
							j.georss$where.gml$Point.gml$pos.$t +
							"' rel='map-" + settings.username + "'>" +
							"<img src='" + settings.mapIconLocation + "'></a>");
						$html.append($locationLink);
					}
					if (settings.showPhotoCaption)
					{
						if (settings.showPhotoCaptionDate && settings.showPhotoDate)
						{
							$c = $d;
						}
						if ($c.length > settings.showCaptionLength)
						{
							$c = $c.substring(0, settings.showCaptionLength);
						}
						if (settings.showPhotoDownload)
						{
							$c += '<a href="' + j.media$group.media$content[0].url + '">' +
								settings.labels.downloadphotos + '</a>';
						}
						$html.append($c);
					}
				}
				if (typeof (settings.onclickThumb) === "function")
				{
					var obj = j;
					$html.bind('click.pwi', obj, clickThumb);
				}
			}
			if (settings.showPhotoDownloadPopup)
			{
				var $downloadDiv = $("<div style='display: none'/>");
				$downloadDiv.append("<a class='downloadlink' href='" +
					j.media$group.media$content[0].url + "'/>");
				$html.append($downloadDiv);
			}
			var $captioDiv = $("<div style='display: none'/>");
			$captioDiv.append("<a class='captiontext'>" + $caption + "</a>");
			$html.append($captioDiv);
			return $html;
		}

		// Albums callback
		function albums(j)
		{
			var $scAlbums = $("<div class='scAlbums'/>");
			var $startDate, $endDate;
			if (navigator.userAgent.match(/(iPad)|(iPhone)|(iPod)/i) == null)
			{
				$startDate = new Date(settings.albumStartDateTime);
				if (isNaN($startDate))
				{
					$startDate = new Date(settings.albumStartDateTime.replace(/-/g, "/"));
				}
				$endDate = new Date(settings.albumEndDateTime);
				if (isNaN($endDate))
				{
					$endDate = new Date(settings.albumEndDateTime.replace(/-/g, "/"));
				}
			}
			else
			{
				$startDate = new Date(settings.albumStartDateTime.replace(/-/g, "/"));
				$endDate = new Date(settings.albumEndDateTime.replace(/-/g, "/"));
			}

			sortData(j.feed.entry, settings.sortAlbums);

			// Select albums to show
			var $albumCounter = 0;
			var $albumsToShow = $.grep(j.feed.entry, function (n, i)
			{
				if (i >= settings.albumMaxResults) return false;
				var $albumDate = new Date(Number(n.gphoto$timestamp.$t));

				// Identifica gli album con l'id univoco (gphoto$id), oltre che con il nome (gphoto$name), che potrebbe anche cambiare
				if (settings.albumStartDateTime != "" && $albumDate >= $startDate) return false;
				if (settings.albumEndDateTime != "" && $albumDate <= $endDate) return false;
				if ((n.gphoto$albumType !== undefined) && ($.inArray(n.gphoto$albumType.$t, settings.removeAlbumTypes) != -1)) return false;
				if (($.inArray(n.gphoto$id.$t, settings.albums) == -1) && ($.inArray(n.gphoto$name.$t, settings.albums) == -1)) return false;
				if (($.inArray(n.gphoto$id.$t, settings.removeAlbums) > -1) || ($.inArray(n.gphoto$name.$t, settings.removeAlbums) > -1)) return false;

				var $keywordMatch = true;
				if (settings.albumKeywords.length > 0)
				{
					$keywordMatch = false;
					var $matched = n.summary.$t.match(/\[keywords\s*:\s*(.*)\s*\]/);
					if ($matched)
					{
						var $keywordArray = new Array();
						var $keywords = $matched[1].split(/,/);
						for (var p in $keywords)
						{
							var $newmatch = $keywords[p].match(/\s*['"](.*)['"]\s*/);
							if ($newmatch)
							{
								$keywordArray.push($newmatch[1]);
							}
						}
						if ($keywordArray.length > 0)
						{
							$keywordMatch = true;
							for (var p in settings.albumKeywords)
							{
								if ($.inArray(settings.albumKeywords[p], $keywordArray) < 0)
								{
									$keywordMatch = false;
									break;
								}
							}
						}
					}
				}
				if ($keywordMatch == false)
					return false;

				$albumCounter++;
				if (($albumCounter > (settings.albumsPerPage * settings.albumPage)) ||
					($albumCounter <= (settings.albumsPerPage * (settings.albumPage - 1))))
					return false;
				else
					return true;

				return false;
			});

			// In caso di caricamento fallito, adesso crea un album vuoto e prosegue
			if ($albumsToShow.length == 0)
			{
				var obj = $.parseJSON(
					'{"feed":{"entry":[{"title":{"$t":"' + settings.labels.unknown + '"},"gphoto$timestamp":{"$t":0},"gphoto$numphotos":{"$t":0},"media$group":{"media$thumbnail":[{"url":"http://unknown.jpg","height":180,"width":180}]}}]}}'
				);
				$albumsToShow = obj.feed.entry;
			}

			// Show the selected albums
			$.each($albumsToShow, function (i, n)
			{

				// Build main album container
				var $scAlbum = $(
					"<div class='pwi_album' style='" +
						"width:" + (settings.albumThumbSize + 1) + "px;" +
						"'/>"
				);

				// Build album thumbnail image including the link to the local album url
				if (settings.showAlbumThumbs)
				{
					var $thumbnail0 = n.media$group.media$thumbnail[0];
					$scAlbum.append
						(
							'<a href="' + settings.album_local_url + '">' +
								"<img src='" + $thumbnail0.url +
								"' height='" + $thumbnail0.height +
								"' width='" + $thumbnail0.width +
								"' alt='" + n.title.$t +
								"' class='coverpage" +
								"'/>" +
								'</a>'
						);
				}

				// Build album title
				if (settings.showAlbumTitle)
				{
					// Google Plus Album title
					var title = $("<div class='pwi_album_title'/>");
					title.append(((n.title.$t.length > settings.showAlbumTitlesLength) ? n.title.$t.substring(0, settings.showCaptionLength) : n.title.$t));
					$scAlbum.append(title);
				}

				if (settings.showCustomTitle)
				{
					// Custom local album title
					var title = $("<div class='pwi_album_title'/>");
					title.append(settings.album_local_title);
					$scAlbum.append(title);
				}

				if (settings.showAlbumdate)
				{
					var date = $("<div class='pwi_album_title'/>");
					date.append('<span class="indicator og-calendar" ' + 'title="' + settings.labels.date + '">' + new Date(Number(n.gphoto$timestamp.$t))._format("d mmm yyyy") + '</span> ');
					$scAlbum.append(date);
				}

				if (settings.showAlbumPhotoCount)
				{
					var numphotos = $("<div class='pwi_album_title'/>");
					numphotos.append('<span class="indicator og-camera" ' + 'title="' + settings.labels.numphotos + '">' + n.gphoto$numphotos.$t + '</span> ');
					$scAlbum.append(numphotos);
				}

				$scAlbums.append($scAlbum);
			});

			// less albums-per-page then max so paging
			if ($albumCounter > settings.albumsPerPage)
			{
				var $pageCount = ($albumCounter / settings.albumsPerPage);
				var $ppage = $("<div class='pwi_prevpage'/>").text(settings.labels.prev),
					$npage = $("<div class='pwi_nextpage'/>").text(settings.labels.next);
				$navRow = $("<div class='pwi_pager'/>");
				if (settings.albumPage > 1)
				{
					$ppage.addClass('link').bind('click.pwi', function (e)
					{
						e.stopPropagation();
						settings.albumPage = (parseInt(settings.albumPage, 10) - 1);
						albums(j);
						return false;
					});
				}
				$navRow.append($ppage);
				for (var p = 1; p < $pageCount + 1; p++)
				{
					if (p == settings.albumPage)
					{
						tmp = "<div class='pwi_pager_current'>" + p + "</div> ";
					}
					else
					{
						tmp = $("<div class='pwi_pager_page'>" + p + "</div>").bind('click.pwi', p, function (e)
						{
							e.stopPropagation();
							settings.albumPage = e.data;
							albums(j);
							return false;
						});
					}
					$navRow.append(tmp);
				}
				if (settings.albumPage < $pageCount)
				{
					$npage.addClass('link').bind('click.pwi', function (e)
					{
						e.stopPropagation();
						settings.albumPage = (parseInt(settings.albumPage, 10) + 1);
						albums(j);
						return false;
					});
				}
				$navRow.append($npage);
				$navRow.append(strings.clearDiv);

				if ($navRow.length > 0 && (settings.showPager === 'both' || settings.showPager === 'top'))
				{
					$scAlbums.prepend($navRow.clone(true));
				}
				if ($navRow.length > 0 && (settings.showPager === 'both' || settings.showPager === 'bottom'))
				{
					$scAlbums.append($navRow);
				}
			}

			// end paging

			settings.albumstore = j;
			show(false, $scAlbums);

			alignPictures('div.pwi_album');
		}

		// Error retrieving albums callback
		function handle_albums_error(data)
		{
			var $scAlbums = $("<div class='scAlbums'/>");
			// Build main album container
			var $scAlbum = $(
				"<div class='pwi_album' style='" +
					"width:" + (settings.albumThumbSize + 1) + "px;" +
					"'/>"
			);

			// Custom local album title
			var title = $("<div class='pwi_album_title'/>");
			title.append(settings.labels.ajax_error);
			$scAlbum.append(title);

			$scAlbums.append($scAlbum);
			show(false, $scAlbums);

		}


		function album(j)
		{
			var $scPhotos, $scPhotosDesc, tmp = "",
				$np = j.feed.openSearch$totalResults.$t,
				$at = "", $navRow = "",
				$loc = j.feed.gphoto$location === undefined ? "" : j.feed.gphoto$location.$t,
				$ad,
				$album_date = formatDate(j.feed.gphoto$timestamp === undefined ? '' : j.feed.gphoto$timestamp.$t),
				$item_plural = ($np == "1") ? false : true;
			var $relUsername = settings.username.replace(/[@\.]/g, "_");

			if (j.feed.subtitle === undefined)
			{
				$ad = "";
			}
			else
			{
				var $matched = j.feed.subtitle.$t.match(/\[keywords\s*:\s*.*\s*\](.*)/);
				if ($matched)
				{
					$ad = $matched[1];
				}
				else
				{
					$ad = j.feed.subtitle.$t;
				}
			}

			$at = (j.feed.title === "undefined" || settings.albumTitle.length > 0) ? settings.albumTitle : j.feed.title.$t;
			$scPhotos = $("<div/>");
			if (settings.mode != 'album' && settings.mode != 'keyword')
			{
				tmp = $("<div class='pwi_album_backlink'>" + settings.labels.albums + "</div>").bind('click.pwi', function (e)
				{
					e.stopPropagation();
					getAlbums();
					return false;
				});
				$scPhotos.append(tmp);
			}
			if (settings.showAlbumDescription)
			{
				$scPhotosDesc = $("<div class='pwi_album_description'/>");
				$scPhotosDesc.append("<div class='title'>" + $at + "</div>");
				$scPhotosDesc.append("<div class='details'>" + $np + " " +
					($item_plural ? settings.labels.photos : settings.labels.photo) +
					(settings.showAlbumdate ? ", " + $album_date : "") +
					(settings.showAlbumLocation && $loc ? ", " + $loc : "") + "</div>");
				$scPhotosDesc.append("<div class='description'>" + $ad + "</div>");
				$scPhotos.append($scPhotosDesc);
			}

			if ((settings.popupPlugin !== "slimbox") && (settings.showPhotoLocation) && (typeof(google) != "undefined"))
			{
				var $geoTagged = $.grep(j.feed.entry, function (n, i)
				{
					if ((n.georss$where) && (n.georss$where.gml$Point) &&
						(n.georss$where.gml$Point.gml$pos))
					{
						return true;
					}
					else
					{
						return false
					}
				});

				var $globalMap = $("<div class='pwi_overviewmap' />");
				var $link = $("<a class='fancybox.inline' href='#map_canvas' rel='map_overview-" + $relUsername + "' >" +
					settings.labels.showMap + "</a>");
				if (($.browser.msie) && (parseFloat($.browser.version) < 8.0))
				{
					// For some reason the href field contains the complete path
					$link[0].href = "#map_canvas";
				}
				$globalMap.append($link);
				$scPhotos.append($globalMap);
				$scPhotos.append(strings.clearDiv);

				var $mapDiv = $("<div style='display:none' />");
				var $windowHeight = $(window).height() * 0.75;
				var $windowWidth = $(window).width() * 0.75;
				$mapDiv.append("<div id='map_canvas' style='width: " + $windowWidth + "px; height: " + $windowHeight + "px' />");
				$scPhotos.append($mapDiv);
				$.fn.pwi.additionalMapsSettings = $geoTagged;
			}

			if ($np > settings.maxResults)
			{
				$pageCount = ($np / settings.maxResults);
				var $ppage = $("<div class='pwi_prevpage'/>").text(settings.labels.prev),
					$npage = $("<div class='pwi_nextpage'/>").text(settings.labels.next);
				$navRow = $("<div class='pwi_pager'/>");
				if (settings.page > 1)
				{
					$ppage.addClass('link').bind('click.pwi', function (e)
					{
						e.stopPropagation();
						settings.page = (parseInt(settings.page, 10) - 1);
						getAlbum();
						return false;
					});
				}
				$navRow.append($ppage);
				for (var p = 1; p < $pageCount + 1; p++)
				{
					if (p == settings.page)
					{
						tmp = "<div class='pwi_pager_current'>" + p + "</div> ";
					}
					else
					{
						tmp = $("<div class='pwi_pager_page'>" + p + "</div>").bind('click.pwi', p, function (e)
						{
							e.stopPropagation();
							settings.page = e.data;
							getAlbum();
							return false;
						});
					}
					$navRow.append(tmp);
				}
				if (settings.page < $pageCount)
				{
					$npage.addClass('link').bind('click.pwi', function (e)
					{
						e.stopPropagation();
						settings.page = (parseInt(settings.page, 10) + 1);
						getAlbum();
						return false;
					});
				}
				$navRow.append($npage);
				$navRow.append(strings.clearDiv);
			}

			if ($navRow.length > 0 && (settings.showPager === 'both' || settings.showPager === 'top'))
			{
				$scPhotos.append($navRow);
			}

			sortData(j.feed.entry, settings.sortPhotos);

			var startShow = ((settings.page - 1) * settings.maxResults);
			var endShow = settings.maxResults * settings.page;
			for (var i = 0; i < $np; i++)
			{
				var $scPhoto = photo(j.feed.entry[i], !((i >= startShow) && (i < endShow)), $relUsername);
				$scPhotos.append($scPhoto);
			}

			if ($navRow.length > 0 && (settings.showPager === 'both' || settings.showPager === 'bottom'))
			{
				$scPhotos.append($navRow.clone(true));
			}

			if (settings.showPermaLink)
			{
				$scPhotos.append(strings.clearDiv);
				var $permaLinkEnable = $("<div id='permalinkenable' class='pwi_nextpage'/>").text(settings.labels.showPermaLink).bind('click.pwi', p, function (e)
				{
					e.stopPropagation();
					$('#permalinkbox').show();
					$('#permalinkenable').hide();
					return false;
				});

				var $url = document.URL.split("?", 2);
				var $permalinkUrl = $url[0] + "?pwi_album_selected=" + j.feed.gphoto$name.$t +
					"&pwi_albumpage=" + settings.page;

				$scPhotos.append($permaLinkEnable);
				var $permaShowBox = $("<div style='display:none;' id='permalinkbox' />");
				var $permaShowBoxForm = $("<form />");
				var $permalinkInputBox = $("<input type='text' size='40' name='PermaLink' readonly />").val($permalinkUrl);
				$permaShowBoxForm.append($permalinkInputBox);
				$permaShowBox.append($permaShowBoxForm);
				$scPhotos.append($permaShowBox);
			}

			settings.photostore[settings.album] = j;
			var $s = $(".pwi_photo", $scPhotos).css(settings.thumbCss);
			$scPhotos.append(strings.clearDiv);
			show(false, $scPhotos);

			alignPictures('div.pwi_photo');
		}


		function latest(j)
		{
			var $scPhotos = $("<div/>"),
				$len = j.feed ? j.feed.entry.length : 0,
				i = 0;
			var $relUsername = settings.username.replace(/[@\.]/g, "_");

			sortData(j.feed.entry, settings.sortPhotos);

			while (i < settings.maxResults && i < $len)
			{
				var $scPhoto = photo(j.feed.entry[i], false, $relUsername);
				$scPhotos.append($scPhoto);
				i++;
			}
			$scPhotos.append(strings.clearDiv);
			var $s = $("div.pwi_photo", $scPhotos).css(settings.thumbCss);
			if ((settings.popupPlugin === "fancybox") || (settings.popupPlugin === "colorbox"))
			{
				settings.popupExt($s.find("a[rel='lb-" + $relUsername + "']"));
				settings.popupExt($s.find("a[rel='yt-" + $relUsername + "']"), "yt");
				settings.popupExt($s.find("a[rel='map-" + $relUsername + "']"), "map");
				var $s = $(".pwi_overviewmap", $scPhotos).css(settings.thumbCss);
				settings.popupExt($s.find("a[rel='map_overview-" + $relUsername + "']"), "map_overview");
			}
			else if (settings.popupPlugin === "slimbox")
			{
				$s.find("a[rel='lb-" + $relUsername + "']").slimbox(settings.slimbox_config,
					function (el)
					{
						var $newTitle = el.title;
						if (el.parentNode.childNodes && (el.parentNode.childNodes.length > 1))
						{
							var $caption = $(".captiontext", el.parentNode);
							if ($caption.length > 0)
							{
								$newTitle = $caption[0].innerHTML;
							}
							var $links = $(".downloadlink", el.parentNode);
							if ($links.length > 0)
							{
								var downloadLink = '<a href="' + $links[0].href + '">Download</a>';
								$newTitle = $newTitle + "&nbsp;&nbsp;" + downloadLink;
							}
						}
						return [el.href, $newTitle];
					}
				);
			}
			show(false, $scPhotos);

			alignPictures('div.pwi_photo');
		}

		function clickAlbumThumb(event)
		{
			event.stopPropagation();
			event.preventDefault();
			settings.onclickAlbumThumb(event);
		}

		function clickThumb(event)
		{
			event.stopPropagation();
			event.preventDefault();
			settings.onclickThumb(event);
		}


		function getAlbums()
		{
			if (settings.albumstore.feed)
			{
				albums(settings.albumstore);
			}
			else
			{
				show(true, '');

				var $u = strings.picasaUrl + settings.username +
					'?kind=album&access=' + settings.albumTypes + '&alt=json&thumbsize=' +
					settings.albumThumbSize + (settings.albumCrop ? "c" : "u");

				$.ajax({
					url:$u,
					dataType:'json',
					success:albums,
					error:handle_albums_error
				});
			}

			return $self;
		}


		function checkPhotoSize(photoSize)
		{
			var $allowedSizes = [94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600];
			if (settings.photoSize === "auto")
			{
				var $windowHeight = $(window).height();
				var $windowWidth = $(window).width();
				var $minSize = ($windowHeight > $windowWidth) ? $windowWidth : $windowHeight;
				for (var i = 1; i < $allowedSizes.length; i++)
				{
					if ($minSize < $allowedSizes[i])
					{
						return $allowedSizes[i - 1];
					}
				}
			}
			else
			{
				return photoSize;
			}
		}

		function getAlbum()
		{
			if (settings.photostore[settings.album])
			{
				album(settings.photostore[settings.album]);
			}
			else
			{
				// Aggiunto supporto per album id numerico
				var numeric = settings.album.match(/^[0-9]{19}$/);
				var album_type;
				if (numeric) album_type = 'albumid';
				else album_type = 'album';

				var $u = strings.picasaUrl + settings.username +
					((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") + '?kind=photo&alt=json' +
					((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
					((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
					'&imgmax=d&thumbsize=' + settings.thumbSize +
					((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize);
				show(true, '');
				$.getJSON($u, 'callback=?', album);
			}
			return $self;
		}


		function GetAlbumCover()
		{
			// Aggiunto supporto per album id numerico
			// Pur essendo le foto dai posts un album in formato alfanumerico, va trattato come numerico (|posts)
			var numeric = settings.album.match(/^[0-9]{19}|posts$/);
			var album_type;
			if (numeric) album_type = 'albumid';
			else album_type = 'album';

			var url = strings.picasaUrl + settings.username + ((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") +
				'?imgmax=d' +
				// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
				((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
				((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");

			// http://api.jquery.com/jQuery.ajax/
			$.ajax({
				'url':url,
				'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
				'beforeSend':settings.beforeSend,
				'success':settings.success,
				'error':settings.error,
				'complete':settings.complete,

				'album_local_url':settings.album_local_url,
				'album_local_title':settings.album_local_title,
				'album_id':settings.album_id,
				'manual_date':settings.manual_date
			});

			return $self;
		}


		function GetAlbumData()
		{
			// Aggiunto supporto per album id numerico
			// Pur essendo le foto dai posts un album in formato alfanumerico, va trattato come numerico (|posts)
			var numeric = settings.album.match(/^[0-9]{19}|posts$/);
			var album_type;
			if (numeric) album_type = 'albumid';
			else album_type = 'album';

			var url = strings.picasaUrl + settings.username + ((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") +
				'?imgmax=d' +
				// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
				((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
				((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");


			// http://api.jquery.com/jQuery.ajax/
			$.ajax({
				'url':url,
				'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
				'beforeSend':settings.beforeSend,
				'success':settings.success,
				'error':settings.error,
				'complete':settings.complete
			});

			return $self;
		}


		function GetUserAlbums()
		{
			var url = strings.picasaUrl + settings.username +
				// alt can be 'json', 'rss' or 'atom' (https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats), but we need it as json to work with cross domain
				'?alt=json' +
				// '&kind=album' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
				'&access=' + settings.albumTypes +
				'&thumbsize=' + settings.albumThumbSize + (settings.albumCrop ? "c" : "u");

			// http://api.jquery.com/jQuery.ajax/
			$.ajax({
				'url':url,
				'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
				'beforeSend':settings.beforeSend,
				'success':settings.success,
				'error':settings.error,
				'complete':settings.complete
			});

			return $self;
		}


		function getLatest()
		{
			show(true, '');
			var $u = strings.picasaUrl + settings.username +
				(settings.album !== "" ? '/album/' + settings.album : '') +
				'?kind=photo&max-results=' + settings.maxResults + '&alt=json&q=' +
				((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
				((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
				'&imgmax=d&thumbsize=' + settings.thumbSize +
				((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize);
			$.getJSON($u, 'callback=?', latest);
			return $self;
		}

		function show(loading, data)
		{
			if (loading)
			{
				if (settings.loadingImage.length > 0)
				{
					$(settings.loadingImage).show();
				}
				document.body.style.cursor = "wait";
				if ($.blockUI)
				{
					$self.block(settings.blockUIConfig);
				}
			}
			else
			{
				if (settings.loadingImage.length > 0)
				{
					$(settings.loadingImage).hide();
				}
				document.body.style.cursor = "default";
				if ($.blockUI)
				{
					$self.unblock();
				}
				$self.html(data);
			}
		}

		return _initialize();
	};


	$.fn.pwi.defaults = {
		mode:'albums', //-- can be: album, albums, latest (keyword = obsolete but backwards compatible, now just fill in a keyword in the settings to enable keyword-photos)
		username:'', //-- Must be explicitly set!!!
		album:"", //-- For loading a single album
		authKey:"", //-- for loading a single album that is private (use in 'album' mode only)
		albums:[], //-- use to load specific albums only: ["MyAlbum", "TheSecondAlbumName", "OtherAlbum"]
		keyword:"",
		albumKeywords:[], //-- Only show albums containing one of these keywords in the description. Use [keywords: "kw1", "kw2"] within the description
		albumStartDateTime:"", //-- Albums on or after this date will be shown. Format: YYYY-MM-DDTHH:MM:SS or YYYY-MM-DD for date only
		albumEndDateTime:"", //-- Albums before or on this date will be shown
		albumCrop:true, //-- crop thumbs on albumpage to have all albums in square thumbs (see albumThumbSize for supported sizes)
		albumTitle:"", //-- overrule album title in 'album' mode
		albumThumbSize:160, //-- specify thumbnail size of albumthumbs (default: 72, supported cropped/uncropped: 32, 48, 64, 72, 104, 144, 150, 160 and uncropped only: 94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600)
		albumMaxResults:999, //-- load only the first X albums
		albumsPerPage:999, //-- show X albums per page (activates paging on albums when this amount is less then the available albums)
		albumPage:1, //-- force load on specific album
		albumTypes:"public", //-- load public albums, not used for now
		page:1, //-- initial page for an photo page
		photoSize:"auto", //-- size of large photo loaded in slimbox, fancybox or other. Allowed sizes: auto, 94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600
		maxResults:50, //-- photos per page
		showPager:'bottom', //'top', 'bottom', 'both' (for both albums and album paging)
		thumbSize:72, //-- specify thumbnail size of photos (default: 72, cropped not supported, supported cropped/uncropped: 32, 48, 64, 72, 104, 144, 150, 160 and uncropped only: 94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600)
		thumbCrop:false, //-- force crop on photo thumbnails (see thumbSize for supported sized)
		thumbAlign:false, //-- Allign thumbs vertically between rows
		thumbCss:{
			'margin':'5px'
		},
		onclickThumb:"", //-- overload the function when clicked on a photo thumbnail
		onclickAlbumThumb:"", //-- overload the function when clicked on a album thumbnail
		sortAlbums:"none", // Can be none, ASC_DATE, DESC_DATE, ASC_NAME, DESC_NAME
		sortPhotos:"none", // Can be none, ASC_DATE, DESC_DATE, ASC_NAME, DESC_NAME
		removeAlbums:[], //-- Albums with this type in the gphoto$albumType will not be shown. Known types are Blogger, ScrapBook, ProfilePhotos, Buzz, CameraSync
		removeAlbumTypes:[], //-- Albums with this type in the gphoto$albumType will not be shown. Known types are Blogger, ScrapBook, ProfilePhotos, Buzz, CameraSync
		showAlbumTitle:true, //--following settings should be self-explanatory
		showCustomTitle:false,
		showAlbumTitlesLength:9999,
		showAlbumThumbs:true,
		showAlbumdate:true,
		showAlbumPhotoCount:true,
		showAlbumDescription:true,
		showAlbumLocation:true,
		showPhotoCaption:false,
		showPhotoCaptionDate:false,
		showCaptionLength:9999,
		showPhotoDownload:false,
		showPhotoDownloadPopup:false,
		showPhotoDate:true,
		showPermaLink:false,
		showPhotoLocation:false,
		mapIconLocation:"",
		mapSize:0.75, // 75% of the window
		useQueryParameters:true,
		loadingImage:"",
		videoBorder:"images/video.jpg",
		labels:{
			photo:"photo",
			photos:"photos",
			downloadphotos:"Download photos",
			albums:"Back to albums",
			unknown:"Unknown",
			page:"Page",
			prev:"Previous",
			next:"Next",
			showPermaLink:"Show PermaLink",
			showMap:"Show Map",
			videoNotSupported:"Video not supported"
		}, //-- translate if needed
		months:["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		fancybox_config:{
			config_photos:{
				closeClick:false,
				nextEffect:'none',
				loop:false,
				beforeLoad:formatPhotoTitleFancyBox,
				helpers:{
					buttons:{}
				}
			},
			config_youtube:{
				arrows:false,
				fitToView:false,
				width:'90%',
				height:'90%',
				autoSize:false,
				closeClick:false,
				openEffect:'none',
				closeEffect:'none'
			},
			config_maps:{
				arrows:false,
				width:'90%',
				height:'90%'
			},
			config_map_overview:{
				arrows:false,
				afterShow:mapOverviewCallback
			}
		},
		colorbox_config:{
			config_photos:{
				title:formatPhotoTitleColorBox,
				loop:false,
				slideshow:true,
				slideshowAuto:false

			},
			config_youtube:{
				iframe:true,
				innerWidth:'80%',
				innerHeight:'80%',
				rel:'nofollow'
			},
			config_maps:{
				iframe:true,
				innerWidth:'80%',
				innerHeight:'80%',
				rel:'nofollow'
			},
			config_map_overview:{
				inline:true,
				rel:'nofollow',
				onComplete:mapOverviewCallback
			}
		},
		slimbox_config:{
			loop:false,
			overlayOpacity:0.6,
			overlayFadeDuration:400,
			resizeDuration:400,
			resizeEasing:"swing",
			initialWidth:250,
			initlaHeight:250,
			imageFadeDuration:400,
			captionAnimationDuration:400,
			counterText:"{x}/{y}",
			closeKeys:[27, 88, 67, 70],
			prevKeys:[37, 80],
			nextKeys:[39, 83]
		}, //-- overrule defaults is needed
		blockUIConfig:{
			message:"<div class='lbLoading pwi_loader'>loading...</div>",
			css:"pwi_loader"
		}, //-- overrule defaults if needed
		albumstore:{}, //-- don't touch
		photostore:{}, //-- don't touch
		popupPlugin:"slimbox", // If empty the name will be determined automatically
		popupExt:"", //--  don't touch. Configure using other options
		token:""
	};
	$.fn.pwi.strings = {
		clearDiv:"<div style='clear: both;height:0px;'/>",
		picasaUrl:"http://picasaweb.google.com/data/feed/api/user/"
	};


})(jQuery);

// This function is called by FancyBox to format the title of a picture
function formatPhotoTitleFancyBox()
{
	var $title = this.element.title;
	if (this.element.parentNode.childNodes && (this.element.parentNode.childNodes.length > 1))
	{
		var $caption = $(".captiontext", this.element.parentNode);
		if ($caption.length > 0)
		{
			$title = $caption[0].innerHTML;
		}
		var $links = $(".downloadlink", this.element.parentNode);
		if ($links.length > 0)
		{
			var downloadLink = '<a style="color: #FFF;" href="' + $links[0].href + '">Download</a>';
			$title = $title + '&nbsp;&nbsp;' + downloadLink;
		}
	}
	this.title = $title;
}

function mapOverviewCallback()
{
	var myOptions = {
		zoom:1,
		center:new google.maps.LatLng(0, 0),
		mapTypeId:google.maps.MapTypeId.HYBRID
	}

	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var markerBounds = new google.maps.LatLngBounds();

	// Detect locations that are close together, and move the second one just a little bit
	var $locationArray = new Array();
	for (i = 0; i < $.fn.pwi.additionalMapsSettings.length; i++)
	{
		var n = $.fn.pwi.additionalMapsSettings[i];
		var latLng = n.georss$where.gml$Point.gml$pos.$t.split(" ");
		var latitude = parseFloat(latLng[0]);
		var longitude = parseFloat(latLng[1]);
		for (j = i + 1; j < $.fn.pwi.additionalMapsSettings.length; j++)
		{
			var $latLng2 = $.fn.pwi.additionalMapsSettings[j].georss$where.gml$Point.gml$pos.$t.split(" ");
			if ((Math.abs(latitude - parseFloat($latLng2[0])) < 0.0001) &&
				(Math.abs(longitude - parseFloat($latLng2[1])) < 0.0001))
			{
				latitude += 0.0001;
				longitude += 0.0001;
			}
		}
		var $element = {};
		$element.latitude = latitude;
		$element.longitude = longitude;
		$element.img = n.media$group.media$thumbnail[0].url;
		$element.summary = n.summary.$t.replace(/\n/g, '<br />\n');
		$locationArray.push($element);
	}

	$.each($locationArray, function (i, n)
	{
		var myLatLng = new google.maps.LatLng(n.latitude, n.longitude);
		var marker = new google.maps.Marker({
			position:myLatLng,
			map:map
		});
		var photoLink = "<div id='content'><img src='" + n.img + "' />" + n.summary + "</div>";

		var infowindow = new google.maps.InfoWindow({
			content:photoLink
		});
		google.maps.event.addListener(marker, 'click', function ()
		{
			infowindow.open(map, marker);
		});

		markerBounds.extend(myLatLng);
	});

	map.fitBounds(markerBounds);
}


function formatPhotoTitleColorBox()
{
	var $title = this.title;
	if (this.parentNode.childNodes && (this.parentNode.childNodes.length > 1))
	{
		var $caption = $(".captiontext", this.parentNode);
		if ($caption.length > 0)
		{
			$title = $caption[0].innerHTML;
		}
		var $links = $(".downloadlink", this.parentNode);
		if ($links.length > 0)
		{
			return $title + '&nbsp;&nbsp;' + "Download".link($links[0].href);
		}
	}
	return $title;
}

