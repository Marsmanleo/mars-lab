let loading = null

export const Dialog = {
    loadingOn: function (msg) {
        uni.showLoading({
            mask: true,
            title: msg || '加载中...'
        })
    },
    loadingOff: function () {
        uni.hideLoading()
    },
    tip: function (msg, cb) {
        var ms = 1000;
        if (msg && msg.length > 10) {
            ms = 1000 * parseInt(msg.length / 5);
        }
        uni.showToast({
            duration: ms,
            icon: 'none',
            title: msg,
            position: 'bottom',
            mask: false,
            complete: () => {
                setTimeout(() => {
                    cb && cb()
                }, ms)
            }
        })
    },
    tipSuccess: function (msg, cb) {
        var ms = 2000;
        if (msg && msg.length > 10) {
            ms = 1000 * parseInt(msg.length / 5);
        }
        uni.showToast({
            duration: ms,
            icon: 'success',
            title: msg,
            mask: false,
            complete: () => {
                setTimeout(() => {
                    cb && cb()
                }, ms)
            }
        })
    },
    tipError: function (msg, cb) {
        var ms = 2000;
        if (msg && msg.length > 10) {
            ms = 1000 * parseInt(msg.length / 5);
        }
        uni.showToast({
            duration: ms,
            icon: 'none',
            title: msg,
            mask: false,
            complete: () => {
                setTimeout(() => {
                    cb && cb()
                }, ms)
            }
        })
    },
    confirm: function (msg, cb, cancelCB) {
        uni.showModal({
            title: '提示',
            content: msg,
            success: (res) => {
                if (res.confirm) {
                    cb && cb()
                } else if (res.cancel) {
                    cancelCB && cancelCB()
                }
            }
        })
    },
    alertSuccess: function (msg, cb) {
        uni.showModal({
            title: '提示',
            content: msg,
            showCancel: false,
            success: (res) => {
                cb && cb()
            }
        })
    },
    alertError: function (msg, cb) {
        uni.showModal({
            title: '提示',
            content: msg,
            showCancel: false,
            success: (res) => {
                cb && cb()
            }
        })
    },
    input: function (title, cb, defaultValue) {
        defaultValue = defaultValue || ''
        uni.showModal({
            title: title,
            content: defaultValue,
            showCancel: false,
            editable: true,
            success: (res) => {
                cb && cb(res.content)
            }
        })
    }
}
