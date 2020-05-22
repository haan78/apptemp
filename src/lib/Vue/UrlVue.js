import Url from "./Url"
export default {
    install(Vue,name ="$url") {
        Object.defineProperty(Vue.prototype,name,{ value:Url })
    }
}