<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{

    public function __construct()
    {
        parent::_initialize();
    }

    /**
     * @return mixed
     * @desc 文章列表
     */
    public function index() {
        $request=\request()->input();
        $offset = $request["p"]??1;

        $count =DB::table("carver_article")->where("uid",$request['uid'])->count();

        $list = DB::table("carver_article")->where("uid",$request['uid'])->orderByDesc("id")->forPage($offset,10)->get();

        if($list){
            $list=$list->toarray();
        }

        for($i=0;$i<count($list);$i++){

            $list[$i]->article_img = "http://".$_SERVER['HTTP_HOST']."/storage/".$list[$i]->article_img;
            $list[$i]->article_content = substr(html2text($list[$i]->article_content??''),0,250);
            $list[$i]->add_time = date("Y-m-d",(string)$list[$i]->add_time);
        }

        $data['count'] = $count;
        $data['data'] = $list;
        $data['hot'] = DB::table("carver_article")->orderByDesc("is_top")->limit(10)->get()->toArray();

        return $data;
    }

    /**
     * @return mixed
     * @desc 文章内容
     */
    public function content(){
        $request=\request()->input();

        if(!isset($request['id'])){
            return  returnJson(500,"系统错误～",0);
        }

        $id = $request['id'];

        $result = DB::table("carver_article")->where("id",$id)->first();

        $imgUrl=substr($this->apiUrl,0,strripos(trim($this->apiUrl,"/"),"/"));
        $result->add_time = date("Y-m-d",(string)$result->add_time);
        $result->update_time = $result->update_time ? date("Y-m-d",(string)$result->update_time) : "从未更新";
        $result->article_content = preg_replace('/src="/','src="'.$imgUrl.'/',$result->article_content);
        $result->url = $imgUrl."/content/".$id;

        $desc = substr(html2text($result->article_content),0,250);
        $logo = $imgUrl."/favicon.png";
        $result->qz_share = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=".$result->article_img."&title=".$result->article_title."&desc=".$desc."&summary=".$desc."&site=-SOURCE-&pics=".$logo;
        $result->qq_share = "http://connect.qq.com/widget/shareqq/index.html?url=".$result->article_img."&title=".$result->article_title."&source=-SOURCE-&desc=".$desc."&pics=".$logo."&summary=".$desc;
        $result->wb_share = "http://service.weibo.com/share/mobile.php?url=".$result->article_img."&title=".$result->article_title."&pic=".$logo."&appkey=2235474446";

        return  $result;
    }

    public function incLook(){
        try {
            $params=\request()->input();
            $look_num=DB::table("carver_article")->where("id",$params['id'])->increment("look_num",1);
            if($look_num){
                returnJson(200,"操作成功～",1);
            }
            returnJson(500,"系统错误～");
        }catch (\Exception $e){
            returnJson(500,"系统错误～");
        }

    }


    /**
     * @return mixed
     * @desc 发布文章
     */
    public function publish(){

        try {
            DB::beginTransaction();
            $params=\request()->input();
            $articleInfo['article_title']=$params['article_title'];
            $articleInfo['article_desc']=$params['article_desc'];
            $articleInfo['article_content']=$params['article_content'];
            $articleInfo['article_img']=$params['article_img'];
            $articleInfo['ip']=\request()->ip();;
            $articleInfo['add_time']=time();
            $addArticle=DB::table("carver_article")->insert($articleInfo);

            if($addArticle>0){
                DB::commit();
                $returnJson['data']['code']=1;
                $returnJson['status']=200;
                $returnJson['data']['msg']='发布成功～';
                return $returnJson;
            }
            DB::rollBack();
            $returnJson['data']['code']=0;
            $returnJson['status']=500;
            $returnJson['data']['msg']='系统错误～';
            return $returnJson;

        }catch (\Exception $e){;
            $returnJson['data']['code']=0;
            $returnJson['status']=500;
            $returnJson['data']['msg']="系统错误～";
            return $returnJson;
        }

    }
}
