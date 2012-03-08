<?php

namespace Brickstorm\SmsBundle\Vendor;

class SmsFactor
{
	//input parameters ---------------------
	var $username;                          //your username
	var $password;                          //your password
	var $sender;                            //sender text
	var $message;                           //message text
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


	function SendSMS($username, $password, $sender, $message, $flash, $inputgsmnumbers, $type, $bookmark)
	{
		$this->username = $username;
		$this->password = $password;
		$this->sender = $sender;
		$this->message = $message;
		$this->flash = $flash;
		$this->inputgsmnumbers = $inputgsmnumbers;
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