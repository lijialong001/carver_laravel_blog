<template>
    <div class="container-index">
        <!--fixed-->
        <div class="background-fixed">
            <div class="blog-container">
                <h1 class="blog-name">{{init.info.nick || init.info.name}}<p style="font-size: 16px; color:#666">全栈开发工程师</p></h1>

                <div class="index-btnss">
                    <!--用户登录-->
                    <el-form ref="form"  label-width="200px">
                        <el-form-item label="手机号">
                            <el-input  type="number" v-model="user.phone" maxlength="2" placeholder="请输入您的手机号哦" ></el-input>
                        </el-form-item>
                        <el-form-item label="密码">
                            <el-input  v-model="user.pwd" type="password"  placeholder="请输入您的密码" ></el-input>
                        </el-form-item>
                        <el-form-item label="确认密码">
                            <el-input  v-model="user.repwd" type="password" placeholder="请输入您的确认密码"></el-input>
                        </el-form-item>
                        <el-form-item label="电子邮箱">
                            <el-input  v-model="user.email"  type="email" placeholder="请输入邮箱哦" ></el-input>
                        </el-form-item>
                        <el-form-item style="margin: 0">
                            <el-button type="primary" style="width: 100px" @click="userPost">登录</el-button>
                            <el-button type="primary" style="width: 100px" @click="userPost">注册</el-button>
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
                phone:"",
                pwd:"",
                repwd:"",
                email:"",
                show:false,
            },
        }
    },
    created(){
        this.$emit("SetHeader",false);
        this.$emit("SetScrollTop");
        sessionStorage['title'] = document.title = "登录 - "+(this.init.info.nick || this.init.info.name)+ "的博客"

    }
}
</script>

