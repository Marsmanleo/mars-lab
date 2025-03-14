<template name="ImageCropper">
    <view>
        <canvas canvas-id="image-canvas"
                id="image-canvas"
                class="image-canvas"
                :style="{top: cropperTop, height: cropperHeight}"
                disable-scroll="false"></canvas>
        <canvas canvas-id="crop-layout-canvas"
                id="crop-layout-canvas"
                class="crop-layout-canvas"
                :style="{top: cropperTop, height: cropperHeight}"
                disable-scroll="false"
                @touchstart="onTouchStart"
                @touchmove="onTouchMove"
                @touchend="onTouchEnd"></canvas>
        <view class="corp-layout-wrapper" :style="{display: styleDisplay}">
            <view class="corp-layout">
                <view class="btn-wrapper">
                    <view @click="doSelectImage()" hover-class="hover" :style="{width: buttonWidth}">
                        <text>重选</text>
                    </view>
                    <view @click="doClose" hover-class="hover" :style="{width: buttonWidth}">
                        <text>关闭</text>
                    </view>
                    <view @click="doRotate" hover-class="hover" :style="{width: buttonWidth, display: buttonDisplay}">
                        <text>旋转</text>
                    </view>
                    <view @click="doSave" hover-class="hover" :style="{width: buttonWidth}">
                        <text>保存</text>
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
    const tabHeight = 50;

    const toBase64 = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/'
    ];
    export default {
        name: "ImageCropper",
        data() {
            return {
                cropperHeight: '0px',
                styleDisplay: 'none',
                cropperTop: '-10000px',
                cropStyle: {},
                buttonWidth: '24%',
                buttonDisplay: 'flex',
                hasImage: false,

                imagePx: 0,
                imagePy: 0,
                imageWidth: 0,
                imageHeight: 0,
                imageWidthRaw: 0,
                imageHeightRaw: 0,
                cropSaveWidthInPx: 0,
                cropSaveHeightInPx: 0,
            };
        },
        props: {
            cropWidth: {
                type: String,
                default: '350rpx'
            },
            cropHeight: {
                type: String,
                default: '350rpx'
            },
            cropSaveWidth: {
                type: String,
                default: '300rpx'
            },
            cropSaveHeight: {
                type: String,
                default: '300rpx'
            },
            minScale: {
                type: Number,
                default: 0.3
            },
            maxScale: {
                type: Number,
                default: 4
            },
            canScale: {
                type: Boolean,
                default: true,
            },
            canRotate: {
                type: Boolean,
                default: true,
            },
            stretch: {
                type: String,
                default: '',
            },
            lock: {
                type: String,
                default: '',
            },
            quality: {
                type: Number,
                default: 0.9
            },
            hasTabBar: {
                type: Boolean,
                default: false,
            },
        },
        created() {

            this.canvasImage = uni.createCanvasContext('image-canvas', this);
            this.canvasCropLayout = uni.createCanvasContext('crop-layout-canvas', this);

            if (this.hasTabBar) {
                this.moreHeight = 0;
                this.doWindowResize();
            } else {
                uni.showTabBar({
                    complete: (res) => {
                        this.moreHeight = (res.errMsg === 'showTabBar:ok') ? 50 : 0;
                        this.doWindowResize();
                    }
                });
            }
        },
        methods: {
            doWindowResize() {
                let sysInfo = uni.getSystemInfoSync();
                this.platform = sysInfo.platform;
                this.pixelRatio = sysInfo.pixelRatio;
                this.windowWidth = sysInfo.windowWidth;
                // #ifdef H5
                this.drawTop = sysInfo.windowTop;
                this.windowHeight = sysInfo.windowHeight + sysInfo.windowBottom;
                this.cropperHeight = this.windowHeight - tabHeight + 'px';
                // #endif
                // #ifdef APP-PLUS
                if (this.platform === 'android') {
                    this.windowHeight = sysInfo.screenHeight + sysInfo.statusBarHeight;
                    this.cropperHeight = this.windowHeight - tabHeight + 'px';
                } else {
                    this.windowHeight = sysInfo.windowHeight + this.moreHeight;
                    this.cropperHeight = this.windowHeight - tabHeight + 6 + 'px';
                }
                // #endif
                // #ifdef MP
                this.windowHeight = sysInfo.windowHeight + this.moreHeight;
                this.cropperHeight = this.windowHeight - tabHeight - 2 + 'px';
                // #endif
                this.pxRatio = this.windowWidth / 750;
                // console.log('doWindowResize',this.pixelRatio,this.windowWidth,this.windowHeight,this.cropperHeight,this.pxRatio)

                this.cropSaveWidth && (this.cropSaveWidthInPx = this.cropSaveWidth.indexOf('rpx') >= 0 ? parseInt(this.cropSaveWidth) * this.pxRatio : parseInt(this.cropSaveWidth));
                this.cropSaveHeight && (this.cropSaveHeightInPx = this.cropSaveHeight.indexOf('rpx') >= 0 ? parseInt(this.cropSaveHeight) * this.pxRatio : parseInt(this.cropSaveHeight));

                if (this.styleDisplay === 'flex') {
                    this.doDrawInit(true);
                }
            },
            doSelectImage() {
                if (this.fSelecting) return;
                this.fSelecting = true;
                setTimeout(() => {
                    this.fSelecting = false;
                }, 500);

                uni.chooseImage({
                    count: 1,
                    sizeType: ['original', 'compressed'],
                    sourceType: ['album', 'camera'],
                    success: (r) => {
                        uni.showLoading({mask: true});
                        let path = this.imgPath = r.tempFilePaths[0];
                        uni.getImageInfo({
                            src: path,
                            success: r => {
                                this.imageWidthRaw = r.width;
                                this.imageHeightRaw = r.height;
                                this.path = path;
                                if (!this.hasImage) {
                                    let style = this.cropStyle || {};
                                    if (this.cropWidth && this.cropHeight) {
                                        let cropWidth = this.cropWidth.indexOf('rpx') >= 0 ? parseInt(this.cropWidth) * this.pxRatio : parseInt(this.cropWidth),
                                            cropHeight = this.cropHeight.indexOf('rpx') >= 0 ? parseInt(this.cropHeight) * this.pxRatio : parseInt(this.cropHeight);
                                        style.width = cropWidth + 'px';
                                        style.height = cropHeight + 'px';
                                        style.top = (this.windowHeight - cropHeight - tabHeight) / 2 + 'px';
                                        style.left = (this.windowWidth - cropWidth) / 2 + 'px';
                                    } else {
                                        uni.showModal({
                                            title: '裁剪框的宽或高没有设置',
                                            showCancel: false
                                        })
                                        return;
                                    }
                                    this.cropStyle = style;
                                }

                                if (this.hasTabBar) {
                                    this.doDrawInit(true);
                                } else {
                                    uni.hideTabBar({
                                        complete: () => {
                                            this.doDrawInit(true);
                                        }
                                    });
                                }
                            },
                            fail: () => {
                                uni.showToast({
                                    title: "error3",
                                    duration: 2000,
                                })
                            },
                            complete() {
                                uni.hideLoading();
                            }
                        });
                    }
                })
            },
            doSave() {
                if (this.fUploading) return;
                this.fUploading = true;
                setTimeout(() => {
                    this.fUploading = false;
                }, 1000)

                let style = this.cropStyle,
                    x = parseInt(style.left),
                    y = parseInt(style.top),
                    width = parseInt(style.width),
                    height = parseInt(style.height),
                    cropSaveWidth = this.cropSaveWidthInPx || width,
                    cropSaveHeight = this.cropSaveHeightInPx || height;

                // #ifdef H5
                //x *= this.pixelRatio;
                //y *= this.pixelRatio;
                cropSaveWidth = width;
                cropSaveHeight = height;
                // #endif

                // console.log('corp.1', this.canvasImage)
                // console.log('crop.2', style)
                // console.log('crop.3', x, y, width, height, cropSaveWidth, cropSaveHeight)
                // console.log('crop.4', this)

                uni.showLoading({mask: true});
                this.styleDisplay = 'none';
                this.cropperTop = '-10000px';
                this.hasImage = false;
                uni.canvasToTempFilePath({
                    x: x,
                    y: y,
                    width: width,
                    height: height,
                    destWidth: cropSaveWidth,
                    destHeight: cropSaveHeight,
                    canvasId: 'image-canvas',
                    fileType: 'png',
                    quality: this.quality,
                    success: (r) => {
                        // console.log('canvasToTempFilePath',r)
                        const me = this
                        // #ifdef H5
                        me.$emit('save', {data: r.tempFilePath})
                        // #endif
                        // #ifdef MP-WEIXIN
                        const base64 = wx.getFileSystemManager().readFileSync(r.tempFilePath, 'base64')
                        me.$emit('save', {data: `data:image/png;base64,${base64}`})
                        // #endif
                        // #ifdef APP-PLUS
                        plus.io.resolveLocalFileSystemURL(r.tempFilePath, function (entry) {
                            entry.file(function (file) {
                                var fileReader = new plus.io.FileReader();
                                fileReader.readAsDataURL(file);
                                fileReader.onloadend = function (evt) {
                                    me.$emit('save',{data:evt.target.result})
                                }
                            })
                        })
                        console.log(uni.getFileSystemManager())
                        // #endif
                    },
                    fail: (res) => {
                        uni.showToast({
                            title: "error",
                            duration: 2000,
                        })
                    },
                    complete: () => {
                        uni.hideLoading();
                        this.hasTabBar || uni.showTabBar();
                    }
                }, this);
            },
            doDrawInit(ini = false) {
                let imageWidthRaw = this.imageWidthRaw,
                    imageHeightRaw = this.imageHeightRaw,
                    imgRadio = imageWidthRaw / imageHeightRaw,
                    imageWidth = this.windowWidth - 40,
                    imageHeight = this.windowHeight - tabHeight - 80,
                    pixelRatio = this.pixelRatio,
                    cropWidth = parseInt(this.cropStyle.width),
                    cropHeight = parseInt(this.cropStyle.height);

                this.fixWidth = 0;
                this.fixHeight = 0;
                this.lckWidth = 0;
                this.lckHeight = 0;
                switch (this.stretch) {
                    case 'x':
                        this.fixWidth = 1;
                        break;
                    case 'y':
                        this.fixHeight = 1;
                        break;
                    case 'long':
                        if (imgRadio > 1) this.fixWidth = 1; else this.fixHeight = 1;
                        break;
                    case 'short':
                        if (imgRadio > 1) this.fixHeight = 1; else this.fixWidth = 1;
                        break;
                    case 'longSel':
                        if (cropWidth > cropHeight) this.fixWidth = 1; else this.fixHeight = 1;
                        break;
                    case 'shortSel':
                        if (cropWidth > cropHeight) this.fixHeight = 1; else this.fixWidth = 1;
                        break;
                }
                switch (this.lock) {
                    case 'x':
                        this.lckWidth = 1;
                        break;
                    case 'y':
                        this.lckHeight = 1;
                        break;
                    case 'long':
                        if (imgRadio > 1) this.lckWidth = 1; else this.lckHeight = 1;
                        break;
                    case 'short':
                        if (imgRadio > 1) this.lckHeight = 1; else this.lckWidth = 1;
                        break;
                    case 'longSel':
                        if (cropWidth > cropHeight) this.lckWidth = 1; else this.lckHeight = 1;
                        break;
                    case 'shortSel':
                        if (cropWidth > cropHeight) this.lckHeight = 1; else this.lckWidth = 1;
                        break;
                }
                if (this.fixWidth) {
                    imageWidth = cropWidth;
                    imageHeight = imageWidth / imgRadio;
                } else if (this.fixHeight) {
                    imageHeight = cropHeight;
                    imageWidth = imageHeight * imgRadio;
                } else if (imgRadio < 1) {
                    if (imageHeightRaw < imageHeight) {
                        imageWidth = imageWidthRaw;
                        imageHeight = imageHeightRaw;
                    } else {
                        imageHeight = imageHeight;
                        imageWidth = imageHeight * imgRadio;
                    }
                } else {
                    if (imageWidthRaw < imageWidth) {
                        imageWidth = imageWidthRaw;
                        imageHeight = imageHeightRaw;
                    } else {
                        imageWidth = imageWidth;
                        imageHeight = imageWidth / imgRadio;
                    }
                }

                this.scaleSize = 1;
                this.rotateDeg = 0;
                this.imagePx = (this.windowWidth - imageWidth) / 2;
                this.imagePy = (this.windowHeight - imageHeight - tabHeight) / 2;
                this.imageWidth = imageWidth;
                this.imageHeight = imageHeight;

                let style = this.cropStyle,
                    left = parseInt(style.left),
                    top = parseInt(style.top),
                    width = parseInt(style.width),
                    height = parseInt(style.height),
                    canvas = this.canvas,
                    canvasImage = this.canvasImage,
                    canvasCropLayout = this.canvasCropLayout;

                canvasCropLayout.setLineWidth(3);
                canvasCropLayout.setStrokeStyle('grey');
                canvasCropLayout.setGlobalAlpha(0.4);
                canvasCropLayout.setFillStyle('black');
                canvasCropLayout.strokeRect(left, top, width, height);
                canvasCropLayout.fillRect(0, 0, this.windowWidth, top);
                canvasCropLayout.fillRect(0, top, left, height);
                canvasCropLayout.fillRect(0, top + height, this.windowWidth, this.windowHeight - height - top - tabHeight);
                canvasCropLayout.fillRect(left + width, top, this.windowWidth - width - left, height);
                canvasCropLayout.setStrokeStyle('red');
                canvasCropLayout.moveTo(left + 20, top);
                canvasCropLayout.lineTo(left, top);
                canvasCropLayout.lineTo(left, top + 20);
                canvasCropLayout.moveTo(left + width - 20, top);
                canvasCropLayout.lineTo(left + width, top);
                canvasCropLayout.lineTo(left + width, top + 20);
                canvasCropLayout.moveTo(left + 20, top + height);
                canvasCropLayout.lineTo(left, top + height);
                canvasCropLayout.lineTo(left, top + height - 20);
                canvasCropLayout.moveTo(left + width - 20, top + height);
                canvasCropLayout.lineTo(left + width, top + height);
                canvasCropLayout.lineTo(left + width, top + height - 20);
                canvasCropLayout.stroke();

                canvasCropLayout.draw(false, () => {
                    if (ini) {
                        this.styleDisplay = 'flex';
                        // #ifdef H5
                        this.cropperTop = this.drawTop + 'px';
                        // #endif
                        // #ifndef H5
                        this.cropperTop = '0';
                        // #endif
                        canvasImage.setFillStyle('black');
                        this.doDrawImage();
                    }
                });
            },
            doDrawImage() {
                let tm_now = Date.now();
                if (tm_now - this.drawTm < 20) return;
                this.drawTm = tm_now;
                let canvasImage = this.canvasImage;
                // console.log('draw', this.windowWidth, this.windowHeight, this.imageWidth, this.imageHeight, this.imagePx, this.imagePy)
                canvasImage.fillRect(0, 0, this.windowWidth, this.windowHeight - tabHeight);
                canvasImage.translate(this.imagePx + this.imageWidth / 2, this.imagePy + this.imageHeight / 2);
                canvasImage.scale(this.scaleSize, this.scaleSize);
                canvasImage.rotate(this.rotateDeg * Math.PI / 180);
                canvasImage.drawImage(this.imgPath, -this.imageWidth / 2, -this.imageHeight / 2, this.imageWidth, this.imageHeight);
                canvasImage.draw(false);
            },
            doClose() {
                this.styleDisplay = 'none';
                this.cropperTop = '-10000px';
                this.hasImage = false;
                this.hasTabBar || uni.showTabBar();
            },
            doRotate() {
                // #ifdef APP-PLUS
                if (this.platform === 'android') {
                    if (this.fRotateing) return;
                    this.fRotateing = true;
                    setTimeout(() => {
                        this.fRotateing = false;
                    }, 500);
                }
                // #endif

                this.rotateDeg += 90 - this.rotateDeg % 90;
                this.doDrawImage();
            },
            onTouchStart(e) {
                let touches = e.touches,
                    touch0 = touches[0],
                    touch1 = touches[1];

                this.touch0 = touch0;
                this.touch1 = touch1;

                if (touch1) {
                    let x = touch1.x - touch0.x,
                        y = touch1.y - touch0.y;
                    this.fgDistance = Math.sqrt(x * x + y * y);
                }
            },
            onTouchMove(e) {

                let touches = e.touches,
                    touch0 = touches[0],
                    touch1 = touches[1];

                if (touch1) {
                    let x = touch1.x - touch0.x,
                        y = touch1.y - touch0.y,
                        fgDistance = Math.sqrt(x * x + y * y),
                        scaleSize = 0.005 * (fgDistance - this.fgDistance),
                        beScaleSize = this.scaleSize + scaleSize;

                    do {
                        if (!this.canScale) break;
                        if (beScaleSize < this.minScale) break;
                        if (beScaleSize > this.maxScale) break;
                        this.scaleSize = beScaleSize;
                    } while (0);
                    this.fgDistance = fgDistance;

                    if (touch1.x !== touch0.x && this.canRotate) {
                        x = (this.touch1.y - this.touch0.y) / (this.touch1.x - this.touch0.x);
                        y = (touch1.y - touch0.y) / (touch1.x - touch0.x);
                        this.rotateDeg += Math.atan((y - x) / (1 + x * y)) * 180 / Math.PI;
                        this.touch0 = touch0;
                        this.touch1 = touch1;
                    }

                    this.doDrawImage();
                } else if (this.touch0) {
                    let x = touch0.x - this.touch0.x,
                        y = touch0.y - this.touch0.y,
                        beX = this.imagePx + x,
                        beY = this.imagePy + y;
                    if (Math.abs(x) < 100 && !this.lckWidth) this.imagePx = beX;
                    if (Math.abs(y) < 100 && !this.lckHeight) this.imagePy = beY;
                    this.touch0 = touch0;
                    this.doDrawImage();
                }
            },
            onTouchEnd(e) {
                let touches = e.touches,
                    touch0 = touches && touches[0],
                    touch1 = touches && touches[1];
                if (touch0) {
                    this.touch0 = touch0;
                } else {
                    this.touch0 = null;
                    this.touch1 = null;
                }
            },
        }
    }
</script>

<style lang="less">
    .image-canvas {
        display: flex;
        position: fixed !important;
        background: #000000;
        left: 0;
        z-index: 100000;
        width: 100%;
    }

    .my-avatar {
        width: 150rpx;
        height: 150rpx;
        border-radius: 100%;
    }

    .crop-layout-canvas {
        display: flex;
        position: fixed !important;
        left: 0;
        z-index: 100001;
        width: 100%;
    }

    .corp-layout-wrapper {
        height: 50px;
        position: fixed !important;
        box-sizing: border-box;
        border: 1px solid #F1F1F1;
        background: #ffffff;
        width: 100%;
        left: 0;
        bottom: 0;
        z-index: 100009;
        flex-direction: row;
    }

    .corp-layout {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 10rpx 20rpx;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        align-self: center;
    }

    .btn-wrapper {
        display: flex;
        flex-direction: row;
        /* #ifndef H5 */
        flex-grow: 1;
        /* #endif */
        /* #ifdef H5 */
        height: 50px;
        /* #endif */
        justify-content: space-between;
    }

    .btn-wrapper view {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #333;
        border: 1px solid #f1f1f1;
        border-radius: 6%;
    }

    .hover {
        background: #f1f1f1;
        border-radius: 6%;
    }

    .clr-wrapper {
        display: flex;
        flex-direction: row;
        flex-grow: 1;
    }

    .clr-wrapper view {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #333;
        border: 1px solid #f1f1f1;
        border-radius: 6%;
    }

    .image-color-slider {
        flex-grow: 1;
    }
</style>