<template>
	<el-header :class="{'el-header2':ShowHeader}">
		<div class="container bodyinfo" >
			<div :class="{logo:true,logo2:ShowHeader}" class="leftTop"></div>

            <el-row :gutter="20" >
                <el-col :span="9" v-if="this.$route.path.match(/info|leave/)===null" class="middleTop">
                    <div class="grid-content bg-purple">
                        <el-tabs @tab-click="handleClick" >

                            <el-tab-pane :label="vo.name" :redirecturl="vo.url" v-for="vo,i in nav" :key="i" @mouseenter="onAn(i)" @mouseleave="onAn(i)" :class="{ active: active(vo.url) }">
                            </el-tab-pane>

                        </el-tabs>
                    </div>
                </el-col>

                <el-col :span="5">

                    <div class="grid-content bg-purple rightTop">
                        <div v-if="this.logins === 'false'">
                            <router-link to="/login"  class="index-btns-btn">请登录</router-link>
                        </div>

                        <el-dropdown trigger="click" class="selfUser">
                          <span class="el-dropdown-link">
                              <span v-if="this.logins === 'true'">
                                  {{this.userName}}
                              </span>
                          </span>
                                <el-dropdown-menu slot="dropdown">

                                    <el-dropdown-item>
                                        <router-link to="/"    class="index-btns-btn">首页</router-link>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
					<div @click="selfInfo">
					   个人中心
					</div>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <router-link to="/publish"    class="index-btns-btn">发布文章</router-link>
                                    </el-dropdown-item>
                                    <el-dropdown-item >
                                        <div @click="outLogin">退出登录</div>
    <!--                                    <router-link to="/"    class="index-btns-btn" @click="outLogin">退出登录</router-link>-->
                                    </el-dropdown-item>

                                </el-dropdown-menu>
                        </el-dropdown>

                    </div>
                </el-col>
            </el-row>

		</div>
	</el-header>
</template>


<script>

export default {
		props: ['nav','ShowHeader','init'],
		data() {
			return {
                activeIndex: '1',
                logins:sessionStorage.login?sessionStorage.login:'false',
                userName:sessionStorage.username? sessionStorage.username:'请登录'
			}
		},
		computed: {

		},
		mounted: function(){
			//实时获取高度
			window.addEventListener('scroll',this.handleScroll)
		},
		methods: {

            handleClick(tab, event) {
                this.$router.push({path: tab.$attrs.redirecturl})

            },



           //个人中心
        selfInfo:function (){
            this.$message({
                message: "暂时未开放该功能",
                center: true,
                type: 'warning'
            });
        },
            
            outLogin:function (e){
                this.$confirm('此操作将注销登录, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    sessionStorage.login=false;
                    this.$message({
                        message: '退出成功～',
                        type: 'success'
                    });
                    this.$router.push('/login')
                    this.$router.go(0);
                    location.reload();


                }).catch(() => {
                    this.$message({
                        type: 'info',
                        center: true,
                        message: '已取消操作～'
                    });
                });
                // this.$router.push({path: "/"})
            },


			active: function(e) {
				if(this.$route.path == e) {
					return true;
				} else {
					return false;
				}
			},
			onAn: function(i) {
				this.nav[i].an = !this.nav[i].an;
			},
			//获取高度并且交给返回顶部与导航切换

			handleScroll:function(){
				var Top = document.body.scrollTop || document.documentElement.scrollTop;
				this.$emit("scrollTop",Top);

				if(this.$route.path=="/" || this.$route.path=="/login"){
					if(Top>640){
						this.$emit("SetHeader",true);
					}else{
						this.$emit("SetHeader",false);
					}
				}else{
					this.$emit("SetHeader",true);
				}
			}
		},
	}
</script>

<style>
	/*header*/
	.el-header {
		position: fixed;
		width: 100%;
		top: 0;
		z-index: 100;
		background-color: initial;
		color: #333;
		text-align: center;
		line-height: 60px;
		transition:all 1s;
		-moz-transition:all 1s;
		-webkit-transition:all 1s;
		-o-transition:all 1s;
	}

	.el-header .logo {
		float: left;
		height: 60px;
		width: 300px;
		text-align: left;
		background-image: url(/static/home/img/logo.png);
		background-repeat: no-repeat;
		transition:all 1s;
		-moz-transition:all 1s;
		-webkit-transition:all 1s;
		-o-transition:all 1s;
	}
	.el-header .logo2 {
		transition:all 1s;
		-moz-transition:all 1s;
		-webkit-transition:all 1s;
		-o-transition:all 1s;
		background-position: 0px -60px;
	}
	.el-header ul {
		float: right;
		width: 800px;
	}

	.el-header ul li {
		position: relative;
	}

	.el-header ul li.active>a,
	.el-header ul li>a:hover {
		border-bottom: var(--color) solid 2px;
	}

	.el-header ul li a {
		color: #F0F9F2;
	}

	.el-header ul li>a {
		display: block;
		float: right;
		height: 40px;
		margin: 10px 5px;
		padding: 0 20px;
		line-height: 40px;
		font-size: 16px;
	}

	.el-header ul li>a i {
		margin-left: 5px;
	}

	.el-header ul li div {
		/*display: none;*/
		position: absolute;
		min-width: 100px;
		top: 50px;
		right: 12px;

	}

	.el-header ul li div a {
		width: 100%;
		height: 30px;
		line-height: 30px;
		display: block;
	}



	.el-header2 {
		transition:all 1s;
		-moz-transition:all 1s;
		-webkit-transition:all 1s;
		-o-transition:all 1s;
		background-color: rgba(255,255,255,0.5);
		box-shadow: 0 0.0625rem 0.3125rem rgba(0,0,0,.09);
	}

	.el-header2 ul li a{
		color: #333;
	}
	.el-header2 ul li div{
		background-color: #FFF;
		box-shadow: 0 0.0625rem 0.3125rem rgba(0,0,0,.09);
	}
	.el-header2 ul li div a:hover {
		background-color: rgba(100, 100, 100, 0.2);
	}

    .el-dropdown-link {
        cursor: pointer;
        font-size: 22px;
    }
    .el-icon-arrow-down {
        font-size: 12px;
    }


    .el-tabs__item{
        width: 200px;
        height: 80px;
        padding: 0;
        text-align: center;
        line-height: 80px;
        font-size: 20px;
        color: gray;
        border-radius: 40px;
    }

    /*.el-tabs__item.is-active{*/
    /*    color: #ffffff;*/
    /*    background: linear-gradient(45deg, #DD2E9B 0%, #F47039 99%);*/
    /*}*/
    .el-tabs__nav-wrap::after {
        position: static !important;
    }

    /*.el-tabs__active-bar{*/
    /*    display: none;*/
    /*}*/



    .bodyinfo{
        width: 100%;
    }

    .el-header .logo2{
        height: 70px;
        width: 400px;
    }

    .el-header .logo{
        height: 70px;
        width: 400px;
    }

    .middleTop{
        height: 70px;
        width: 450px;
    }

    .rightTop{
        height: 70px;
        width: 375px;
        margin-right: 0px;
        position: absolute;
        top: 9px;
        right: 0px;
    }

    .selfUser {
        float: right;
        padding-right: 40px;
    }











</style>
