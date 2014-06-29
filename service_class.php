 <?php
namespace Service;
require '/curl_class.php';
use \Curl\Curl;
class Service
{
    
    function __construct()
    {
        $this->curl = new Curl();
    }
    
    public function select_service($url)
    {
        $split_url = explode('/', $url);
        if ($split_url[0] == 'https:' && $split_url[2] == 'github.com') {
            return "git_issue";
        } else if ($split_url[0] == 'https:' && $split_url[2] == 'bitbucket.org') {
            return "bit_issue";
        } else {
            return "Unrecognised";
        }
    }
    
    public function get_repo_name($url)
    {
        $split_url = explode('/', $url);
        return $split_url[4];
    }
    
    public function git_issue($user_name, $password, $url, $title, $body, $repo_name)
    {
        $service_url = 'https://api.github.com/repos/' . $user_name . '/' . $repo_name . '/issues';
        $data        = array(
            'title' => $title,
            'body' => $body
        );
        $data        = $this->curl->postFieldsJson($data);
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->curl->setUserAgent($user_name);
        $this->curl->setBasicAuthentication($user_name, $password);
        $this->curl->sslVerify(0);
        $post_data = $this->curl->post($service_url, $data);
        $this->curl->close();
        return $post_data;
        
    }
    
    public function bit_issue($user_name, $password, $url, $title, $body, $repo_name)
    {
        $service_url = 'https://bitbucket.org/api/1.0/repositories/' . $user_name . '/' . $repo_name . '/issues';
        $data        = array(
            'title' => $title,
            'body' => $body
        );
        $data        = $this->curl->postFieldsHttp($data);
        $this->curl->setBasicAuthentication($user_name, $password);
        $this->curl->sslVerify(0);
        $post_result = $this->curl->post($service_url, $data);
        $this->curl->close();
        return $post_result;
    }
} 
