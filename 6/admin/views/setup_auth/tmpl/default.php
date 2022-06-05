<?php
/**
* This file is part of Ozio Gallery 4.
*
* Ozio Gallery 4 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Foobar is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\HTML\HTMLHelper;
	HTMLHelper::_('jquery.framework');
?>
<script src="https://accounts.google.com/gsi/client" onload="initClient()" async defer></script>


<div id="ozio_setup_messages">
</div>
<div id="buttonDiv"></div>
<!--/*migration_changes
			relace iframe with google sign button
			*/-->
<button id="ozio_setup_auth" class="btn btn-primary" onclick="getToken();" type="button"><?php echo JText::_('COM_OZIOGALLERY4_AUTH_CREDENTIALS');?></button>


