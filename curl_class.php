<?php

namespace Curl;

class Curl
{
    public $curl;
    public $curl_error_code = 0;
    public $curl_error_message = null;
    public $raw_response = null;
    
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL library is not loaded');
        }
        $this->curl = curl_init();
        $this->setOpt(CURLINFO_HEADER_OUT, true);
        $this->setOpt(CURLOPT_HEADER, true);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $this->setOpt(CURLOPT_TIMEOUT, 10);
    }
    
    
    public function post($url, $data)
    {
        $this->setOpt(CURLOPT_URL, $url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $data);
        return $this->exec();
    }
    
    
    public function setBasicAuthentication($username, $password)
    {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username . ':' . $password);
    }
    
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $key . ': ' . $value;
        $this->setOpt(CURLOPT_HTTPHEADER, array_values($this->headers));
    }
    
    public function setUserAgent($user_agent)
    {
        $this->setOpt(CURLOPT_USERAGENT, $user_agent);
    }
    
    public function sslVerify($status)
    {
        $this->setOpt(CURLOPT_SSL_VERIFYPEER, $status);
    }
    
    public function setOpt($option, $value)
    {
        $ch = $this->curl;
        
        $required_options = array(
            CURLINFO_HEADER_OUT => 'CURLINFO_HEADER_OUT',
            CURLOPT_HEADER => 'CURLOPT_HEADER',
            CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER'
        );
        
        if (in_array($option, array_keys($required_options), true) && !($value === true)) {
            trigger_error($required_options[$option] . ' is a required option', E_USER_WARNING);
        }
        
        $this->options[$option] = $value;
        return curl_setopt($ch, $option, $value);
    }
    
    public function postFieldsJson($data = array())
    {
        return (empty($data) ? '' : json_encode($data));
    }
    
    public function postFieldsHttp($data = array())
    {
        return (empty($data) ? '' : http_build_query($data));
    }
    
    protected function exec()
    {
        $ch                     = $this;
        $ch->raw_response       = curl_exec($ch->curl);
        $ch->curl_error_code    = curl_errno($ch->curl);
        $ch->curl_error_message = curl_error($ch->curl);
        return $ch->raw_response . $ch->curl_error_code . $ch->curl_error_message;
    }
    
    public function close()
    {
        curl_close($this->curl);
    }
}
