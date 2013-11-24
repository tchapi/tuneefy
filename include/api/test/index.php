<?php
require_once("../../oauth/OAuth.class.php");
require_once("../../../config.php");
/*
 * Config Section
 */
$domain = $_SERVER['HTTP_HOST'];
$base = "/oauth/example";
$base_url = "http://$domain$base";

/**
 * Some default objects
 */

$hmac_method = new OAuthSignatureMethod_HMAC_SHA1();


$key = @$_REQUEST['key'];
$secret = @$_REQUEST['secret'];
$endpoint = @$_REQUEST['endpoint'];
$action = @$_REQUEST['action'];
$dump_request = @$_REQUEST['dump_request'];
$sig_method = $hmac_method;


$test_consumer = new OAuthConsumer($key, $secret, NULL);



if ($action == "sign_and_execute_request") {
  $parsed = parse_url($endpoint);
  $params = array();
  parse_str($parsed['query'], $params);

  $req_req = OAuthRequest::from_consumer_and_token($test_consumer, NULL, "GET", $endpoint, $params);
  $req_req->sign_request($sig_method, $test_consumer, NULL);

  if ($dump_request) {
    Header('Content-type: text/plain');
    print "request url: " . $req_req->to_url(). "\n";
    print_r($req_req);
    exit;
  }
  Header("Location: $req_req");
}

?>
<html>
<head>
<title>OAuth Test Client</title>
</head>
<body>
<h1>OAuth Test Client</h1>
<form method="POST" name="oauth_client">
<h3>Signature Method : hmac</h3>

<h3>Enter The Endpoint to Test</h3>
endpoint: <input type="text" name="endpoint" value="<?php echo _API_URL; ?>/lookup?q=radiohead&limit=10&alt=json" size="100"/><br />
<small style="color: green">Note: You can include query parameters in there to have them parsed in and signed too</small>
<h3>Enter Your Consumer Key / Secret</h3>
consumer key: <input type="text" name="key" value="<?php echo _API_KEY; ?>" /><br />
consumer secret: <input type="text" name="secret" value="<?php echo _API_SECRET; ?>" /><br />
dump request, don't redirect: <input type="checkbox" name="dump_request" value="1" <?php if ($dump_request) echo 'checked="checked"'; ?>/><br />
make a token request (don't forget to copy down the values you get)
<input type="submit" name="action" value="sign_and_execute_request" />
</body>