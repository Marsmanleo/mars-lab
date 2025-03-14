import {Api} from "../../../lib/api";
import {AgentUtil} from "../../../lib/ui";

export const ShareMixin = {
    onShareAppMessage(res) {
        return this.getShareInfo()
    },
    onShareTimeline(res) {
        return this.getShareInfo()
    },
    methods: {
        getShareInfo() {
            let title = this.pageTitle
            if (!title && this.config) {
                title = [
                    this.config.siteName,
                    this.config.siteSlogan,
                ].filter(v => v).join(' | ')
            }
            let path = this.$r.pathWithQueries()
            const share = Object.assign({title, path}, this.share)
            // console.log('getShareInfo', share)
            return share
        }
    }
}

export const ShareH5 = {
    wechat(title, desc, imgUrl) {
        // #ifdef H5
        if (!AgentUtil.isWechat()) {
            return
        }
        if ('function' === typeof title) {
            if (!title()) {
                setTimeout(() => {
                    this.wechat(title, desc, imgUrl)
                }, 100)
                return
            }
            title = title()
        }
        if ('function' === typeof desc) {
            desc = desc()
        }
        if ('function' === typeof imgUrl) {
            imgUrl = imgUrl()
        }
        let info = {
            title: title || '分享标题',
            desc: desc || '',
            imgUrl: imgUrl || '',
            link: window.location.href,
        }
        // console.log('info', info)
        Api.post('wechat_share/config', {url: info.link}, function (res) {
            if (!info.imgUrl || res.data.defaultImageFirst) {
                info.imgUrl = res.data.defaultImage
            }
            // alert(JSON.stringify(info))
            wx.config(res.data.json);
            wx.ready(function () {
                wx.updateAppMessageShareData(info);
                wx.updateTimelineShareData(info);
            })
        })
        // #endif
    }
}
