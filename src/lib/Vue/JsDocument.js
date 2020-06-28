export default (title,metaList) => {
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

    var mList = metaList;
    if ( !Array.isArray(metaList) ) {
        mList = [
            { httpEquiv:"Content-Type",content:"text/html" },
            { charset:"UTF-8"},
            { name:"viewport", content:"width=device-width, initial-scale=1.0"}
        ];
    }

    for (var k in mList) {
        var meta = document.createElement("meta");
        for(var op in mList[k]) {
            meta[op] = mList[k][op];
        }
        document.head.appendChild(meta);
    }

    try {
        var d = eval( decodeBase64( '%ENBEDED_DATA%' ) );
        window.sessionStorage.setItem("ENBEDED_DATA",d);
    } catch(e) {
        console.log(e);
        window.sessionStorage.setItem("ENBEDED_DATA",false);
    }
    
    document.title = (title ? title : "");
    document.body.innerHTML = "";
    var container = document.createElement("div");
    document.body.appendChild(container);
    return container;
}