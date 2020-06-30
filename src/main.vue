<template>
    <div>
        <el-button type="success">This is Main</el-button>
        <br/><a href="_.php?a=logout">Logut</a>
    </div>
</template>

<style>
    @import "./theme/index.css";
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

    }
}
</script>