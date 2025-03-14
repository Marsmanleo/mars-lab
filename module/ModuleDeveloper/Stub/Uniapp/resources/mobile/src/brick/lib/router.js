import {SystemSetting} from "../../config/setting";
import {StrUtil} from "./util";

export const Router = {
    replace(path, param) {
        // console.log('Router.replace', path, param)
        param = param || {}
        if (SystemSetting.route.tabBar.includes(path)) {
            uni.redirectTo({
                url: Router.pathBuild(path, param),
                animationType: 'fade-in'
            })
        } else {
            // console.log('Router.replace.redirectTo', path)
            uni.redirectTo({
                url: Router.pathBuild(path, param),
                animationType: 'pop-in'
            })
        }
    },
    to(path, param, autoWeb) {
        if (true !== autoWeb) {
            autoWeb = false
        }
        // console.log('Router.to', path, param, autoWeb)
        // detect /xxx#/pages/home?a=1 or /xxx#/brick/module/xxx/ttt?b=1
        if (path.match(/^[a-zA-Z0-9_\/]+#(\/(pages|brick\/module)\/[a-zA-Z0-9_\/]+)/)) {
            path = path.split('#')[1]
        }
        if (path.startsWith('/pages') || path.startsWith('/brick/module')) {
            if (SystemSetting.route.tabBar.includes(path)) {
                uni.navigateTo({
                    url: Router.pathBuild(path, param),
                    animationType: 'fade-in',
                })
            } else {
                uni.navigateTo({
                    url: Router.pathBuild(path, param),
                    animationType: 'pop-in'
                })
            }
        } else {
            if (autoWeb) {
                return uni.navigateTo({
                    url: '/brick/module/system/web?url=' + encodeURIComponent(Router.urlBuild(path, param))
                })
            } else {
                // #ifdef H5
                const urlInfo = Router.parseH5Url(path)
                const urlCurrent = Router.parseH5Url()
                // console.log(urlInfo, urlCurrent)
                if (
                    urlInfo.protocol === urlCurrent.protocol &&
                    urlInfo.host === urlCurrent.host &&
                    urlInfo.pathname === urlCurrent.pathname
                ) {
                    // console.log('hash', urlInfo.hash);
                    if (urlInfo.hash && (urlInfo.hash.startsWith('#/pages') || urlInfo.hash.startsWith(
                        '#/brick/module'))) {
                        let url = urlInfo.hash.substring(1)
                        let path = url.split('?')[0]
                        // console.log('hash url', url, path)
                        if (SystemSetting.route.tabBar.includes(path)) {
                            return uni.switchTab({
                                url
                            })
                        } else {
                            return uni.navigateTo({
                                url
                            })
                        }
                    }
                }
                // #endif
                return uni.navigateTo(Router.urlBuild(path, param))
            }
        }

    },
    /**
     *
     * @param path
     * @param value 当前值
     * @param config 应用配置（比如限制最小最大等）
     * @param cbConfirm
     * @param cbCancel
     * @param param path的参数（比如ID什么的，追加到url中）
     */
    startForCallback(path, value, config, cbConfirm, cbCancel, param) {
        value = value || null
        config = config || {}
        const eventKey = StrUtil.randomString(10)
        uni.setStorageSync(`Router.startForCallback.InitData.${eventKey}`, {
            value,
            config
        })
        uni.$once(`Router.startForCallback.${eventKey}`, res => {
            // console.log(`Router.startForCallback.${eventKey}`, res)
            // res = {type:'cancel|confirm',data:data}
            setTimeout(() => {
                switch (res.type) {
                    case 'cancel':
                        cbCancel && cbCancel(res.data)
                        break
                    case 'confirm':
                        cbConfirm && cbConfirm(res.data)
                        break
                }
            }, 100)
        })
        param = param || {}
        uni.navigateTo({
            url: Router.pathBuild(path, Object.assign({
                _e_: eventKey
            }, param))
        })
    },
    relaunch() {
        uni.reLaunch({
            url: SystemSetting.route.home
        })
    },
    /**
     * 返回上一页
     */
    back(delta) {
        delta = delta || 1
        uni.navigateBack({
            delta
        })
    },
    /**
     * 触发路由时间并返回上一页，将会调用匹配到路由的页面 onRouterEvent(event, param) 方法
     * @param path 路由路径后缀
     * @param event 事件名称
     * @param param 事件参数
     */
    fireAndBack(path, event, param) {
        Router.fire(path, event, param)
        Router.back()
    },
    /**
     * 触发路由事件，将会调用匹配到路由的页面 onRouterEvent(event, param) 方法
     * @param path 路由路径后缀
     * @param event 事件名称
     * @param param 事件参数
     */
    fire(path, event, param) {
        param = param || {}
        const pages = getCurrentPages()
        // 判断path为字母数字下划线
        if (path.match(/^[a-zA-Z0-9_]+$/)) {
            path = '/' + path
        }
        pages.forEach(p => {
            let pPath
            // #ifdef H5 | APP-PLUS | MP-WEIXIN | MP-TOUTIAO
            pPath = p.route
            // #endif
            if (pPath.endsWith(path)) {
                // #ifdef H5
                p['onRouterEvent'] && p['onRouterEvent'](event, param)
                // #endif
                // #ifdef APP-PLUS | MP-WEIXIN | MP-TOUTIAO
                p.$vm['onRouterEvent'] && p.$vm['onRouterEvent'](event, param)
                // #endif
            }
        })
    },
    urlBuild(url, param) {
        param = param || {}
        param = Object.keys(param).map(o => {
            return encodeURIComponent(o) + '=' + encodeURIComponent(param[o])
        }).join('&')
        if (param) {
            url += '?' + param
        }
        return url
    },
    getPage() {
        const pages = getCurrentPages()
        return pages[pages.length - 1]
    },
    pathWithQueries() {
        return Router.pathBuild(Router.path(), Router.getQueries())
    },
    pathBuild(path, param) {
        if (!path.startsWith('/')) {
            path = '/' + path
        }
        param = param || {}
        param = Object.keys(param).map(o => {
            return encodeURIComponent(o) + '=' + encodeURIComponent(param[o])
        }).join('&')
        if (param) {
            path += '?' + param
        }
        return path
    },
    path() {
        const page = Router.getPage()
        let path
        // #ifdef H5
        path = page.$route.path
        // #endif
        // #ifdef MP-WEIXIN
        path = page.route
        // #endif
        // #ifdef APP-PLUS
        path = page.route
        // #endif
        // #ifdef MP-TOUTIAO
        path = page.route
        // #endif
        if (!path.startsWith('/')) {
            path = '/' + path
        }
        if (path === '/') {
            path = SystemSetting.route.home
        }
        return path
    },
    parseH5Url: function (url) {
        // #ifdef H5
        url = url || window.location.href;
        var parser = document.createElement("a");
        parser.href = url;
        var param = {};
        var pairs = (parser.search || '?').substring(1).split('&');
        for (var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            if (pos === -1) {
                continue;
            }
            param[pairs[i].substring(0, pos)] = decodeURIComponent(pairs[i].substring(pos + 1));
        }
        return {
            protocol: parser.protocol,
            host: parser.host,
            hostname: parser.hostname,
            port: parser.port,
            pathname: parser.pathname,
            hash: parser.hash,
            search: parser.search,
            origin: parser.origin,
            param: param,
        };
        // #endif
        // #ifdef MP-WEIXIN || APP-PLUS
        return {
            protocol: null,
            host: null,
            hostname: null,
            port: null,
            pathname: null,
            hash: null,
            search: null,
            origin: null,
            param: {},
        }
        // #endif
    },
    /**
     * 可直接跳转的URL，兼容H5、微信小程序
     * @param path
     * @returns {string|*}
     */
    pathToUrl(url) {
        url = url || Router.pathWithQueries()
        // #ifdef H5
        const wl = window.location
        return `${wl.protocol}//${wl.host}${wl.pathname}#${url}`
        // #endif
        return url
    },
    /**
     * 在部分授权登录时链接会变为 http://www.example.com/?xxx=bbb#/pages/foo/bar
     * 该函数会刷新页面，移除 xxx=bbb 部分请求
     */
    refreshCleanUrl() {
        const url = Router.pathBuild(Router.path(), Object.assign(Router.getQueries(), {
            '_t': Math.random()
        }))
        // #ifdef H5
        // console.log('refreshCleanUrl', Router.pathToUrl(url))
        window.location.href = Router.pathToUrl(Router.pathWithQueries())
        // #endif
    },

    getQueries() {
        const page = Router.getPage()
        // console.log('page', page)
        // #ifdef H5
        return page.$vm.$mp.query
        // #endif
        // #ifdef APP-PLUS
        return page.options
        // #endif
        // #ifdef MP-WEIXIN
        const query = page.$vm.$mp.query
        let newQuery = {}
        for (let k in query) {
            newQuery[decodeURIComponent(k)] = decodeURIComponent(query[k])
        }
        // console.log('getQueries', query, newQuery)
        return newQuery
        // #endif
    },
    getQuery(name, defaultValue) {
        const queries = Router.getQueries()
        // console.log('queries', queries)
        if (name in queries) {
            return queries[name]
        }
        return defaultValue || null
    },
    getQueryStringSeperatedIntegers(name, defaultValue, seperator) {
        seperator = seperator || ','
        defaultValue = defaultValue || []
        const value = Router.getQuery(name, defaultValue)
        if (value) {
            return value.split(seperator).map(o => {
                return parseInt(o)
            })
        }
        return defaultValue
    },
    getQueryInteger(name) {
        let v = parseInt(Router.getQuery(name, 0))
        if (isNaN(v)) {
            v = 0
        }
        return v
    },
    getQueryJson(name) {
        const query = Router.getQuery(name, '{}')
        try {
            return JSON.parse(query)
        } catch (e) {
            try {
                return JSON.parse(decodeURIComponent(query))
            } catch (e) {
            }
            return {}
        }
    },
    mode() {
        return SystemSetting.routerMode
    },
    MODE_HISTORY: 'history',
    MODE_HASH: 'hash',
}
