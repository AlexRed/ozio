<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class OzioGalleryController extends JController
{
	function __construct()
	{
		parent::__construct();
	}

	function display()
	{
			parent::display(true);

	}
}
?>