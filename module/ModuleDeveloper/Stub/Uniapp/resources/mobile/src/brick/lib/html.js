const HtmlUtil = {
    adapt: (html) => {
        if (!html) {
            return html;
        }
        // #ifdef H5
        return html;
        // #endif

        // #ifdef MP-WEIXIN
        //控制小程序中图片大小
        let newContent = html.replace(/<img[^>]*>/gi, function (match, capture) {
            // console.log(match.search(/style=/gi));
            if (match.search(/style=/gi) === -1) {
                match = match.replace(/\<img/gi, '<img style=""');
            }
            return match;
        });
        newContent = newContent.replace(/style="/gi, '$& max-width:100% !important; ');
        newContent = newContent.replace(/<br[^>]*\/>/gi, '');
        return newContent;
        // #endif

        return 'HtmlUtil.html.NotSupport'
    }

}

export {
    HtmlUtil
}
