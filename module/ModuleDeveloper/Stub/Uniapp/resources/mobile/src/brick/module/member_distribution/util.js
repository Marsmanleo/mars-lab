export const MemberDistributionUtil = {
    initEntryData($this, data) {
        if (!data || !data.MemberDistribution_Code) {
            return;
        }
        $this.$api.post('member_distribution/from', {
            code: data.MemberDistribution_Code
        }, res => {
        }, res => {
            return true
        })
    }
}
