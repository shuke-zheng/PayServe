<?php
/**
 * Created by PhpStorm.
 * User: 多牛_xiaojun
 * Date: 2018/6/20
 * Time: 10:44
 */
namespace Shuke\PayServe\Serve;

use Shuke\PayServe\Contracts\PayServe;

class ComeTruePay implements PayServe{
    public function __construct($config){
        $this->api = $config['api'];
        $private_key = $this->chunk_split($config['PRIVATE_KEY']);
        $this->PRIVATE_KEY = $private_key;
        $public_key = $this->split($config['PUBLIC_KEY']);
        $this->PUBLIC_KEY = $public_key;

    }

    public function PayTo(array $data)
    {
        // TODO: Implement PaymentServices() method.
        $data['sign'] = $this->sign($data);
        $data = http_build_query($data);
        $url = $this->api.'?'.$data;
        $ret = $this->GetJsonData($url);
        return $ret;
    }

    public function PayOver(array $data){
        // TODO: Implement GetPayData() method.
        $state = $this->decode($data,$data['sign']);
        return $state;
    }

    private function GetJsonData(string $url){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
    }
    // encryption
    private function sign($data){
        ksort($data);
        $data_str = $this->getHttpBuildStrs($data);
        openssl_sign($data_str, $sign, $this->PRIVATE_KEY, OPENSSL_ALGO_SHA1);

        $sign = base64_encode($sign);
        return $sign;
    }
    //  发送处理
    private function getHttpBuildStrs(array $data)
    {
        $data_array = array();
        foreach ($data as $key => $value) {
            $data_array[] = $key .'='. $value;
        }
        return implode('&', $data_array);
    }

    private function chunk_split($key){
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($key, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        return $private_key;
    }

    private function decode(array $data,string $sign){
        ksort($data);
        $data_str = $this->getHttpBuildStrs($data);
        $sign = base64_decode($sign);
        $state = openssl_verify($data_str, $sign, $this->PUBLIC_KEY , OPENSSL_ALGO_SHA1);
        return $state;
    }

    private function split($key){
        $public_key = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($key, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        return $public_key;
    }
}