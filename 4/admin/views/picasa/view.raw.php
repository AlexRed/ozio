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
		
		
		$copy_params = array('kind','thumbsize','rnd','imgmax','authkey','tag','start-index','max-results');
		
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
		
		///albumid/
		if (empty($input_params['album_id']) || !preg_match('/^[a-zA-Z0-9\.\-@_]+$/',$input_params['album_id'])){
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
		
		$json_resp = $this->response_parse($response,isset($picasa_params['callback']));
		
		
		
		if ($json_resp===NULL && isset($picasa_params['access_token'])){
			//Nuovo access token
			$credentials = $this->refresh_token($credentials);
			$picasa_params['access_token'] = $credentials['access_token'];
			curl_setopt($curl, CURLOPT_URL, $url."?".http_build_query($picasa_params));

			
			$response = curl_exec($curl);
			$json_resp = $this->response_parse($response,isset($picasa_params['callback']));
		}
		if ($json_resp!==NULL){
		}else{
			header('HTTP/1.0 403 Forbidden');
		}

		echo $response;		

		die();
        //parent::display($tpl);
    }
	
	function response_parse($response,$callback){
		if ($callback){
			$parti = explode("\n",$response);
			foreach($parti as $p){
				/*
				// API callback
jQuery1124012552826618160506_1487071237494(.*);

preg_match ('/^jQuery[^(]+\((.*)\); *$/i','jQuery1124012552826618160506_1487071237494(pie())ro);  ',$matches);var_export($matches);

				*/
				$matches = array();
				if (preg_match ('/^ *jQuery[^(]+\((.*)\); *$/i',$p,$matches)){
					$response = $matches[1];
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