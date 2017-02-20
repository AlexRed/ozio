<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

//Cambia il nome rispetto admin
class OzioGalleryViewPicasa extends JViewLegacy
{
	
    function display($tpl = null)
    {
		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		
		$jinput = $app->input;
		
		
		$copy_params = array('kind','thumbsize','rnd','imgmax','authkey','tag','start-index','max-results');
		
		
		$ozio_payload = $jinput->get('ozio_payload', '', 'RAW');
		//user_id=5&v=2&alt=json&kind=album&access=public&thumbsize='+g_picasaThumbSize
		$input_params = array();
		parse_str($ozio_payload, $input_params);
		
		//error_log(var_export($input_params,true),3,'C:\workspace\php-errors.log');
		
		if (empty($input_params['user_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['user_id'])){
			die();
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'client_id', 'client_secret', 'user_id','status','refresh_token','access_token')));
		$query->from($db->quoteName('#__ozio_setup'));
		$query->where($db->quoteName('user_id') . ' = '. $db->quote($input_params['user_id']));	
		$query->where($db->quoteName('refresh_token') . ' IS NOT NULL');
		$query->where($db->quoteName('status') . ' = '. $db->quote('authorized'));
		$query->order('id DESC');			
		$db->setQuery($query);
			
			
		$picasa_params = array();
		$credentials = $db->loadAssoc();
		if (empty($credentials)){
			//prendo solo quelli public
			$picasa_params['access'] = 'public';
		}else{
			//uso le credenziali
			$picasa_params['access'] = 'all';
			$picasa_params['access_token'] = $credentials['access_token'];
		}
		foreach ($copy_params as $k){
			if (isset($input_params[$k])){
				$picasa_params[$k] = $input_params[$k];
			}
		}
		
		$url = 'https://picasaweb.google.com/data/feed/api/user/'.$input_params['user_id'];
		
		
		$single_album = true;
		///albumid/
		if (empty($input_params['album_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['album_id'])){
			$single_album = false;
		}else{
			
			$url = $url.'/albumid/'.$input_params['album_id'];
			if (empty($input_params['photo_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['photo_id'])){
			}else{
				$url = $url.'/photoid/'.$input_params['photo_id'];
			}
		}
		
		$picasa_params['v'] = 2;
		$picasa_params['alt'] = 'json';
		
		$start_index = $jinput->get('ozio-picasa-start-index', '', 'RAW');
		$callback = $jinput->get('ozio-picasa-callback', '', 'RAW');
		
		if ($start_index!==''){
			$picasa_params['start-index'] = $start_index;
		}
		if ($callback!==''){
			$picasa_params['callback'] = $callback;
		}
		
		
		
		
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url."?".http_build_query($picasa_params));
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',"GData-Version: 2","If-Match: *"));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("GData-Version: 3"));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_VERBOSE, true);

		// Make the REST call, returning the result
		$response = curl_exec($curl);
		//'Token invalid - Invalid token: Token expired.'
		
		
		$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		
		
		if ($json_resp===NULL && isset($picasa_params['access_token'])){
			//Nuovo access token
			$credentials = $this->refresh_token($credentials);
			$picasa_params['access_token'] = $credentials['access_token'];
			curl_setopt($curl, CURLOPT_URL, $url."?".http_build_query($picasa_params));

			$response = curl_exec($curl);

			$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		}
		
		//error_log(var_export($json_resp,true));
		if ($json_resp!==NULL){
			$menu_id = $jinput->get('ozio-menu-id', 0, 'INT');
			$allowed_albums = $this->get_allowed_albums($menu_id);
			
			if ($single_album){
				if (in_array($input_params['album_id'],$allowed_albums) || 
					(isset($json_resp['feed']) && isset($json_resp['feed']['rights']) && $json_resp['feed']['rights']['$t']=='public' ) ||
					(isset($json_resp['feed']) && isset($json_resp['feed']['gphoto$access']) && $json_resp['feed']['gphoto$access']['$t']=='public'  )
					
					
					){
					//ok
					echo $response;
				}else{
					header('HTTP/1.0 403 Forbidden');
					echo "Album id not allowed";
				}
				
			}else{
				//filter albums allowed
				
				$albums_in = $json_resp['feed']['entry'];
				$albums_out = array();
				
				foreach ($albums_in as $album){
					if ($album["rights"]['$t']=='public'){
						$albums_out[] = $album;
						continue;
					}
					$parti = explode('/',$album["id"]['$t']);
					$album_id = array_pop($parti);
					if (in_array($album_id,$allowed_albums)){
						$albums_out[] = $album;
						continue;
					}
					
				}
				
				
				$json_resp['feed']['entry'] = $albums_out;
				
				if (empty($callback_name)){
					echo json_encode($json_resp);
				}else{
					echo $callback_name."(".json_encode($json_resp).");";
				}
			}
		}else{
			header('HTTP/1.0 403 Forbidden');
			echo $response;

		}
		

		

		die();
        //parent::display($tpl);
    }
	
	function get_allowed_albums($menu_id){
		$allowed_albums = array();
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		
		
		$item = $menu->getItem($menu_id);
		if ($item===null){
			return $allowed_albums;
		}
		
		if (strpos($item->link, "&view=00fuerte") === false && strpos($item->link, "&view=lightgallery") === false && strpos($item->link, "&view=nano") === false && strpos($item->link, "&view=jgallery") === false){
			//invalid id
		}else if (strpos($item->link, "&view=lightgallery") !== false && $item->params->get("source_kind", "photo")!=='photo'){
			//invalid id
		}else if (strpos($item->link, "&view=00fuerte") !== false || strpos($item->link, "&view=lightgallery") !== false){
			
			$params = $item->params->toArray();
			
			$allowed_albums[] = !isset($params['albumvisibility']) || $params['albumvisibility']=='public'?$params['gallery_id']:$params['limitedalbum'];
			
		}else{
			$kind=$item->params->get("ozio_nano_kind", "picasa");
			$albumvisibility=$item->params->get("albumvisibility", "public");
			if ($kind=='picasa' && $albumvisibility=='limited'){
				$allowed_albums[] = $item->params->get("limitedalbum", "");
			}else if ($kind=='picasa'){

				$non_printable_separator="\x16";
				$new_non_printable_separator="|!|";
				$albumList=$item->params->get("ozio_nano_albumList", array());
				if (!empty($albumList) && is_array($albumList) ){
					if (count($albumList)==1){
						if (strpos($albumList[0],$non_printable_separator)!==FALSE){
							list($albumid,$title)=explode($non_printable_separator,$albumList[0]);
						}else{
							list($albumid,$title)=explode($new_non_printable_separator,$albumList[0]);
						}
						$allowed_albums[] = $albumid;
					}else{
						foreach ($albumList as $a){
							if (strpos($a,$non_printable_separator)!==FALSE){
								list($albumid,$title)=explode($non_printable_separator,$a);
							}else{
								list($albumid,$title)=explode($new_non_printable_separator,$a);
							}
							$allowed_albums[] = $albumid;
						}
					}
				}	

			}
		}
				
	
		return $allowed_albums;
	}
	
	function response_parse($response,$callback,&$callback_name){
		$callback_name= '';
		if ($callback){
			$parti = explode("\n",$response);
			foreach($parti as $p){
				/*
				// API callback
jQuery1124012552826618160506_1487071237494(.*);

preg_match ('/^jQuery[^(]+\((.*)\); *$/i','jQuery1124012552826618160506_1487071237494(pie())ro);  ',$matches);var_export($matches);

				*/
				$matches = array();
				if (preg_match ('/^ *(jQuery[^(]+)\((.*)\); *$/i',$p,$matches)){
					$callback_name = $matches[1];
					$response = $matches[2];
				}
				
			}
			
			
		}
		return json_decode($response,true);
	}
	
	function refresh_token($credentials){
		$postfields = array(
			'client_id' => $credentials['client_id'],
			'client_secret' => $credentials['client_secret'],
			'grant_type' => 'refresh_token',
			'refresh_token' => $credentials['refresh_token'],
		
		);
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL,"https://www.googleapis.com/oauth2/v4/token");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_VERBOSE, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields));
		

		// receive server response ...

		$response = curl_exec ($curl);

		curl_close ($curl);
		
		$resp=json_decode($response,true);
		if (isset($resp['access_token'])){
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			 
			// Fields to update.
			$fields = array(
				$db->quoteName('access_token') . ' = ' . $db->quote($resp['access_token']),
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($credentials['id'])
			);
			 
			$query->update($db->quoteName('#__ozio_setup'))->set($fields)->where($conditions);
			 
			$db->setQuery($query);
			 
			$result = $db->execute();

			$credentials['access_token'] = $resp['access_token'];
			
			return $credentials;
		}else{
			JError::raiseError(500, "Unable to refresh token");
			die();
		}		
		
		
	}
	
}