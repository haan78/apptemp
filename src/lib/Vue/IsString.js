export default {
    anEmail:function(str){
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(str).toLowerCase());
    },
    aNumber:function(str) {
        var regx = /^[+-]?([1-9][0-9]*)(\.\d+)?$/;
        return regx.test(str);
    }
}