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
	JHtml::_('bootstrap.modal');
?>
<?php include_once JPATH_COMPONENT . '/layouts/modalbox.php'; ?>


<div class="row">
<div id="j-sidebar-container" class="col-md-3">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="col-md-9">
  
<div id="ozio_setup_messages">
</div>

 <?php echo JText::_('COM_OZIOGALLERY4_SETUP_INSTRUCTIONS');?>

<button id="ozio_credentials_add" class="btn btn-large btn-primary" type="button" data-toggle="modal" data-target="#ozio_cerentials_modal"><?php echo JText::_('COM_OZIOGALLERY4_ADD_CREDENTIALS');?></button>
<div class="table-responsive">
<table id="ozio_credentials_list" class="table table-striped">
<thead>

<tr>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_CLIENT_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_CLIENT_SECRET');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_USER_ID');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_STATUS');?></th>
<th><?php echo JText::_('COM_OZIOGALLERY4_SETUP_TABLE_ACTIONS');?></th>
</tr>

</thead>
<tbody>
</tbody>
</table>
</div>
 <?php echo JText::_('COM_OZIOGALLERY4_SETUP_INSTRUCTIONS_BOTTOM');?>

<div class="modal" tabindex="-1" role="dialog"  id="ozio_cerentials_modal" aria-hidden="true">
    <div class="modal-lg modal-dialog ">
         <div class="modal-content p-md-4">
              <div class="modal-header p-md-4">
                <h3><?php echo JText::_('COM_OZIOGALLERY4_SETUP_MODAL_HEADER');?></h3>
                <button type="button" class="close ozio-close-modal" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body p-md-4" style="overflow:auto;">
            	
            	
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
            	
            
               <?php echo JText::_('COM_OZIOGALLERY4_SETUP_MODAL_INSTRUCTIONS');?>
            	
            	
              </div>
              <div class="modal-footer">
                <span class="btn ozio-close-modal" data-dismiss="modal"><i class="icon-cancel"></i> <?php echo JText::_('JLIB_HTML_BEHAVIOR_CLOSE');?></span>
                <span id="ozio_credentials_save" class="btn btn-primary"><?php echo JText::_('COM_OZIOGALLERY4_SETUP_MODAL_SALVA');?></span>
              </div>
        </div>
    </div>
</div>



<div class="modal" tabindex="-1" role="dialog"  id="ozio_auth_modal" aria-hidden="true">
<div class="modal-dialog modal-lg ">
     <div class="modal-content p-md-4">
         
  <div class="modal-header p-md-4">
    
    <h3><?php echo JText::_('COM_OZIOGALLERY4_AUTH_MODAL_HEADER');?></h3>
    <button type="button" class="close ozio-close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
  </div>
  <div class="modal-body p-md-4">
	
  </div>
  <div class="modal-footer">
    <span class="btn ozio-close-modal" data-dismiss="modal"><i class="icon-cancel"></i> <?php echo JText::_('JLIB_HTML_BEHAVIOR_CLOSE');?></span>
  </div>
</div></div></div>




</div></div>
