import MyDate from "./MyDate"
export default {
    install(Vue,name ="$date") {
        Object.defineProperty(Vue.prototype,name,{ value:MyDate })
    }
}