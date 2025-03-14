<template>
    <el-dialog :visible.sync="visible" append-to-body>
        <div slot="title">
            模块回收站
        </div>
        <div>
            <table class="ub-table tw-font-mono" v-loading="loading">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>版本</th>
                    <th>路径</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="loading">
                    <td colspan="4">
                        <div class="ub-empty">
                            <div class="icon">
                                <i class="iconfont icon-refresh tw-inline-block tw-animate-spin"></i>
                            </div>
                            <div class="text">
                                正在加载...
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="records.length===0 && !loading">
                    <td colspan="4">
                        <div class="ub-empty">
                            <div class="icon">
                                <i class="iconfont icon-empty-box"></i>
                            </div>
                            <div class="text">
                                暂无数据
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-for="(module,moduleIndex) in records">
                    <td>{{module.title}}</td>
                    <td>{{module.version}}</td>
                    <td>
                        <code>{{module.filename}}</code>
                    </td>
                    <td>
                        <a href="javascript:;" class="ub-lister-action danger" @click="doDelete(module)">
                            <i class="iconfont icon-trash"></i>
                            清除
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        name: "TrashModuleDialog",
        data() {
            return {
                visible: false,
                loading: false,
                records: []
            }
        },
        methods: {
            doList() {
                this.loading = true
                this.$api.post(this.$url.admin('module_developer/tools/trash_module'), {}, res => {
                    this.loading = false
                    this.records = res.data.records
                }, res => {
                    this.loading = false
                })
            },
            show() {
                this.visible = true
                this.doList()
            },
            doDelete(module) {
                this.$dialog.confirm(`确认清除 ${module.filename}（不可恢复）？`, () => {
                    this.$dialog.loadingOn()
                    this.$api.post(this.$url.admin('module_developer/tools/trash_module/delete'), module, res => {
                        this.$dialog.loadingOff()
                        this.$dialog.tipSuccess('操作成功')
                        this.doList()
                    }, res => {
                        this.$dialog.loadingOff()
                    })
                })
            }
        }
    }
</script>

