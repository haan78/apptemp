import IsString from "./IsString";
export default {
    install(Vue,name ="$is") {
        Object.defineProperty(Vue.prototype,name,{ value:IsString })
    }
}