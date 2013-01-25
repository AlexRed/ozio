<?php defined('_JEXEC') or die('Restricted access');
/**
* This file is part of Ozio Gallery 3
*
* Ozio Gallery 3 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* (at your option) any later version.
*
* Ozio Gallery is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Ozio Gallery.  If not, see <http://www.gnu.org/licenses/>.
*
* @copyright Copyright (C) 2010 Open Source Solutions S.L.U. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see RT-LICENSE.php
*/


abstract class Loader
{
	abstract protected function type();
	abstract protected function http_headers();
	abstract protected function content_header();
	abstract protected function content_footer();


	public function Show()
	{
		$this->headers();
		$this->http_headers();
		$this->content_header();
		$this->load();
		$this->content_footer();

		//die();
		JFactory::getApplication()->close();
	}


	private function headers()
	{
		// Prepare some useful headers
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		// must not be cached by the client browser or any proxy
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}


	protected function load()
	{
		// Complete the script name with its path
		$filename = JRequest::getVar("filename", "", "GET");
		// Only admit lowercase a-z, underscore and minus. Forbid numbers, symbols, slashes and other stuff.
		// For your security, *don't* touch the following regular expression.
		preg_match('/^[a-z_-]+$/', $filename) or $filename = "invalid";
		$local_name = $this->IncludePath . "/" . $this->type() . "/" . $filename . "." . $this->type();

		require_once $local_name;
	}

}


class cssLoader extends Loader
{
	protected function type()
	{
		return "css";
	}

	protected function http_headers()
	{
		header('Content-Type: text/css; charset=utf-8');
	}

	protected function content_header()
	{
		echo "/* css generator begin */\n";
	}

	protected function content_footer()
	{
		echo "\n/* css generator end */";
	}
}

class jsLoader extends Loader
{
	protected function type()
	{
		return "js";
	}

	protected function http_headers()
	{
		header('Content-Type: application/javascript; charset=utf-8');
	}

	protected function content_header()
	{
		// echo "//<![CDATA[\n";
	}

	protected function content_footer()
	{
		// echo "\n//]]>";
	}
}
