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
?>

<html>
<body>

<script>
    function parseJwt (token) {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));

        return JSON.parse(jsonPayload);
    }
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: " + response.credential);
      const responsePayload =  parseJwt(response.credential);

        console.log("googleid:"+responsePayload.sub, '*');
        window.top.postMessage("googleid:"+responsePayload.sub, '*')
    }
    window.onload = function () {
        google.accounts.id.initialize({
            client_id: "522128536465-4qenhkv3hfvnjpdcbot7vlvuuktti9ct.apps.googleusercontent.com",
            callback: handleCredentialResponse
        });
        google.accounts.id.renderButton(
            document.getElementById("buttonDiv"),
            { theme: "outline", size: "small",type:"icon" }  // customization attributes
        );
        google.accounts.id.prompt(); // also display the One Tap dialog
    }
</script>
<style>
    .ozio-buttons-frame {
        height: auto !important;
        width: auto !important;
        border-radius: 10px;
        border: 1px solid #E0E0E0;
        display: inline-block;
        padding-right: 5px;
        margin-left: 8px;
        vertical-align: middle;
        position: absolute;
        overflow: hidden;
    }
</style>
<div id="buttonDiv"></div>
</div>
</body>
</html>
