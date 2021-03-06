 **第一步：后台配置：** 

 **1. /application/database.php 配置数据库** 

 **2. /public 项目入口** 

 **3. 使用composer安装所有依赖包** 

```composer install```

 **4. nginx 伪静态规则** 

```

location / {
    if (!-e $request_filename){
	rewrite  ^(.*)$  /index.php?s=$1  last;   break;
    }
}
```

 **第二步：前台配置：** 


- 前台基于 Vue.js + element-ui 开发 由 vue-cli webpack 编译
- 前台文件在/web 目录下


核心文件 (之外的可选文件)

- /src
- /static
- /index.html


 **1. 开发前请安装node.js 并运行以下命令安装vue以及依赖** 


```
npm i vue -g
npm i vue-cli -g
vue init webpack
    输入y 回车
    输入项目名称 如 app 回车
    再次回车
    输入作者 如 carver_laravel_blog 回车
    回车 输入y 回车
    输入n 回车
    输入n 回车
    输入n 回车
    选择第三项 No, I will handle that myself

npm i element-ui

安装依赖包资源较大,建议使用淘宝NPM国内镜像 详情请进 http://npm.taobao.org/

npm i
```


 **2. 如何调试** 

先配置api根地址

在 /src/App.vue 文件中 内附注释

`npm run dev`

浏览器打开 http://127.0.0.1:8080 即可看到实时界面

 **3. 如何编译** 

`npm run build`

编译后,文件会存放到 /dist/下

 **编译注意事项** 

如果重新始初化webpack的话 资源文件打包路径会有错
将可选文件夹 /dist /config /build 复盖您的webpack

 **4. 如何布暑** 

建议前后端分为两个站点

前台 www.xxx.com

后台 api.xxx.com

前端打包后,将/dist/ 下的 文件 放到www.xxx.com 站点下

后台将文件放到 api.xxx.com 下即可

 **5. 部署打包前台需要注意事项：** 

 **打包部署上线如果只能访问首页，其他的路由访问404的情况，需要进行以下操作：** 

 **方法一：
修改src/router/index.js文件中的mode属性，设置为hash。（这种调整，会使url出现"#")** 

 **方法二：
调整nginx.conf配置文件（添加对应的伪静态），直接访问静态页** 


```
location / {
  if (!-e $request_filename) {
    rewrite  ^(.*)$ /index.html?s=/$1  last;
    break;
  }
}
```






