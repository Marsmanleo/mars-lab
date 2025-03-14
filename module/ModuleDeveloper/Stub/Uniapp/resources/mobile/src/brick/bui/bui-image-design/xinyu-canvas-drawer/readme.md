# xinyu-canvas-drawer-uniapp
uniapp中使用canvas绘制图片的一款组件，可用于海报、分享图等各种图的绘制，多端通用，内聚了所有图片加载处理的过程。组件由原本的旧版Canvas改为现在的Canvas2D，兼容了微信小程序、APP、网页H5三端，修复了二维码的绘制问题！

# 引入教程：
在页面中引入组件并注册：
```js
import XinyuCanvasDrawer from "@/uni_modules/xinyu-canvas-drawer/index.vue";
export default{
	components: {
		XinyuCanvasDrawer
	}
}
```
在页面中使用：
```html
<xinyu-canvas-drawer ref="poster" :width="750" :height="750"></xinyu-canvas-drawer>
<image :src="src" style="width: 750rpx;height: 750rpx;"></image>
```

# 组件介绍
其中width为待绘制的图片目标宽度，height为目标高度，单位为px。这个尺寸的canvas不会显示在页面上，渲染过程会在页面外部进行。
渲染的图片过程需要通过js部分控制，执行draw方法后的Promise返回的内容为图片的Base64编码，可以通过脚本逻辑控制后续处理~

# 渲染过程
渲染过程采用链式操作方法，此方法只能在mounted之后运行，因为没有mounted时$refs.poster是undefined：
```js
let posterRef = this.$refs.poster;
uni.showLoading({
	title: "渲染海报中"
});
this.src = await posterRef
	.setBackgroundColor("#F4F4F4") //指定渲染图片的背景色
	.draw();
uni.hideLoading();
```
链式操作内置了背景图设置、矩形的绘制、本地/云端图片的绘制、二维码绘制与文本绘制五种绘制功能。最终draw方法返回的是Promise对象，也只有调用draw方法await成功后才可以认为渲染完成了。

# 保存图片
保存图片过程我已经集成了，只需要通过ref调用即可~
```js
this.$refs.poster.saveImageToPhotosAlbum(this.src);
```

# 更多
更多的内容就不细说了。组件中有完整的代码注释哦~ 本代码我会持续更新！
