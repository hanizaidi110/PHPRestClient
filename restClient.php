
<html>
<head><title> PHP REST CLIENT </title></head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

<p><h1>PhP REsT Client</h1></p>

<!--p> ID: <input type='text' name='id' value='' />  </p>
<p> Name: <input type='text' name='attritubeName' /> </p-->

<p> URL: <input type='text' name='url' /> </p> 
<p> REST Method <input type='text' name='method'
 placeholder='GET,DELETE,PUT or POST'/> </p>
  <p> Attribute Name <input type='text' name="attribute_name" value=""/> </p>
  <p> ID <input type='text' name='id' value="" /> </p>
 <input type="submit" name='submit' value="Submit" />


</form> 
</body>
</html>

<?php
set_time_limit(0);// to infinity

// -- CURL GET REQUEST-- //

if(isset($_POST['submit'])){

$url=$_POST['url'];

if($_POST['method']=="GET"){

$curl = curl_init();

$proxy = '127.0.0.1:3128';
//curl_setopt($curl, CURLOPT_PROXYPORT, '3128');
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_PROXY, $proxy);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


$result = curl_exec($curl);
echo curl_error($curl).'<br/>';
echo curl_errno($curl);

//parse json request

curl_close($curl);

}

//--- CURL POST REQUEST --//

if($_POST['method']=="POST"){
$url='http://localhost:8090/api/v1/thingies';

$attribute_name=$_POST['attribute_name'];
$proxy = '127.0.0.1';
$proxyPort = '3128';

$params=array('attribute_name'=>'John');
$encoded = '';
foreach($params as $name => $value){
    $encoded .= urlencode($name).'='.urlencode($value).'&';
}
$encoded = substr($encoded, 0, strlen($encoded)-1);

$defaults = array(
CURLOPT_URL => $url.'?attribute_name='.$attribute_name, 
CURLOPT_HTTPPROXYTUNNEL => 1,
CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
CURLOPT_RETURNTRANSFER => 1,
CURLOPT_HEADER => 1,
CURLOPT_POST => 1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_PROXY => $proxy,
CURLOPT_PROXYPORT => $proxyPort,
CURLOPT_FOLLOWLOCATION => 1,
CURLINFO_HEADER_OUT => 1,
CURLOPT_CONNECTTIMEOUT => 0,
CURLOPT_TIMEOUT => 400,
CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.87 Safari/537.36",
//CURLOPT_POSTFIELDS => $encoded,

CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Expect:'),
CURLINFO_HTTP_CODE => true,
);


$ch = curl_init();
$httpCode= curl_getinfo($ch, CURLINFO_HTTP_CODE);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt_array($ch, $defaults);

$result = curl_exec($ch);

echo '<br/>'.$result.'<br/>';

echo curl_error($ch).'<br/>';

echo curl_errno($ch);

echo '<br/>'."http code: ".$httpCode;
}

//---  CURL DELETE REQUEST ---//

if($_POST['method']=="DELETE"){

$id=$_POST['id'];
$json='';
$proxy = '127.0.0.1:3128';
	 
	$url = "http://localhost:8090/api/v1/thingies/$id";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
   // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);

    return $result;
}

//-- CURL PUT REQUEST --//

if($_POST['method']=="PUT"){
$id=$_POST['id'];
$attribute_name=$_POST['attribute_name'];

$url= "http://localhost:8090/api/v1/thingies/$id?attribute_name=$attribute_name";

$proxy = '127.0.0.1:3128';
//$params=array('attribute_name'=>'John');
$defaults = array(
CURLOPT_URL => $url,
CURLOPT_CUSTOMREQUEST => "PUT",
//CURLOPT_PUT => true,
CURLOPT_PROXY => $proxy,
CURLOPT_CONNECTTIMEOUT => 0,
CURLOPT_TIMEOUT => 400,
//CURLOPT_POSTFIELDS => http_build_query($params),
CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
);


$ch = curl_init();
curl_setopt_array($ch, $defaults);

$result = curl_exec($ch);

echo '<br/>'.$result.'<br/>';

echo curl_error($ch).'<br/>';

echo curl_errno($ch);
}
}
?>
