acs
===

ACS XEROX TEST

Sample url is :

php create_issue.php -u :username -p password https://github.com/username/repository_name "issue title" "issue body"


php create_issue.php -u :username -p password https://bitbucket.org/username/repository_name "issue title" "issue content"


required params

-u username 

-p password

url

"title"


optional params

"body"

This application has a main library in curl_class.php which contains all the curl methods.

A wrapper class service_class.php, which based on request url , formulates the API request for the requested service , in this case GitHub and BitBucket.

A php simple script, create_issue.php, which validates the request parameters and call the wapper class methods.

In order to add any new similer type of service API(apart from GitHub and BitBucket), only wrapper class(service_class.php) needs to be added with the method for that service , and main library (curl_class.php), validation script(create_issue.php) need not to be modified. 





