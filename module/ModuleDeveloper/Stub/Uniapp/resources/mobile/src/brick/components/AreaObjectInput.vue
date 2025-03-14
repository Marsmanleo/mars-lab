<template>
    <view class="pb-area-object-input">
        <picker mode="multiSelector" :value="datavValue" :range="pickerOptions" @change="onChange"
                @columnchange="onColumnChange">
            {{datav.join('')?datav.join(','):'[请选择]'}}
        </picker>
    </view>
</template>

<script>

    import {Tree} from "../lib/tree";

    export default {
        name: "AreaObjectInput",
        props: {
            value: {
                type: Object,
                default: () => {
                    return {}
                }
            },
            dataName: {
                type: String,
                default: 'data',
            },
            provinceKey: {
                type: String,
                default: 'province',
            },
            cityKey: {
                type: String,
                default: 'city',
            },
            districtKey: {
                type: String,
                default: 'district',
            },
        },
        data() {
            return {
                provinces: [],
                cities: [],
                districts: [],
                datas: [],
                datav: ['', '', ''],
                datavValue: [0, 0, 0],
            }
        },
        watch: {
            value: {
                handler(newValue, oldValue) {
                    const v = [
                        this.value[this.provinceKey] || '',
                        this.value[this.cityKey] || '',
                        this.value[this.districtKey] || '',
                    ]
                    if (JSON.stringify(v) !== JSON.stringify(this.datav)) {
                        this.datav = v
                    }
                },
                deep: true,
                immediate: true
            },
        },
        computed: {
            pickerOptions() {
                return [
                    this.provinces.map(o => o.name),
                    this.cities.map(o => o.name),
                    this.districts.map(o => o.name),
                ]
            }
        },
        mounted() {
            this.$api.post('widget/area_china_all', {}, res => {
                this.datas = res.data
                this.calcChange()
            })
        },
        methods: {
            onChange(e) {
                let value = {}
                value[this.provinceKey] = this.provinces[e.detail.value[0]].name
                value[this.cityKey] = this.cities[e.detail.value[1]].name
                value[this.districtKey] = this.districts[e.detail.value[2]].name
                this.datav = [
                    value[this.provinceKey],
                    value[this.cityKey],
                    value[this.districtKey]
                ]
                this.$emit('input', {
                    target: {
                        dataset: {
                            name: this.dataName,
                        }
                    },
                    detail: {
                        value: value,
                    }
                })
            },
            onColumnChange(e) {
                switch (e.detail.column) {
                    case 0:
                        this.datavValue[0] = e.detail.value
                        this.datavValue[1] = 0
                        this.datavValue[2] = 0
                        break
                    case 1:
                        this.datavValue[1] = e.detail.value
                        this.datavValue[2] = 0
                        break
                    case 2:
                        this.datavValue[2] = e.detail.value
                        break
                }
                this.calcChange()
            },
            calcChange() {
                this.provinces = this.datas.filter(o => o.parentAreaId === 0)
                let province = this.provinces[this.datavValue[0]]
                this.cities = this.datas.filter(o => o.parentAreaId === province.areaId)
                let city = this.cities[this.datavValue[1]]
                this.districts = this.datas.filter(o => o.parentAreaId === city.areaId)
                let district = this.districts[this.datavValue[2]]
            }
        }
    }
</script>

<style lang="less">
    .pb-area-object-input {
        text-align: center;
        border: 1upx solid #EEE;
        background: #FFF;
        line-height: 80upx;
        height: 80upx;
    }
</style>
