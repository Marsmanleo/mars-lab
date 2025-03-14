<template>
    <view>
        <view class="tw-flex tw-flex-wrap tw-justify-center">
            <view v-for="(n,nIndex) in navs" :key="nIndex"
                  class="tw-text-center tw-mx-3 tw-flex-grow tw-py-1"
                  style="max-width:25%;"
                  @click="doLink(n)">
                <view class="tw-h-12 tw-w-12 tw-inline-block tw-text-white tw-rounded-full tw-text-center tw-leading-12"
                      :style="{'background-color':n.color||'var(--color-primary)'}"
                >
                    <text :class="n.icon" style="font-size:1.3rem;"></text>
                </view>
                <view class="tw-pt-1">
                    {{ n.title }}
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseConfigMixin} from "@/brick/components/Common/mixins/store";

export default {
    name: "bui-home-nav",
    mixins: [StoreBaseConfigMixin],
    props: {
        name: {
            type: String,
            default: ''
        }
    },
    computed: {
        navs() {
            if (!this.config) {
                return []
            }
            return this.config[this.name];
        },
    },
    methods: {
        doLink(n) {
            this.$r.to(n.link, null, true)
        }
    }
}
</script>

