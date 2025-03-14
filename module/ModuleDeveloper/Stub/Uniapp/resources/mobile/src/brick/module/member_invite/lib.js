export const MemberInviteMixin = {
    data() {
        return {
            inviteCode: null
        }
    },
    computed: {
        inviteUrl() {
            return this.$r.pathToUrl()
        },
        inviteShow() {
            const code = this.$storage.get('MemberInvite_Code')
            return code && (code === this.inviteCode)
        }
    },
    methods: {
        inviteInit() {
            this.inviteCode = this.$r.getQuery('inviteCode')
            if (this.inviteCode) {
                this.$api.post('member_invite/from', {
                    inviteCode: this.inviteCode
                }, res => {

                })
            }
        }
    }
}
