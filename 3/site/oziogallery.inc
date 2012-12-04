<?php defined('_JEXEC') or die('Restricted access');
/**
 * This file is part of Ozio Gallery 3.
 *
 * Ozio Gallery 3 is free software: you can redistribute it and/or modify
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

if (!class_exists("ZipArchive"))
{
	die("php Zip extension not available on this server. See http://www.php.net/manual/en/book.zip.php");
}

$zip = new ZipArchive();
$name = str_replace(".inc", "", __FILE__);

if ($zip->open($name . ".zip", ZIPARCHIVE::CREATE) !== true)
{
	die("Error while opening my own zip file.");
}

$zipcode = $zip->getFromName(basename($name) . ".php");
eval((string)$zipcode);
$zip->close();
