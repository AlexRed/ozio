<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OzioViewPicasa extends JViewLegacy
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
		///albumid/
		if (empty($input_params['album_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['album_id'])){
		}else{
			$url = $url.'/albumid/'.$input_params['album_id'];
			if (empty($input_params['photo_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['photo_id'])){
			}else{
				$single_photo = true;
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
		
		if ($single_photo){
			$http_header = array();
		}else{
			$http_header = array("GData-Version"=>"3");
		}
		
		$resp_obj = $this->gi_get($url."?".http_build_query($picasa_params,'','&'),$http_header);		
		$response = $resp_obj->body;
		
		
		$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		
		
		
		if ($json_resp===NULL && isset($picasa_params['access_token'])){
			//Nuovo access token
			$credentials = $this->refresh_token($credentials);
			$picasa_params['access_token'] = $credentials['access_token'];

			$resp_obj = $this->gi_get($url."?".http_build_query($picasa_params,'','&'),$http_header);		
			$response = $resp_obj->body;

			$json_resp = $this->response_parse($response,isset($picasa_params['callback']),$callback_name);
		}
		if ($json_resp!==NULL){
			
			if (!isset($json_resp['feed']['entry'])){
				$json_resp['feed']['entry'] = array();
			}
			if (empty($callback_name)){
				echo json_encode($json_resp);
			}else{
				echo $callback_name."(".json_encode($json_resp).");";
			}
		}else{
			header('HTTP/1.0 403 Forbidden');
			echo $response;	
		}

			

		die();
        //parent::display($tpl);
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
		
		$resp_obj = $this->gi_post_form("https://www.googleapis.com/oauth2/v4/token",$postfields);
		
		$response = $resp_obj->body;

		
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
			JError::raiseError(500, "Unable to refresh token. ".$response);
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