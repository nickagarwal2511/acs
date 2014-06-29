<?php
require '/service_class.php';
use \Service\Service;

//Validate all fields are present in request
$param_count = count($argv);
if ($param_count < 7) {
    echo "params missing";
} else {
    $user_tag    = '';
    $user_name   = '';
    $pasword_tag = '';
    $password    = '';
    $repo_tag    = '';
    $url         = $argv[5];
    $title       = $argv[6];
    if (isset($argv[7])) {
        $body = $argv[7];
    } else {
        $body = '';
    }
    
    foreach ($argv as $key => $value) {
        if ($value == '-u' && $user_name == '') {
            $user_tag  = $argv[$key];
            $user_name = $argv[$key + 1];
        } else if ($value == '-p' && $password == '') {
            $pasword_tag = $argv[$key];
            $password    = $argv[$key + 1];
        }
    }
    //Validate all required fields are present in correct form in request
    if (($user_tag == '-u') && ($pasword_tag == '-p') && !empty($user_name) && !empty($password) && !empty($url) && !empty($title)) {
        $service          = new Service();
        $selected_service = $service->select_service($url);
        $repo_name        = $service->get_repo_name($url);
        if ($selected_service != 'Unrecognised' && !empty($repo_name)) {
            echo $service_result = $service->$selected_service($user_name, $password, $url, $title, $body, $repo_name);
        } else {
            echo "$selected_service" . " : Service , repo missing : " . $repo_name;
        }
    } else {
        echo "required params missing";
    }
}
