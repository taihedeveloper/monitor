<?php
include_once('phpcas/libraries/CAS.php');
phpCAS::client(CAS_VERSION_2_0, 'xxxxxxxxxxxxxx', 80, 'cas');
phpCAS::setNoCasServerValidation();
phpCAS::handleLogoutRequests();
if (phpCAS::checkAuthentication())
{
    $username = phpCAS::getUser();
}
else
{
    phpCAS::forceAuthentication();
}
setcookie("personalid", $username);


$ch = curl_init();
$url = "http://xxxxxxxxxxxx/monitor/loginuser?username=" . $username;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
$result = json_decode($output, true);
function curl_file_get_contents($durl){
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
    curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}


if ($result['error_code'] != 22000)
{
    echo file_get_contents('nopermisson.html');
}
else
{
    //获取完整的url
    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $user_select = "fe/template/user_select/index.html";

    if(strpos($url,$user_select) !== false){
        echo file_get_contents($user_select);
    }else{
        echo file_get_contents('index.html');
    }
}
?>
