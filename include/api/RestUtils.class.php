<?php

/* *************************************************** */
/*                                                     */
/*                  REST UTILS CLASS                   */
/*                                                     */
/* *************************************************** */


class RestUtils
{

  public static function processRequest()
	{
		// What is the method used ?
		$request_method = strtolower($_SERVER['REQUEST_METHOD']);
		$request = new RestRequest();
		
		// Storing the requested data
		$data = array();

		switch ($request_method)
		{
			
			/* *** GET *** */
			case 'get':
				
				// Because of the htaccess ...
				// Extra parameters in the request URI
        $params = array();
        $parts = preg_split("~=|&~", $_SERVER['REQUEST_URI'], -1, PREG_SPLIT_NO_EMPTY); 
        
        // Skip through the segments by 2
        for($i = 0; $i < count($parts); $i = $i + 2){
        
          // First segment needs a lift because it contains the script name (method actually)
          if ($i == 0) {
            $parts[$i] = preg_replace("/(\/[^\?]*\?)(.*)/", "$2", $parts[$i]);
          }
          
          if (isset($parts[$i]) && isset($parts[$i+1])) {
            // First segment is the param name, second is the value 
            $params[$parts[$i]] = $parts[$i+1];
          }
        }
        
        $data = $params; 
        $request->validateRequest();
        break;
			
			/* *** POST *** */
			case 'post':
				$data = $_POST;
        $request->invalidateRequest();
				break;
			
			/* *** PUT *** */
			case 'put':
				// basically, we read a string from PHP's special input location,
				// and then parse it out into an array via parse_str... per the PHP docs:
				// Parses str  as if it were the query string passed via a URL and sets
				// variables in the current scope.
				parse_str(file_get_contents('php://input'), $put_vars);
				$data = $put_vars;
        $request->invalidateRequest();
				break;
		}
		
    // Storing the data into data (redundant for GET, but well)
    $request->setData((object)$data);

		// Storing the method used
		$request->setMethod($request_method);

		// Set the raw data, so we can access it if needed (there may be
		// other pieces to your requests)
		$request->setRequestVars($data);
    
    // If we have data (as per in a post request), we store it
    if(isset($data['data']))  
    {  
        // translate the JSON to an Object for use however you want  
        $request->setData(json_decode($data['data']));  
    }
    
    // If we force JSON or XML instead of ACCEPT
    if(isset($data['alt']) && ($data['alt'] == 'json' || $data['alt'] == 'xml') ){
    
      $request->setHttpAccept($data['alt']);
    }
     
		return $request;
	}

  public static function sendResponse($status = 200, $body = '', $content_type = 'json', $apiMode = true, $json_key = null)
	{
	  $messages = RestUtils::getStatusCodeMessage($status);
		
		// Set the status	
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $messages['official'];
		header($status_header);
		
		// We need to create the body if none is passed (== error)
		if ($body === '' || $body === null)
		{

			$body = array("code" => $status, "message" => $messages['message']);

		}

		// And we can send the response

    if ($content_type == 'json'){
	  		        
      /* ******************************* */
      //            JSON                 //
      /* ******************************* */
    		
      if ($apiMode == true) {
        // Now we add some metadata
        $response = array("provider" => "tuneefy", 
                          "api" => true, 
                          "version" => "0.9b", 
                          "status" => $status==200?"splendid":"bloody hell", 
                          "data" => $body);
      } else {
        $response = array("json_key" => $json_key, 
                          "data" => $body);
      }
      
      // Sets the content type
      header("Content-type: application/json; charset=UTF-8 ");
      
      echo json_encode($response);

	  } else if ($content_type == 'xml'){
	      
      /* ******************************* */
      //            XML                  //
      /* ******************************* */  		  
      
  		// Sets the content type
  		header("Content-type: text/xml; charset=UTF-8 ");
          
      $xml = new XMLHelper();
      
      // Adding the metadata
      $xml->push('response', array("provider" => "tuneefy", 
                                   "api" => true, 
                                   "version" => "0.9b", 
                                   "status" => $status==200?"splendid":"bloody hell"));
      $xml->push('data');
      
      function recursive_push($arrResult, $xml){ 
        while(list($key,$value)=each($arrResult)){ 
          if (is_array($value)){
            if (is_numeric($key)) // for elements that are integer keys, like js arrays
              $xml->push("item", array("rank" => $key));
            else
              $xml->push($key);
              
              recursive_push($value, $xml); 
            $xml->pop();
          } else { 
            for ($i=0; $i<count($value);$i++){ 
              if (is_numeric($key))
                $xml->element("item", $value, array("id" => $key));
              else
                $xml->element($key, $value);
            } 
          } 
        } 
      }

      recursive_push($body, $xml); 
      
      $xml->pop(); // data
      $xml->pop(); // response

      echo utf8_encode($xml->getXml());
	  
	  }
	  
		exit;
	}


	public static function getStatusCodeMessage($status)
	{
		$codes = Array(
		    100 => array("official" => 'Continue', "message" => 'Let me go on'),
		    101 => array("official" => 'Switching Protocols', "message" => 'Crossing wires'),
		    200 => array("official" => 'OK', "message" => 'Great job!'),
		    201 => array("official" => 'Created', "message" => 'Done'),
		    202 => array("official" => 'Accepted', "message" => 'Challenge: accepted'),
		    203 => array("official" => 'Non-Authoritative Information', "message" => 'Are you sure ?'),
		    204 => array("official" => 'No Content', "message" => 'Ain\'t nuthin\''),
		    205 => array("official" => 'Reset Content', "message" => 'From scratch'),
		    206 => array("official" => 'Partial Content', "message" => 'It seems like something is missing here'),
		    300 => array("official" => 'Multiple Choices', "message" => 'Too many choices'),
		    301 => array("official" => 'Moved Permanently', "message" => 'We\'ve moved'),
		    302 => array("official" => 'Found', "message" => 'Fantastic'),
		    303 => array("official" => 'See Other', "message" => 'Ask another chap'),
		    304 => array("official" => 'Not Modified', "message" => 'Haven\'t touched it, trust me'),
		    305 => array("official" => 'Use Proxy', "message" => 'Do use a proxy, for fuck\'s sake'),
		    306 => array("official" => '(Unused)', "message" => '(Unused. Yet)'),
		    307 => array("official" => 'Temporary Redirect', "message" => 'Redirecting you in a moment'),
		    400 => array("official" => 'Bad Request', "message" => 'That is not a way indeed to formulate a query, gentleman.'),
		    401 => array("official" => 'Unauthorized', "message" => 'Thou shall not trespass'),
		    402 => array("official" => 'Payment Required', "message" => 'Money. Now.'),
		    403 => array("official" => 'Forbidden', "message" => 'Na na na'),
		    404 => array("official" => 'Not Found', "message" => 'Really sorry but this thing is gone'),
		    405 => array("official" => 'Method Not Allowed', "message" => 'You can\'t do this I\'m sorry !'),
		    406 => array("official" => 'Not Acceptable', "message" => 'I cannot let you say this'),
		    407 => array("official" => 'Proxy Authentication Required', "message" => 'What credentials ?'),
		    408 => array("official" => 'Request Timeout', "message" => 'Zzzzzzzz'),
		    409 => array("official" => 'Conflict', "message" => 'War!'),
		    410 => array("official" => 'Gone', "message" => 'Sorry bro, gone.'),
		    411 => array("official" => 'Length Required', "message" => 'What\'s the size of this, may I beg you?'),
		    412 => array("official" => 'Precondition Failed', "message" => 'Precondition Failed'),
		    413 => array("official" => 'Request Entity Too Large', "message" => 'That is way too big, sir'),
		    414 => array("official" => 'Request-URI Too Long', "message" => 'That is far too long, sir'),
		    415 => array("official" => 'Unsupported Media Type', "message" => 'I can\'t handle this'),
		    416 => array("official" => 'Requested Range Not Satisfiable', "message" => 'I\'m not satisfied with your range'),
		    417 => array("official" => 'Expectation Failed', "message" => 'I deserved better'),
		    500 => array("official" => 'Internal Server Error', "message" => 'Christ on a bike !'),
		    501 => array("official" => 'Not Implemented', "message" => 'Gimme a minute to code this'),
		    502 => array("official" => 'Bad Gateway', "message" => 'You\'re being naughty!'),
		    503 => array("official" => 'Service Unavailable', "message" => 'We\'ll be back soon'),
		    504 => array("official" => 'Gateway Timeout', "message" => 'Too late, bro'),
		    505 => array("official" => 'HTTP Version Not Supported', "message" => 'Are you living in the eighties?')
		);

		return (isset($codes[$status])) ? $codes[$status] : '';
	}
}

/* *************************************************** */
/*                                                     */
/*                 REST REQUEST CLASS                  */
/*                                                     */
/* *************************************************** */

class RestRequest
{
	private $request_vars;
	private $data;
	private $http_accept;
	private $method;
	private $is_authorised;

	public function __construct()
	{
		$this->request_vars		= array();
		$this->data				    = '';
		
		if (isset($_SERVER['HTTP_ACCEPT']))
		  $this->http_accept		= (strpos($_SERVER['HTTP_ACCEPT'], 'json')) ? 'json' : 'xml';
		else $this->http_accept = 'xml';
		
		$this->method			    = 'get';
		$this->is_authorised  = false;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}

	public function setRequestVars($request_vars)
	{
		$this->request_vars = $request_vars;
	}
	
	public function setHttpAccept($http_accept)
	{
		$this->http_accept = $http_accept;
	}
	
	public function validateRequest()
	{
		$this->is_authorised = true;
	}
	
	public function invalidateRequest()
	{
		$this->is_authorised = false;
	}
	
	public function isAuthorised()
	{
	  return $this->is_authorised;
  }

	public function getData()
	{
		return $this->data;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getHttpAccept()
	{
		return $this->http_accept;
	}

	public function getRequestVars()
	{
		return $this->request_vars;
	}
}
