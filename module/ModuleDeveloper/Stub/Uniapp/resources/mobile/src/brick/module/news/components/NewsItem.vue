<template>
    <view class="tw-flex tw-py-2">
        <view class="tw-flex-grow tw-overflow-hidden tw-flex tw-flex-col"
              @tap.stop="$r.to('/brick/module/news/show',{id:item.id})">
            <view class="tw-font-bold tw-truncate">
                {{ item.title }}
            </view>
            <view class="ub-text-muted ub-text-small tw-max-h-10 tw-leading-5 tw-overflow-hidden tw-flex-grow">
                {{ item.summary }}
            </view>
            <view class="ub-text-muted ub-text-small">
                <text class="iconfont icon-time"></text>
                {{ item.day }}
            </view>
        </view>
        <view class="tw-w-24 tw-flex-shrink-0 tw-pl-3">
            <c-cover :src="item.cover" border-radius="0.25rem"
                     @tap.stop="$r.to('/brick/module/news/show',{id:item.id})"/>
        </view>
    </view>
</template>

<script>
export default {
    name: "NewsItem",
    props: {
        item: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },
}
</script>

