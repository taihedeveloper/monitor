<?php
/**
 * @name Service_Page_ObtainData
 * @desc
 * @author chengbiao@taihe.com
 */

class Service_Page_ObtainData {
    private $objServiceDataObtainData;

    public function __construct(){
        $this->_db = Bd_Db_ConnMgr::getConn('ClusterOne');
    }

    public function getToken($monitoruser)
    {
        $curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $passwdsql = "SELECT password FROM robot_acount WHERE tel_no = '" . $monitoruser . "'";
        $passwd_ret = $this->_db->query($passwdsql);
        $passwd = $passwd_ret[0]['password'];
        $tokenurl = "https://passport.qianqian.com/v2/api/login?login_type=1&login_id=" . $monitoruser . "&password=" . $passwd;
        $cookies = 'device_id=monitordevice; device_type=1; tpl=baidu_music';
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_COOKIE, $cookies);
        curl_setopt($curl, CURLOPT_URL, $tokenurl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($curl);
        curl_close($curl);
        list($header, $body) = explode("\r\n\r\n", $output, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $cookie_arr = $matches[1];
        $token = "";
        foreach($cookie_arr as $cookie_one)
        {
            if (substr($cookie_one, 1, 6) == 'token_')
            {
                $token = substr($cookie_one, 8);
            }
        }
        return $token;
    }

    public function is_not_json($str)
    {
        return is_null(json_decode($str));
    }

    public function execute($arrInput){
        $username = $arrInput['username'];
        $request_type = $arrInput['request_type'];
        $post_content = $arrInput['post_content'];
		$post_content = json_decode($post_content, true);
        $url = $arrInput['url'];
		$timeout = $arrInput['timeout'];
        $referer = $arrInput['referer'];
        $useragent = $arrInput['useragent'];
        $monitoruser = $arrInput['monitoruser'];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if(!empty($monitoruser)){
            $token = $this->getToken($monitoruser);
            if ($token)
            {
                $cookies = "token_=" . $token;
                curl_setopt($curl, CURLOPT_COOKIE, $cookies);
            }
        }
		if(!empty($referer)){
			curl_setopt($curl, CURLOPT_REFERER, $referer);
		}
		if(!empty($useragent)){
			curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		}
		if(!empty($timeout)){
			curl_setopt($curl, CURLOPT_TIMEOUT, (int)($timeout/1000));
		}
        if(strpos($url, 'https://') !== false)
        {
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
        }
		curl_setopt($curl, CURLOPT_URL, $url);
		if($request_type == 1 && (!empty($post_content) && (!empty($post_content["Json"]) || !empty($post_content["urlEncode"])))){
			curl_setopt($curl,CURLOPT_POST, true);
			if(empty($post_content["Json"])){	//urlencode
				$url_encode_arr = $post_content["urlEncode"];
				$curl_encode = "";
				foreach($url_encode_arr as $key => $val){
					$curl_encode .= "$key=" . urlencode($val) ."&";
				}
				$curl_encode = substr($curl_encode, 0, -1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_encode);
			}else{
				curl_setopt($curl,CURLOPT_POSTFIELDS,$post_content["Json"]);  
			}
		}
		$data = curl_exec($curl);
		curl_close($curl);
                if ($this->is_not_json($data))
                {
                    $ret_data = $data;
                }
                else
                {
                    $ret_data = json_decode($data, true);
                }
		return $ret_data;
    }
}
