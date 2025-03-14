<template>
    <div>
        <DeveloperTools @update="doLoad()" :member-user="memberUser" :token="storeApiToken"></DeveloperTools>
        <div v-if="!memberUser.id" class="ub-alert warning">
            <i class="iconfont icon-warning"></i>
            您还没有登录，请登录后再对您发布的模块进行打包操作
            <a href="javascript:;" @click="doMemberLoginShow()">立即登录</a>
        </div>
        <div class="ub-panel">
            <div class="head">
                <div class="more">
                    <el-button round style="padding:0.25rem 0.5rem;" :loading="memberUserLoading"
                               @click="doMemberLoginShow()">
                        <span v-if="memberUserLoading">
                            登录中
                        </span>
                        <span v-else-if="memberUser.id>0">
                            <div v-if="memberUser.avatar" class="ub-cover-1-1 tw-rounded-full"
                                 style="width:0.8rem;display:inline-block;vertical-align:middle;"
                                 :style="{backgroundImage:`url(${memberUser.avatar})`}"></div>
                            <i v-else class="iconfont icon-user"></i>
                            {{ memberUser.username }}
                        </span>
                        <span v-else>
                            <i class="iconfont icon-user"></i>
                            未登录
                        </span>
                    </el-button>
                </div>
                <div class="title">
                    <i class="iconfont icon-category"></i> 本地模块
                    <el-checkbox class="tw-ml-4" v-model="search.mine">只显示我发布的模块</el-checkbox>
                    <el-input class="tw-ml-4" placeholder="关键词搜索" v-model="search.keywords" prefix-icon="el-icon-search"
                              style="width:10rem;"></el-input>
                    <el-button @click="doLoad">
                        <i class="iconfont icon-refresh"></i>
                        刷新
                    </el-button>
                </div>
            </div>
            <div v-loading="loading">
                <el-table
                    :data="filterModules"
                    style="width:100%">
                    <el-table-column width="400" label="名称">
                        <template slot-scope="scope">
                            <div class="tw-font-bold">
                                <span class="ub-tag sm info" v-if="scope.row._isLocalDir">本地</span>
                                <span class="tw-font-mono"
                                      v-html="$highlight(scope.row.title,search.keywords)"></span>
                            </div>
                            <div class="ub-text-muted">
                                <i class="iconfont icon-file"></i>
                                <span class="tw-font-mono"
                                      v-html="$highlight(scope.row.name,search.keywords)"></span>
                            </div>
                            <div class="ub-text-muted">
                                <i class="iconfont icon-tag"></i>
                                <span class="tw-font-mono">v{{ scope.row.version }}</span>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column width="100" label="状态">
                        <template slot-scope="scope">
                            <span v-if="scope.row._isInstall&&scope.row._isEnable" class="ub-text-success">
                                <i class="iconfont icon-dot-sm"></i>已启用
                            </span>
                            <span v-if="scope.row._isInstall&&!scope.row._isEnable" class="ub-text-warning">
                                <i class="iconfont icon-dot-sm"></i>已禁用
                            </span>
                            <span v-if="!scope.row._isInstall" class="ub-text-danger">
                                <i class="iconfont icon-dot-sm"></i>未安装
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <a v-if="memberUser.manageModules[scope.row.name]&&memberUser.manageModules[scope.row.name].owner"
                               href="javascript:;"
                               @click="doPublish(scope.row)" class="tw-mr-4">
                                <i class="iconfont icon-fly"></i> 发布 V{{ scope.row.version }}
                            </a>
                            <a v-if="memberUser.manageModules[scope.row.name]&&memberUser.manageModules[scope.row.name].owner"
                               :href="doDownloadZipHref(scope.row)" target="_blank" class="tw-mr-4">
                                <i class="iconfont icon-file"></i> V{{ scope.row.version }} 压缩包
                            </a>
                            <a v-if="(!scope.row._isLocalDir)&&memberUser.manageModules[scope.row.name]&&(memberUser.manageModules[scope.row.name].owner||memberUser.manageModules[scope.row.name].info)"
                               href="javascript:;"
                               @click="doPublishInfo(scope.row)" class="tw-mr-4">
                                <i class="iconfont icon-refresh"></i> 更新资料
                            </a>
                            <a v-if="(scope.row._isLocalDir)&&memberUser.manageModules[scope.row.name]&&(memberUser.manageModules[scope.row.name].owner||memberUser.manageModules[scope.row.name].info)"
                               href="javascript:;"
                               @click="doPublishInfo(scope.row)" class="tw-mr-4">
                                <i class="iconfont icon-refresh"></i> 更新资料
                            </a>
                            <a href="javascript:;"
                               v-if="!scope.row._isLocalDir"
                               @click="doPreviewApiContent(scope.row)" class="tw-mr-4">
                                <i class="iconfont icon-book"></i> 接口文档
                            </a>
                            <a href="javascript:;"
                               v-if="!scope.row._isLocalDir"
                               @click="doPreviewUtilContent(scope.row)" class="tw-mr-4">
                                <i class="iconfont icon-book"></i> 工具类文档
                            </a>
                            <!--                            <br>-->
                            <!--                            <a href="javascript:;"-->
                            <!--                               v-if="!scope.row._isLocalDir"-->
                            <!--                               @click="doGenerateCRUD(scope.row)" class="tw-mr-4">-->
                            <!--                                <i class="iconfont icon-code"></i> 一键CRUD-->
                            <!--                            </a>-->
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <el-dialog :visible.sync="memberUserShow" append-to-body custom-class="pb-member-info-dialog">
            <div slot="title">
                <a href="https://modstart.com" target="_blank">
                    <img class="tw-h-8" :src="$url.cdn('vendor/ModuleDeveloper/image/logo_modstart.png')"/>
                </a>
            </div>
            <div v-if="!memberUser.id">
                <div style="padding:0 1.5rem;">
                    <div class="tw-py-2 tw-text-center tw-text-lg">
                        请登录账号
                    </div>
                    <div class="ub-form vertical">
                        <div class="line">
                            <div class="label">用户名</div>
                            <div class="field">
                                <input type="text" class="form" @keyup="doSubmitCheck"
                                       v-model="memberLoginInfo.username" placeholder="输入用户名"/>
                            </div>
                        </div>
                        <div class="line">
                            <div class="label">密码</div>
                            <div class="field">
                                <input type="password" class="form" v-model="memberLoginInfo.password"
                                       @keyup="doSubmitCheck"
                                       placeholder="输入密码"/>
                            </div>
                        </div>
                        <div class="line">
                            <div class="label">验证码</div>
                            <div class="field">
                                <div class="row no-gutters">
                                    <div class="col-8">
                                        <input type="text" class="form" v-model="memberLoginInfo.captcha"
                                               @keyup="doSubmitCheck"
                                               autocomplete="off"
                                               placeholder="图片验证码"/>
                                    </div>
                                    <div class="col-4">
                                        <img class="captcha" title="刷新验证" :src="memberLoginCaptchaImage"
                                             @click="doMemberLoginCaptchaRefresh()"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="line">
                            <div class="field">
                                <button type="submit" class="btn btn-primary btn-block" @click="doMemberLoginSubmit()">
                                    登录
                                </button>
                            </div>
                        </div>
                        <div class="line">
                            <div class="field">
                                <div class="tw-float-right">
                                    <a href="javascript:;" style="color:#19B84D;" @click="doScanLogin()">
                                        <i class="iconfont icon-wechat"></i>
                                        微信扫一扫
                                    </a>
                                </div>
                                还没有账号？<a href="https://modstart.com" target="_blank">立即注册</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div>
                    <div
                        class="tw-bg-white tw-rounded-sm tw-mb-2 tw-box tw-px-5 tw-py-3 tw-mb-3 tw-flex tw-items-center tw-zoom-in"
                        data-repeat="3">
                        <div class="tw-w-10 tw-h-10 tw-flex-none tw-image-fit tw-rounded-full tw-overflow-hidden">
                            <div class="circle tw-border tw-border-gray-200 tw-border-solid tw-shadow ub-cover-1-1"
                                 :style="{backgroundImage:`url(${memberUser.avatar||'/asset/image/avatar.svg'})`}"></div>
                        </div>
                        <div class="tw-ml-4 tw-mr-auto">
                            <div class="tw-font-medium">{{ memberUser.username || '' }}</div>
                        </div>
                        <div>
                            <a href="javascript:;" @click="doMemberUserLogout()">退出</a>
                        </div>
                    </div>
                    <div class="tw-p-4">
                        <a class="btn btn-round" href="https://modstart.com/developer" target="_blank">
                            <i class="iconfont icon-code"></i>
                            成为模块开发者
                        </a>
                    </div>
                </div>
            </div>
        </el-dialog>
        <el-dialog :visible.sync="commandDialogShow"
                   :show-close="commandDialogFinish"
                   :close-on-press-escape="false"
                   :close-on-click-modal="false"
                   append-to-body>
            <div slot="title">
                <div class="ub-text-bold ub-text-primary">
                    <i class="iconfont icon-code"></i>
                    {{ commandDialogTitle }}
                </div>
            </div>
            <div class="tw-bg-gray-900 tw-font-mono tw-leading-8 tw-p-4 tw-text-white">
                <div v-for="(msg,msgIndex) in commandDialogMsgs" v-html="msg"></div>
                <div v-if="!commandDialogFinish">
                    <i class="iconfont icon-loading tw-inline-block tw-animate-spin"></i>
                    当前操作已运行 {{ commandDialogRunElapse }} s ...
                </div>
                <div v-else>
                    <i class="iconfont icon-check"></i>
                    操作已运行完成
                </div>
            </div>
            <div class="tw-p-4 tw-text-center" v-if="commandDialogFinish">
                <el-button type="danger" @click="commandDialogShow=false">关闭</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import {BeanUtil} from '@ModStartAsset/svue/lib/util'
import {Storage} from '@ModStartAsset/svue/lib/storage'
import DeveloperTools from "../components/DeveloperTools";

export default {
    name: "ModuleDeveloper",
    components: {DeveloperTools},
    data() {
        return {
            loading: true,
            commandDialogMsgs: [],
            commandDialogRunStart: 0,
            commandDialogRunElapse: 0,
            commandDialogShow: false,
            commandDialogFinish: false,
            commandDialogTitle: '',
            memberUserShow: false,
            memberUserLoading: false,
            memberLoginCaptchaImage: null,
            search: {
                mine: false,
                keywords: '',
            },
            memberLoginInfo: {
                username: '',
                password: '',
                captcha: '',
            },
            storeApiToken: Storage.get('developerStoreApiToken', ''),
            memberUser: {
                id: 0,
                username: '',
                avatar: '',
                manageModules: {},
                setting: {
                    moduleHotfixEnable: false,
                },
            },
            modules: [],
        }
    },
    computed: {
        filterModules() {
            const results = this.modules.filter(o => {
                if (this.search.mine) {
                    if (!(o.name in this.memberUser.manageModules)) {
                        return false
                    }
                }
                if (this.search.keywords) {
                    const keywords = this.search.keywords.toLowerCase()
                    if (o.name && o.name.toLowerCase().includes(keywords)) {
                        return true
                    }
                    if (o.title && o.title.toLowerCase().includes(keywords)) {
                        return true
                    }
                    if (o.description && o.description.toLowerCase().includes(keywords)) {
                        return true
                    }
                    return false
                }
                return true
            })
            return results
        }
    },
    mounted() {
        this.doLoad()
        this.doLoadStore()
        setInterval(() => {
            this.commandDialogRunElapse = parseInt(((new Date()).getTime() - this.commandDialogRunStart) / 1000)
        }, 1000)
    },
    methods: {
        doStoreRequest(url, data, cbSuccess, cbError) {
            const cbErrorDefault = (res) => {
                this.$dialog.tipError(res.msg)
            }
            if (!cbError) {
                cbError = cbErrorDefault
            }
            $.ajax({
                url: `${window.__data.apiBase}/api/${url}`,
                dataType: 'jsonp',
                data: Object.assign(data, {
                    api_token: this.storeApiToken,
                    modstartParam: JSON.stringify(window.__data.modstartParam),
                }),
                timeout: 10 * 1000,
                success: (res) => {
                    if (res.code) {
                        if (res.code === 1002) {
                            this.doMemberLoginCaptchaRefresh()
                        }
                        if (true !== cbError(res)) {
                            cbErrorDefault(res)
                        }
                    } else {
                        cbSuccess(res)
                    }
                },
                error: (res) => {
                    if (true !== cbError({code: -1, msg: '请求出现错误'})) {
                        cbErrorDefault({code: -1, msg: '请求出现错误'})
                    }
                },
                jsonp: 'callback',
            });
        },
        doMemberUserLogout() {
            this.$dialog.confirm('确认退出？', () => {
                this.storeApiToken = ''
                Storage.set('developerStoreApiToken', '')
                this.doLoadStoreMember()
                this.memberUserShow = false
                this.doLoadStore()
            })
        },
        doSubmitCheck(e) {
            if (e.keyCode === 13) {
                this.doMemberLoginSubmit()
            }
        },
        doMemberLoginSubmit() {
            if (!this.memberLoginInfo.username || !this.memberLoginInfo.password || !this.memberLoginInfo.captcha) {
                return
            }
            this.$dialog.loadingOn()
            this.doStoreRequest('store/login', this.memberLoginInfo, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('登录成功')
                this.doLoadStoreMember()
                this.memberUserShow = false
                this.memberLoginInfo.username = ''
                this.memberLoginInfo.password = ''
                this.memberLoginInfo.captcha = ''
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        doMemberLoginCaptchaRefresh(cb) {
            this.doStoreRequest('store/login_captcha', {}, res => {
                this.memberLoginCaptchaImage = res.data.image
                cb && cb()
            })
        },
        doMemberLoginShow() {
            if (this.memberUser.id > 0) {
                this.memberUserShow = true
            } else {
                this.$dialog.loadingOn()
                this.doMemberLoginCaptchaRefresh(() => {
                    this.$dialog.loadingOff()
                    this.memberUserShow = true
                })
            }
        },
        doLoadStoreMember() {
            this.memberUserLoading = true
            this.doStoreRequest('store/member', {}, res => {
                this.memberUserLoading = false
                BeanUtil.update(this.memberUser, res.data)
            }, res => {
                this.memberUserLoading = false
            })
        },
        doLoadStore() {
            if (!!this.storeApiToken) {
                this.doLoadStoreMember()
            } else {
                this.doStoreRequest('store/config', {}, res => {
                    this.storeApiToken = res.data.apiToken
                    Storage.set('developerStoreApiToken', res.data.apiToken)
                    this.doLoadStoreMember()
                })
            }
        },
        doLoad() {
            this.loading = true
            this.$api.post(this.$url.admin('module_developer/all'), {}, res => {
                this.loading = false
                this.modules = res.data.modules
            })
        },
        doCommand(command, data, step, title) {
            step = step || null
            title = title || null
            if (null === step) {
                this.commandDialogMsgs = []
                this.commandDialogShow = true
                this.commandDialogFinish = false
            }
            if (title) {
                this.commandDialogTitle = title
                this.commandDialogMsgs.push('<i class="iconfont icon-hr"></i> ' + title)
            }
            this.commandDialogRunStart = (new Date()).getTime()
            this.commandDialogRunElapse = 0
            this.$api.post(this.$url.admin(`module_developer/${command}`), {
                token: this.storeApiToken,
                step: step,
                data: JSON.stringify(data)
            }, res => {
                if (Array.isArray(res.data.msg)) {
                    this.commandDialogMsgs = this.commandDialogMsgs.concat(res.data.msg)
                } else {
                    this.commandDialogMsgs.push(res.data.msg)
                }
                if (res.data.finish) {
                    this.commandDialogFinish = true
                    this.doLoad()
                    return
                } else {
                    setTimeout(() => {
                        this.doCommand(res.data.command, res.data.data, res.data.step)
                    }, 1000)
                }
            }, res => {
                this.commandDialogMsgs.push('<i class="iconfont icon-close ub-text-danger"></i> <span class="ub-text-danger">' + res.msg + '</span>')
                this.commandDialogFinish = true
                return true
            })
        },
        doPublish(module) {
            this.$dialog.confirm(`确定发布 ${module.title} ${module.name} V${module.version}？`, () => {
                this.doCommand('publish', {
                    module: module.name,
                    version: module.version,
                }, null, `发布模块 ${module.title} ${module.name} V${module.version}`)
            })
        },
        doDownloadZipHref(module) {
            return this.$url.admin(`module_developer/download_zip?module=${module.name}&version=${module.version}`)
        },
        doPublishInfo(module) {
            this.$dialog.confirm(`确定更新资料 ${module.title} ${module.name} V${module.version}？`, () => {
                this.doCommand('publish_info', {
                    module: module.name,
                    version: module.version,
                    isLocalDir: module._isLocalDir,
                }, null, `更新模块资料 ${module.title} ${module.name} V${module.version}`)
            })
        },
        doPreviewApiContent(module) {
            this.$dialog.dialog(this.$url.admin('module_developer/api_doc', {module: module.name}), {
                width: '90%', height: '90%'
            })
        },
        doPreviewUtilContent(module) {
            this.$dialog.dialog(this.$url.admin('module_developer/util_doc', {module: module.name}), {
                width: '90%', height: '90%'
            })
        },
        doGenerateCRUD(module) {
            this.$dialog.tipError('正在开发')
        },
        doScanLogin() {
            this.$dialog.loadingOn()
            this.doStoreRequest('store/login_wechatmp_qrcode', {}, res => {
                this.$dialog.loadingOff()
                let isOpen = false
                let dialog = null
                const checkLogin = () => {
                    if (!isOpen) {
                        return
                    }
                    this.doStoreRequest('store/login_wechatmp_info', {}, res => {
                        if (res.data.memberUserId) {
                            this.doLoadStoreMember()
                            this.$dialog.dialogClose(dialog)
                            this.$dialog.tipSuccess('登录成功')
                        } else if (res.data.oauthUserInfo) {
                            this.$dialog.dialogClose(dialog)
                            this.$dialog.alertError('当前微信未绑定账号，请先注册')
                        } else {
                            setTimeout(() => {
                                checkLogin()
                            }, 3000)
                        }
                    })
                }
                checkLogin();
                dialog = this.$dialog.dialogContent(`<img style="width:200px;height:200px;" src="${res.data.qrcode}" />`, {
                    openCallback: () => {
                        isOpen = true
                        setTimeout(() => {
                            checkLogin()
                        }, 3000)
                    },
                    closeCallback: () => {
                        isOpen = false
                    },
                })
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

<style lang="less">
.pb-member-info-dialog {
    max-width: 18rem;
}
</style>


