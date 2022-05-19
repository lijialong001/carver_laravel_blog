<?php

/**
 * 根据ip获取地址信息
 * @param $ip
 * @return array
 * @throws \Exception
 */
if(!function_exists('ipForCountry')){
    function ipForCountry($ip)
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
    if(!function_exists('curlRequest')){
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


    if(!function_exists("returnJson")){
        function returnJson($status=500,$msg='',$code=0,$data=[]){
            $returnJson['status']=$status;
            $returnJson['data']['msg']=$msg;
            $returnJson['data']['code']=$code;
            $returnJson['data']['data']=$data;
            return $returnJson;
        }
    }



    if(!function_exists('html2text')){
        //html转text
        function html2text($str){
            $str = preg_replace("/<style .*?<\/style>/is", "", $str);
            $str = preg_replace("/<script .*?<\/script>/is", "", $str);
            $str = preg_replace("/<br \s*\/?\/>/i", "", $str);
            $str = preg_replace("/<\/?p>/i", "", $str);
            $str = preg_replace("/<\/?td>/i", "", $str);
            $str = preg_replace("/<\/?div>/i", "", $str);
            $str = preg_replace("/<\/?blockquote>/i", "", $str);
            $str = preg_replace("/<\/?li>/i", "", $str);
            $str = preg_replace("/\&nbsp\;/i", "", $str);
            $str = preg_replace("/\&nbsp/i", "", $str);
            $str = preg_replace("/\&amp\;/i", "", $str);
            $str = preg_replace("/\&amp/i", "", $str);
            $str = preg_replace("/\&lt\;/i", "", $str);
            $str = preg_replace("/\&lt/i", "", $str);
            $str = preg_replace("/\&ldquo\;/i", '', $str);
            $str = preg_replace("/\&ldquo/i", '', $str);
            $str = preg_replace("/\&lsquo\;/i", "", $str);
            $str = preg_replace("/\&lsquo/i", "", $str);
            $str = preg_replace("/\&rsquo\;/i", "", $str);
            $str = preg_replace("/\&rsquo/i", "", $str);
            $str = preg_replace("/\&gt\;/i", "", $str);
            $str = preg_replace("/\&gt/i", "", $str);
            $str = preg_replace("/\&rdquo\;/i", '', $str);
            $str = preg_replace("/\&rdquo/i", '', $str);
            $str = strip_tags($str);
            $str = html_entity_decode($str, ENT_QUOTES);
            $str = preg_replace("/\&\#.*?\;/i", "", $str);

            return $str;
        }
    }



}

