import Vue from 'vue'
import {Storage} from "../lib/storage";

const base = {
    state: {
        config: {},
        lazy: {},
        hashLazyValue: null,
        init: false,
        biz: {},
        ui: {
            rootFontSize: '20px',
            darkMode: null,
            theme: 'auto',
        },
        page: {
            title: '',
        }
    },

    mutations: {
        SET_CONFIG: (state, data) => {
            state.config = data
            state.init = true
        },
        SET_BIZ: (state, data) => {
            state.biz = data
        },
        SET_LAZY: (state, data) => {
            Vue.set(state.lazy, data[0], data[1])
        },
        SET_LAZY_VALUE_HASH: (state, hashLazyValue) => {
            if (state.hashLazyValue !== null) {
                for (let k in hashLazyValue) {
                    if (hashLazyValue[k] !== state.hashLazyValue[k]) {
                        Vue.prototype.$lazystore.update(k)
                    }
                }
            }
            state.hashLazyValue = hashLazyValue
        },
        SET_PAGE_TITLE: (state, value) => {
            state.page.title = value
        },
        SET_ROOT_FONT_SIZE: (state, value) => {
            state.ui.rootFontSize = value
        },
        SET_DARK_MODE: (state, value) => {
            state.ui.darkMode = value
            if (null === state.ui.darkMode) {
                state.ui.darkMode = Storage.get('darkMode', false)
            }
            Storage.set('darkMode', state.ui.darkMode)
            let theme = (state.ui.darkMode ? 'dark' : 'light')
            state.ui.theme = theme
            // #ifdef H5
            document.querySelector('html').setAttribute('data-theme', theme)
            // #endif
            // #ifdef APP-PLUS
            plus.nativeUI.setUIStyle(theme)
            // #endif
        }
    }
}

export default base
