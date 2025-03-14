<template>
    <view v-if="!!field" class="pb-field-input">
        <view v-if="field.type==='Number'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <input type="digit"
                   :placeholder="field.placeholder || '点击填写'"
                   :data-name="datavName"
                   :value="value"
                   @input="HandleInput"/>
        </view>
        <view v-if="field.type==='Decimal'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <input type="digit"
                   :placeholder="field.placeholder || '点击填写'"
                   :data-name="datavName"
                   :value="value"
                   @input="HandleInput"/>
        </view>
        <view v-if="field.type==='Date'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="picker-container">
                <picker mode="date" :value="value" @change="HandleInput">
                    <view class="picker" :class="{empty:!datav}">
                        {{datav || '[选择日期]'}}
                    </view>
                </picker>
            </view>
        </view>
        <view v-if="field.type==='Datetime'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="picker-container">
                <DatetimeInput :data-name="datavName" :value="value" @input="HandleInput"/>
            </view>
        </view>
        <view v-if="field.type==='Time'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="picker-container">
                <picker mode="time" :value="timeValue" @change="HandleInput">
                    <view class="picker" :class="{empty:!timeValue}">
                        {{timeValue || '[选择时间]'}}
                    </view>
                </picker>
            </view>
        </view>
        <view v-if="field.type==='Text'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <input :placeholder="field.placeholder || '点击填写'"
                   :data-name="datavName"
                   :value="value || ''"
                   @input="HandleInput"/>
        </view>
        <view v-if="field.type==='Textarea'" class="c-form-group align-start">
            <view class="title" :title="field.title">{{field.title}}</view>
            <textarea maxlength="-1"
                      :placeholder="field.placeholder || '点击填写'"
                      @input="HandleInput"
                      :value="value || ''"
                      :data-name="datavName"></textarea>
        </view>
        <view v-if="field.type==='RichText'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <input disabled value="[富文本暂不支持手机编辑]"/>
        </view>
        <view v-if="field.type==='Select'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="picker-container">
                <picker @change="HandleInput" :value="selectValue" :range="field.option">
                    <view class="picker" :class="{empty:selectValue<0}">
                        {{selectValue>-1?field.option[selectValue]:'[点击选择]'}}
                    </view>
                </picker>
            </view>
        </view>
        <view v-if="field.type==='MultiSelect'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="multi-select-container">
                <view class="item" v-for="(item,itemIndex) in field.option" :key="itemIndex" @tap.stop="HandleInput(item)">
                    <UICheckbox :value="multiSelectChecked(item)"/>
                    <text class="text">{{item}}</text>
                </view>
            </view>
        </view>
        <view v-if="field.type==='Image'" class="c-form-group">
            <view class="title" :title="field.title">{{field.title}}</view>
            <view class="picker-container">
                <ImageInput :data-name="datavName" :value="value || ''" @input="HandleInput"/>
            </view>
        </view>
    </view>
</template>

<script>
    import UICheckbox from "./UICheckbox";
    import DatetimeInput from "./DatetimeInput";
    import ImageInput from "./ImageInput";

    export default {
        name: "FieldInput",
        components: {ImageInput, DatetimeInput, UICheckbox},
        computed: {
            selectValue() {
                if (!this.field || this.field.type !== 'Select') {
                    return -1
                }
                return this.field.option.indexOf(this.datav)
            },
            timeValue() {
                if (!this.field || this.field.type !== 'Time') {
                    return null
                }
                return this.datav ? this.datav.substr(0, 5) : null
            }
        },
        props: {
            value: null,
            datavName: {
                type: String,
                default: ''
            },
            field: {
                type: Object,
                default: () => null
            },
        },
        data() {
            return {
                datav: null,
            }
        },
        watch: {
            value: {
                handler(n, o) {
                    if (undefined === this.value) {
                        if (this.field) {
                            switch (this.field.type) {
                                case 'MultiSelect':
                                    this.datav = []
                                    break
                                default:
                                    this.datav = null
                                    break
                            }
                        } else {
                            this.datav = null
                        }
                        return
                    }
                    const str = JSON.stringify(this.value)
                    if (JSON.stringify(this.datav) !== str) {
                        if (this.field) {
                            switch (this.field.type) {
                                case 'MultiSelect':
                                    if (!Array.isArray(this.value)) {
                                        this.datav = [this.value]
                                    } else {
                                        this.datav = JSON.parse(str)
                                    }
                                    break
                                default:
                                    this.datav = JSON.parse(str)
                                    break
                            }
                        } else {
                            this.datav = JSON.parse(str)
                        }
                    }
                },
                immediate: true
            }
        },
        methods: {
            multiSelectChecked(v) {
                return !!(this.datav && Array.isArray(this.datav) && this.datav.includes(v))
            },
            HandleInput(e) {
                // console.log('HandleInput', e)
                switch (this.field.type) {
                    case 'MultiSelect':
                        if (!Array.isArray(this.datav)) {
                            this.datav = []
                        }
                        if (!this.datav.includes(e)) {
                            this.datav.push(e)
                        } else {
                            this.datav.splice(this.datav.indexOf(e), 1)
                        }
                        break
                    case 'Select':
                        if (e.detail.value > -1) {
                            this.datav = this.field.option[e.detail.value]
                        } else {
                            if (this.field.option.length > 0) {
                                this.datav = this.field.option[0]
                            } else {
                                this.datav = null
                            }
                        }
                        break
                    default:
                        this.datav = e.detail.value
                        break
                }
                this.$emit('input', {
                    target: {
                        dataset: {
                            name: this.datavName
                        }
                    },
                    detail: {
                        value: this.datav,
                    },
                })
            },
        }
    }
</script>

<style lang="less" scoped>
    .pb-field-input {
        margin-bottom: 1px;
    }
</style>
