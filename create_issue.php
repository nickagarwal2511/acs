<?php
require '/curl_class.php';
use \Curl\Curl;

$curl = new Curl();
//Validate all fields are present in request
$param_count=count($argv);
if($param_count < 9){
echo "params missing";
}else{
define('Git_URL', 'https://api.github.com/repos/');
define('Bit_URL', 'https://bitbucket.org/api/1.0/repositories/');
define('SSL_STATUS',0);

$user_tag = '';
$user_name = '';
$pasw_tag = '';
$pasw = '';
$repo_tag = '';
$repo_name = '';
$url = $argv[7];
$title = $argv[8];

if(isset($argv[9])){
	$body = $argv[9];
}else{
	$body = '';
}

foreach($argv as $key => $value){
	if($value == '-u' && $user_name==''){
		$user_tag = $argv[$key];
		$user_name = $argv[$key+1];
	}else if($value == '-p' && $pasw==''){
		$pasw_tag = $argv[$key];
		$pasw = $argv[$key+1];
	}else if($value == '-r' && $repo_name==''){
		$repo_tag = $argv[$key];
		$repo_name = $argv[$key+1];
	}
}
//Validate all required fields are present in correct sequence in request
if(($user_tag == '-u') 
	&& ($pasw_tag == '-p') 
	&& ($repo_tag == '-r') 
	&& !empty($user_name)
	&& !empty($pasw) 
	&& !empty($repo_name) 
	&& !empty($title)){
	
	// Select Service based on URL
	$git= strpos($url,"github.com");
	$bit= strpos($url,"bitbucket.org");
	echo $bit.'------'.$git;
	if(!empty($git) && $git !==0 ){
		$service_url = Git_URL.$user_name.'/'.$repo_name.'/issues';
		echo $service_url;
		$data=array('title'=>$title,'body'=>$body);
		$data=$curl->postFieldsJson($data);
		$curl->setHeader('Content-Type','application/json');
		$curl->setUserAgent($user_name);
	}else if(!empty($bit) && $bit !== 0){
		$service_url = Bit_URL.$user_name.'/'.$repo_name.'/issues/';
		echo $service_url;
		$data=array('title'=>$title,'content'=>$body,'status'=>'new','priority'=>'trivial','kind'=>'bug');
		$data=$curl->postFieldsHttp($data);
	}else{
		echo "Unknown URL";
	}
	
	if(isset($service_url) && !empty($service_url))
	{
		$curl->setBasicAuthentication($user_name,$pasw);
		$curl->sslVerify(SSL_STATUS);
		$post=$curl->post($service_url, $data);
		$curl->close();
		var_dump($post);
	}else{
		echo "URL Unknown";
	}
}else{
 echo "required params missing2";
}
}
