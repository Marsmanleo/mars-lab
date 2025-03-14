const Configs = {
    default: {
        version: '1.0.0',
        apiDebug: false,
        // #ifdef H5
        apiBase: '/api/',
        // #endif

    },
    // 开发环境配置
    development: {
        apiDebug: false,
        // #ifndef H5
        apiBase: '$CurrentDomainUrl$/api/',
        // #endif
    },
    // 生产环境配置
    production: {
        // #ifndef H5
        apiBase: '$CurrentDomainUrl$/api/',
        // #endif
    },
}
export const SystemSettingEnv = Object.assign(Configs.default, Configs[process.env.NODE_ENV])

export const SystemSetting = {
    softName: 'creditShop',
    channel: 'default',
    tokenKey: 'api-token',
    routerMode: 'hash',
    h5AutoFrame: true,
    primaryColor: '#587BEB',
    route: {
        home: '/pages/home',
        login: '/brick/module/member/login',
        frame: '/brick/module/system/frame',
        authIgnores: [
            '/pages/home',
            '/pages/home/$module_name_mobile_short$/page1',
            '/pages/home/$module_name_mobile_short$/page2',
        ],
        tabBar: [
            '/pages/home',
            '/pages/home/$module_name_mobile_short$/page1',
            '/pages/home/$module_name_mobile_short$/page2',
        ],
    },
}
