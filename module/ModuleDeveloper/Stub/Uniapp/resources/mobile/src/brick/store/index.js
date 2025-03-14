import Vue from 'vue'
import Vuex from 'vuex'
import base from './base'
import version from './version'
import user from './user'
import reload from './reload'
import api from './api'
import auth from './auth'

Vue.use(Vuex)
const store = new Vuex.Store({
    modules: {
        version,
        base,
        user,
        reload,
        api,
        auth,
    },
})

export default store
