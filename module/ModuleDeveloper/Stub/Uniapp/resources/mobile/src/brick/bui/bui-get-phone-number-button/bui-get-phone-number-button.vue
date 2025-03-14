<template>
    <view>
        <button class="btn btn-primary-line btn-sm btn-round btn-block"
                open-type="getPhoneNumber"
                @getphonenumber="onGetPhoneNumber">
            获取已绑定手机号
        </button>
    </view>
</template>

<script>
export default {
    name: "bui-get-phone-number-button",
    methods: {
        onGetPhoneNumber(e) {
            if (e.detail.code) {
                this.$api.post('oauth/login_wechat_mini_program_get_phone_number', {
                    code: e.detail.code
                }, res => {
                    this.$emit('change', res.data)
                })
            }
        }
    }
}
</script>

