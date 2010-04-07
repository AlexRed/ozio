<?php
/**
* This file is part of Ozio Gallery 2.
*
* Ozio Gallery 2 is free software: you can redistribute it and/or modify
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


$to = $_POST["to"];
$subject = $_POST["subject"];
$message = $_POST["message"];
$from = $_POST["from"];

function safe( $name ) {
   return str_replace (array ("\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:","to:","cc:"), "", $name);
}

$emailPattern = '/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i';

if (preg_match($emailPattern, $to) && preg_match($emailPattern, $from))
	echo mail ($to, safe($subject), $message, "From:".safe($from));
else
	echo 'error::Wrong e-mail format!';