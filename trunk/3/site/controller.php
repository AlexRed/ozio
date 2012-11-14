<?php
/*
* This file is part of Ozio Gallery
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

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class OzioGalleryController extends JController
{
	public function display($cachable = false, $urlparams = false)
	{
		return parent::display($cachable);
	}


	public function proxy()
	{
		$uri = JFactory::getURI();
		$query = $uri->getQuery(true);

		$user = $query["user"] ? "user/" . $query["user"] : "";
		$urlwrapper = new UrlWrapper();
		$url = "http://picasaweb.google.com/data/feed/api/" . $user;
		$data = $urlwrapper->Get($url);
		// Needed by Mootools::Request
		header('content-type: application/atom+xml; charset=UTF-8; type=feed');
		echo $data;
		jexit();
	}

}


class UrlWrapper
{
	protected $method;

	public function __construct()
	{
		$this->method = "none";
		if (!ini_get('allow_url_fopen')) return;

		$functions = array("file_get_contents", "curl_init");
		foreach ($functions as $function)
		{
			if (function_exists($function))
			{
				$this->method = $function;
				return;
			}
		}
	}

	public function Get($url)
	{
		return $this->{$this->method}($url);
	}

	protected function file_get_contents($url)
	{
		return file_get_contents($url);
	}

	protected function curl_init($url)
	{
		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		$data = curl_exec($handle);
		curl_close($handle);
		return $data;
	}

	protected function none()
	{
		// Server lacks. Returns an empty page.
		return "";
	}
}