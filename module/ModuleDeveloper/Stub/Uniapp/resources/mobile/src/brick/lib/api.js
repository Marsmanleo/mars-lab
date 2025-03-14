import {Dialog} from './dialog.js'
import {SystemSetting} from "./../../config/setting";
import {VersionManager} from "./version";

let apiRequest = null, apiStore = null

const defaultOption = {
    // 是否静默，出现错误时不提示
    silent: false,
}

const init = (store) => {
    if (null !== apiRequest) {
        return
    }
    apiRequest = true
    apiStore = store
    // console.log('Api.init')
}

const url = (url, param, withToken) => {
    param = param || {}
    if (withToken) {
        param[apiStore.state.api.tokenKey] = apiStore.state.api.token
    }
    param = Object.keys(param).map(o => {
        return encodeURIComponent(o) + '=' + encodeURIComponent(param[o])
    }).join('&')
    if (param) {
        url += '?' + param
    }
    return apiStore.state.api.baseUrl + url
}

const request = (url, method, data, successCB, failCB, option) => {

    // console.log('Api.request', apiStore)

    option = Object.assign({}, defaultOption, option)

    let header = {}
    let token = apiStore.state.api.token
    if (token) {
        header[apiStore.state.api.tokenKey] = token
    }
    header['is-ajax'] = true

    // #ifdef H5
    // #endif
    // #ifdef APP-PLUS
    var systemInfo = uni.getSystemInfoSync()
    var clientInfo = plus.push.getClientInfo()
    header['api-device-id'] = clientInfo.clientid
    // com.soft.xxx/1.0.0 example-channel (android|ios 1.0.0; Redmi S 2) + Old
    header['user-agent'] = 'com.soft.' + SystemSetting.softName + '/' + VersionManager.version() + ' ' + SystemSetting.channel
        + ' (' + (systemInfo.platform ? systemInfo.platform : 'default')
        + ' ' + (systemInfo.system ? systemInfo.system : '0.0.0') + '; ' + (systemInfo.brand + ' ' + systemInfo.model) + ') '
        + plus.navigator.getUserAgent()
    // #endif
    // #ifdef MP-WEIXIN
    // #endif

    apiStore.state.api.debug && console.log(`>>>>>>>>>> request.${url}.request`, JSON.stringify(data))
    let requestUrl = url
    if (!url.startsWith('/')) {
        requestUrl = apiStore.state.api.baseUrl + url
    }
    uni.request({
        url: requestUrl,
        data: data,
        header: header,
        method: method,
        dataType: 'json',
        responseType: 'text',
        success: (res) => {
            apiStore.state.api.debug && console.log(`>>>>>>>>>> request.${url}.response.success`, JSON.stringify(res))
            if (res.header && (apiStore.state.api.tokenKey in res.header)) {
                apiStore.commit('SET_API_TOKEN', res.header[apiStore.state.api.tokenKey])
            }
            // console.log('res.statusCode',res.statusCode)
            if (200 !== res.statusCode) {
                processResponse({code: -1, msg: '请求错误:' + res.data}, successCB, failCB, option)
            } else {
                processResponse(res.data, successCB, failCB, option)
            }
        },
        fail: (res) => {
            apiStore.state.api.debug && console.log(`>>>>>>>>>> request.${url}.response.error`, JSON.stringify(res))
            processResponse(JSON.stringify(res), successCB, failCB, option)
        }
    })
}

const postUpload = (url, param, successCallback, failCallback, option) => {
    option = Object.assign({}, defaultOption, option)
    const failCB = failCallback || defaultFailCallback
    const successCB = successCallback || defaultSuccessCallback
    let header = {}
    let token = apiStore.state.api.token
    if (token) {
        header[apiStore.state.api.tokenKey] = token
    }
    header['is-ajax'] = true
    apiStore.state.api.debug && console.log(`>>>>>>>>>> request.postUpload.${url}.request`, JSON.stringify(param))
    uni.uploadFile({
        url: apiStore.state.api.baseUrl + url,
        filePath: param.filePath,
        name: param.name || 'file',
        header: header,
        formData: Object.assign({}, param.formData),
        success: (res) => {
            apiStore.state.api.debug && console.log(`>>>>>>>>>> request.postUpload.${url}.response.success`, JSON.stringify(res))
            if (res.header && (apiStore.state.api.tokenKey in res.header)) {
                apiStore.commit('SET_API_TOKEN', res.header[apiStore.state.api.tokenKey])
            }
            if (200 !== res.statusCode) {
                processResponse({code: -1, msg: '请求错误:' + res.data}, successCB, failCB, option)
            } else {
                let data = null
                try {
                    data = JSON.parse(res.data)
                } catch (e) {
                    try {
                        data = eval(res.data)
                    } catch (e) {
                    }
                }
                if (null === data) {
                    data = res.data
                }
                processResponse(data, successCB, failCB, option)
            }
        },
        fail: (res) => {
            apiStore.state.api.debug && console.log(`>>>>>>>>>> request.postUpload.${url}.response.error`, JSON.stringify(res))
            processResponse(JSON.stringify(res), successCB, failCB, option)
        }
    });
}


const processResponse = function (res, successCB, failCB, option) {
    option = Object.assign({}, defaultOption, option)
    if (typeof (res) === 'string' || !('code' in res)) {
        processResponse({code: -1, msg: '请求失败:' + res}, successCB, failCB, option)
        return
    }
    if (res.code) {
        let processed = false
        for (let processor of apiStore.state.api.codeProcessors) {
            if (processor.code === res.code) {
                const result = processor.callback.call(null, res)
                if (result === true) {
                    processed = true
                }
            }
        }
        if (!processed) {
            if (!failCB(res, option)) {
                Dialog.tipError(res.msg)
            }
        }
    } else {
        if (successCB(res, option)) {
            Dialog.tipSuccess(res.msg)
        }
    }
}

const defaultFailCallback = function (res, option) {
    option = Object.assign({}, defaultOption, option)
    if (!option.silent) {
        Dialog.tipError(res.msg)
    }
    return true
}

const defaultSuccessCallback = function (res, option) {
    option = Object.assign({}, defaultOption, option)
    if (res.msg) {
        Dialog.tipError(res.msg)
    }
}

/**
 *
 * @param url string 请求地址
 * @param param object 请求参数
 * @param successCallback function 如果需要系统默认处理   返回 true
 * @param failCallback    function 如果不需要系统默认处理 返回 true
 * @param option object 请求配置
 */
const post = (url, param, successCallback, failCallback, option) => {
    const failCB = failCallback || defaultFailCallback
    const successCB = successCallback || defaultSuccessCallback
    request(url, 'POST', param, successCB, failCB, option)
}
//
// /**
//  *
//  * @param url
//  * @param param
//  * @param successCallback function 如果需要系统默认处理   返回 true
//  * @param failCallback    function 如果不需要系统默认处理 返回 true
//  */
// const get = (url, param, successCallback, failCallback) => {
//     const failCB = failCallback || defaultFailCallback
//     const successCB = successCallback || defaultSuccessCallback
//     request(url, 'GET', param, successCB, failCB)
// }
//

const Api = {
    init,
    post,
    postUpload,
    url
}

export {
    Api
}
