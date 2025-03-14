import {Dialog} from './../lib/dialog'
import {SystemSetting} from "../../config/setting";

const reload = {

    state: {
        hashPC: null,
        hashUpdateDialog: false,
    },

    mutations: {
        SET_RELOAD_HASH_PC: (state, hashPC) => {
            // #ifdef H5
            if (state.hashPC && state.hashPC !== hashPC) {
                if (!state.hashPCUpdateDialog) {
                    state.hashPCUpdateDialog = true
                    Dialog.confirm('页面代码有更新，现在刷新页面？', () => {
                        uni.reLaunch({url: SystemSetting.route.home})
                    }, () => {
                        state.hashPCUpdateDialog = false
                    })
                }
            } else {
                state.hashPC = hashPC
            }
            // #endif
            // #ifdef APP-PLUS
            state.hashPC = hashPC
            // #endif
            // #ifdef MP-WEIXIN
            state.hashPC = hashPC
            // #endif
        },
    }
}

export default reload
