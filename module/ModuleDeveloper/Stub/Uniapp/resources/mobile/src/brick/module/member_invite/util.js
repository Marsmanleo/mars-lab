export const MemberInviteUtil = {
    initEntryData($this, data) {
        if (!data || !data.MemberInvite_InviteCode) {
            return;
        }
        $this.$api.post('member_invite/from', {
            inviteCode: data.MemberInvite_InviteCode
        }, res => {
        }, res => {
            return true
        })
    }
}
