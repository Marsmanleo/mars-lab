<template>
    <div>
        <div class="ub-panel">
            <div class="head">
                <div class="title">
                    常用工具
                </div>
            </div>
            <div class="body">
                <el-button @click="$refs.addModuleDialog.show()">
                    <i class="iconfont icon-plus"></i>
                    普通模块
                </el-button>
                <el-button @click="$refs.addCmsThemeModuleDialog.show()">
                    <i class="iconfont icon-plus"></i>
                    Cms主题模块
                </el-button>
                <el-button @click="$refs.addBlogThemeModuleDialog.show()">
                    <i class="iconfont icon-plus"></i>
                    Blog主题模块
                </el-button>
                <el-button @click="$refs.addThemeModuleDialog.show()">
                    <i class="iconfont icon-plus"></i>
                    通用主题模块
                </el-button>
                <el-button @click="$refs.addUniappModuleDialog.show()">
                    <i class="iconfont icon-plus"></i>
                    Uniapp移动端模块
                </el-button>
                <el-button @click="$refs.trashModuleDialog.show()">
                    <i class="iconfont icon-trash"></i>
                    模块回收站
                </el-button>
                <el-button @click="doDialogPage('all_api')">
                    <i class="iconfont icon-file"></i>
                    全站API文档
                </el-button>
                <el-button @click="doDialogPage('all_db_structure')">
                    <i class="iconfont icon-credit"></i>
                    全站数据字典
                </el-button>
                <el-button v-if="memberUser&&memberUser.setting&&memberUser.setting.moduleHotfixEnable"
                           @click="doDialogPage('package_file')">
                    <i class="iconfont icon-download"></i>
                    HotFix打包
                </el-button>
            </div>
        </div>
        <AddModuleDialog ref="addModuleDialog" @update="$emit('update')"></AddModuleDialog>
        <AddCmsThemeModuleDialog ref="addCmsThemeModuleDialog" @update="$emit('update')"/>
        <AddBlogThemeModuleDialog ref="addBlogThemeModuleDialog" @update="$emit('update')"/>
        <AddThemeModuleDialog ref="addThemeModuleDialog" @update="$emit('update')"/>
        <AddUniappModuleDialog ref="addUniappModuleDialog" @update="$emit('update')"/>
        <TrashModuleDialog ref="trashModuleDialog" @update="$emit('update')"/>
    </div>
</template>

<script>
import AddModuleDialog from "./AddModuleDialog";
import TrashModuleDialog from "./TrashModuleDialog";
import AddCmsThemeModuleDialog from "./AddCmsThemeModuleDialog";
import AddThemeModuleDialog from "./AddThemeModuleDialog";
import AddUniappModuleDialog from "./AddUniappModuleDialog";
import AddBlogThemeModuleDialog from "./AddBlogThemeModuleDialog";

export default {
    name: "DeveloperTools",
    components: {
        AddBlogThemeModuleDialog,
        AddUniappModuleDialog,
        AddThemeModuleDialog, AddCmsThemeModuleDialog, TrashModuleDialog, AddModuleDialog
    },
    props: {
        memberUser: {
            type: Object,
            default: () => {
                return null
            }
        },
        token: {
            type: String,
            default: null
        }
    },
    methods: {
        doDialogPage(url) {
            this.$dialog.dialog(this.$url.admin('module_developer/tools/' + url, {token: this.token}), {
                width: '90%',
                height: '90%',
            })
        }
    }
}
</script>

