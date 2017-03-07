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
?>

<div id="ozio_setup_messages">
</div>

 <?php echo JText::_('COM_OZIOGALLERY3_SETUP_INSTRUCTIONS');?>

<button id="ozio_credentials_add" class="btn btn-large btn-primary" type="button"><?php echo JText::_('COM_OZIOGALLERY3_ADD_CREDENTIALS');?></button>

<table id="ozio_credentials_list" class="table table-striped">
<thead>

<tr>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_CLIENT_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_CLIENT_SECRET');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_USER_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_STATUS');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY3_SETUP_TABLE_ACTIONS');?></th>
</tr>

</thead>
<tbody>
</tbody>
</table>
 <?php echo JText::_('COM_OZIOGALLERY3_SETUP_INSTRUCTIONS_BOTTOM');?>


<div id="ozio_cerentials_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo JText::_('COM_OZIOGALLERY3_SETUP_MODAL_HEADER');?></h3>
  </div>
  <div class="modal-body" style="overflow:auto;">
	
	
<div class="control-group">
  <label class="control-label">Google Client ID</label>
  <div class="controls">
    <input id="google_client_id" class="input-block-level" type="text" placeholder="Google Client ID">
   
  </div>
</div>	
<div class="control-group">
  <label class="control-label">Google Client Secret</label>
  <div class="controls">
    <input id="google_client_secret" class="input-block-level" type="text" placeholder="Google Client Secret">
    
  </div>
</div>	
	

   <?php echo JText::_('COM_OZIOGALLERY3_SETUP_MODAL_INSTRUCTIONS');?>
	
	
  </div>
  <div class="modal-footer">
    <span class="btn" data-dismiss="modal"><i class="icon-cancel"></i> <?php echo JText::_('JLIB_HTML_BEHAVIOR_CLOSE');?></span>
    <span id="ozio_credentials_save" class="btn btn-primary"><?php echo JText::_('COM_OZIOGALLERY3_SETUP_MODAL_SALVA');?></span>
  </div>
</div>



<div id="ozio_auth_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3><?php echo JText::_('COM_OZIOGALLERY3_AUTH_MODAL_HEADER');?></h3>
  </div>
  <div class="modal-body">
	
  </div>
  <div class="modal-footer">
    <span class="btn" data-dismiss="modal"><i class="icon-cancel"></i> <?php echo JText::_('JLIB_HTML_BEHAVIOR_CLOSE');?></span>
  </div>
</div>


