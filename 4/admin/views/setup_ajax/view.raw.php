<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OzioViewSetup_Ajax extends JViewLegacy
{
    function display($tpl = null)
    {
		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		
		$jinput = $app->input;
		
		
		$action = $jinput->post->get('action', '', 'RAW');
		if ($action == 'add_credentials'){
			$client_id = trim($jinput->post->get('client_id', '', 'RAW'));
			$client_secret = trim($jinput->post->get('client_secret', '', 'RAW'));
			
			// client_id,
			// client_secret		
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			 
			$columns = array('client_id', 'client_secret', 'status');
			$values = array($db->quote($client_id), $db->quote($client_secret),$db->quote('pending'));
			$query
				->insert($db->quoteName('#__ozio_setup'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			$db->setQuery($query);
			$db->execute();
			
			echo json_encode(array("status"=>true));
			
		}else if ($action == 'list_credentials'){
			//id,
			//client_id
			//client_secret
			//user_id
			//status: pending  authorized
			//
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'client_id', 'client_secret', 'user_id','status')));
			$query->from($db->quoteName('#__ozio_setup'));
			$query->order('id DESC');			
			$db->setQuery($query);
			
			$list = $db->loadAssocList();
			
			echo json_encode(array("list"=>$list,"status"=>true));
			
		}else if ($action == 'delete_credentials'){
			//id
			$id = $jinput->post->get('id', 0, 'INT');
						
			$db = JFactory::getDbo();
			 
			$query = $db->getQuery(true);
			 
			$query->delete($db->quoteName('#__ozio_setup'));
			$query->where(array( $db->quoteName('id') . ' = ' . $db->quote($id)	));
			 
			$db->setQuery($query);
			 
			$result = $db->execute();			
			
			echo json_encode(array("status"=>true));
		}else if ($action == 'revoke'){
			//id
			//code
			$id = $jinput->post->get('id', 0, 'INT');
			
				
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'client_id', 'client_secret', 'user_id','status','access_token')));
			$query->from($db->quoteName('#__ozio_setup'));
			$query->where($db->quoteName('id') . ' = '. $db->quote($id));	
			$db->setQuery($query);
				
			$credentials = $db->loadAssoc();
			if (empty($credentials)){
				echo json_encode(array("status"=>false,"msg"=>"Credentials id not found"));
				die();
			}			
			
						
			$resp_obj = $this->gi_get("https://accounts.google.com/o/oauth2/revoke?token=".urlencode($credentials['access_token']));
			$response = $resp_obj->body;


			$query = $db->getQuery(true);
				 
			// Fields to update.
			$fields = array(
				$db->quoteName('refresh_token') . ' = NULL ',
				$db->quoteName('access_token') . ' = NULL',
				$db->quoteName('user_id') . ' = NULL',
				$db->quoteName('status') . ' = ' . $db->quote('pending'),
			);
			 
			// Conditions for which records should be updated.
			$conditions = array(
				$db->quoteName('id') . ' = ' . $db->quote($id)
			);
			 
			$query->update($db->quoteName('#__ozio_setup'))->set($fields)->where($conditions);
			 
			$db->setQuery($query);
			 
			$result = $db->execute();

				
				
			echo json_encode(array("status"=>true));			
			
		}else if ($action == 'google_signin_callback'){
			//id
			//code
			$id = $jinput->post->get('id', 0, 'INT');
			$code = trim($jinput->post->get('code', '', 'RAW'));
			
				
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'client_id', 'client_secret', 'user_id','status')));
			$query->from($db->quoteName('#__ozio_setup'));
			$query->where($db->quoteName('id') . ' = '. $db->quote($id));	
			$db->setQuery($query);
				
			$credentials = $db->loadAssoc();
			if (empty($credentials)){
				echo json_encode(array("status"=>false,"msg"=>"Credentials id not found"));
				die();
			}
			
			//https://developers.google.com/identity/protocols/OAuth2WebServer
			
			$postfields = array(
				'code' => $code,
				'client_id' => $credentials['client_id'],
				'client_secret' => $credentials['client_secret'],
				'redirect_uri' => 'postmessage',
				'grant_type' => 'authorization_code',
			
			);
			
			//If you need to re-prompt the user for consent, include the prompt parameter in the authorization code request, and set the value to consent.
			$resp_obj = $this->gi_post_form("https://www.googleapis.com/oauth2/v4/token",$postfields);
			$response = $resp_obj->body;

			$resp=json_decode($response,true);
			
			/*
{
  "access_token":"1/fFAGRNJru1FTz70BzhT3Zg",
  "expires_in":3920,
  "token_type":"Bearer",
  "refresh_token":"1/xEoDL4iW3cxlI7yDbSRFYNG01kVKM2C-259HOF2aQbI"
}			*/
			
			//se invece l'utente aveva già dato il consenso
			
/*
{
  "access_token":"1/fFAGRNJru1FQd77BzhT3Zg",
  "expires_in":3920,
  "token_type":"Bearer",
}
*/			
			if (isset($resp['refresh_token']) && isset($resp['id_token'])){
				$dec_token = $this->decodeToken($resp['id_token']);
				
				$query = $db->getQuery(true);
				 
				// Fields to update.
				$fields = array(
					$db->quoteName('refresh_token') . ' = ' . $db->quote($resp['refresh_token']),
					$db->quoteName('access_token') . ' = ' . $db->quote($resp['access_token']),
					$db->quoteName('user_id') . ' = ' . $db->quote($dec_token[1]['sub']),
					//$db->quoteName('expires_on') . ' = ' . $db->quote(time()+$resp['expires_in']),
					$db->quoteName('status') . ' = ' . $db->quote('authorized'),
				);
				 
				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('id') . ' = ' . $db->quote($id)
				);
				 
				$query->update($db->quoteName('#__ozio_setup'))->set($fields)->where($conditions);
				 
				$db->setQuery($query);
				 
				$result = $db->execute();

				
				
				echo json_encode(array("status"=>true,"msg"=>JText::_('COM_OZIOGALLERY3_AUTH_OK_MESSAGE')));
			}else{
				
				echo json_encode(array("status"=>false,"msg"=>"Error in getting refresh token. ".$response));
			}

		}else{
			
		
			echo json_encode(array("status"=>false,"msg"=>"Invalid action"));
			die();
		}


        //parent::display($tpl);
    }
	function decodeToken($id_token){
		$parts = array();
		$base = explode('.',$id_token);
		foreach ($base as $b){
			$parts[] = json_decode(base64_decode($b),true);
		}
		return $parts;
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