import {
    HandleInput,
    RouterTo
} from "../../../lib/ui";

export const ComponentListerMixin = {
    data() {
        return {
            listLoading: false,
            order: {
                field: null,
                type: 'asc',
            },
            list: {
                page: 1,
                pageSize: 10,
                records: [],
                total: 0,
            },
            selectIds: [],
            filter: [],
            search: {},
        }
    },
    onPullDownRefresh(e) {
        this.doSearch()
    },
    onReachBottom(e) {
        this.doNextPage()
    },
    methods: {
        HandleInput,
        RouterTo,
        init() {
            this.$refs.listStatus.init()
            this.doList(1)
        },
        doSearch() {
            this.$refs.listStatus.init()
            this.doList(1)
        },
        doSearchReset() {
            console.log('implements doSearchReset')
        },
        onSortChange(column, prop, order) {
            if (null === order) {
                this.order.field = null
                this.order.type = null
            } else {
                this.order.field = column.prop
                this.order.type = (order === 'ascending' ? 'asc' : 'desc')
            }
            this.doList(1)
        },
        doList(page) {
            console.error('should implements doList')
        },
        doListProcess(url, page, param, cb) {
            param = param || {}
            page = page || this.list.page
            this.list.page = page
            if (this.list.page === 1) {
                this.list.records = []
            }
            this.listLoading = true
            this.$api.post(url, Object.assign({
                order: JSON.stringify(this.order),
                search: JSON.stringify(this.search),
                filter: JSON.stringify(this.filter),
                page: this.list.page,
                pageSize: this.list.pageSize
            }, param), res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
                this.list.page = res.data.page
                this.list.pageSize = res.data.pageSize
                this.list.records = this.list.records.concat(res.data.records)
                this.list.total = res.data.total
                if (res.data.records.length !== res.data.pageSize) {
                    this.$refs.listStatus && this.$refs.listStatus.noMore()
                }
                cb && cb(res)
            }, res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
            })
        },
        doNextPage() {
            if (!this.$refs.listStatus.shouldNext()) {
                return
            }
            this.doList(this.list.page + 1)
        },
    },
}

export const ListerMixin = {
    data() {
        return {
            // 列表显示模式 concat拼接模式 list列表模式
            mode: 'concat',
            listLoading: false,
            order: {
                field: null,
                type: 'asc',
            },
            list: {
                page: 1,
                pageSize: 10,
                records: [],
                total: 0,
            },
            selectIds: [],
            filter: [],
            search: {},
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    onPullDownRefresh(e) {
        this.doSearch()
    },
    onReachBottom(e) {
        this.doNextPage()
    },
    mounted() {
    },
    methods: {
        HandleInput,
        RouterTo,
        init() {
            this.$refs.listStatus && this.$refs.listStatus.init()
            this.doList(1)
        },
        doSearch() {
            this.$refs.listStatus && this.$refs.listStatus.init()
            this.doList(1)
        },
        doSearchReset() {
            console.log('implements doSearchReset')
        },
        onSortChange(data) {
            const item = data.item
            if (item) {
                this.order.field = item.field
                this.order.type = item.type
            } else {
                this.order.field = null
                this.order.type = null
            }
            this.doSearch()
        },
        doList(page) {
            console.error('should implements doList')
        },
        doListProcess(url, page, param, cb, resSuccessPreprocessCB) {
            param = param || {}
            page = page || this.list.page
            this.list.page = page
            this.listLoading = true
            this.$api.post(url, Object.assign({
                order: JSON.stringify(this.order),
                search: JSON.stringify(this.search),
                filter: JSON.stringify(this.filter),
                page: this.list.page,
                pageSize: this.list.pageSize
            }, param), res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
                if (this.list.page === 1) {
                    this.list.records = []
                }
                if (resSuccessPreprocessCB) {
                    res = resSuccessPreprocessCB(res)
                }
                this.list.page = res.data.page
                this.list.pageSize = res.data.pageSize
                if ('concat' === this.mode) {
                    this.list.records = this.list.records.concat(res.data.records)
                } else {
                    this.list.records = res.data.records
                }
                this.list.total = res.data.total
                if (res.data.records.length !== res.data.pageSize) {
                    this.$refs.listStatus && this.$refs.listStatus.noMore()
                }
                cb && cb(res)
            }, res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
            })
        },
        doNextPage() {
            if (!this.$refs.listStatus.shouldNext()) {
                return
            }
            this.doList(this.list.page + 1)
        },
        onTableSelectChange(ids) {
            this.selectIds = ids.map(o => o.id)
        }
    },
}
