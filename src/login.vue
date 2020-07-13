<template>
    <div>Login
        <form action="_.php?a=enter" method="POST" >
            <input type="text" name="user" value="USER" />
            <button type="submit">ENTER</button>
        </form>
        Ali
        <div v-show="getM()=='n'">Nothing Just Welcome</div>
        <div v-show="getM()=='w'">Wrong Auth</div>
        <div v-show="getM()=='e'">Exit to System</div>
    </div>
</template>
<style>
    @import "./assets/theme/index.css";
</style>
<script>
export default {
    data() {
        return {}
    },
    created() {
        
    },
    methods:{
        getM() {
            var params = this.$url.params();
            return ( params.m ? params.m : 'n' );
        }
    }
}
</script>