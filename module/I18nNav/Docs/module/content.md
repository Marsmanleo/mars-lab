## 模块介绍

「通用导航配置(多语言)」提供多位置的导航配置工具

## 功能特性

- 多位置管理
- 窗口打开方式设置
- 文字、链接灵活配置
- 支持二级菜单
- 菜单图标


## 调用方式

### 根据位置直接渲染菜单HTML

```html
<div>
    {!!  \Module\I18nNav\View\I18nNavView::position('head') !!}
</div>
```

### 使用菜单数据自定义直接渲染

```html
@foreach(\Module\I18nNav\Util\NavUtil::listByPositionWithCache('footer') as $nav)
    <a href="{{$nav['link']}}" {{\Module\I18nNav\Type\NavOpenType::getBlankAttributeFromValue($nav)}}>
        {{$nav['name']}}
    </a>
@endforeach
```



{ADMIN_MENUS}
