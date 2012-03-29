<?php

namespace Brickstorm\SmsBundle\Transport;

class SmsFactor
{
	//input parameters ---------------------
	var $username = 'bbergstorm@gmail.com'; //your username
	var $password = 'brickstorm';           //your password
	var $sender;                            //sender text : 11 chars
	var $message;                           //message text : 1120 chars
	var $flash;                             //Is flash message (1 or 0)
	var $inputgsmnumbers = array();         //destination gsm numbers
	var $type;                              //msg type ("bookmark" - for wap push, "longSMS" for text messages only)
	var $bookmark;                          //wap url (example: www.google.com)
	//--------------------------------------

	var $host;
	var $path;
	var $XMLgsmnumbers;
	var $xmldata;
	var $request_data;
	var $response;


	function send($sender, $message, $inputgsmnumbers, $flash=1, $type='longSMS', $bookmark=null)
	{
		$this->sender = $sender;
		$this->message = $message;
		$this->flash = $flash;
		$this->inputgsmnumbers = is_array($inputgsmnumbers) ? $inputgsmnumbers : array(trim($inputgsmnumbers));
		$this->type = $type;
		$this->bookmark = $bookmark;

		$this->host = "https://secure.smsfactor.com/API/apiV2.php";

		$this->convertGSMnumberstoXML();
		$this->prepareXMLdata();

		$this->response = $this->do_post_request($this->host,$this->request_data);

		return $this->response;
	}

	function convertGSMnumberstoXML()
	{
		$gsmcount = count($this->inputgsmnumbers); #counts gsm numbers

		for ( $i = 0; $i < $gsmcount; $i++ )
		{
			$this->XMLgsmnumbers .= "<gsm>" . $this->inputgsmnumbers[$i] . "</gsm>";
		}
	}

	function prepareXMLdata()
	{
		$this->xmldata = "<sms><authentification><username>" . $this->username . "</username><password>" . $this->password . "</password></authentification><message><sender>" . $this->sender . "</sender><text>" . $this->message . "</text><isFlash>" . $this->flash . "</isFlash><type>" . $this->type . "</type><bookmark>" . $this->bookmark . "</bookmark></message><recipients>" . $this->XMLgsmnumbers . "</recipients></sms>";
		$this->request_data = 'XML=' . $this->xmldata;
	}


	function do_post_request($url, $postdata, $optional_headers = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}

?>