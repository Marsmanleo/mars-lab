import {mapState} from 'vuex'
import store from "./index"

let storeStateKeys = [];
try {
    storeStateKeys = store.state ? Object.keys(store.state) : [];
} catch (e) {
}

export const StoreMixin = {
    created() {
        // this.$m.storeSet = (name, value) => {
        //     this.$store.commit('STORE_SET', {name, value})
        // }
    },
    computed: {
        ...mapState(storeStateKeys)
    }
}
