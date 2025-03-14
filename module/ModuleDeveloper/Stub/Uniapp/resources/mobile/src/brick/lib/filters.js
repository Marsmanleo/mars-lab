import {HtmlUtil} from "./html";

const RichHtmlMixin = {
    filters: {
        formatRichHtml(value) {
            return HtmlUtil.adapt(value);
        }
    }
}

export {
    RichHtmlMixin
}
