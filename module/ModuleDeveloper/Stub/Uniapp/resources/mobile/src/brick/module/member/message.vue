<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="我的消息" has-action>
            <block slot="action">
                <view @click="doClear">
                    <text class="iconfont icon-trash item tw-text-gray-200"
                          :class="{'ub-text-danger':list.records.length>0}"
                          style="font-size:1rem;"></text>
                </view>
            </block>
        </c-page-header>
        <view class="ub-list no-bg">
            <view class="item-message" v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="content">
                    <rich-text class="ub-html" :nodes="item.content || ''"/>
                </view>
                <view class="time">
                    <text class="iconfont icon-time"></text>
                    {{ item.createTime }}
                </view>
                <view class="dot" v-if="item.status===MemberMessageStatus.UNREAD.value"></view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :records="list.records" empty-text="暂无消息"/>
        </view>
    </view>
</template>

<script>
import {ListerMixin} from "../../components/Common/mixins/lister";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

const MemberMessageStatus = {
    "UNREAD": {
        "key": "UNREAD",
        "value": 1,
        "name": "未读"
    },
    "READ": {
        "key": "READ",
        "value": 2,
        "name": "已读"
    }
};

export default {
    name: "message",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            MemberMessageStatus,
            search: {
                status: 0,
            },
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_message', page, {}, res => {
                this.doMarkRead(res.data.records.filter(o => o.status === MemberMessageStatus.UNREAD.value).map(o => o.id))
            })
        },
        doMarkRead(ids) {
            if (ids.length > 0) {
                this.$api.post('member_message/read', {_id: ids.join(',')}, res => {
                })
            }
        },
        doClear() {
            this.$dialog.confirm('确认清空？', () => {
                this.$api.post('member_message/delete_all', {}, res => {
                    this.doList(1)
                })
            })
        }
    }
}
</script>

<style lang="less" scoped>
.item-message {
    background: #FFF;
    border-radius: 0.1rem;
    margin: 0.5rem;
    padding: 0.25rem 0.5rem;
    position: relative;

    .dot {
        width: 0.5rem;
        height: 0.5rem;
        background: red;
        border-radius: 50%;
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
    }

    .time {
        color: var(--color-muted);
        line-height: 1rem;
        font-size: 0.5rem;

        .iconfont {
            margin-right: 0.25rem;
        }
    }

    .content {
        font-size: 0.6rem;
    }
}
</style>
