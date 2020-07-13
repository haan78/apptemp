<template>
    <div>
        <el-button :loading="isLoading" type="success" @click="test()">This is Main</el-button>
        <br/><a href="_.php?a=logout">Logut</a>
    </div>
</template>

<style>
    @import "./assets/theme/index.css";
</style>

<script>
export default {
    data() {
        return {};
    },
    created() {
        console.log(this.getServerData("DATA"));
    },
    methods:{
        test() {
            let self = this;
            self.ajax("_.php/topla?ajax",[3,6],(response)=>{
                self.$message.success(response.RESULT.toString());
            })
        }
    }
}
</script>