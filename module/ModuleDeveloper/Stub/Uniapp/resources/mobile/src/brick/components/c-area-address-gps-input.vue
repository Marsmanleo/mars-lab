<template>
    <view class="pb-area-address-gps-object-input">
        <view>
            <picker mode="multiSelector" :value="areaValue" :range="pickerOptions"
                    @change="onChange" @columnchange="onColumnChange">
                <view class="tw-border tw-border-gray-100 tw-border-solid tw-rounded tw-h-9 tw-px-2">
                    <view v-if="areaValueString" class="tw-leading-9 tw-text-sm">{{ areaValueString }}</view>
                    <view v-else class="ub-text-muted tw-leading-9 tw-text-sm">请选择地区</view>
                </view>
            </picker>
        </view>
        <view class="tw-mt-1">
            <uni-easyinput class="address" placeholder="街道门牌号" :value="detailValue" @input="onDetailInput"/>
        </view>
        <view class="tw-mt-1">
            <view class="tw-border tw-border-gray-100 tw-border-solid tw-rounded tw-h-9 tw-px-2" @click="doChooseGps">
                <text class="iconfont icon-address tw-mr-1"></text>
                <text v-if="gpsValue" class="tw-leading-9 tw-text-sm">{{ gpsValue }}</text>
                <text v-if="!gpsValue" class="ub-text-muted tw-leading-9 tw-text-sm">选择定位</text>
            </view>
        </view>
    </view>
</template>

<script>
export default {
    name: "c-area-address-gps-input",
    props: {
        value: {
            type: Object,
            default: () => {
                return {}
            }
        },
        addressAreaKey: {
            type: String,
            default: 'addressArea',
        },
        addressDetailKey: {
            type: String,
            default: 'addressDetail',
        },
        addressLngKey: {
            type: String,
            default: 'addressLng',
        },
        addressLatKey: {
            type: String,
            default: 'addressLat',
        },
        addressGpsNameKey: {
            type: String,
            default: 'addressGpsName',
        },
    },
    computed: {
        pickerOptions() {
            return [
                this.provinces.map(o => o.title),
                this.cities.map(o => o.title),
                this.districts.map(o => o.title),
            ]
        },
        detailValue() {
            return this.value[this.addressDetailKey] ? this.value[this.addressDetailKey] : ''
        },
        areaValueString() {
            return this.value[this.addressAreaKey] ? this.value[this.addressAreaKey] : ''
        },
        gpsValue() {
            return this.value[this.addressGpsNameKey] ? this.value[this.addressGpsNameKey] : ''
        }
    },
    data() {
        return {
            provinces: [],
            cities: [],
            districts: [],
            areaData: [],
            areaValue: [0, 0, 0],
        }
    },
    mounted() {
        this.$api.post('area/china', {}, res => {
            this.areaData = res.data
            this.calcChange()
        })
    },
    methods: {
        doTest(){
            uni.onCompassChange();
            uni.stopCompass();
        },
        doChooseGps() {
            uni.chooseLocation({
                success: (res) => {
                    this.$nextTick(() => {
                        this.$forceUpdate()
                        let newValue = Object.assign(this.value, {
                            [this.addressLngKey]: res.longitude,
                            [this.addressLatKey]: res.latitude,
                            [this.addressGpsNameKey]: res.name,
                        })
                        this.$emit('input', newValue)
                    })
                },
                fail: (res) => {
                    console.log('uni.chooseLocation.fail', res)
                }
            })
        },
        onDetailInput(v) {
            let newValue = Object.assign(this.value, {
                [this.addressDetailKey]: v
            })
            this.$emit('input', newValue)
        },
        onChange(e) {
            let value = []
            value.push(this.provinces[e.detail.value[0]].title)
            value.push(this.cities[e.detail.value[1]].title)
            value.push(this.districts[e.detail.value[2]].title)
            let newValue = Object.assign(this.value, {
                [this.addressAreaKey]: value.join(',')
            })
            this.$emit('input', newValue)
        },
        onColumnChange(e) {
            switch (e.detail.column) {
                case 0:
                    this.areaValue[0] = e.detail.value
                    this.areaValue[1] = 0
                    this.areaValue[2] = 0
                    break
                case 1:
                    this.areaValue[1] = e.detail.value
                    this.areaValue[2] = 0
                    break
                case 2:
                    this.areaValue[2] = e.detail.value
                    break
            }
            this.calcChange()
        },
        calcChange() {
            this.provinces = this.areaData.filter(o => o.pid === 0)
            let province = this.provinces[this.areaValue[0]]
            this.cities = this.areaData.filter(o => o.pid === province.id)
            let city = this.cities[this.areaValue[1]]
            this.districts = this.areaData.filter(o => o.pid === city.id)
            let district = this.districts[this.areaValue[2]]
        }
    }
}
</script>
