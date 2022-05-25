<template>

	<el-container style="width: 100%;margin-bottom: 1000px">

<!--            <div class="userInfo" v-show="user.show">-->
<!--              <div class="form">-->
<!--                <h3>告诉我,你是谁? <i class="iconfont r pot" @click="user.show=false">&#xe611;</i></h3>-->
<!--                <el-form ref="form" label-width="80px">-->
<!--                  <el-form-item label="腾讯扣扣">-->
<!--                    <el-input v-model="user.qq" type="number" @change="getQqInfo($event)" placeholder="可使用QQ号实时获取昵称+头像"></el-input>-->
<!--                  </el-form-item>-->
<!--                  <el-form-item label="网名昵称">-->
<!--                    <el-input v-model="user.name" placeholder="就是你的网名"></el-input>-->
<!--                  </el-form-item>-->
<!--                  <el-form-item label="电子邮箱">-->
<!--                    <el-input v-model="user.email" type="email" placeholder="可以是QQ邮箱"></el-input>-->
<!--                  </el-form-item>-->
<!--                  <el-form-item label="博客网址">-->
<!--                    <el-input v-model="user.url" placeholder="可以是QQ空间/个人网站/Github"></el-input>-->
<!--                  </el-form-item>-->
<!--                  <el-form-item style="margin: 0">-->
<!--                    <el-button type="primary" style="width: 200px" @click="userPost" >确定无误</el-button>-->
<!--                  </el-form-item>-->
<!--                </el-form>-->
<!--              </div>-->
<!--            </div>-->

        <div class="article_title" style="position: absolute;
            left: 50%;
            top: 80%;
            transform: translate(-50%, -150%);width: 90%;margin-top: 150px;height: 400px" >
            <div class="form">
                <el-form ref="form" label-width="80px" :label-position="labelPosition">
                    <el-form-item label="文章标题:">
                        <el-input v-model="article.article_title" type="text" placeholder="请输入文章标题"></el-input>
                    </el-form-item>
                    <el-form-item label="文章简介:">
                        <el-input v-model="article.article_desc" placeholder="请输入文章简介"></el-input>
                    </el-form-item>
                    <el-form-item label="文章封面:">
                        <el-upload
                            class="avatar-uploader"
                            action=""
                            :on-change="beforeAvatarUpload"
                            :http-request="uploadImage"
                            name="file"
                            :show-file-list="false"
                            >
                            <img v-if="bannerRuleForm.imageUrl" style="width: 100px;height: 100px" :src="bannerRuleForm.imageUrl" class="avatar" />
                            <i v-else class="el-icon-picture avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>

                </el-form>
            </div>
        </div>



		<div class="container" style="position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -150%);
            background: green;margin-bottom: 2000px;margin-top: 900px" v-loading.fullscreen.lock="!show" element-loading-text="拼命加载中" element-loading-background="rgba(255, 255, 255, 0.5)">
            <div class="leave">
                <!--DIV输入框-->
                <div class="editor" v-html="content" @keyup="setContent($event)" contenteditable></div>
                <span class="pcls" v-show="pclShow">{{pcl}}</span>
                <div class="tools">
                    <!--加粗--><i @click="setB" class="iconfont">&#xe6fe;</i>
                    <!--设置链接--><i @click="setURL" class="iconfont">&#xe63e;</i>
                    <!--显示表情--><i @click="setFACE" class="iconfont">&#xe659;</i>
                    <!--引用--><i @click="setREFER" class="iconfont">&#xe65d;</i>
                    <!--点击的所有表情--><ul v-show="showFACE" class="qqbq">
                        <li v-for="i in qqbq" ><img @click="inpFACE(i)" :src="'static/home/img/qqbq/'+i+'.gif'" /></li>
                    </ul>
                    <!--上传图片--><i @click="setIMG" class="iconfont">&#xe791;</i>
                    <!--上传代码--><i @click="setCODE" class="iconfont">&#xe6b9;</i>
                    <el-button @click="postLeave" class="r">添加文章</el-button>
                </div>
             </div>
        </div>
	</el-container>
</template>

<script>
	export default {
		props: ['init'],
		data() {
			return {
                show:true,
                page:{
                  count:0,
                  size:10
                },
                content: "",
                content2: "",
                pid: 0,
                pcl:"在这里输入您的留言吧~",
                pclShow:true,
                showFACE: false,
                qqbq:[],
                list:[],
                user:{
                  name:"",
                  qq:"",
                  url:"",
                  email:"",
                  show:false,
                  pid:""
                },
		uid:sessionStorage.uid,
                article:{
                    article_title:'',
                    article_desc:''
                },
                labelPosition: 'top',
                formLabelAlign: {
                    name: '',
                    region: '',
                    type: ''
                },
                bannerRuleForm:{
                    imageUrl:'',
                    imageTempUrl:''
                }
			}
		},
		created() {

		        for(var i=1;i<=74;i++){
                    this.qqbq.push(i);
                }
                this.$emit("SetHeader", true);
                this.$emit("SetScrollTop");
                sessionStorage['title'] = document.title = "发布文章 - "+(this.init.info.nick || this.init.info.name)+ "的博客"
                // this.getPage(1);
		},
		methods: {

            //上传文件之前的验证
            beforeAvatarUpload(file) {

                var img_type=['image/jpeg','image/png'];
                var isJPG=img_type.indexOf(file.raw.type)

                const isLt2M = file.raw.size / 1024 / 1024 < 2;

                if (isJPG===-1) {
                    this.$message.error('上传图片只能是 jpg / png 格式!');
                    return  ;
                }
                if (!isLt2M) {
                    this.$message.error('上传图片大小不能超过 2MB!');
                    return ;
                }
                return isJPG && isLt2M;
            },



            //上传文件
            uploadImage(item) {
                var file = item.file;
                let formData= new FormData();
                formData.append('file',file)

                this.$http({
                    url: 'http://api.carver.lijialong.site/api/uploadImg', //后端提供的接口
                    method: 'post',
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(({data}) => {
                    this.bannerRuleForm.imageUrl =data.filepath;
                    this.bannerRuleForm.imageTempUrl=data.filepath;
                    this.$message.success('上传成功～');
                })
            },

          setContent:function($e){//模拟双向绑定
            this.content2 = $e.target.innerHTML;
            if(this.content2.length<=0){
              this.pclShow = true;
            }else{
              this.pclShow = false;
            }
          },
			handleSizeChange(val) {
				console.log(`每页 ${val} 条`);
			},
			handleCurrentChange(val) {
				console.log(`当前页: ${val}`);
			},


            //加粗
			setB: function(){
              this.$prompt('请输入加粗文字',{
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                showClose:false
              }).then(({ value }) => {
                this.content = this.content2 = this.content2+"<b>"+value+"</b>";
              });
			},


            //连接
			setURL: function(){
                this.$prompt('请输入URL', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  showClose:false
                }).then(({ value }) => {
                  var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                  if(!reg.test(value)) {
                    this.$message.error('URL错误');
                    return;
                  }
                  this.content = this.content2 = this.content2+'<a href="'+value+'">'+value+'</a>';
                });
			},


             //显示表情
			 setFACE: function(){
				this.showFACE = !this.showFACE;
			 },


              
              //表情
              inpFACE: function(i){
                this.content = this.content2 = this.content2+"<img src=\"static/home/img/qqbq/"+i+".gif\">";
              },


              //引用
			  setREFER: function(){
                    this.$prompt('请输入引用内容', {
                      confirmButtonText: '确定',
                      cancelButtonText: '取消',
                      inputType:"textarea",
                      showClose:false
                    }).then(({ value }) => {
                      this.content = this.content2 = this.content2+"<blockquote>"+value+"</blockquote>";
                    });
              },


              //图片
			  setIMG: function(){
                    this.$prompt('请输入图片地址', {
                      confirmButtonText: '确定',
                      cancelButtonText: '取消',
                      showClose:false,
                    }).then(({ value }) => {
                      var reg=/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
                      if(!reg.test(value)) {
                        this.$message.error('URL错误');
                        return;
                      }
                      this.content = this.content2 = this.content2+'<img src="'+value+'" />';
                    });
			  },

                //code
			    setCODE: function(){
                    this.$prompt('请输入代码内容', {
                      confirmButtonText: '确定',
                      cancelButtonText: '取消',
                      inputType:"textarea",
                      showClose:false
                    }).then(({ value }) => {
                      this.content = this.content2 = this.content2+"<pre>"+value+"</pre>";
                    });
			    },


            /**
             * 提交文章
             */
            postLeave:function () {

                var self = this;


                if(self.article.article_title.length===0){
                    self.$message.error({
                        message:'文章标题不得为空～',
                        center:true
                    });
                    return;
                }

                if(self.article.article_desc.length===0){
                    self.$message.error({
                        message:'文章简介不得为空～',
                        center:true
                    });
                    return;
                }

                if(self.bannerRuleForm.imageUrl.length===0){
                    self.$message.error({
                        message:'文章封面不得为空～',
                        center:true
                    });
                    return;
                }

                if(self.content2.length<10){
                    self.$message.error({
                        message:'文章内容不得少于10个字～',
                        center:true
                    });
                    return;
                }



                this.$emit("posts",{
                  url:'/api/article/publish',
                  data:{
                      user_id:this.uid,
                      article_content:this.content2,
                      article_title:this.article.article_title,
                      article_desc:this.article.article_desc,
                      article_img:this.bannerRuleForm.imageTempUrl,
		                
                  },
                  success:function(e){
                    self.showFACE = false;
                    if(e.data.status==200){
                        if(e.data.data.code==1){
                          self.article_content = "";
                          self.content2 = "";
                          self.bannerRuleForm.imageUrl='';
                          self.$message.success({
                              message:"文章发布成功～",
                              center:true
                          });
                            window.location.href='/article';
                          // sessionStorage['leave_1'] = "";
                          // self.getPage(1);
                        }else{
                          self.$message.error({
                              message:e.data.data.msg,
                              center:true
                          });
                        }
                    }else{
                      self.$message.error({
                          message:'服务器异常 状态码' + e.data.status,
                          center:true
                      });
                    }
                  },error:function(e){
                    self.$message.error({
                        message:'服务器异常',
                        center:true
                    });
                  }});

          },

              //验证填写的用户信息
              // isUser:function () {
              //   if(this.user.name.length>0 && this.user.qq.length>0 &&  this.user.email.length>0 && this.user.url.length>0){
              //     return true;
              //   }else{
              //     return false;
              //   }
              // },


              //添加用户信息并提交文章【停用】
              // userPost:function(){
              //   if(!this.isUser()){
              //     this.$message.error('信息未填写完整');
              //     return;
              //   }
              //   this.$emit("posts",{
              //     url:'/api/user/add.html',
              //     data:{
              //       qq:this.user.qq,
              //       name:this.user.name,
              //       email:this.user.email,
              //       url:this.user.url,
              //     }});
              //
              //   this.user.show = false;
              //   this.postLeave();
              //
              // },


                //获取QQ信息 【停用】
              // getQqInfo:function(e){
              //   var self = this;
              //   this.$emit("gets",{
              //     url:'/api/user/qqinfo.html?qq='+e,
              //     success:function (e) {
              //       self.user.name = e.data.name;
              //       self.user.email = e.data.email;
              //       self.user.url = e.data.url;
              //     }
              //   });
              // },

              getPage(p) { //切换页面
                var self = this;
                self.show = false;
                var name = 'leave_'+p;
                if (sessionStorage[name]){
                  var data  = JSON.parse(sessionStorage[name]);
                  self.show = true;
                  self.list = data.list;
                  self.page.count = data.count;
                }else{
                  this.$emit("gets",{url:'/api/publish/index?p='+p,success:function(e){
                      if(e.status==200){
                        self.show = true;
                        self.list = e.data.list;
                        self.page.count = e.data.count;
                        sessionStorage[name] = JSON.stringify(e.data);
                      }
                    },error:function(e){
                      self.$message.error('服务器异常');
                    }});
                }
              }

		}
	}
</script>

<style>
.userInfo{
  width: 100%;
  height: 100%;
  position: fixed;
  background-color: rgba(0,0,0,0.5);
  z-index: 109;
  top:0;
  left: 0;
}
.userInfo .form{
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400px;
    height: 400px;
    margin-left: -200px;
    margin-top: -200px;
    background-color: #FFF;
    border-radius: 10px;
    padding: 20px;
    box-sizing: border-box;

  }
.userInfo .form h3{
  margin-bottom: 20px;
}

.pcls{
  position: absolute;
  color: #CCC;
  margin-top: -395px;
  margin-left: 10px;
}



.avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 200px;
    height: 200px;
    line-height: 200px;
    text-align: center;
  }






</style>
