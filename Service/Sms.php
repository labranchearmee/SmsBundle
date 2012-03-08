<?php

namespace Brickstorm\SmsBundle\Service;

class Sms
{

    function __construct($transport){
      $this->transport = $transport;
    }

    /**
    * send sms
    */
    function send($sender, $message, $phonenumber){
      $this->transport->send($sender, $message, $phonenumber);
    }
}