
<p align="center">
  <a href="https://lab.themiluo.cn/">
    <img src="https://lab.themiluo.cn/data/image/2024/07/05/8066_msza_9388.png" alt="一刘码农" width="360" />
  </a>
</p>
<p align="center">
  企业网站管理V1.0
</p>

 

##  💡 系统简介

基于 `Laravel` 企业内容建站系统。 

系统完全开源，基于 **Apache 2.0** 开源协议，**免费且不限制商业使用**。


 
## 🎨 系统演示

### 前台演示地址

[https://lab.themiluo.cn/](https://lab.themiluo.cn/)

> 用户密码自行注册使用

### 后台演示地址

[https://lab.themiluo.cn/admin](https://lab.themiluo.cn/admin)

> 账号：`cms` 密码：`123456`  


 

##  🔧 系统安装

### 环境要求

 
- **Laravel 9.0 版本**
    - `PHP 8.1`
    - `MySQL` `>=5.0`
    - `PHP Extension`：`Fileinfo`
    - `Apache/Nginx`

> 我们的测试基于 PHP 的 5.6 / 7.0 / 8.0 / 8.1 版本，系统稳定性最好

 

##  🔨 开发速看


以下以一个简单的新闻增删改查页面为例，快速了解 ModStart 开发的大致流程。

### 数据表迁移文件

```php
class CreateNews extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 200)->nullable()->comment('');
            $table->string('cover', 200)->nullable()->comment('');
            $table->string('summary', 200)->nullable()->comment('');
            $table->text('content')->nullable()->comment('');
        });
    }
    public function down()
    {
        //
    }
}
```

### 控制器代码

```php
class NewsController extends Controller
{
    use HasAdminQuickCRUD;
    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('news')
            ->field(function ($builder) {
                $builder->id('id','ID');
                $builder->text('title', '名称');
                $builder->image('cover', '封面');
                $builder->textarea('summary', '摘要');
                $builder->richHtml('content', '内容');
                $builder->display('created_at', '创建时间');
                $builder->display('updated_at', '更新时间');
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', 'ID');
                $filter->like('title', '标题');
            })
            ->title('新闻管理');
    }
}
```

### 增加路由和导航

在 `routes.php` 增加路由信息

```php
$router->match(['get', 'post'], 'news', 'NewsController@index');
$router->match(['get', 'post'], 'news/add', 'NewsController@add');
$router->match(['get', 'post'], 'news/edit', 'NewsController@edit');
$router->match(['get', 'post'], 'news/delete', 'NewsController@delete');
$router->match(['get', 'post'], 'news/show', 'NewsController@show');
```


在 `ModuleServiceProvider.php` 中注册菜单信息

```php
AdminMenu::register(function () {
    return [
        [
            'title' => '新闻管理',
            'icon' => 'list',
            'sort' => 150,
            'url' => '\App\Admin\Controller\NewsController@index',
        ]
    ];
});
```

这样一个简单的新闻增删改查页面就开发完成了。



 
