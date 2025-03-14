import App from './App'
import Vue from 'vue'
import store from './brick/store'

import {
    EventBus
} from './brick/lib/event-bus.js'

import {
    BrickUni
} from './brick/BrickUni.js'

BrickUni.init()

import {
    Bootstrap
} from './config/bootstrap.js'

Bootstrap.init()

store.registerModule('$moduleName$', require('./store/$moduleName$').default)

App.mpType = 'app'
const vueApp = new Vue({
    store,
    ...App,
    created() {
        Bootstrap.created(this)
    },
    mounted() {
        EventBus.$emit('AppMounted')
        Bootstrap.mounted(this)
    }
})
vueApp.$mount()
