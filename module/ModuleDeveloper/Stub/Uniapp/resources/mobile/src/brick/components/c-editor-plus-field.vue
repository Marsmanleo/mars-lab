<template>
    <view>
        <view v-if="value&&value.length>0">
            <view v-for="(item,itemIndex) in value" :key="itemIndex">
                <view v-if="item.type==='text'">
                    {{item.data.content}}
                </view>
                <view v-else-if="item.type==='image'">
                    <image v-if="item.data.image" :src="item.data.image" class="tw-w-full" mode="widthFix"/>
                </view>
                <view v-else-if="item.type==='images'">
                    <view class="row">
                        <view class="col-4 tw-relative" v-for="(itemImage,itemImageIndex) in item.data.images" :key="itemImageIndex">
                            <view class="ub-cover-1-1 contain tw-border tw-border-solid tw-border-gray-100 tw-rounded"
                                  :style="{backgroundImage:`url(${itemImage})`}"></view>
                        </view>
                    </view>
                </view>
                <view v-else>
                    未识别类型：{{JSON.stringify(item)}}
                </view>
            </view>
        </view>
        <view v-else class="ub-text-muted ub-text-center">
            {{ emptyText }}
        </view>
    </view>
</template>

<script>
export default {
    name: "c-editor-plus-field",
    props: {
        value: {
            type: Array,
            default: () => {
                return []
            }
        },
        emptyText: {
            type: String,
            default: '暂无内容'
        }
    }
}
</script>

