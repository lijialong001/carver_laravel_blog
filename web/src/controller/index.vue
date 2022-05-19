<template>
	<div class="container-index">
		<!--fixed-->
		<div class="background-fixed">
			<div class="blog-container">
				<h1 class="blog-name">{{typeof init.info.nick!=="undefined"?'': init.info.nick|| init.info.name}}<p style="font-size: 16px; color:#666">懂得放下～</p></h1>
				<p class="blog-description" v-html="init.info.desc"> </p>
				<div class="index-btns">
<!--					<a target="_blank" :href="init.info.github" class="index-btns-btn">-->
<!--						<i class="iconfont">&#xee   e2;</i> GitHub-->
<!--					</a>-->
					<router-link to="/article" class="index-btns-btn">进入博客</router-link>
				</div>
			</div>
		</div>

		<!--使用技术-->
		<div class="container-body">
			<h1>Laravel 8 && Vue 2</h1>
			<p class="description">博客尝试使用以下技术框架构建</p>
			<div class="technology">
				<div class="core">
					<b class="fed">FED</b>
					<b class="api">API</b>
				</div>
				<div class="list">
					<div class="l">
						<em></em>
						<ul>
							<li>Vue 2</li>
							<li>Element 2</li>
							<li>Layui 2</li>
							<li>...</li>
						</ul>
					</div>
					<div class="r">
						<em></em>
						<ul>
							<li>Laravel 8</li>
							<li>Lnmp</li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!--关于我-->
		<div class="container-about">
			<h1>欢迎使用Carver交流平台</h1>
			<span>欢迎 {{init.info.nick?init.info.nick:''}} ~</span>

		</div>
	</div>
</template>

<script>
	export default {
		props: ['init'],
		data() {
			return {
			}
		},
		created(){
			this.$emit("SetHeader",false);
			this.$emit("SetScrollTop");
			sessionStorage['title'] = document.title = "首页 - "+(this.init.info.nick || this.init.info.name)+ "的博客"

		}
	}
</script>
