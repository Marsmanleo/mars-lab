export const GlobalEvent = {
    fire(name, param) {
        const pages = getCurrentPages()
        // console.log(pages)
        pages.forEach(p => {
            // #ifdef H5
            if (p['$OnGlobalEvent']) {
                p['$OnGlobalEvent'](name, param)
            }
            // #endif
            // #ifdef APP-PLUS
            if (p.$vm['$OnGlobalEvent']) {
                p.$vm['$OnGlobalEvent'](name, param)
            }
            // #endif
            // #ifdef MP-WEIXIN
            if (p.$vm['$OnGlobalEvent']) {
                p.$vm['$OnGlobalEvent'](name, param)
            }
            // #endif

        })
    }
}