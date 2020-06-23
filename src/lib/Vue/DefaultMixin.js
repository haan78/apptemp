
export default {
    
    methods:{
        session(name,value) {
            if ( typeof value === "undefined" ) {
                return window.sessionStorage.getItem(name);
            } else {
                window.sessionStorage.setItem(name,value);
                return value;
            }
        },

        link(path) {            
            if (this.$router) {
                if (this.$router.currentRoute.path != path ) {
                    this.$router.push(path);
                }
            }            
        },

        setLoading(value) {
            let self = this;
            if ( typeof self.$parent.loading === "boolean" ) {
                self.$parent.loading = value;
            }
            if ( typeof self.loading === "boolean" ) {
                self.loading = value;
            }
        },

        setData(t,s) {
            if ( typeof t === "object" && t !== null ) {
                for (var k in t) {
                    if ( typeof t[k] === "object" && t[k] !== null ) {
                        this.setData(t[k],(typeof s[k] !== "undefined" ? s[k] : null));
                    } else {
                        t[k] = ( s && typeof s[k] !== "undefined" ? s[k] : null);
                    }                    
                }
            }
        },

        defaultError(type,message) {
            //console.error(type+":"+message);
            this.$notify.error({
                title: 'Hata / '+type,
                message: message
              });
        },
        
        WebMethod(method,data,onSuccess,onError) {
            let self = this;
            var err = ( typeof onError === "function" ? onError : self.defaultError );
            self.setLoading(true);
            self.$http.post("index.php/"+method+"?a=ajax",(data ? data : null )).then( (response)=>{
                self.setLoading(false);
                if ( typeof response.data === "object" ) {
                    if ( response.data.success ) {
                        if ( typeof onSuccess === "function" ) {
                            onSuccess( response.data );
                        }
                    } else {
                        err( "Application",response.data.text );
                    }
                } else {
                    err( "Server",response.data );
                }
            }).catch((error)=>{
                self.setLoading(false);
                err( "Network",error );
            });
        }
    }
}