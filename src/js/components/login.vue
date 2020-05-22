<template>
    <div>
        <form method="POST" action="index.php?a=enter" ref="form">
            <fieldset v-show="mode=='login'">
                <legend><b>Login Form</b></legend>
                <label>Email</label><br/>
                <input type="email" v-model="email" placeholder="Email" name="email" ><br/><br/>
                <label>Password</label><br/>
                <input type="password" v-model="password" placeholder="Password" name="password" ><br/><br/>
                <button type="button" @click="submit()">Enter</button>
                <button type="button" @click="mode='mailsend'">Forgot Pass?</button>
            </fieldset>
            <fieldset v-show="mode=='mailsend'">
                <legend><b>Forgot Password</b></legend>
                <label>Email</label><br/>
                <input type="email" v-model="email" placeholder="Email" name="email" ><br/>                
                <button type="button" @click="mailsend()">Send Mail</button>
                <button type="button" @click="mode='login'">Login</button>
            </fieldset>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            mode:"login",
            email:null,
            password:null
        }
    },
    methods:{
        submit() {
            this.$refs.form.submit();
        },
        mailsend() {

        }
    }
}
</script>