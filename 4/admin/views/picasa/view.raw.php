<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');


if (!function_exists('http_response_code')) {
	function http_response_code($code = NULL) {

		if ($code !== NULL) {

			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					$code=500;
					$text = 'Internal Server Error'; break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

			header($protocol . ' ' . $code . ' ' . $text);

			$GLOBALS['http_response_code'] = $code;

		} else {

			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

		}

		return $code;

	}
}

class OzioViewPicasa extends JViewLegacy
{
	
    function display($tpl = null)
    {
		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		
		$jinput = $app->input;
		
		
		$copy_params = array('kind','thumbsize','rnd','imgmax',/*'authkey',*/'tag','start-index','max-results','pageToken');
		
		$ozio_payload = $jinput->get('ozio_payload', '', 'RAW');
		//user_id=5&v=2&alt=json&kind=album&access=public&thumbsize='+g_picasaThumbSize
		$input_params = array();
		parse_str($ozio_payload, $input_params);
		
		
		
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
			header('HTTP/1.0 403 Forbidden');
			echo "Empy credentials";
			die();
		}
		foreach ($copy_params as $k){
			if (isset($input_params[$k])){
				$picasa_params[$k] = $input_params[$k];
			}
		}

		

		$start_index = $jinput->get('ozio-picasa-start-index', '', 'RAW');
		$callback = $jinput->get('ozio-picasa-callback', '', 'RAW');
		$pageToken = $jinput->get('ozio-picasa-pageToken', '', 'RAW');
		
		
		if ($start_index!==''){
			$picasa_params['start-index'] = $start_index;
		}
		if ($callback!==''){
			$picasa_params['callback'] = $callback;
			$callback_name = $callback;
		}		
		if ($pageToken!==''){
			$picasa_params['pageToken'] = $pageToken;
			$input_params['pageToken'] = $pageToken;
		}
		if (!isset($picasa_params['imgmax'])){
			$picasa_params['imgmax'] = 'd';
		}
		if (!isset($picasa_params['thumbsize'])){
			$picasa_params['thumbsize'] = '32,48,64,72,104,144,150,160';
		}

		$url = NULL;
		$single_photo = false;
		$single_album = true;
		///albumid/
		
		$http_header = array();
		$library_params = array(
			'access_token' => $credentials['access_token']
		);
		
		$picasa_req_type = '';
		
		$library_body = array();
		
		if (empty($input_params['album_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['album_id'])){
			$url = 'https://photoslibrary.googleapis.com/v1/albums';
			$picasa_req_type = 'get_albums';

			$library_params['pageSize'] = 50;
			if (!empty($input_params['pageToken'])){
				$library_params['pageToken'] = $input_params['pageToken'];
			}
			$single_album = false;
		}else{
			if (empty($input_params['photo_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['photo_id'])){

				$picasa_req_type = 'get_photos';
			
				$url = 'https://photoslibrary.googleapis.com/v1/mediaItems:search';
				
				$library_body['pageSize'] = 100;
				$library_body['albumId'] = $input_params['album_id'];
				
				if (!empty($input_params['pageToken'])){
					$library_body['pageToken'] = $input_params['pageToken'];
				}
				
			}else{
				$single_photo = true;
				
				$picasa_req_type = 'get_photo';
				
				$url = 'https://photoslibrary.googleapis.com/v1/mediaItems/'.$input_params['photo_id'];
			}
		
		}

		$out = array(
			'feed' => array( 
				'entry' => array()
			) 
		);
		
		
		for ($pagina=0;$pagina<10;$pagina++){
			
			if (empty($library_body)){
				$resp_obj = $this->gi_get($url."?".http_build_query($library_params,'','&'),$http_header);
			}else{
				$resp_obj = $this->gi_post($url."?".http_build_query($library_params,'','&'),$library_body,$http_header);
			}
			$response = $resp_obj->body;
			$json_resp = json_decode($response,true);
			
			
			$already_refresh = false;
			for ($ei = 0; $ei<5; $ei++){
			
				if ($json_resp===NULL){
					http_response_code(500);
					die();
				}
				
				if (isset($json_resp['error'])){
					
					if ($json_resp['error']['code']==401){
						//Nuovo access token
						if ($already_refresh){
							http_response_code($json_resp['error']['code']);
							echo $response;
							die();
						}
						
						
						$already_refresh = true;
						$credentials = $this->refresh_token($credentials);
						
						$library_params['access_token'] = $credentials['access_token'];
						
						if (empty($library_body)){
							$resp_obj = $this->gi_get($url."?".http_build_query($library_params,'','&'),$http_header);
						}else{
							$resp_obj = $this->gi_post($url."?".http_build_query($library_params,'','&'),$library_body,$http_header);
						}
						
						$response = $resp_obj->body;
						
						$json_resp = json_decode($response,true);
						
					}else if ($json_resp['error']['code']==500 || $json_resp['error']['code']==503){
						//Exponential backoff
						
						$s_sleep = mt_rand(1, pow(2,$ei+1));
						sleep($s_sleep);
						
					}else{
						http_response_code($json_resp['error']['code']);
						echo $response;
						die();
					}
					
				}else{
					break;
				}
			}
			
			
			if ($json_resp===NULL){
				http_response_code(500);
				die();
			}
			if (isset($json_resp['error'])){
				http_response_code($json_resp['error']['code']);
				echo $response;
				die();
			}
			
			if ($picasa_req_type=='get_albums'){
					
				foreach ($json_resp['albums'] as $a){
					$o = array();
					
					$o['gphoto$numphotos']['$t'] = $a['mediaItemsCount'];
					$o['media$group']['media$title']['$t'] = $a['title'];
					$o['media$group']['media$description']['$t'] = '';
					$o['title']['$t'] = $a['title'];
					$o['summary']['$t'] = '';
					$o['author']=array(array('name' =>array('$t'=>'')));
					$o['id']['$t'] = 'https://picasaweb.google.com/data/entry/user/'.$input_params['user_id'].'/albumid/'.$a['id'];
					
					$o['gphoto$id']['$t'] = $a['id'];
					$o['gphoto$timestamp']['$t'] = '';
					$o['gphoto$albumType']['$t'] = '';
					
					
					$o['media$group']['media$thumbnail'] = array();
					
					$thumbsizes = explode(',',$picasa_params['thumbsize']);
					foreach ($thumbsizes as $ts){
					
						$ts=rtrim(trim($ts),'u');
						
						if (mb_substr($ts,-1)=='c'){
							$ts = intval(mb_substr($ts,0,mb_strlen($ts)-1),10);
							$o['media$group']['media$thumbnail'][] = array('url' => $a['coverPhotoBaseUrl'].'=w'.$ts.'-h'.$ts.'-c', 'width'=>$ts,'height'=>$ts );
						}else{
							$ts = intval($ts,10);
							
							$o['media$group']['media$thumbnail'][] = array('url' => $a['coverPhotoBaseUrl'].'=w'.$ts.'-h'.$ts, 'width'=>$ts,'height'=>$ts );
						}
					
						
					}
					
					$o['media$group']['media$content'] = array();
					
					$imgmax = $picasa_params['imgmax'];
					$ts=rtrim(trim($imgmax),'u');
					if (mb_substr($ts,-1)=='c'){
						$ts = intval(mb_substr($ts,0,mb_strlen($ts)-1),10);
						$o['media$group']['media$content'][] = array('url' => $a['coverPhotoBaseUrl'].'=w'.$ts.'-h'.$ts.'-c');
					}else if ($ts=='d'){
						$ts=2048;
						$o['media$group']['media$content'][] = array('url' => $a['coverPhotoBaseUrl'].'=d');
					}else{
						$ts = intval($ts,10);
						
						$o['media$group']['media$content'][] = array('url' => $a['coverPhotoBaseUrl'].'=w'.$ts.'-h'.$ts);
					}
					
					$out['feed']['entry'][] = $o;
				}				
				
				if ($start_index!==''){
					$out['feed']['openSearch$startIndex']['$t'] = intval($start_index,10);
				}else{
					$out['feed']['openSearch$startIndex']['$t'] = 1;
				}
				$out['feed']['openSearch$itemsPerPage']['$t'] = max(50,count($out['feed']['entry']));
				$out['feed']['openSearch$totalResults']['$t'] = $out['feed']['openSearch$startIndex']['$t']+ count($out['feed']['entry']) -1;
				
				
				unset($out['feed']['openSearch$nextPageToken']['$t']);
				if (isset($json_resp['nextPageToken'])){
					$out['feed']['openSearch$totalResults']['$t'] = $out['feed']['openSearch$totalResults']['$t'] + 50;
					$out['feed']['openSearch$nextPageToken']['$t'] = $json_resp['nextPageToken'];
				}
				
				
				if (isset($json_resp['nextPageToken'])){
					$library_params['pageToken'] = $json_resp['nextPageToken'];
				}else{
					break;
				}
			}else if ($picasa_req_type=='get_photos'){
					
				foreach ($json_resp['mediaItems'] as $a){
					
					$o = $this->media_item2entry($a,$picasa_params, $input_params);
					
					$out['feed']['entry'][] = $o;
				}
				

				$out['feed']['title']['$t'] = $this->get_album_name($input_params['album_id'],$credentials);
				
				$out['feed']['id']['$t'] ='https://picasaweb.google.com/data/feed/user/'.$input_params['user_id'].'/albumid/'.$input_params['album_id'];
				$out['feed']['subtitle']['$t']='';
				$out['feed']['rights']['$t'] ='protected';
				$out['feed']['author'] = array( array( 'name' => array('$t' => $input_params['user_id']), 'uri' => array('$t' => 'https://picasaweb.google.com/'.$input_params['user_id'])));
				$out['feed']['gphoto$id']['$t'] = $input_params['album_id'];
				$out['feed']['gphoto$name']['$t'] = $input_params['album_id'];
				$out['feed']['gphoto$access']['$t'] = 'protected';
				$out['feed']['gphoto$user']['$t'] = $input_params['user_id'];
				$out['feed']['gphoto$nickname']['$t'] = $input_params['user_id'];
				
				//$out['feed']['updated']['$t'] = '2019-02-21T06:40:25.851Z';
				//$out['feed']['gphoto$timestamp']['$t'] = '1550675725000';
				//$out['feed']['gphoto$numphotos']['$t'] =  2;
				
				
				if ($start_index!==''){
					$out['feed']['openSearch$startIndex']['$t'] = intval($start_index,10);
				}else{
					$out['feed']['openSearch$startIndex']['$t'] = 1;
				}
				$out['feed']['openSearch$itemsPerPage']['$t'] = max(100,count($out['feed']['entry']));
				$out['feed']['openSearch$totalResults']['$t'] = $out['feed']['openSearch$startIndex']['$t']+ count($out['feed']['entry']) -1;
				unset($out['feed']['openSearch$nextPageToken']['$t']);
				if (isset($json_resp['nextPageToken'])){
					$out['feed']['openSearch$totalResults']['$t'] = $out['feed']['openSearch$totalResults']['$t'] + 100;
					$out['feed']['openSearch$nextPageToken']['$t'] = $json_resp['nextPageToken'];
				}
				
				
				if (isset($json_resp['nextPageToken'])){
					$library_body['pageToken'] = $json_resp['nextPageToken'];
				}else{
					break;
				}
			}else if ($picasa_req_type=='get_photo'){
				$out['feed'] = $this->media_item2entry($json_resp,$picasa_params, $input_params);
				break;
			}
		}
		
		if ($picasa_req_type=='get_albums'){
			if (isset($picasa_params['max-results'])){
				$out['feed']['entry'] = array_slice ($out['feed']['entry'],0,$picasa_params['max-results']);
			}
		}else if ($picasa_req_type=='get_photos'){
			if (isset($picasa_params['max-results'])){
				$out['feed']['entry'] = array_slice ($out['feed']['entry'],0,$picasa_params['max-results']);
			}
		}else if ($picasa_req_type=='get_photo'){
		}
		
		if (empty($callback_name)){
			echo json_encode($out);
		}else{
			echo $callback_name."(".json_encode($out).");";
		}


			

		return;
        //parent::display($tpl);
    }
		
	function get_album_name($album_id,$credentials){
		$url = 'https://photoslibrary.googleapis.com/v1/albums/'.$album_id;
		
		$http_header = array();
		$library_params = array(
			'access_token' => $credentials['access_token']
		);
		
		$resp_obj = $this->gi_get($url."?".http_build_query($library_params,'','&'),$http_header);

		$response = $resp_obj->body;
		$json_resp = json_decode($response,true);
		
		
		$already_refresh = false;
		for ($ei = 0; $ei<5; $ei++){
		
			if ($json_resp===NULL){
				http_response_code(500);
				die();
			}
			
			if (isset($json_resp['error'])){
				
				if ($json_resp['error']['code']==401){
					//Nuovo access token
					if ($already_refresh){
						http_response_code($json_resp['error']['code']);
						echo $response;
						die();
					}
					
					
					$already_refresh = true;
					$credentials = $this->refresh_token($credentials);
					
					$library_params['access_token'] = $credentials['access_token'];
					
					$resp_obj = $this->gi_get($url."?".http_build_query($library_params,'','&'),$http_header);
					
					$response = $resp_obj->body;
					
					$json_resp = json_decode($response,true);
					
				}else if ($json_resp['error']['code']==500 || $json_resp['error']['code']==503){
					//Exponential backoff
					
					$s_sleep = mt_rand(1, pow(2,$ei+1));
					sleep($s_sleep);
					
				}else{
					http_response_code($json_resp['error']['code']);
					echo $response;
					die();
				}
				
			}else{
				break;
			}
		}
		
		
		if ($json_resp===NULL){
			http_response_code(500);
			die();
		}
		if (isset($json_resp['error'])){
			http_response_code($json_resp['error']['code']);
			echo $response;
			die();
		}
		
		/*
		(
		  'id' => '',
		  'title' => '',
		  'productUrl' => '',
		  'mediaItemsCount' => '2',
		  'coverPhotoBaseUrl' => '',
		  'coverPhotoMediaItemId' => '',
		)		
		*/

		return $json_resp['title'];
	}

	
	function media_item2entry($a,$picasa_params,$input_params){

		$o = array();
		
		$o['link'] = array();
		
		$o['media$group']['media$thumbnail'] = array();
		
		$thumbsizes = explode(',',$picasa_params['thumbsize']);
		foreach ($thumbsizes as $ts){
		
			$ts=rtrim(trim($ts),'u');
			
			if (mb_substr($ts,-1)=='c'){
				$ts = intval(mb_substr($ts,0,mb_strlen($ts)-1),10);
				$o['media$group']['media$thumbnail'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts.'-c', 'width'=>$ts,'height'=>$ts );
			}else{
				$ts = intval($ts,10);
				
				if ($a['mediaMetadata']['height']<$a['mediaMetadata']['width']){
					$o['media$group']['media$thumbnail'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts, 'width'=>$ts,'height'=>round($a['mediaMetadata']['height']*$ts/max($a['mediaMetadata']['width'],1)) );
				}else{
					$o['media$group']['media$thumbnail'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts, 'width'=>round($a['mediaMetadata']['width']*$ts/max($a['mediaMetadata']['height'],1)),'height'=>$ts );
				}
				
				
			}
		
			
		}
		
		$o['media$group']['media$content'] = array();
		
		$imgmax = $picasa_params['imgmax'];
		$ts=rtrim(trim($imgmax),'u');
		if (mb_substr($ts,-1)=='c'){
			$ts = intval(mb_substr($ts,0,mb_strlen($ts)-1),10);
			$o['media$group']['media$content'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts.'-c', 'width'=>$ts,'height'=>$ts, 'type' => $a['mimeType']  );
			$o['content'] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts.'-c', 'type' => $a['mimeType']  );
		}else if ($ts=='d'){
			$o['media$group']['media$content'][] = array('url' => $a['baseUrl'].'=d','width'=>$a['mediaMetadata']['width'], 'height'=>$a['mediaMetadata']['height'], 'type' => $a['mimeType']);
			$o['content']= array('url' => $a['baseUrl'].'=d','type' => $a['mimeType']);
		}else{
			$ts = intval($ts,10);
			if ($a['mediaMetadata']['height']<$a['mediaMetadata']['width']){
				$o['media$group']['media$content'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts, 'width'=>$ts,'height'=>round($a['mediaMetadata']['height']*$ts/max($a['mediaMetadata']['width'],1)), 'type' => $a['mimeType'] );
			}else{
				$o['media$group']['media$content'][] = array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts, 'width'=>round($a['mediaMetadata']['width']*$ts/max($a['mediaMetadata']['height'],1)),'height'=>$ts , 'type' => $a['mimeType']);
			}
			$o['content']= array('url' => $a['baseUrl'].'=w'.$ts.'-h'.$ts, 'type' => $a['mimeType']);
		}
		
		
		$l = array();
		
		$l['rel'] = 'self';
		$l['type'] = 'application/atom+xml';
		
		$l['href'] = 'https://picasaweb.google.com/data/entry/api/user/'.$input_params['user_id'].'/albumid/'.$input_params['album_id'].'/photoid/'.$a['id'].'?alt=json';
		
		$o['link'][] = $l;
		
		$o['gphoto$id']['$t'] = $a['id'];
		$o['gphoto$width']['$t'] = $a['mediaMetadata']['width'];
		$o['gphoto$height']['$t'] = $a['mediaMetadata']['height'];
		//$o['gphoto$size']['$t'] = round(0.044*$a['mediaMetadata']['width']*$a['mediaMetadata']['height']);
		$o['gphoto$access']['$t'] = "only_you";
		
		$date = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z',preg_replace('/[0-9][0-9][0-9]Z$/', 'Z',$a['mediaMetadata']['creationTime']));
		if ($date===FALSE){
			$date = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z',$a['mediaMetadata']['creationTime']);
		}
		if ($date===FALSE){
			$date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z',$a['mediaMetadata']['creationTime']);
		}
		
		if ($date===FALSE){
			$o['gphoto$timestamp']['$t'] = 0;
		}else{
			$o['gphoto$timestamp']['$t'] = $date->format('U').'000';
		}
		$o['updated']['$t'] = $a['mediaMetadata']['creationTime'];
		$o['gphoto$checksum']['$t'] = '';	
		$o['gphoto$albumid']['$t'] = $input_params['album_id'];	
		
		
		if (!isset($a['description'])){
			$a['description'] = '';
		}
		if (!isset($a['filename'])){
			$a['filename'] = $a['description'];
		}
		
		$o['title']['$t'] = $a['filename'];
		$o['media$group']['media$title']['$t'] =$a['filename'];

		$o['summary']['$t'] = $a['description'];
		$o['media$group']['media$description']['$t'] = $a['description'];
		$o['subtitle']['$t'] = $a['description'];		
	
		$o['media$group']['media$credit'] = array( array( '$t' => $input_params['user_id']));
	
		//$o['gphoto$commentCount']['$t'] = 0;
		//$o['gphoto$viewCount']['$t'] = 0;
		
		if (isset($a['mediaMetadata']['photo'])){
			if (isset($a['mediaMetadata']['photo']['cameraMake'])){
				$o['exif$tags']['exif$make']['$t'] = $a['mediaMetadata']['photo']['cameraMake'];
			}
			if (isset($a['mediaMetadata']['photo']['cameraModel'])){
				$o['exif$tags']['exif$model']['$t'] = $a['mediaMetadata']['photo']['cameraModel'];
			}
			if (isset($a['mediaMetadata']['photo']['focalLength'])){
				$o['exif$tags']['exif$focallength']['$t'] = $a['mediaMetadata']['photo']['focalLength'];
			}
			if (isset($a['mediaMetadata']['photo']['apertureFNumber'])){
				$o['exif$tags']['exif$fstop']['$t'] = $a['mediaMetadata']['photo']['apertureFNumber'];
			}
			if (isset($a['mediaMetadata']['photo']['isoEquivalent'])){
				$o['exif$tags']['exif$iso']['$t'] = $a['mediaMetadata']['photo']['isoEquivalent'];
			}
			if (isset($a['mediaMetadata']['photo']['exposureTime'])){
				$o['exif$tags']['exif$exposure']['$t'] = $a['mediaMetadata']['photo']['exposureTime'];
			}
			$o['exif$tags']['exif$time'] = $o['gphoto$timestamp']['$t'];
		}	
		
		return $o;
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
		
		
		
	function gi_post($url,$postfields,$headers=array()){
		
		$timeout = null;

		$headers['Content-Type'] = 'application/json; charset=utf-8';
		
		$data = json_encode($postfields);
		
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