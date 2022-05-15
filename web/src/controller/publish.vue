<template>

	<el-container style="width: 100%;margin-bottom: 1000px">

            <div class="userInfo" v-show="user.show">
              <div class="form">
                <h3>告诉我,你是谁? <i class="iconfont r pot" @click="user.show=false">&#xe611;</i></h3>
                <el-form ref="form" label-width="80px">
                  <el-form-item label="腾讯扣扣">
                    <el-input v-model="user.qq" type="number" @change="getQqInfo($event)" placeholder="可使用QQ号实时获取昵称+头像"></el-input>
                  </el-form-item>
                  <el-form-item label="网名昵称">
                    <el-input v-model="user.name" placeholder="就是你的网名"></el-input>
                  </el-form-item>
                  <el-form-item label="电子邮箱">
                    <el-input v-model="user.email" type="email" placeholder="可以是QQ邮箱"></el-input>
                  </el-form-item>
                  <el-form-item label="博客网址">
                    <el-input v-model="user.url" placeholder="可以是QQ空间/个人网站/Github"></el-input>
                  </el-form-item>
                  <el-form-item style="margin: 0">
                    <el-button type="primary" style="width: 200px" @click="userPost" >确定无误</el-button>
                  </el-form-item>
                </el-form>
              </div>
            </div>

        <div class="article_title" style="position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -150%);width: 90%;margin-top: 150px" >
            <div class="form">
                <el-form ref="form" label-width="80px" :label-position="labelPosition">
                    <el-form-item label="文章标题:">
                        <el-input v-model="user.qq" type="text" @change="getQqInfo($event)" placeholder="请输入文章标题"></el-input>
                    </el-form-item>
                    <el-form-item label="文章简介:">
                        <el-input v-model="user.name" placeholder="请输入文章简介"></el-input>
                    </el-form-item>
                </el-form>
            </div>
        </div>



		<div class="container" style="position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -150%);
            background: green;margin-bottom: 2000px;margin-top: 800px" v-loading.fullscreen.lock="!show" element-loading-text="拼命加载中" element-loading-background="rgba(255, 255, 255, 0.5)">
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
                        <li v-for="i in qqbq" ><img @click="inpFACE(i)" :src="'../static/home/img/qqbq/'+i+'.gif'" /></li>
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
                show:false,
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
                labelPosition: 'top',
                formLabelAlign: {
                    name: '',
                    region: '',
                    type: ''
                }
			}
		},
		created() {
		        for(var i=1;i<=74;i++){
                    this.qqbq.push(i);
                }
                this.$emit("SetHeader", true);
                this.$emit("SetScrollTop");
                sessionStorage['title'] = document.title = "留言 - "+(this.init.info.nick || this.init.info.name)+ "的博客"
                this.getPage(1);
		},
		methods: {
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
                this.content = this.content2 = this.content2+"<img src=\"/static/home/img/qqbq/"+i+".gif\">";
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
            if(self.content2.length<10){
              self.$message.error('留言内容不得少于10个字');
              return;
            }
            if(!this.isUser()){
              this.user.show = true;
              return;
            }
            this.$emit("posts",{
              url:'/api/leave/add.html',
              data:{
                content:this.content2,
                qq:this.user.qq,
                pid:this.pid
              },
              success:function(e){
                self.showFACE = false;
                if(e.status==200){
                    if(e.data.code==1){
                      self.content = "";
                      self.content2 = "";
                      self.pid = 0;
                      self.$message.success("留言成功");
                      sessionStorage['leave_1'] = "";
                      self.getPage(1);
                    }else{
                      self.$message.error(e.data.msg);
                    }
                }else{
                  self.$message.error('服务器异常 状态码' + e.status);
                }
              },error:function(e){
                self.$message.error('服务器异常');
              }});

          },

            /**
             * @desc 验证填写的用户信息
             * @returns {boolean}
             */
              isUser:function () {
                if(this.user.name.length>0 && this.user.qq.length>0 &&  this.user.email.length>0 && this.user.url.length>0){
                  return true;
                }else{
                  return false;
                }
              },


            /**
             * @desc 提交用户信息并且添加对应的文章
             */
              userPost:function(){
                if(!this.isUser()){
                  this.$message.error('信息未填写完整');
                  return;
                }
                this.$emit("posts",{
                  url:'/api/user/add.html',
                  data:{
                    qq:this.user.qq,
                    name:this.user.name,
                    email:this.user.email,
                    url:this.user.url,
                  }});

                this.user.show = false;
                this.postLeave();

              },

              getQqInfo:function(e){
                var self = this;
                this.$emit("gets",{
                  url:'/api/user/qqinfo.html?qq='+e,
                  success:function (e) {
                    self.user.name = e.data.name;
                    self.user.email = e.data.email;
                    self.user.url = e.data.url;
                  }
                });
              },

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
</style>
