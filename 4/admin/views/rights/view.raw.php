<?php
defined('_JEXEC') or die;
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

jimport('joomla.application.component.view');

class OzioViewRights extends JViewLegacy
{
    function display($tpl = null)
    {
		
		return;//NON fa più nulla.
		/*
		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		
		$jinput = $app->input;
		
		
		$action = $jinput->post->get('action', '', 'RAW');
		if ($action == 'get_albums'){
			$access_token=$jinput->post->get('access_token', '', 'RAW');;
			if (empty($access_token)){
				die();
			}
			$user_id=$jinput->post->get('user_id', '', 'RAW');;
			if (empty($user_id) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$user_id)){
				die();
			}
			
			
			$url = "https://picasaweb.google.com/data/feed/api/user/".$user_id."?".http_build_query(array('v'=>2,'kind'=>'album','access'=>'all','alt'=>'json','access_token'=>$access_token),'','&');
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',"GData-Version: 2","If-Match: *"));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($curl, CURLOPT_VERBOSE, true);

			// Make the REST call, returning the result
			$response = curl_exec($curl);

			$resp=json_decode($response,true);
			
			if (empty($resp) || empty($resp['feed']) || !isset($resp['feed']['entry'])){
				die();
			}
			
			$return_val=array(
				"status" => true,
				"user_id" => $user_id,
				"entries" =>array(),
				"access_token" => $access_token
			);
			foreach($resp['feed']['entry'] as $entry){
				foreach ($entry['link'] as $link){
					if ($link['rel']=='edit'){
						$p = explode('/',$entry['id']['$t']);
						$p = explode('?',$p[8]);
						$return_val['entries'][]=array(
							'title'=> $entry['title']['$t'],
							'rights'=>$entry['rights']['$t'],
							'id'=>$p[0],
							'link'=> $link['href'],
							'num_photos'=> $entry['gphoto$numphotos']['$t']
						);
						break;
					}
				}
			}
			
			echo json_encode(array("response"=>$return_val));					
			
			
		}else if ($action == 'change_rights'){
			

			$new_access= $jinput->post->get('new_access', '', 'RAW');
			if (!in_array($new_access,array('public','private','protected'))){
				die();
			}
			$url=$jinput->post->get('url', '', 'RAW');
			$access_token=$jinput->post->get('access_token', '', 'RAW');;
			if (empty($access_token)){
				die();
			}

			if (strpos($url,'https://picasaweb.google.com')!==0){
				die();
			}

			$xml="<entry xmlns='http://www.w3.org/2005/Atom' xmlns:gphoto='http://schemas.google.com/photos/2007'>
			  <gphoto:access>".$new_access."</gphoto:access>
			</entry>";

			$url=$url."&".http_build_query(array('access_token'=>$access_token),'','&');

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',"GData-Version: 2","If-Match: *"));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($curl, CURLOPT_VERBOSE, true);

			// Make the REST call, returning the result
			$response = curl_exec($curl);

			$resp=json_decode($response,true);

			echo json_encode(array("response"=>$resp));					
			
		}else{
			die();
		}

		*/
        //parent::display($tpl);
    }
}