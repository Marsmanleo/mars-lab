const ImageUtil = {
    base64: (url, cb) => {
        // #ifdef MP-WEIXIN
        uni.getFileSystemManager().readFile({
            filePath: url,
            encoding: 'base64',
            success: res => {
                cb(res.data, 'jpeg')
            },
            fail: (e) => {
                cb(null, null)
            }
        })
        // #endif
        // #ifndef MP-WEIXIN
        uni.request({
            url: url,
            method: 'GET',
            responseType: 'arraybuffer',
            success: res => {
                let base64 = wx.arrayBufferToBase64(res.data)
                cb(base64, 'jpeg')
            }, fail: (e) => {
                cb(null, null)
            }
        })
        // #endif
    }

}

export {
    ImageUtil
}
