
export default {

    data() {
        return {
            ajaxActivationCount:0
        }
    },

    computed:{
        isLoading() {
            return (this.ajaxActivationCount > 0);
        }
    },
    
    methods:{

        link(path) {            
            if (this.$router) {
                if (this.$router.currentRoute.path != path ) {
                    this.$router.push(path);
                }
            }            
        },

        getServerData(key) {
            var decodeBase64 = function(s) {
                var e={},i,b=0,c,x,l=0,a,r='',w=String.fromCharCode,L=s.length;
                var A="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
                for(i=0;i<64;i++){e[A.charAt(i)]=i;}
                for(x=0;x<L;x++){
                    c=e[s.charAt(x)];b=(b<<6)+c;l+=6;
                    while(l>=8){((a=(b>>>(l-=8))&0xff)||(x<(L-2)))&&(r+=w(a));}
                }
                return r;
            };
            var base64str = window.sessionStorage.getItem(key);
            if (base64str!==null) {
                try {
                    return JSON.parse( decodeBase64(base64str) );
                } catch( e ) {
                    console.log(e);
                    return null;
                }                
            } else {
                return null;
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
            this.$message.error("HATA("+type+"):"+message);
        },

        defaultAjaxResponseEvaluation(data) {
            if ( !data.ERROR ) {
                return {
                    "success":true,
                    "data": data
                }
            } else {
                return {
                    "success":false,
                    "text":data.ERROR
                }
            }
        },
        
        ajax(url,data,onSuccess,onError) {
            
            let self = this;
            var err = ( typeof onError === "function" ? onError : self.defaultError );
            self.ajaxActivationCount += 1;
            self.$http.post(url,(data ? data : null )).then( (response)=>{
                if (self.ajaxActivationCount>0) {
                    self.ajaxActivationCount -= 1;
                } 
                if ( typeof response.data === "object" ) {

                    var are = self.defaultAjaxResponseEvaluation(response.data);
                    if (are.success) {
                        if ( typeof onSuccess === "function" ) {
                            onSuccess( are.data );
                        }
                    } else {
                        err( "Application",response.data );
                    }

                } else {
                    err( "Server",response.data );
                }
            }).catch((error)=>{
                if (self.ajaxActivationCount>0) {
                    self.ajaxActivationCount -= 1;
                } 
                err( "Network",error );
            });
        }
    }
}