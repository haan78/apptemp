
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

        defaultError(type,message) {
            //console.error(type+":"+message);
            this.$notify.error({
                title: 'Hata / '+type,
                message: message
              });
        },

        ebs(tc,cb) {
            let self = this;
            self.$jsonp("http://ebs.teb.org.tr/ek_servisler/eczaci_hukuk_jsonp.php?tc="+tc,{ callbackName:"cb",callbackQuery:"cb" }).then( (response)=>{
              if ( response.success ) {
                cb(response.result);
              } else {
                self.$message.error(response.result);
              }
      
            }).catch((error)=>{
              self.$message.error("JSONP iletim hatası");
              console.log(error);
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