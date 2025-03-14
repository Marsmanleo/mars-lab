<template>
    <view class="tw-inline-flex tw-items-center">
        <view class="tw-w-6 tw-mr-1">
            <c-cover :src="member.avatar" round></c-cover>
        </view>
        <view>
            {{ member.nickname }}
        </view>
    </view>
</template>

<script>
export default {
    name: "bui-member-user",
    props: {
        member: {
            type: Object,
            default: () => {
                return null
            }
        }
    },
}
</script>

<style scoped>

</style>
