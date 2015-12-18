<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OzioViewRights extends JViewLegacy
{
    function display($tpl = null)
    {
		$app = JFactory::getApplication();
		$app->setHeader('Access-Control-Allow-Origin', 'https://www.opensourcesolutions.es');				

		$document = JFactory::getDocument();
		$document->setMimeEncoding('application/json');
		
		$jinput = $app->input;

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

		$url=$url."&access_token=".$access_token;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml',"GData-Version: 2","If-Match: *"));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_VERBOSE, true);

		// Make the REST call, returning the result
		$response = curl_exec($curl);

		$resp=json_decode($response,true);

		echo json_encode(array("response"=>$resp));		

        //parent::display($tpl);
    }
}