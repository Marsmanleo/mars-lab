<template>
    <el-dialog :visible.sync="visible" append-to-body>
        <div slot="title">
            增加Cms主题模块
        </div>
        <div>
            <div class="ub-form">
                <div class="line">
                    <div class="label">
                        <span>*</span>
                        模块
                    </div>
                    <div class="field">
                        <ModuleSelector v-model="data.module"></ModuleSelector>
                    </div>
                </div>
                <div class="line">
                    <div class="label">
                        <span>*</span>
                        标识
                    </div>
                    <div class="field">
                        <el-input v-model="data.name">
                            <template slot="prepend">{{data.module?data.module:'{模块}'}}Theme</template>
                        </el-input>
                    </div>
                </div>
                <div class="line">
                    <div class="label">
                        <span>*</span>
                        名称
                    </div>
                    <div class="field">
                        <el-input v-model="data.title">
                            <template slot="append">模板</template>
                        </el-input>
                    </div>
                </div>
                <div class="line">
                    <div class="label">描述</div>
                    <div class="field">
                        <el-input v-model="data.description"></el-input>
                    </div>
                </div>
                <div class="line">
                    <div class="label">环境</div>
                    <div class="field">
                        <el-checkbox-group v-model="data.env">
                            <el-checkbox label="laravel5">Laravel5</el-checkbox>
                            <el-checkbox label="laravel9">Laravel9</el-checkbox>
                        </el-checkbox-group>
                    </div>
                </div>
                <div class="line">
                    <div class="field">
                        <el-button type="primary" :loading="loading" @click="doSubmit">确定</el-button>
                    </div>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
import ModuleSelector from "./ModuleSelector";

export default {
    name: "AddThemeModuleDialog",
    components: {ModuleSelector},
    data() {
        return {
            visible: false,
            loading: false,
            data: {
                type: 'theme',
                module: '',
                name: '',
                title: '',
                description: '',
                env:[
                    'laravel5',
                    'laravel9'
                ],
            }
        }
    },
    methods: {
        show() {
            this.data.name = ''
            this.visible = true
        },
        doSubmit() {
            this.loading = true
            this.$api.post(this.$url.admin('module_developer/tools/add_module'), this.data, res => {
                this.loading = false
                this.$dialog.tipSuccess('操作成功')
                this.$emit('update')
                this.visible = false
            }, res => {
                this.loading = false
            })
        }
    }
}
</script>

