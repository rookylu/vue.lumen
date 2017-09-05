# Restful API build with lumen

## 安装部署

1. clone源代码: git clone https://git.oschina.net/niusee/banshi-server.git
2. 安装: 进入克隆的目录， 然后执行composer install。 [composer介绍](http://docs.phpcomposer.com/00-intro.html)
3. 修改storage目录权限: 让服务有权限写入
4. 复制.env文件: 拷贝.env.example到.env， 或者直接mv .env.example .env, 建议保留.env.example, .env不纳入版本库
5. 配置env, 包括数据库连接、JWT、APP_KEY等。
6. 迁移库表: php artisan migrate, 执行database/migrations中的数据表创建， 执行每个文件中的up方法。
7. 初始化数据表数据: php artisan db:seed, 执行database/seeds中数据填充程序。

### 下面还有一些比较常用的命令
1. 刷新数据库: php artisan migrate:refresh, 删除数据库中的表
2. 文档生成命令: apidoc -i App/Http/Controllers/Api/V1 -o public/apidoc/
3. 创建表: php artisan make:migration create_users_table --create=users

## 项目结构
```
+---   app                    # 控制器、模型以及其他应用相关的代码都位于这里
|---   bootstrap              # 应用引导入口, 包含一些基本的初始化信息、类库的注册、应用启动
|---   config                 # 项目配置目录, 包含有授权、文件系统、jwt、mail、services之类的配置
|---   database               # 数据库管理： 工厂、迁移、初始化相关的配置, 比如数据表创建、数据表的数据初始化等
|---   public                 # 网站公共入口， index.php, 文档也放这里
|---   resources              # 资源目录，包括语言、视图之类的目录文件
|---   routes                 # 路由配置目录
|---   storage                # 存储目录，包括日志、文件等。注意sql日志需要手工创建目录logs下面的sql, 注意该目录需要其他角色用户的写权限。755， 777
|---   tests                  # 测试及单元测试目录
|---   .env|.env.example      # 全局配置信息，.env.example是范例配置, 下面会对每个区块简单介绍下。
|---   composer.json          # composer install的依赖关系文件
```

### .env配置介绍
包括应用配置、API配置、数据库连接配置、JWT配置、缓存队列配置、mail配置、sql日志配置等

#### 应用配置
```
APP_ENV=local                                  # 可区分本地环境、生产环境
APP_DEBUG=true                                 # 调试模式开启
APP_KEY=gB37jhKZ8VlxSvUSoCZBVJTbQr9eU2J7       # 应用安全key, lumen取消了php artisan key:generate, 可以通过php artisan jwt:secret产生两次，一个作为APP_KEY, 一个做jwt的key
APP_TIMEZONE=Asia/Shanghai                     # 时区配置
```

#### API配置
这里使用的是[dingo/api](https://github.com/dingo/api)。 配置这块主要是版本号、API名称、API前缀等配置信息
```
API_VERSION=v1
API_NAME=lumen-api-demo
API_PREFIX=api
API_STANDARDS_TREE=vnd
API_SUBTYPE=lumen
API_DEBUG=true
```

#### JWT配置
[jwt-ath](https://github.com/tymondesigns/jwt-auth)在laraval(lumen)中使用JSON Web Tokens提供了一种简单的授权方式。 详细参考文档:https://github.com/tymondesigns/jwt-auth/wiki


```
JWT_SECRET=hPHDIZdCulMYg8VFBszhZPghBRF2iqlm
# token 过期时间为 默认1小时，根据自己需要配置
# JWT_TTL=
# JWT_REFRESH_TTL=
```
配置中设置JWT安全key, 过期时间以及可重刷时间。安全key可以通过php artisan jwt:secret产生，产生后自动写入.env文件。

token过期时间默认1小时，可以根据个人需要调整。

> token过期时间: 即创建的token有效时间长度，默认1天。
> token可刷新时间: 如果token已经过期，且在可刷新时间内，那么就可以用旧token换新token。


客户端实现逻辑:
客户端在请求了authorizations之后，会得到token, ttl, refresh ttl, 可以存储在客户端， 然后通过下面的步骤进行状态维持:
1. 判断token是否过期
2. 如果过期，如果在可刷新时间范围内，那么客户端可主动尝试刷新，刷新成功，重置token, ttl, reresh ttl。
3. 如果ttl过期，且refresh ttl也超过时间限制了，那么客户端需要用户重新登录。

客户端在请求需要授权检查的接口时， 需要在headers提供Authorization头，值等于'Bearer ' + token(之前获取到的)

服务端实现逻辑:
服务端使用了jwt中间件，需要授权的接口，可在构造函数中进行指明。即哪些接口需要授权检查:
```
	public function __construct() {
		$this->middleware('api.auth');
		$this->middleware('api.auth', ['only' => 'index']);
	}
```
上面指明只有index需要授权检查。

除了在构造器中实现，另外可以使用路由的group来实现:
```
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'cors',
        //'api.throttle'
    ],
    // each route have a limit of 100 of 1 minutes
    //'limit' => 100, 'expires' => 1
], function ($api) {
    // 下面这些API请求不要授权检查
    $api->post('someaction', [...]);
    $api->delete('someaction', [...]);
    $api->get('someaction', [...]);
    ...


    $api->group(['middleware' => 'api.auth'], function ($api) {
        // 这里边的API请求需要授权检查
        $api->get('needauthaction', [...]);
        $api->post('needauthaction', [...]);
        $api->patch('needauthaction', [...]);
        $api->delete('needauthaction', [...]);
        ...
    })
})
```

### 数据库连接配置
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lumen_api
DB_USERNAME=root
DB_PASSWORD=123456
```

### 缓存、队列配置
```
#CACHE_DRIVER=memcached
#QUEUE_DRIVER=sync
CACHE_DRIVER=file
#CACHE_DRIVER=redis
QUEUE_DRIVER=database
```

### 邮件配置
```
MAIL_DRIVER=
MAILGUN_DOMAIL=
MAILGUN_SECRET=
```

### SQL日志配置
```
SQL_LOG_QUERIES=true
SQL_LOG_SLOW_QUERIES=true
SQL_SLOW_QUERIES_MIN_EXEC_TIME=100
SQL_LOG_OVERRIDE=false
SQL_LOG_DIRECTORY=logs/sql
SQL_CONVERT_TIME_TO_SECONDS=false
```


## 重要概念及参考链接
- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- dingo api 中文文档 [dingo-api-wiki-zh](https://github.com/liyu001989/dingo-api-wiki-zh)
- jwt(json-web-token) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc 生成在线文档 [apidocjs](http://apidocjs.com/)
- rest api 参考规范 [jsonapi.org](http://jsonapi.org/format/)
- api 调试工具 [postman](https://www.getpostman.com/)
- 有用的文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- php lint [phplint](https://github.com/overtrue/phplint)
- Laravel 理念 [From Apprentice To Artisan](https://my.oschina.net/zgldh/blog/389246)
- 我对 REST 的理解 [http://blog.lyyw.info/2017/02/09/2017-02-09%20%E5%AF%B9rest%E7%9A%84%E7%90%86%E8%A7%A3/](http://blog.lyyw.info/2017/02/09/2017-02-09%20%E5%AF%B9rest%E7%9A%84%E7%90%86%E8%A7%A3/)
- 项目api在线文档 [http://lumen.lyyw.info/apidoc](https://lumen.lyyw.info/apidoc)


## REST API设计

github的api真的很有参考价值[github-rest-api](https://developer.github.com/v3/)。

该项目中的api介绍:
```
--- 例子： 用户，帖子，评论
    get    /api/posts                帖子列表
    post   /api/posts                创建帖子
    get    /api/posts/5              id为5的帖子详情
    put    /api/posts/5              替换帖子5的全部信息
    patch  /api/posts/5              修改部分帖子5的信息
    delete /api/posts/5              删除帖子5
    get    /api/posts/5/comments     帖子5的评论列表
    post   /api/posts/5/comments     添加评论
    get    /api/posts/5/comments/8   id为5的帖子的id为8的评论详情
    put    /api/posts/5/comments/8   替换帖子评论内容
    patch  /api/posts/5/comments/8   部分更新帖子评论
    delete /api/posts/5/comments/8   删除某个评论
    get    /api/users/4/posts        id为4的用户的帖子列表
    get    /api/user/posts           当前用户的帖子列表

    // 登录，刷新，登出
    // 或许可以有更好的命名
    post    /api/authorizations  创建一个token
    put     /api/authorizations/current  刷新当前 token
    delete  /api/authorizations/current  删除当前 token
```


### github API导读
#### 版本号
默认情况下，访问`https://api.github.com`返回v3版本的REST API。鼓励使用Accept头明确表明希望请求的版本号:
```
Accept: application/vnd.github.v3+json      # 请求v3版本，格式json
```

#### Schema
1. github的api都是通过https来提供的。
2. 空字段需要用null包含进来，而不是忽略掉。
3. 所有时间戳都以ISO 8601格式: `YYYY-MM-DDTHH:MM:SSZ`


## API Doc规范
```
@api {method} path [title]
  只有使用@api标注的注释块才会在解析之后生成文档，title会被解析为导航菜单(@apiGroup)下的小菜单
  method可以有空格，如{POST GET}
@apiGroup name
  分组名称，被解析为导航栏菜单
@apiName name
  接口名称，在同一个@apiGroup下，名称相同的@api通过@apiVersion区分，否者后面@api会覆盖前面定义的@api
@apiDescription text
  接口描述，支持html语法
@apiVersion verison
  接口版本，major.minor.patch的形式

@apiIgnore [hint]
  apidoc会忽略使用@apiIgnore标注的接口，hint为描述
@apiSampleRequest url
  接口测试地址以供测试，发送请求时，@api method必须为POST/GET等其中一种

@apiDefine name [title] [description]
  定义一个注释块(不包含@api)，配合@apiUse使用可以引入注释块
  在@apiDefine内部不可以使用@apiUse
@apiUse name
  引入一个@apiDefine的注释块

@apiParam [(group)] [{type}] [field=defaultValue] [description]
@apiHeader [(group)] [{type}] [field=defaultValue] [description]
@apiError [(group)] [{type}] field [description]
@apiSuccess [(group)] [{type}] field [description]
  用法基本类似，分别描述请求参数、头部，响应错误和成功
  group表示参数的分组，type表示类型(不能有空格)，入参可以定义默认值(不能有空格)
@apiParamExample [{type}] [title] example
@apiHeaderExample [{type}] [title] example
@apiErrorExample [{type}] [title] example
@apiSuccessExample [{type}] [title] example
  用法完全一致，但是type表示的是example的语言类型
  example书写成什么样就会解析成什么样，所以最好是书写的时候注意格式化，(许多编辑器都有列模式，可以使用列模式快速对代码添加*号)

@apiPermission name
  name必须独一无二，描述@api的访问权限，如admin/anyone
```

## 填充假数据
* database/migrations: 创建表的类
* database/seeds: 填充初始化数据的类
* database/factories: 为模型创建模拟数据的工厂类
* database/seeds/DatabaseSeeder.php: 要填充数据的需要在这里挂钩起来

## 踩坑记录
1. PHP7.0 composer install报错 composer install --ignore-platform-reqs
2. HTTP Code: http://racksburg.com/choosing-an-http-status-code/
