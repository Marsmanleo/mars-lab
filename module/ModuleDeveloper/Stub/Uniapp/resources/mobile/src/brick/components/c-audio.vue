<template>
    <view class="pb-audio-player">
        <view data-time-total class="time-total">{{ durationTime }}</view>
        <view v-if="!playing" class="play" @click="doPlay">
            <text class="iconfont icon-play"></text>
        </view>
        <view v-if="playing" class="pause" @click="doStop">
            <text class="iconfont icon-pause"></text>
        </view>
        <view data-time-play class="time-play">{{ currentTime }}</view>
        <view class="bar-box">
            <view class="bar" @click="doSeek" :id="id">
                <view class="bar-played" @click="doSeek" :style="{width:percentComplete+'%'}"></view>
            </view>
        </view>
        <!-- #ifdef H5 -->
        <!-- #endif -->
    </view>
</template>

<script>
import {StrUtil} from "../lib/util";

const convertTimeHHMMSS = (val) => {
    if (!val) {
        return '--:--'
    }
    let hhmmss = new Date(val * 1000).toISOString().substr(11, 8);
    return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
}
export default {
    name: 'AudioPlayer',
    props: {
        url: {
            type: String,
            default: ''
        },
        autoPlay: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            id: 'AudioPlayerBar_' + StrUtil.randomString(10),
            audio: uni.createInnerAudioContext(),
            loaded: false,
            playing: false,
            durationSeconds: 0,
            currentSeconds: 0,
        }
    },
    computed: {
        currentTime() {
            return convertTimeHHMMSS(this.currentSeconds)
        },
        durationTime() {
            return convertTimeHHMMSS(this.durationSeconds)
        },
        percentComplete() {
            return parseInt(this.currentSeconds / this.durationSeconds * 100)
        },
        muted() {
            return this.volume / 100 === 0
        }
    },
    mounted() {
        if (this.autoPlay) {
            setTimeout(() => {
                this.doPlay()
            }, 1000)
        }
    },
    methods: {
        doStop() {
            if (!this.playing) {
                return
            }
            this.audio.pause()
        },
        doPlay() {
            if (this.audio.src && this.audio.src !== this.url) {
                this.audio.stop()
                this.playing = false
            }
            if (this.playing) {
                return
            }
            this.audio.stop()
            this.audio.src = this.url
            this.audio.autoplay = false
            this.audio.offTimeUpdate()
            this.audio.onTimeUpdate(() => {
                this.currentSeconds = this.audio.currentTime
                this.durationSeconds = this.audio.duration
            })
            this.audio.offPlay()
            this.audio.onPlay(() => {
                this.playing = true
            })
            this.audio.offPause()
            this.audio.onPause(() => {
                this.playing = false
            })
            this.audio.offEnded()
            this.audio.onEnded(() => {
                this.playing = false
            })
            this.audio.seek(0)
            this.audio.play()
        },
        doSeek(e) {
            if (!this.playing) {
                return
            }
            const query = uni.createSelectorQuery().in(this)
            query.select('#' + this.id).boundingClientRect(data => {
                this.audio.seek(parseInt(this.audio.duration * (e.target.x - e.target.offsetLeft) / data.width))
            }).exec()
            return false
        },
    }
}
</script>

<style lang="less" scoped>
@import "../../config/theme";

.pb-audio-player {
    background: #FFF;
    border: 0.05rem solid #EEE;
    height: 2.5rem;
    line-height: 2.5rem;
    border-radius: 0.2rem;
    overflow: hidden;
    padding: 0.25rem 0;

    .download, .play, .pause, .time-play, .time-total {
        display: block;
        text-align: center;
        float: left;
        width: 2rem;
        color: #333;
        line-height: 2rem;
        font-size: 0.5rem;
        text-decoration: none;
    }

    .download, .play, .pause {
        font-size: 1.5rem;

        &:hover {
            color: var(--color-primary);
        }
    }

    .download, .time-total {
        float: right;
    }

    .bar-box {
        padding-left: 4rem;
        padding-right: 2rem;

        .bar {
            margin-top: 0.5rem;
            height: 1rem;
            background: #EEE;
            border-radius: 0.25rem;

            .bar-played {
                border-radius: 0.25rem;
                height: 1rem;
                background: #999;
                width: 1%;
            }
        }
    }
}
</style>


