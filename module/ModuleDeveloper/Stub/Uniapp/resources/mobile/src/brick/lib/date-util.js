let date = require('date-and-time')
if (date.default) {
    date = date.default
}

export const DateUtil = {
    date() {
        return date.format(new Date(), 'YYYY-MM-DD')
    },
    datetime() {
        return date.format(new Date(), 'YYYY-MM-DD HH:mm:ss')
    },
    time() {
        return date.format(new Date(), 'HH:mm:ss')
    },
    timestampInMs() {
        return (new Date()).getTime()
    },
    timestampInSec() {
        return Math.floor((new Date()).getTime() / 1000)
    },
    timeHourMinute() {
        return date.format(new Date(), 'HH:mm')
    },
    stringDatetime() {
        return date.format(new Date(), 'YYYYMMDD_HHmmss')
    },
    formatDuration(ms, showAll) {
        showAll = showAll || false;
        const ss = ms % 1000;
        ms = (ms - ss) / 1000;
        const s = ms % 60;
        ms = (ms - s) / 60;
        const m = ms % 60;
        ms = (ms - m) / 60;
        const h = ms;
        var t = (h ? h + ":" : "")
            + (showAll || h + m ? ("0" + m).substr(-2) + ":" : "")
            + (showAll || h + m + s ? ("0" + s).substr(-2) + "." : "")
            + ("00" + ss).substr(-3);
        return t;
    }
}
