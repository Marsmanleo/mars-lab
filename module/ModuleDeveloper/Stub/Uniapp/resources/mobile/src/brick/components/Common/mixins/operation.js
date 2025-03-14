import {HandleInput} from "../../../lib/ui";

export const ViewMixin = {
    onReady() {
        this.$dialog.loadingOn()
        this.$starter.boot(() => {
            this.init()
        })
    },
    data() {
        return {
            isInit: false,
            dataLoading: true,
            data: {
                id: 0
            }
        }
    },
    methods: {
        initPrepare() {
            for (let i = 0; i < arguments.length; i++) {
                if (i === arguments.length - 1) {
                    if (this.isInit) {
                        return
                    }
                    this.isInit = true
                    arguments[i]()
                } else {
                    if (!arguments[i]) {
                        setTimeout(() => {
                            this.init()
                        }, 100)
                        return
                    }
                }
            }
        },
        doSubmit() {
            console.log('implements doSubmit')
        }
    }
}

export const EditMixin = {
    onReady() {
        this.$dialog.loadingOn()
        this.$starter.boot(() => {
            this.init()
        })
    },
    data() {
        return {
            isInit: false,
            data: {
                id: 0
            }
        }
    },
    methods: {
        HandleInput,
        initPrepare() {
            for (let i = 0; i < arguments.length; i++) {
                if (i === arguments.length - 1) {
                    if (this.isInit) {
                        return
                    }
                    this.isInit = true
                    arguments[i]()
                } else {
                    if (!arguments[i]) {
                        setTimeout(() => {
                            this.init()
                        }, 100)
                        return
                    }
                }
            }
        },
        doSubmit() {
            console.log('implements doSubmit')
        }
    }
}