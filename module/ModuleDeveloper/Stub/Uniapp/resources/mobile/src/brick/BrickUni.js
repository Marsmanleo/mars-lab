import Vue from 'vue'
import {Router} from "./lib/router";
import {EventBus} from "./lib/event-bus";
import {AgentUtil} from "./lib/ui";
import {SystemSetting} from "@/config/setting";
import store from "./store";
import {Dialog} from "./lib/dialog";
import {Api} from "./lib/api";
import {Starter} from "@/config/bootstrap";
import {LazyStore} from "./lib/lazystore";
import {Storage} from "./lib/storage";
import {HtmlUtil} from "./lib/html";

// #ifdef H5
import VueLazyLoad from 'vue-lazyload'

Vue.use(VueLazyLoad)
// #endif

const BrickUni = {
    init() {

        Vue.config.productionTip = false

        Vue.prototype.$delay = (cb, delay) => {
            delay = delay || 100
            setTimeout(cb, delay)
        }

        Vue.prototype.$store = store
        Vue.prototype.$dialog = Dialog
        Vue.prototype.$api = Api
        Vue.prototype.$starter = Starter
        Vue.prototype.$lazystore = new LazyStore(store)
        Vue.prototype.$html = HtmlUtil
        Vue.prototype.$r = Router
        Vue.prototype.$m = {}
        Vue.prototype.$storage = Storage
        Vue.prototype.$hasModule = (m) => {
            if (!store.state.base.config.modules) {
                return false
            }
            return store.state.base.config.modules.includes(m)
        }
        // Vue.prototype.$handleInput = HandleInput
    },
    starter(cb) {
        if (!store.state.base.init) {
            setTimeout(() => this.starter(cb), 100)
            return
        }
        const path = Router.path()
        const pathWithQueries = Router.pathWithQueries()
        // #ifdef H5
        if (SystemSetting.h5AutoFrame && !AgentUtil.isMobile() && !AgentUtil.isFrame()) {
            if (!pathWithQueries.startsWith(SystemSetting.route.frame)) {
                const l = window.location
                const frameUrl = Router.pathBuild(SystemSetting.route.frame, {
                    url: pathWithQueries
                })
                if (Router.mode() === Router.MODE_HISTORY) {
                    window.location.replace(`${l.protocol}//${l.host}${frameUrl}`)
                } else {
                    window.location.replace(`${l.protocol}//${l.host}${l.pathname}#${frameUrl}`)
                }
            }
            return
        }
        // #endif

        store.commit('SET_DARK_MODE', (('uiDefaultDarkMode' in SystemSetting) ? SystemSetting.uiDefaultDarkMode : false))

        // console.log('Starter.boot', `path=${path}`, `pathWithQueries=${pathWithQueries}`)

        if (SystemSetting.route.authIgnores.includes(path)) {
            cb && cb()
            return
        }
        const checkUser = () => {
            // console.log('checkUser', getApiToken(), store.state.user.user.id)
            if (!store.state.api.token || store.state.user.user.id === 0) {
                Router.replace(SystemSetting.route.login, {
                    redirect: pathWithQueries
                })
            } else {
                cb && cb()
            }
        }
        if (!store.state.user.init) {
            EventBus.$emitDelay('UpdateApp', () => checkUser())
            return
        }
        checkUser()
    },
    launch() {
        const getRootFontSize = (SystemConfig) => {
            let size = 20
            if (SystemConfig.Width > 420) {
                size = 24
            } else if (SystemConfig.Width > 380) {
                size = 22
            }
            return size + 'px'
        }
        const calcSystemConfig = () => {
            uni.getSystemInfo({
                success: e => {
                    const SystemConfig = {
                        // 操作系统 ios、android、windows、macos、linux
                        OSName: e.osName,

                        // 状态栏实际高度
                        StatusHeightRaw: e.statusBarHeight,
                        // 状态栏高度，小程序应用，为了将胶囊保留在状态栏中间，需要重新计算虚拟高度
                        StatusHeight: e.statusBarHeight,

                        // 顶部导航高度
                        HeadHeight: 50,
                        // 胶囊所占用的宽度（包括胶囊左右两边的边距）
                        HeadMenuWidth: 0,
                        // 顶部高度总和
                        HeadHeightTotal: 50,

                        // 底部导航高度
                        FootHeight: 50,
                        // 底部状态高度
                        FootStatusHeight: e.windowHeight - e.safeArea.bottom,
                        // 底部高度总和
                        FootHeightTotal: 50,

                        // 屏幕宽度
                        Width: e.windowWidth,
                        // 屏幕高度
                        Height: e.windowHeight,
                    }

                    // #ifdef MP-WEIXIN
                    let button = wx.getMenuButtonBoundingClientRect();
                    let centerY = (button.top + button.bottom) / 2;
                    SystemConfig.StatusHeight = (centerY - SystemConfig.HeadHeight / 2);
                    SystemConfig.HeadMenuWidth = button.width + (SystemConfig.Width - button.right) * 2;
                    // #endif

                    SystemConfig.HeadHeightTotal = SystemConfig.StatusHeight + SystemConfig.HeadHeight;
                    SystemConfig.FootHeightTotal = SystemConfig.FootHeight + SystemConfig.FootStatusHeight;

                    Vue.prototype.SystemConfig = SystemConfig;
                    console.log('SystemConfig', SystemConfig);

                    store.commit('SET_ROOT_FONT_SIZE', getRootFontSize(SystemConfig))
                }
            })
        }
        const autoUpdate = () => {
            // #ifdef MP-WEIXIN
            const updateManager = wx.getUpdateManager()
            updateManager.onCheckForUpdate((res) => {
                if (!res.hasUpdate) {
                    return
                }
                updateManager.onUpdateReady(() => {
                    wx.showModal({
                        title: '更新提示',
                        content: '新版本已经准备好，是否重启应用？',
                        success: (res) => {
                            if (res.confirm) {
                                updateManager.applyUpdate()
                            }
                        }
                    })
                })
                updateManager.onUpdateFailed(() => {
                    wx.showModal({
                        title: '已经有新版本了哟~',
                        content: '新版本已经上线啦~ 请您删除当前小程序，重新搜索打开哟~',
                    })
                })
            })
            // #endif
        };
        calcSystemConfig();
        autoUpdate();
        // #ifdef H5
        // ios 会出现键盘弹出时，页面高度变化，但是 window.innerHeight 不变的情况
        window.addEventListener('resize', () => {
            calcSystemConfig();
        });
        // #endif
    }
}

export {
    BrickUni
}
