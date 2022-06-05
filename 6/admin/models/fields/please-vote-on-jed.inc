<?php defined('_JEXEC') or die('Restricted access');
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


$GLOBALS["oziogallery3"]["registered"] = false;
$GLOBALS["oziogallery3"]["notregistered"] = true;

if (!function_exists("JPATH_COMPONENT"))
{
	function JPATH_COMPONENT()
	{
		if (isset($_REQUEST['view']) && isset($_REQUEST['format']) && 
		$_REQUEST['view']=='picasa' && $_REQUEST['format']=='raw' 
		){
			
		}else{
			echo copyright("Javascript image gallery for Joomla!");
		}
	}
}


if (!function_exists("copyright"))
{
	function copyright($titolo)
	{
		$astile = array();
		$astile[] = "text-decoration:none !important";
		$sstile_a = implode(";", $astile);

		$astile = array();
		$astile[] = "clear:both !important";
		$astile[] = "padding:12px 0 !important";

		$astile[] = "font-family:arial,verdana,sans-serif !important";
		$astile[] = "font-size:12px !important";
		$astile[] = "font-variant:small-caps !important";

		$sstile_div = implode(";", $astile);
		$url = "'https://www.opensourcesolutions.es'";
		$testo = " Ozio Gallery ";

		return
		'<div>' .
		'<a href=' . $url . '>' .
		$testo .
		'</a></div>';
	}
}
