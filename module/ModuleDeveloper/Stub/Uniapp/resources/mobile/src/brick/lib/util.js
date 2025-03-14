const md5 = require('md5');

export const StrUtil = {
    randomString(len) {
        len = len || 32;
        var $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var maxPos = $chars.length;
        var pwd = '';
        for (let i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    },
    matchWildcard(text, pattern) {
        var escapeRegex = (str) => str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1")
        pattern = pattern.split("*").map(escapeRegex).join(".*")
        pattern = "^" + pattern + "$"
        var regex = new RegExp(pattern)
        return regex.test(text)
    },
    keywordsMatchWildcard(text, pattern) {
        var escapeRegex = (str) => str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1")
        pattern = pattern.split("*").map(escapeRegex).join(".*")
        var regex = new RegExp(pattern)
        return regex.test(text)
    },
    urlencode(str) {
        let ret = "";
        const strSpecial = "!\"#$%&'()*+,/:;<=>?[]^`{|}~%";
        let tt = "";
        for (let i = 0; i < str.length; i++) {
            let chr = str.charAt(i);
            let c = str2asc(chr);
            tt += chr + ":" + c + "n";
            if (parseInt("0x" + c) > 0x7f) {
                ret += "%" + c.slice(0, 2) + "%" + c.slice(-2);
            } else {
                if (chr === " ")
                    ret += "+";
                else if (strSpecial.indexOf(chr) !== -1)
                    ret += "%" + c.toString(16);
                else
                    ret += chr;
            }
        }
        return ret;
    },
    urldecode(str) {
        let ret = "";
        str = str + ''
        for (let i = 0; i < str.length; i++) {
            let chr = str.charAt(i);
            if (chr === "+") {
                ret += " ";
            } else if (chr === "%") {
                let asc = str.substring(i + 1, i + 3);
                if (parseInt("0x" + asc) > 0x7f) {
                    ret += asc2str(parseInt("0x" + asc + str.substring(i + 4, i + 6)));
                    i += 5;
                } else {
                    ret += asc2str(parseInt("0x" + asc));
                    i += 2;
                }
            } else {
                ret += chr;
            }
        }
        return ret;
    },
    uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        })
    }
}


export const BeanUtil = {
    /**
     * @param bean
     * @param valueMap
     * @deprecated
     */
    assign(bean, valueMap) {
        Object.keys(bean).map(o => {
            bean[o] = valueMap[o]
        })
    },
    update(bean, valueMap) {
        Object.keys(valueMap).map(o => {
            bean[o] = valueMap[o]
        })
    },
    equal(o1, o2) {
        return JSON.stringify(o1) === JSON.stringify(o2)
    },
    clone(obj) {
        return JSON.parse(JSON.stringify(obj))
    },
}

export const JsonUtil = {
    extend() {
        return $.extend(...arguments)
    },
    clone(obj) {
        return JSON.parse(JSON.stringify(obj))
    },
    equal(o1, o2) {
        return JSON.stringify(o1) === JSON.stringify(o2)
    },
    notEqual(o1, o2) {
        return !JsonUtil.equal(o1, o2)
    },
    clearObject(obj) {
        let type
        for (var i in obj) {
            type = typeof obj[i]
            switch (type) {
                case 'string':
                    obj[i] = ''
                    break;
                case 'number':
                    obj[i] = 0
                    break;
            }
        }
    }
}

export const ConstUtil = {
    constToIdNameList(constObject) {
        return Object.keys(constObject).map(k => {
            return {
                id: constObject[k].value,
                name: constObject[k].name,
            }
        })
    }
}

export const ScriptUtil = {
    loadScript: function (url, cb) {
        let id = 's_' + md5(url)
        let script = document.getElementById(id)
        if (script) {
            cb && cb({isNew: false})
            return
        }
        script = document.createElement('script')
        script.id = id
        script.src = url
        script.onload = () => {
            cb && cb({isNew: true})
        }
        document.getElementsByTagName('head')[0].appendChild(script)
    },
    loadScriptsInOrder: function (urls, cb) {
        let i = 0
        let load = () => {
            if (i >= urls.length) {
                cb && cb()
                return
            }
            ScriptUtil.loadScript(urls[i], () => {
                i++
                load()
            })
        }
        load()
    }
}
