import {Storage} from "../lib/storage";
import {SystemSettingEnv} from "../../config/setting";
import {EventBus} from "../lib/event-bus";

const api = {

    state: {
        debug: false,
        baseUrl: SystemSettingEnv.apiBase,
        tokenKey: 'api-token',
        token: Storage.get('api-token'),
        codeProcessors: [
            {
                code: 1002,
                callback: (res) => {
                    EventBus.$emitDelay('modstart:captcha.error', {
                        res: res
                    })
                }
            }
            // {
            //     code: 1000,
            //     callback: (res) => {
            //         console.log('not logined')
            //         return true
            //     }
            // }
        ]
    },

    mutations: {
        SET_API_BASE_URL: (state, baseUrl) => {
            state.baseUrl = baseUrl
        },
        SET_API_TOKEN_KEY: (state, tokenKey) => {
            state.tokenKey = tokenKey
            state.token = Storage.get(state.tokenKey)
        },
        SET_API_TOKEN: (state, token) => {
            state.token = token
            Storage.set(state.tokenKey, token)
        },
        ADD_API_CODE_PROCESSOR: (state, processor) => {
            state.codeProcessors.push(processor)
        }
    }
}

export default api
