<template>
    <div class="container-index">
        <!--fixed-->
        <div class="background-fixed">

            <div class="blog-container">
                <h1 class="blog-name">{{init.info.nick || init.info.name}}<p style="font-size: 16px; color:#666">欢迎使用Carver博客</p></h1>

                <div v-if="login!==false">
                    <div class="index-btns">
                        <router-link to="/article" class="index-btns-btn">进入博客</router-link>
                    </div>
                </div>

                <!--登录-->
                <div v-if="login===false" class="loginbtn">
                    <div class="index-btnss">
                        <!--用户登录-->
                        <el-form ref="form"  label-width="200px">
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
                        <!--用户登录-->
                        <el-form ref="form"  label-width="200px">
                            <el-form-item label="手机号">
                                <el-input  type="text" v-model="user.account" placeholder="请输入您的手机号" ></el-input>
                            </el-form-item>
                            <el-form-item label="验证码">
                                <el-input  v-model="user.captcha" type="text"  placeholder="请输入您的手机验证码" ></el-input>
                            </el-form-item>
                            <el-form-item label="密码">
                                <el-input  v-model="user.pwd" type="password"  placeholder="请输入您的密码" ></el-input>
                            </el-form-item>
                            <el-form-item label="确认密码">
                                <el-input  v-model="user.pwd" type="password"  placeholder="请输入您的确认密码" ></el-input>
                            </el-form-item>
                            <el-form-item style="margin: 0">
                                <el-button type="primary" style="width: 83px" @click="goLogin">去登录？</el-button>
                                <el-button type="primary" style="width: 94px" @click="doRegister">确认提交</el-button>
                            </el-form-item>
                        </el-form>

                    </div>




            </div>
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
                show:false
            },
            login:sessionStorage.login?sessionStorage.login:false
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
                    if(e.status==200){
                        if(e.data.code==1){
                            _self.$message({
                                message: '登录成功～',
                                center: true,
                                type: 'success'
                            });
                            sessionStorage.login=true;
                            sessionStorage.alluserinfo=JSON.stringify(e.data);
                            window.location.href='/';
                        }else{
                            _self.$message.error(e.data.msg);
                        }
                    }else{
                        _self.$message.error('服务器异常 状态码' + e.status);
                    }
                },error:function (e){
                    sessionStorage.login=false;
                    _self.$message.error('服务器异常');
                }});

        },
        //注册
        userRegister:function (){
            document.querySelector(".container-index .registerbtn").style.display='block';
            document.querySelector(".container-index .loginbtn").style.display='none';

        },
        //忘记密码
        userForget:function (){
            this.$message.error("暂时没有该功能哦");
        },
        goLogin:function (){
            document.querySelector(".container-index .registerbtn").style.display='none';
            document.querySelector(".container-index .loginbtn").style.display='block';
        }
    }

}
</script>

