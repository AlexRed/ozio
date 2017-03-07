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
		
		
		$copy_params = array('kind','thumbsize','rnd','imgmax',/*'authkey',*/'tag','start-index','max-results');
		
		
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
		
		$single_photo = false;
		$single_album = true;
		///albumid/
		if (empty($input_params['album_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['album_id'])){
			$single_album = false;
		}else{
			
			$url = $url.'/albumid/'.$input_params['album_id'];
			if (empty($input_params['photo_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['photo_id'])){
			}else{
				$url = $url.'/photoid/'.$input_params['photo_id'];
				$single_photo = true;
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
		if ($single_photo){
			$http_header = array();
		}else{
			$http_header = array("GData-Version"=>"3");
		}
		$obj_resp = $this->gi_get($url."?".http_build_query($picasa_params,'','&'),$http_header );
		$response = $obj_resp->body;
		
		
		$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		
		
		if ($json_resp===NULL && isset($picasa_params['access_token'])){
			//Nuovo access token
			$credentials = $this->refresh_token($credentials);
			$picasa_params['access_token'] = $credentials['access_token'];
			
			$obj_resp = $this->gi_get($url."?".http_build_query($picasa_params,'','&'),$http_header );
			$response = $obj_resp->body;
			
			$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		}
		
		//error_log(var_export($json_resp,true));
		if ($json_resp!==NULL){
			$menu_id = $jinput->get('ozio-menu-id', 0, 'INT');
			$array_allowed = $this->get_allowed_albums($menu_id);
			
			
			
			if ($single_album){
				
				if ($single_photo){
					echo $response;
				}else{
					if (!isset($json_resp['feed']['entry'])){
						$json_resp['feed']['entry'] = array();
					}
					if ($array_allowed['black_list']!==false && $this->album_match($json_resp['feed'],$array_allowed['black_list'])){
						header('HTTP/1.0 403 Forbidden');
						echo "Album id not allowed";
						die();
					}
					
					if ($json_resp['feed']["rights"]['$t']=='public' || in_array($input_params['album_id'],$array_allowed['allowed_albums'])){
						if (empty($callback_name)){
							echo json_encode($json_resp);
						}else{
							echo $callback_name."(".json_encode($json_resp).");";
						}
						die();
					}
					
					
					//se è nella whitelist o è vuota la white list
					if ($array_allowed['white_list']===false){
						header('HTTP/1.0 403 Forbidden');
						echo "Album id not allowed";
						die();
					}
					
					if (empty($array_allowed['white_list']) || $this->album_match($json_resp['feed'],$array_allowed['white_list'])){
						if (empty($callback_name)){
							echo json_encode($json_resp);
						}else{
							echo $callback_name."(".json_encode($json_resp).");";
						}
						die();
					}
					header('HTTP/1.0 403 Forbidden');
					echo "Album id not allowed";
					die();
					
				
				}
				
			}else{
				//filter albums allowed
				if (!isset($json_resp['feed']['entry'])){
					$json_resp['feed']['entry'] = array();
				}
				
				$albums_in = $json_resp['feed']['entry'];
				$albums_out = array();
				
				foreach ($albums_in as $album){
					
					if ($array_allowed['black_list']!==false && $this->album_match($album,$array_allowed['black_list'])){
						continue;
					}
					
					if ($album["rights"]['$t']=='public'){
						$albums_out[] = $album;
						continue;
					}
					$parti = explode('/',$album["id"]['$t']);
					$album_id = array_pop($parti);
					if (in_array($album_id,$array_allowed['allowed_albums'])){
						$albums_out[] = $album;
						continue;
					}
					if ($array_allowed['white_list']===false){
						continue;
					}
					
					//se è nella whitelist o è vuota la white list
					if (empty($array_allowed['white_list']) || $this->album_match($album,$array_allowed['white_list'])){
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
	
	function album_match($album,$allowed){
		
		$name= mb_strtolower($album['title']['$t']);
		
		foreach ($allowed as $a){
			$a = mb_strtolower($a);
			if (mb_strstr($name,$a)!==FALSE){
				return true;
			}
		}
		return false;
		
	}
	
	function get_allowed_albums($menu_id){
		$allowed_albums = array();
		$white_list = false;
		$black_list = false;
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
			$white_list = array();
			$black_list = array();
			$wlist = explode('|',$item->params->get("ozio_nano_whiteList", ""));
			$blist = explode('|',$item->params->get("ozio_nano_blackList", ""));
			foreach ($wlist as $k){
				$k = trim($k);
				if (!empty($k)){
					$white_list[] = $k;
				}
			}
			foreach ($blist as $k){
				$k = trim($k);
				if (!empty($k)){
					$black_list[] = $k;
				}
			}
			
			//nano e jgallery
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
				
	
		return array('allowed_albums'=>$allowed_albums,'white_list'=>$white_list,'black_list'=>$black_list);
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
		
		$obj_resp = $this->gi_post_form("https://www.googleapis.com/oauth2/v4/token",$postfields);
		$response = $obj_resp->body;

		
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
			JError::raiseError(500, "Unable to refresh token ".$response);
			die();
		}		
		
		
	}
		
		
	function gi_post_form($url,$postfields,$headers=array()){

		$timeout = null;

		$headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
		
		$data = http_build_query($postfields,'','&');
		
		$options = new \Joomla\Registry\Registry;
		$options->set('transport.curl',
			array(
				CURLOPT_SSL_VERIFYPEER => false,
			)
		);	
		$options->set('follow_location',true);	
		
		try{
			$response = JHttpFactory::getHttp($options)->post($url, $data, $headers, $timeout);
		}
		catch (UnexpectedValueException $e)
		{
			throw new RuntimeException('Could not send data to remote server: ' . $e->getMessage(), 500);
		}
		catch (RuntimeException $e)
		{
			// There was an error connecting to the server or in the post request
			throw new RuntimeException('Could not connect to remote server: ' . $e->getMessage(), 500);
		}
		catch (Exception $e)
		{
			// An unexpected error in processing; don't let this failure kill the site
			throw new RuntimeException('Unexpected error connecting to server: ' . $e->getMessage(), 500);
		}
		
		return $response;
	}

	function gi_get($url,$headers=array()){

		
		$timeout = null;

		
		$options = new \Joomla\Registry\Registry;
		$options->set('transport.curl',
			array(
				CURLOPT_SSL_VERIFYPEER => false,
			)
		);	
		$options->set('follow_location',true);	
		
		try{
			$response = JHttpFactory::getHttp($options)->get($url, $headers, $timeout);
		}
		catch (UnexpectedValueException $e)
		{
			throw new RuntimeException('Could not get data from remote server: ' . $e->getMessage(), 500);
		}
		catch (RuntimeException $e)
		{
			// There was an error connecting to the server or in the post request
			throw new RuntimeException('Could not connect to remote server: ' . $e->getMessage(), 500);
		}
		catch (Exception $e)
		{
			// An unexpected error in processing; don't let this failure kill the site
			throw new RuntimeException('Unexpected error connecting to server: ' . $e->getMessage(), 500);
		}
		
		return $response;
	}	
	
}