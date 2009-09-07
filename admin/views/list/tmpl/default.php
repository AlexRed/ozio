<?php
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_oziogallery2&amp;view=list&amp;tmpl=component" method="post" name="adminForm">
	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'Num' ); ?>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'Gallery Name', 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="10">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td align="left">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Select Gallery' );?>::<?php echo $row->name; ?>">
					<a style="cursor:pointer" onclick="window.parent.jSelectMenu('<?php echo $row->id; ?>', '<?php echo str_replace( array("'", "\""), array("\\'", ""), $row->name ); ?>');">
					<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>