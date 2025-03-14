const Storage = {
    remove(key) {
        uni.removeStorageSync(key)
    },
    set(key, value) {
        uni.setStorageSync(key, JSON.stringify(value))
    },
    get(key, defaultValue) {
        let value = uni.getStorageSync(key)
        if (null === value) {
            return defaultValue
        }
        try {
            return JSON.parse(value)
        } catch (e) {
            return defaultValue
        }
    },
    getArray(key, defaultValue) {
        defaultValue = defaultValue || []
        const value = this.get(key)
        if (null === value || !Array.isArray(value)) {
            return defaultValue
        }
        return value
    },
    getObject(key, defaultValue) {
        defaultValue = defaultValue || {}
        const value = this.get(key)
        if (null === value || !Array.isArray(value) && (typeof value === 'object')) {
            return defaultValue
        }
        return value
    },
}


export {
    Storage
}
