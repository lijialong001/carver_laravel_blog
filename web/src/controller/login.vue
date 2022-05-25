<template>
    <div class="container-index">
        <!--fixed-->
        <div class="background-fixed">
            <div class="blog-container">
                <h1 class="blog-name">{{init.info.nick || init.info.name}}<p style="font-size: 16px; color:#666">欢迎使用Carver博客</p></h1>
                <div v-if="user.login!=='false'">
                    <div class="index-btns">
                        <router-link to="/article" class="index-btns-btn">进入博客</router-link>
                    </div>
                </div>

                <!--登录-->
                <div v-if="user.login==='false'" class="loginbtn">
                    <div class="index-btnss">
                        <!--用户登录-->
                        <el-form ref="form">
                            <el-form-item label="用户名">
                                <el-input  type="text" v-model="user.account" placeholder="请输入您的账号哦" ></el-input>
                            </el-form-item>
                            <el-form-item label="密码">
                                <el-input  v-model="user.pwd" type="password"  placeholder="请输入您的密码" ></el-input>
                            </el-form-item>
                            <el-form-item style="margin: 0">
                                <el-button type="primary" style="width: 70px" @click="userLogin">登录</el-button>
                                <el-button type="primary" style="width: 70px" @click="userRegister">注册</el-button>
                                <el-button type="primary" style="width: 100px" @click="userForget">忘记密码?</el-button>
                            </el-form-item>
                        </el-form>

                    </div>
                </div>

                    <div class="index-btnss registerbtn" style="display: none">
                        <!--用户注册-->
                        <el-form ref="form">
<!--                            <el-form-item label="手机号">-->
<!--                                <br>-->
<!--                                <el-input  type="text" style="width: 257px"  v-model="user.phone" placeholder="请输入您的手机号" ></el-input>-->
<!--                                <el-button type="primary" @click="sendPhone">点击发送</el-button>-->
<!--                            </el-form-item>-->
<!--                            <el-form-item label="验证码">-->
<!--                                <el-input  v-model="user.captcha" type="text"  placeholder="请输入您的手机验证码" ></el-input>-->
<!--                            </el-form-item>-->
                            <el-form-item label="账号">
                                <el-input  v-model="user.account" type="text"  placeholder="请输入您的账号" ></el-input>
                            </el-form-item>
                            <el-form-item label="密码">
                                <el-input  v-model="user.pwd" type="password"  placeholder="请输入您的密码" ></el-input>
                            </el-form-item>
                            <el-form-item label="确认密码">
                                <el-input  v-model="user.repwd" type="password"  placeholder="请输入您的确认密码" ></el-input>
                            </el-form-item>
                            <el-form-item style="margin: 0">
                                <el-button type="primary" style="width: 83px" @click="goLogin">去登录？</el-button>
                                <el-button type="primary" style="width: 94px" @click="doRegister">确认提交</el-button>
                            </el-form-item>
                        </el-form>

                    </div>

            </div>
        </div>

        <div class="container-body">
        </div>
        <!--关于我-->
        <div class="container-about">
            <h1>欢迎使用Carver交流平台</h1>
            <span>欢迎 {{init.info.nick}} ~</span>

        </div>

    </div>
</template>



<script>
export default {
    props: ['init'],
    data() {
        return {
            user:{
                account:"",
                pwd:'',
                repwd:'',
                captcha:'',
                phone:'',
                show:false,
                login:sessionStorage.login?sessionStorage.login:'false'
            },

        }
    },
    created(){
        this.$emit("SetHeader",false);
        this.$emit("SetScrollTop");
        sessionStorage['title'] = document.title = "登录 - "+(this.init.info.nick || this.init.info.name)+ "的博客"

    },
    methods:{
        isUser:function () {
            if(this.user.account.length>0  && this.user.pwd.length>0){
                return true;
            }else{
                return false;
            }
        },
        //登录
        userLogin:function () {
            var _self=this;
            if(!this.isUser()){
                this.$message.error('信息未填写完整～');
                return;
            }

            this.$emit("posts",{
                url:'/api/login/',
                data:{
                    account:this.user.account,
                    pwd:this.user.pwd,
                },
                success:function(e){
                    sessionStorage.login=false;
                    if(e.data.status==200){

                        if(e.data.data.code==1){
                            _self.$message({
                                message: '登录成功～',
                                center: true,
                                type: 'success'
                            });
                            sessionStorage.login=true;
                            sessionStorage.uid=e.data.data.data.uid;
                            sessionStorage.username=e.data.data.data.account;
                            window.location.href='/';
                        }else{
                            _self.$message.error(e.data.data.msg);
                        }
                    }else{
                        _self.$message({
                            message: e.data.data.msg,
                            center: true,
                            type: 'warning'
                        });
                    }
                },error:function (e){
                    console.log(e)
                    sessionStorage.login=false;
                    _self.$message({
                        message: "服务器异常",
                        center: true,
                        type: 'error'
                    });

                }});

        },
        //注册
        userRegister:function (){
            document.querySelector(".container-index .registerbtn").style.display='block';
            document.querySelector(".container-index .loginbtn").style.display='none';
        },
        //忘记密码
        userForget:function (){
            this.$message({
                message: "暂时未开放该功能",
                center: true,
                type: 'warning'
            });
        },
        goLogin:function (){
            document.querySelector(".container-index .registerbtn").style.display='none';
            document.querySelector(".container-index .loginbtn").style.display='block';
        },
        doRegister:function (){
            var _self=this;
            if(!_self.registerCheck()){
                _self.$message.error('信息未填写完整～');
                return;
            }

            var account = /^[a-zA-z]\w{3,15}$/;
            if(!account.test(this.user.account)){
                _self.$message.error('账号必须在4~16位，且不能以数字开头～');
                return;
            }

            if(this.user.pwd !== this.user.repwd){
                _self.$message.error('密码和确认不正确～');
                return;
            }


            this.$emit("posts",{
                url:'/api/register',
                data:{
                    user_account:this.user.account,
                    user_pwd:this.user.pwd,
                },
                success:function(e){

                    if(e.data.status==200){
                        if(e.data.data.code==1){
                            _self.$message({
                                message: '注册成功～',
                                center: true,
                                type: 'success'
                            });
                            window.location.href='/';
                        }else{
                            _self.$message.error(e.data.data.msg);
                        }
                    }else{
                        _self.$message({
                            message: e.data.data.msg,
                            center: true,
                            type: 'warning'
                        });
                    }
                },error:function (e){

                    sessionStorage.login=false;
                    _self.$message({
                        message: "服务器异常",
                        center: true,
                        type: 'error'
                    });

                }});


        },
        registerCheck:function () {
            if(this.user.account.length>0  && this.user.pwd && this.user.repwd){
                return true;
            }else{
                return false;
            }
        },
        sendPhone:function (){
            var myreg = /^[1][3,4,5,7,8,9][0-9]{9}$/;
            if (!myreg.test(this.user.phone)) {
                this.$message.error('请输入正确手机号!');
                return;
            }

        }
    }

}
</script>

