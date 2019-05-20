<?php defined('_JEXEC') or die("Restricted access");
	/**
	* This file is part of Ozio Gallery 4
	*
	* Ozio Gallery 4 is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 2 of the License, or
	* (at your option) any later version.
	*
	* Ozio Gallery is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
	*
	* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
	* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
	*/
	
$GLOBALS["enable_jquery_ozio_plugin"]=true;
	

$backdrop_opacity = $this->Params->get("backdrop-opacity","1");
$lg_toolbar_bg = $this->Params->get("lg-toolbar-bg","rgba(0, 0, 0, 0.45)");
$lg_border_radius_base = $this->Params->get("lg-border-radius-base","2").'px';
$lg_theme_highlight = $this->Params->get("lg-theme-highlight","rgb(169, 7, 7)");
//$lg_icon_bg = $this->Params->get("lg-icon-bg","rgba(0, 0, 0, 0.45)");
$lg_icon_color = $this->Params->get("lg-icon-color","#999");
$lg_counter_color = $this->Params->get("lg-counter-color","#e6e6e6");
$lg_counter_font_size = $this->Params->get("lg-counter-font-size","16").'px';
$lg_next_prev_bg = $this->Params->get("lg-next-prev-bg","rgba(0, 0, 0, 0.45)");

$lg_next_prev_color = $this->Params->get("lg-next-prev-color","#999");
$lg_next_prev_hover_color = $this->Params->get("lg-next-prev-hover-color","#FFF");
$lg_progress_bar_bg = $this->Params->get("lg-progress-bar-bg","#333");
$lg_progress_bar_active_bg = $this->Params->get("lg-progress-bar-active-bg","rgb(169, 7, 7)");

$lg_progress_bar_height = $this->Params->get("lg-progress-bar-height","5").'px';
$zoom_transition_duration = $this->Params->get("zoom-transition-duration","0.3").'s';

$lg_sub_html_bg = $this->Params->get("lg-sub-html-bg","rgba(0, 0, 0, 0.45)");
$lg_sub_html_color = $this->Params->get("lg-sub-html-color","#EEE");

$lg_thumb_toggle_bg = $this->Params->get("lg-thumb-toggle-bg","#0D0A0A");

$lg_thumb_toggle_color = $this->Params->get("lg-thumb-toggle-color","#999");
$lg_thumb_toggle_hover_color = $this->Params->get("lg-thumb-toggle-hover-color","#FFF");
$lg_thumb_bg = $this->Params->get("lg-thumb-bg","#0D0A0A");



$css_custom_style ="
.lg-backdrop.in{
	opacity: ${backdrop_opacity};
}
.lg-toolbar {
    background-color: ${lg_toolbar_bg};
}
.lg-outer .lg-thumb-item.active, .lg-outer .lg-thumb-item:hover {
	border-color: ${lg_theme_highlight};
}

.lg-progress-bar .lg-progress {
	background-color: ${lg_progress_bar_active_bg}; /* lg_theme_highlight */
}

.lg-actions .lg-next,.lg-actions .lg-prev {
	background-color: ${lg_next_prev_bg}; /* lg-icon-bg */
	color: ${lg_next_prev_color}; /* lg-icon-color */
	border-radius: ${lg_border_radius_base};
}

.lg-actions .lg-next:hover,.lg-actions .lg-prev:hover {
	color: ${lg_next_prev_hover_color};
}

.lg-progress-bar {
	background-color: ${lg_progress_bar_bg};
}


#lg-counter {
    color: ${lg_counter_color};
    font-size: ${lg_counter_font_size};
}

.lg-toolbar .lg-icon {
    color: ${lg_icon_color}; /* lg-icon-color */
}


		

.lg-progress-bar .lg-progress {
	background-color: ${lg_progress_bar_active_bg};
}

.lg-progress-bar {
    height: ${lg_progress_bar_height};
}
.lg-progress-bar .lg-progress {
	height: ${lg_progress_bar_height};
}

.lg-outer .lg-toogle-thumb {
	color: ${lg_thumb_toggle_color}; /* lg-icon-color */
	border-radius: ${lg_border_radius_base} ${lg_border_radius_base} 0 0;
	background-color: ${lg_thumb_toggle_bg};
}

.lg-outer .lg-item.lg-complete.lg-zoomable .lg-img-wrap {
	-webkit-transition: -webkit-transform ${zoom_transition_duration} ease 0s;
	-moz-transition: -moz-transform ${zoom_transition_duration} ease 0s;
	-o-transition: -o-transform ${zoom_transition_duration} ease 0s;
	transition: transform ${zoom_transition_duration} ease 0s;
	-webkit-transform: translate3d(0, 0, 0);
	transform: translate3d(0, 0, 0);
	-webkit-backface-visibility: hidden;
	-moz-backface-visibility: hidden;
	backface-visibility: hidden;
}
.lg-outer .lg-item.lg-complete.lg-zoomable .lg-image {
	-webkit-transform: scale3d(1, 1, 1);
	transform: scale3d(1, 1, 1);
	-webkit-transition: -webkit-transform ${zoom_transition_duration} ease 0s, opacity 0.15s !important;
	-moz-transition: -moz-transform ${zoom_transition_duration} ease 0s, opacity 0.15s !important;
	-o-transition: -o-transform ${zoom_transition_duration} ease 0s, opacity 0.15s !important;
	transition: transform ${zoom_transition_duration} ease 0s, opacity 0.15s !important;
	-webkit-transform-origin: 0 0;
	-moz-transform-origin: 0 0;
	-ms-transform-origin: 0 0;
	transform-origin: 0 0;
	-webkit-backface-visibility: hidden;
	-moz-backface-visibility: hidden;
	backface-visibility: hidden;
	
}
.lg-sub-html {
    background-color: ${lg_sub_html_bg};
	color: ${lg_sub_html_color};
}

.lg-outer .lg-toogle-thumb:hover {
	color: ${lg_thumb_toggle_hover_color};
}

.lg-outer .lg-thumb-outer {
	background-color: ${lg_thumb_bg};
}
";

$this->document->addStyleDeclaration($css_custom_style);
	
$list_thumb_width = $this->Params->get("list_thumb_width","200").'px';
$list_thumb_margin = $this->Params->get("list_thumb_margin","20").'px';
$list_thumb_border = $this->Params->get("list_thumb_border","3").'px';
$list_thumb_border_radius = $this->Params->get("list_thumb_border_radius","3").'px';
$list_thumb_border_color = $this->Params->get("list_thumb_border_color","#04070a");
	
$css_custom_style = "
.ozio-lightgallery-container > ul > li{
	margin-right: ${list_thumb_margin};
	margin-bottom: ${list_thumb_margin};
	width: ${list_thumb_width};	
}

.ozio-lightgallery-container > ul > li a {
	border: ${list_thumb_border} solid ${list_thumb_border_color};
    border-radius: ${list_thumb_border_radius};
}
";

$this->document->addStyleDeclaration($css_custom_style);


$max_num_rows_title = intval($this->Params->get("max_num_rows_title","1"));
$max_num_rows_description = intval($this->Params->get("max_num_rows_description","3"));
$max_num_rows_list_title = intval($this->Params->get("max_num_rows_list_title","1"));

$title_max_height=($max_num_rows_title*16).'px';
$desc_max_height = ($max_num_rows_description*15).'px';
$list_title_max_height = ($max_num_rows_list_title*18).'px';

$css_custom_style = "

.lg-sub-html h4 {
    margin: 0;
    font-size: 13px;
    font-weight: bold;
	display: block; /* Fallback for non-webkit */
	display: -webkit-box;
	max-height: ${title_max_height}; /* Fallback for non-webkit */
	line-height: 16px;
	-webkit-line-clamp: ${max_num_rows_title};
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
}

.lg-sub-html p {
    font-size: 12px;
    margin: 5px 0 0;
	display: block; /* Fallback for non-webkit */
	display: -webkit-box;
	max-height: ${desc_max_height}; /* Fallback for non-webkit */
	line-height: 15px;
	-webkit-line-clamp: ${max_num_rows_description};
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
}

.ozio-thumb-list-sub-title {
    text-align: center;
	line-height: 18px;
	font-size: 13px;

	
	display: block; /* Fallback for non-webkit */
	display: -webkit-box;
	height: ${list_title_max_height}; /* Fallback for non-webkit */
	-webkit-line-clamp: ${max_num_rows_list_title};
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
	width: ${list_thumb_width};	
}

";

$this->document->addStyleDeclaration($css_custom_style);

	
?>
<?php if ($this->Params->get("show_page_heading", 1)) { ?>
<h1><?php echo $this->escape($this->Params->get("page_heading")); ?></h1>
<?php } ?>


<?php 

$testo_sotto_mappa=trim(strip_tags($this->Params->get("bottom_description", ""),'<a><b><blockquote><code><del><dd><dl><dt><em><h1><h2><h3><i><kbd><li><ol><p><pre><s><sup><sub><strong><strike><ul><br><hr>'));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='above') {  ?>
	<div class="ozio_lightgallery_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>


<div id="lightgallery" class="ozio-lightgallery-container<?php echo $this->escape($this->Params->get("pageclass_sfx", "")); ?>">
	<div class="ozio_lightgallery_loading" style="width: 100%; overflow: hidden; position: relative; height: 5px;">
	<noscript><?php echo "javascript required" ?></noscript>
	</div>
</div>


<?php 

$testo_sotto_mappa=trim(strip_tags($this->Params->get("bottom_description", ""),'<a><b><blockquote><code><del><dd><dl><dt><em><h1><h2><h3><i><kbd><li><ol><p><pre><s><sup><sub><strong><strike><ul><br><hr>'));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='under') {  ?>
	<div class="ozio_lightgallery_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>

<div style="display:none;">ozio_gallery_lightgallery</div>