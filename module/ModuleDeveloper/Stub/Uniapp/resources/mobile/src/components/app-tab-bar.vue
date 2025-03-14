<template>
    <view class="pb-app-tab-bar">
        <tui-tabbar :current="current" backdropFilter backgroundColor="#FFF" :tabBar="tabBar" color="#777"
                    :selectedColor="primaryColor" @click="doChange"></tui-tabbar>
    </view>
</template>

<script>
import {
    SystemSetting
} from "../config/setting";

export default {
    name: "app-tab-bar",
    data() {
        return {
            primaryColor: SystemSetting.primaryColor,
        };
    },
    computed: {
        tabBar() {
            return [
                {
                    pagePath: '/pages/home',
                    text: '首页',
                    iconPath: '/static/image/home.svg',
                    selectedIconPath: '/static/image/home-focus.svg'
                },
                {
                    pagePath: '/pages/$module_name_mobile_short$/page1',
                    text: 'Page1',
                    iconPath: '/static/image/home.svg',
                    selectedIconPath: '/static/image/home-focus.svg'
                },
                {
                    pagePath: '/pages/$module_name_mobile_short$/page2',
                    text: 'Page2',
                    iconPath: '/static/image/home.svg',
                    selectedIconPath: '/static/image/home-focus.svg'
                },
            ]
        },
        current() {
            for (let i = 0; i < this.tabBar.length; i++) {
                if (this.tabBar[i].pagePath === this.$r.path()) {
                    return i
                }
            }
            return 0
        }
    },
    methods: {
        doChange(tb) {
            this.$r.replace(tb.pagePath)
        }
    }
}
</script>

<style lang="less" scoped>
.pb-app-tab-bar {
    height: 100rpx;
}
</style>
