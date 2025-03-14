import {Dialog} from "./dialog";

export const DownloadUtil = {
    save(filename, url) {
        console.log('save', filename, url)
        const ext = url.split('.').pop().split(/\#|\?/)[0];
        // #ifdef H5
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        // #endif
        // #ifdef MP-WEIXIN | APP-PLUS
        switch (ext) {
            case 'doc':
            case 'docx':
            case 'ppt':
            case 'pptx':
            case 'xls':
            case 'xlsx':
            case 'zip':
                Dialog.loadingOn('正在下载...')
                uni.downloadFile({
                    url: url,
                    success: (res) => {
                        // console.log('downloadFile', res)
                        Dialog.loadingOff()
                        if (res.statusCode === 200) {
                            Dialog.loadingOn('正在保存文件...')
                            uni.openAssetument({
                                filePath: res.tempFilePath,
                                showMenu: true,
                                fileType: this.data.doc.ext,
                                success: () => {
                                    Dialog.loadingOff()
                                    Dialog.tipSuccess('打开文件成功')
                                },
                                fail: () => {
                                    Dialog.loadingOff()
                                    Dialog.tipError('保存出现错误，推荐使用电脑端查看')
                                }
                            })
                        } else {
                            Dialog.tipError('保存出现错误，推荐使用电脑端查看')
                        }
                    },
                    fail: (res) => {
                        Dialog.tipError('下载文件出现错误，推荐使用电脑端查看')
                    }
                });
                break;
            default:
                Dialog.tipError('手机暂不支持的文件格式：' + ext)
        }
        // #endif
    }
}
