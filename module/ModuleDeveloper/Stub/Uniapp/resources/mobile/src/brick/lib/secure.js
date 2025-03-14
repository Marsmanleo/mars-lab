const CryptoJS = require("crypto-js");
import {SystemSettingEnv} from "../../config/setting";

export const SecureUtil = {
    aes: {
        encode: function (data, key) {
            key = key || SystemSettingEnv.encryptKey
            if (!key) {
                console.error('SystemSettingEnv.encryptKey empty')
            }
            return CryptoJS.AES.encrypt(data, key).toString()
        },
        decode: function (data, key) {
            key = key || SystemSettingEnv.encryptKey
            if (!key) {
                console.error('SystemSettingEnv.encryptKey empty')
            }
            return CryptoJS.AES.decrypt(data, key).toString(CryptoJS.enc.Utf8)
        }
    }
}
