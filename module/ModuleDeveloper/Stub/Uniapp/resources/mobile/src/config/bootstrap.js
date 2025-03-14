import store from "./../brick/store";
import {EventBus} from "@/brick/lib/event-bus";
import {Api} from "@/brick/lib/api"
import {BrickUni} from "@/brick/BrickUni";

export const Starter = {
    boot(cb) {
        BrickUni.starter(cb)
    }
}

export const Bootstrap = {
    init() {
        Api.init(store)
        // store.commit('ADD_API_CODE_PROCESSOR', {
        //     code: ResponseCodes.LOGIN_REQUIRED,
        //     callback: (res) => {
        //         console.log('LOGIN_REQUIRED', res)
        //         Dialog.confirm('请登录', () => {
        //             store.commit('SET_USER', {id: 0})
        //             store.commit('SET_API_TOKEN', '')
        //             UserRouterTo(Router.pathWithQueries())
        //         }, () => {
        //             store.commit('SET_USER', {id: 0})
        //             store.commit('SET_API_TOKEN', '')
        //             uni.reLaunch({url: SystemSetting.route.home})
        //         })
        //         return true
        //     }
        // })
        const UpdateApp = (cb) => {
            Api.post('$module_name$/config_app', {}, res => {
                store.commit('SET_RELOAD_HASH_PC', res.data.hashPC)
                store.commit('SET_LAZY_VALUE_HASH', res.data.hashLazyValue)
                store.commit('SET_USER', res.data.user)
                store.commit('SET_BIZ', res.data.biz)
                store.commit('UPDATE_$MODULE_NAME$_FOO', res.data.groupShop)
                cb && cb()
            }, null, {
                silent: true
            })
        }
        EventBus.$on('AppMounted', () => {
            Api.post('$module_name$/config', {}, res => {
                store.commit('SET_CONFIG', res.data)
                setInterval(() => {
                    EventBus.$emitDelay('UpdateApp')
                }, 30 * 1000)
                EventBus.$emitDelay('UpdateApp')
            })
        })
        EventBus.$on('UpdateApp', (cb) => {
            UpdateApp(cb)
        })
    },
    created(me) {
    },
    mounted(me) {
        // me.$lazystore.register('QuestionTags', [], (cb) =>
        //     Api.post('question_tag/all', {}, res => {
        //         cb(res.data)
        //     })
        // )
    }
}



