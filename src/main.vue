<template>
    <div>
        <el-button type="success">This is Main</el-button>
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
        console.log(window.sessionStorage.getItem("ENBEDED_DATA"));
    },
    methods:{

    }
}
</script>