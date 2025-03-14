import {mapState} from 'vuex'
import {Router} from "../../../lib/router";
import {SystemSetting} from "../../../../config/setting";
import {Dialog} from "../../../lib/dialog";

/**
 * 将store中的 user.user 映射到组件的 user 属性
 * 微信小程序直接写 $store.state.user.user 不能自响应
 */
export const StoreUserMixin = {
    computed: mapState({
        userInit: (state) => state.user.init,
        user: (state) => state.user.user
    }),
    methods: {
        waitUserReady(cb) {
            if (!this.userInit) {
                setTimeout(() => {
                    this.waitUserReady(cb);
                }, 100);
                return;
            }
            cb();
        },
        requireUserLogin(cb, quick) {
            quick = quick || false
            if (!this.user.id) {
                if (quick) {
                    Router.to(SystemSetting.route.login, {redirect: Router.pathWithQueries()})
                } else {
                    this.$dialog.confirm('需要登录后才能操作', () => {
                        Router.to(SystemSetting.route.login, {redirect: Router.pathWithQueries()})
                    })
                }
                return
            }
            cb()
        },
        /**
         * @deprecated delete at 2024-06-27
         */
        requireUserLogined(cb, quick) {
            return this.requireUserLogin(cb, quick)
        },
        requireUserLoginRouteTo(url, param, quick) {
            quick = quick || false
            url = url || Router.pathWithQueries()
            param = param || {}
            if (this.user.id) {
                Router.to(url, param)
            } else {
                if (quick) {
                    Router.to(SystemSetting.route.login, {redirect: Router.urlBuild(url, param)})
                } else {
                    if (this.$refs
                        && this.$refs.memberLoginDialog
                        && this.$refs.memberLoginDialog.support) {
                        this.$refs.memberLoginDialog.show(this, () => {
                            Router.to(url, param)
                        })
                    } else {
                        Dialog.confirm('需要登录后才能操作', () => {
                            Router.to(SystemSetting.route.login, {
                                redirect: Router.pathBuild(url, param)
                            })
                        })
                    }
                }
            }
        },
        /**
         * @deprecated delete at 2024-06-27
         */
        requireUserLoginedRouteTo(url, param, quick) {
            return this.requireUserLoginRouteTo(url, param, quick)
        }
    }
}

/**
 * 将store中的base.biz映射到组件的biz属性
 * 微信小程序直接写 $store.state.base.biz 不能自响应
 */
export const StoreBaseBizMixin = {
    computed: mapState({
        biz: (state) => state.base.biz
    })
}

/**
 * 将store中的base.config映射到组件的config属性
 * 微信小程序直接写 $store.state.base.config 不能自响应
 */
export const StoreBaseConfigMixin = {
    computed: mapState({
        config: (state) => state.base.config
    }),
}

/**
 * 将store中的base.ui映射到组件的ui属性
 * 微信小程序直接写 $store.state.base.ui 不能自响应
 */
export const StoreBaseUiMixin = {
    computed: mapState({
        ui: (state) => state.base.ui
    }),
}
