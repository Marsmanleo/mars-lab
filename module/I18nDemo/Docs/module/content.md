## 模块介绍

「多语言快速支持」是一个提供多语言支持、语言翻译表管理等功能

该模块并非是一个可直接使用的模块，要基于该模块进行开发，具体可参考以下示例来开发一个多语言系统：

- `/module/I18nDemo/Admin/Controller/I18nDemoController.php`
- `/module/I18nDemo/Web/Controller/I18nDemoController.php`
- `/module/I18nDemo/Util/I18nDemoUtil.php`

## 功能特性

### 多语言路径访问

开发完成后，访问路径为如下格式，便于 SEO 优化

```
/zh/i18n_demo_category/1
/en/i18n_demo_category/1
/zh/i18n_demo
/en/i18n_demo
/zh/i18n_demo/1
/en/i18n_demo/1
```

### 多语言配置

内容管理 → 多语言 → 多语言站点设置，可直接在 blade 模板文件中使用 `\I18n::config('siteLogo')` 指令调用当前翻译文件。


### 多语言设置管理

内容管理 → 多语言 → 多语言管理，提供多语言管理，如 zh、en 等。


### 多语言翻译

内容管理 → 多语言 → 多语言翻译，可在表中管理不同的语言翻译。

```php
L('翻译Key')
```

{ADMIN_MENUS}
