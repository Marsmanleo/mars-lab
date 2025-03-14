<template>
    <div>
        <el-select v-if="modules.length>0" v-model="currentData" style="width:auto;" filterable>
            <el-option v-for="item in modules"
                       :key="item.name"
                       :label="item.name"
                       :value="item.name"></el-option>
        </el-select>
    </div>
</template>

<script>
import {FieldVModel} from "@ModStartAsset/svue/lib/fields-config"

export default {
    name: "ModuleSelector",
    mixins: [FieldVModel],
    data() {
        return {
            modules: []
        }
    },
    mounted() {
        this.$api.post(this.$url.admin('module_developer/all'), {}, res => {
            this.modules = res.data.modules
        })
    }
}
</script>

<style scoped>

</style>