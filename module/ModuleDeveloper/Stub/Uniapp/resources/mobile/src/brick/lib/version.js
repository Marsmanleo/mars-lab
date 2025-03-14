import {Dialog} from "./dialog";
import Vue from 'vue';
import store from './../store'
import {SystemSettingEnv} from "../../config/setting";

export const VersionManager = {
    version() {
        // #ifdef H5
        return store.state.reload.hashPC
        // #endif
        // #ifdef APP-PLUS
        return plus.runtime.version
        // #endif
        // #ifdef MP-WEIXIN
        return SystemSettingEnv.version
        // #endif
    },
    compare(v1, v2, operator) {
        const v1s = v1.split('.')
        const v2s = v2.split('.')
        if (v1s.length !== 3 || v2s.length !== 3) {
            return false
        }
        const v1number = parseInt(v1s[0]) * 1000000 + parseInt(v1s[1]) * 1000 + parseInt(v1s[2])
        const v2number = parseInt(v2s[0]) * 1000000 + parseInt(v2s[1]) * 1000 + parseInt(v2s[2])
        switch (operator) {
            case '>':
                return v1number > v2number
            case '>=':
                return v1number >= v2number
            case '<':
                return v1number < v2number
            case '<=':
                return v1number <= v2number
        }
        return false
    },

    openDownloadUrl() {
        Dialog.loadingOn()
        Vue.prototype.$api.post('app/download_url', {}, res => {
            Dialog.loadingOff()
            Dialog.tipError('即将下载', () => {
                plus.runtime.openURL(res.data.url)
            })
        })
    }
}
