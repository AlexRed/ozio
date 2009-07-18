<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<table class="adminlist" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td valign="top">
<?php	
		foreach($this->lists['faq'] as $faq) :
			echo '<h2>'.$faq["question"].'</h2>';
			echo '<p>'.$faq["answer"].'</p>';
		endforeach;
?>		
		</td>
	</tr>
</table>