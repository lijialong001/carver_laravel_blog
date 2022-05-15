<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $apiUrl='http://api.blog.lijialong.site/api/';
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * 根据ip获取地址信息
     * @param $ip
     * @return array
     * @throws \Exception
     */
    public function ipForCountry($ip)
    {
        $ip2region = new \Ip2Region();
        $info = $ip2region->btreeSearch($ip);
        // 如果获取不到就不记录
        if (!isset($info)) {
            $data = [
                'country' => '',
                'province' => '',
                'city' => '',
            ];
            return  $data;
        }

        $info = explode('|', $info['region']);
        $data = [
            'country' => $info[0] != '0' ? $info[0] : '',
            'province' => '',
            'city' => '',
        ];
        if (isset($info[2]) && $info[2] != '0') {
            $data['province'] = $info[2];
        }
        if (isset($info[3]) && $info[3] != '0') {
            $data['city'] = $info[3];
        }
        if ($data['province'] == '香港') {
            $data['country'] = '香港';
        }
        return $data;
    }

    /**
     * 发送http请求
     * @param $url
     * @param bool $post
     * @param array $param
     * @param bool $https
     * @param null $header
     * @return mixed
     */
    function curlRequest($url, $post = false, $param = array(), $https = false, $header = null, $show_error = false)
    {
        //curl_init 初始化的时候传递url
        $ch = curl_init($url);
        //curl_setopt 设置一些请求选项
        if ($post) {
            //设置请求方式和请求参数
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        }
        // https请求，默认会进行验证
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //设置header头
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_exec 执行请求会话（发送请求）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);

        if ($res === false && $show_error) {
            $res = curl_error($ch);
        }
        //curl_close 关闭请求会话
        curl_close($ch);
        return $res;
    }


}
