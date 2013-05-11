<?php defined('_JEXEC') or die;

	if (JFactory::getApplication()->isSite())
	{
		JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
	}

	JHtml::_('behavior.tooltip');

	$function	= JRequest::getCmd('function', 'jSelectCallback');
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));

	$states = array();
	$states[0] = array("class" => "unpublish", "description" => "JUNPUBLISHED");
	$states[1] = array("class" => "publish", "description" => "JPUBLISHED");
	$states[-2] = array("class" => "trash", "description" => "JTRASHED");

?>
<form action="<?php echo JRoute::_('index.php?option=com_oziogallery3&view=galleries&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken().'=1');?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filter clearfix">
		<div class="left">

			<label for="filter_search">
				<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
			</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />

			<button type="submit">
			<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();">
			<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>

		</div>

		<div class="right">

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

		</div>
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th class="title">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); /* Note that it is a.title and not a.itemtitle. See galleries.php function getListQuery() */?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort',  'JOPTION_MENUS', 'a.menutype', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_MENU_ITEM_TYPE', 'a.link', $listDirn, $listOrder); ?>
				</th>
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<?php
					// Parse the link to get the value of the view
					$link = NULL;
					// Remove the "index.php?" part that hurts
					$item->link = str_replace("index.php?", "", $item->link);
					// Parse the values
					parse_str($item->link, $link);
					// Todo: We should consider the metadata.xml case (see administrator/components/com_menus/views/items/view.html.php function display())
					$viewname = JText::_($link["option"] . '_' . $link["view"] . "_VIEW_DEFAULT_TITLE");
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>');">
						<?php echo $this->escape($item->itemtitle); ?></a>
					</td>
					<td class="center">
						<div class="jgrid"><span class="state <?php echo $states[(int)$item->published]["class"]; ?>"><span class="text"><?php echo $states[(int)$item->published]["class"]; ?></span></span></div>
					</td>
					<td class="center">
						<?php echo $this->escape($item->title); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($viewname); ?>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
