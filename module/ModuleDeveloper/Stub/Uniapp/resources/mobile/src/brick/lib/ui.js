import {Router} from "./router";
import {Dialog} from "./dialog";
import store from "./../store";
import {SystemSetting} from "../../config/setting";

/**
 * @deprecated
 */
export const HandleInputObject = function (e) {
    // console.log('HandleInputObject.e', e)
    // console.log('HandleInputObject.this', this)
    let pcs = e.target.dataset.name.split('.')
    pcs = pcs.map(o => {
        if (/^\d+$/.test(o)) {
            return parseInt(o)
        }
        return o
    })
    // console.log('HandleInputObject.pcs', pcs)
    switch (pcs.length) {
        case 1:
            for (let k in e.detail.value) {
                this.$set(this[pcs[0]], k, e.detail.value[k])
            }
            break;
        case 2:
            for (let k in e.detail.value) {
                this.$set(this[pcs[0]][pcs[1]], k, e.detail.value[k])
            }
            break;
    }
}

/**
 * @deprecated
 */
export const HandleInput = function (e) {
    // console.log('HandleInput.e', e)
    // console.log('HandleInput.this', this)
    let pcs = e.target.dataset.name.split('.')
    pcs = pcs.map(o => {
        if (/^\d+$/.test(o)) {
            return parseInt(o)
        }
        return o
    })
    // console.log('HandleInput.pcs', pcs)
    switch (pcs.length) {
        case 1:
            this[pcs[0]] = e.detail.value
            break;
        case 2:
            this.$set(this[pcs[0]], pcs[1], e.detail.value)
            break;
        case 3:
            this.$set(this[pcs[0]][pcs[1]], pcs[2], e.detail.value)
            break;
        case 4:
            this.$set(this[pcs[0]][pcs[1]][pcs[2]], pcs[3], e.detail.value)
            break;
    }
}

export const RouterTo = function (url, param) {
    Router.to(url, param)
}

/**
 * @param url
 * @param param
 * @constructor
 * @deprecated delete at 2024-06-27
 * use StoreUserMixin.requireUserLoginedRouteTo
 */
export const UserRouterTo = function (url, param) {
    url = url || Router.pathWithQueries()
    param = param || {}
    if (store.state.user.user.id) {
        Router.to(url, param)
    } else {
        if (this.$refs
            && this.$refs.memberLoginDialog
            && this.$refs.memberLoginDialog.support) {
            this.$refs.memberLoginDialog.show(this, () => {
                Router.to(url, param)
            })
        } else {
            Dialog.confirm('需要登录后才能操作', () => {
                Router.to(SystemSetting.route.login, {
                    redirect: Router.pathBuild(url, param)
                })
            })
        }
    }
}

export const RouterWebUrl = function (url) {
    if (url.startsWith('/pages')) {
        return url
    }
    return '/brick/module/system/web?url=' + encodeURI(url)
}

export const UserTipNotice = function (msg) {
    if (store.state.user.user.id) {
        Dialog.tipError(msg)
    } else {
        Router.to('/brick/module/member/login', {
            redirect: '/pages/home'
        })
    }
}

const UAParser = require('ua-parser-js');
export const AgentUtil = {
    isMobile(ua) {
        try {
            ua = ua || window.navigator.userAgent
        } catch (e) {
            return false
        }
        const parser = new UAParser()
        const device = parser.setUA(ua).getDevice()
        return device.type === 'mobile'
    },
    isWechat(ua) {
        try {
            ua = ua || window.navigator.userAgent
        } catch (e) {
            return false
        }
        ua = ua.toLowerCase()
        return !!ua.match(/micromessenger/i)
    },
    isFrame() {
        return window.parent !== window
    }
}

export const UiMixin = {
    data() {
        return {
            lockBodyOverflowOldValue: null,
        }
    },
    computed: {
        headHeightTotal() {
            return this.SystemConfig.HeadHeightTotal + 'px'
        },
        footHeightTotal() {
            return this.SystemConfig.FootHeightTotal + 'px'
        },
        headerStatusHeight() {
            return this.SystemConfig.StatusHeight + 'px'
        },
        headerStatusHeightRaw() {
            return this.SystemConfig.StatusHeightRaw + 'px'
        },
    },
    methods: {
        lockBodyScroll(enable) {
            // #ifdef H5
            if (null === this.lockBodyOverflowOldValue) {
                this.lockBodyOverflowOldValue = document.body.style.overflow
            }
            if (enable) {
                this.lockBodyOverflowOldValue = document.body.style.overflow
                document.body.style.overflow = 'hidden'
            } else {
                document.body.style.overflow = this.lockBodyOverflowOldValue
            }
            // #endif
        }
    }
}
