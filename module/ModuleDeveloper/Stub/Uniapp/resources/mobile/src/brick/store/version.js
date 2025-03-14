const version = {
    state: {

    },

    mutations: {
        // SET_APP_APP_VERSION: (state, appVersion) => {
        //     state.appVersion = appVersion
        //     const current = VersionManager.version()
        //     console.log(`version -> current:${current}, latest:${appVersion.latest}, min:${appVersion.min}`)
        //     // #ifdef H5
        //     // #endif
        //     // #ifdef APP-PLUS
        //     if (appVersion.min && VersionManager.compare(current, appVersion.min, '<')) {
        //         if (!state.appVersionDialog) {
        //             state.appVersionDialog = true
        //             uni.showModal({
        //                 title: '升级提醒',
        //                 content: `版本 v${current} 已经不再提供服务，请升级到最新版本 v${appVersion.latest}`,
        //                 showCancel: false,
        //                 confirmText: '立即升级',
        //                 success: (res) => {
        //                     state.appVersionDialog = false
        //                     if (res.confirm) {
        //                         VersionManager.openDownloadUrl()
        //                     }
        //                 }
        //             })
        //         }
        //     } else if (appVersion.latest && VersionManager.compare(current, appVersion.latest, '<')) {
        //         if (!state.appVersionUpdateIgnore) {
        //             if (!state.appVersionDialog) {
        //                 state.appVersionDialog = true
        //                 uni.showModal({
        //                     title: '升级提醒',
        //                     content: `检测到最新版本 v${appVersion.latest}`,
        //                     confirmText: '立即升级',
        //                     cancelText: '下次再说',
        //                     success: (res) => {
        //                         state.appVersionDialog = false
        //                         if (res.confirm) {
        //                             VersionManager.openDownloadUrl()
        //                         } else if (res.cancel) {
        //                             state.appVersionUpdateIgnore = true
        //                         }
        //                     }
        //                 })
        //             }
        //         }
        //     }
        //     // #endif
        //     // #ifdef MP-WEIXIN
        //     // #endif
        // },
    }
}

export default version
