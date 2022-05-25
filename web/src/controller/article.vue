<template>
	<el-container >
		<div class="container" v-loading.fullscreen.lock="!show" element-loading-text="拼命加载中" element-loading-background="rgba(255, 255, 255, 0.5)" >
			<el-row :gutter="20">
				<!--文章盒子-->
				<el-col :span="18" class="article-list"  >
					<!--文章列表-->
					<el-row v-for="vo,i in list" class="li" :key="i">
						<el-col :span="6">
							<img :src="vo.article_img" />
						</el-col>
						<el-col :span="18">
							<router-link :to="'/content/'+vo.id"><h1 v-text="vo.article_title"></h1></router-link>
							<p v-text="vo.desc"></p>
							<div class="article-info"><router-link :to="'/content/'+vo.id" v-text="vo.article_desc"></router-link> <span v-text="vo.time"></span>

							<span class="r">
								<i class="iconfont" v-html="'&#xe68a;'+vo.look_num"></i>
								<i class="iconfont" v-html="'&#xe681;'+vo.click_num"></i>
							</span></div>
						</el-col>
					</el-row>

					<div class="page" v-show="list.length>0">
						<el-pagination background :page-size="page.size" @current-change="getPage" layout="prev, pager, next"  :total="page.count"> </el-pagination>
					</div>

					<el-alert v-show="list.length==0" title="你还没有发布你的博客哦，快去登录后发布吧～" type="info" description=" " :closable="false" show-icon></el-alert>

				</el-col>
			</el-row>
		</div>
	</el-container>
</template>

<script>
	export default {
		props: ['init'],
		data() {
			return {
				show:false,
				t:0,
				list:[],
				hot:[],
				page:{
					count:0,
					size:10
				}
			}
		},
		watch:{
			'$route':function(to,from){
				//切换选中状态
				this.t = to.params.t;
				this.getCategory();
			}
		},
		created: function(){

			this.t = this.$route.params.t ? this.$route.params.t : 0;
			this.$emit("SetHeader", true);
			this.$emit("SetScrollTop");
			sessionStorage['title'] = document.title = "博文 - "+(this.init.info.nick || this.init.info.name)+ "的博客"
			this.getCategory();
		},
		methods: {
			getCategory:function(){
				var id = this.t;
				for(var i=0;i< this.init.category.length;i++){
					if(this.init.category[i].id!=id){
						this.init.category[i].an = false;
					}else{
						this.init.category[i].an = true;
					}
					try{
						for(var j=0;j<this.init.category[i].list.length;j++){
							if(this.init.category[i].list[j].id!=id){
								this.init.category[i].list[j].an = false;
							}else{
								this.init.category[i].list[j].an = true;
							}
						}
					}catch(err){

					}
				}
				this.getPage(1);
			},
			getPage(p) { //切换页面
				var self = this;
				self.show = false;
				var name = 'article_'+this.t+'_'+p;
                this.$emit("gets",{url:'/api/article?t='+this.t+'&p='+p+"&uid="+sessionStorage.uid,success:function(e){

                    if(sessionStorage.login==='true'){
                        if(e.status==200){
                            self.show = true;
                            self.list = e.data.data;
                            self.hot = e.data.hot;
                            self.page.count = e.data.count;
                        }
                    }else{
                        self.show = true;
                        self.$message.error({
                            message:'请先登录～',
                            center:true
                        });
                    }

                    },error:function(e){
                        self.$message.error('服务器异常～');
                    }});
			}
		}
	}
</script>
