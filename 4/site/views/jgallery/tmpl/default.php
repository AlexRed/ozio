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

?>
<?php if ($this->Params->get("show_page_heading", 1)) { ?>
<h1><?php echo $this->escape($this->Params->get("page_heading")); ?></h1>
<?php } ?>

<?php 

$testo_sotto_mappa=trim($this->Params->get("bottom_description", ""));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='above') {  ?>
	<div class="ozio_jgallery_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>


<div id="jgallery" class="ozio-jgallery-container<?php echo $this->Params->get("pageclass_sfx", ""); ?>">
	<div class="ozio_jgallery_loading" style="width: 100%; overflow: hidden; position: relative; height: 5px;">
	<noscript><?php echo "javascript required" ?></noscript>
	</div>
</div>


<?php 

$testo_sotto_mappa=trim($this->Params->get("bottom_description", ""));
if (!empty($testo_sotto_mappa) && $this->Params->get("description_pos", "under")=='under') {  ?>
	<div class="ozio_jgallery_bottom_description">
	<?php echo $testo_sotto_mappa; ?>
	</div>
<?php }?>

<div style="display:none;">ozio_gallery_jgallery</div>