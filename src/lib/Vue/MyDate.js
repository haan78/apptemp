
export default {
    toISO(d, withTime) {
        function pad(number) {
            if (number < 10) {
                return '0' + number;
            }
            return number;
        }
        var str = d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        if (withTime) {
            str += " " + pad(d.getHours()) + ":" + pad(d.getMinutes()) + ":" + pad(d.getSeconds());
        }
        return str;
    },
    toTurkish(date, withTime, separator) {
        var sd = "";
        var sep = (separator ? separator : ".");
        if (date instanceof Date) {
            sd = this.toISO(date, withTime);
        } else if (typeof date === "string") {
            sd = date;
        } else {
            return null;
        }
        var arr = sd.split(" ");
        var trString = arr[0].slice(8, 10);
        trString += sep + arr[0].slice(5, 7);
        trString += sep + arr[0].slice(0, 4);
        trString += (typeof arr[1] !== "undefined" ? (" " + arr[1]) : "");
        return trString;
    },
    toDate(sd) {
        if (typeof sd !== "string") return null;
        var values = [0, 0, 0, 0, 0, 0, 0];
        var _arr = sd.trim().split(" ");
        var darr = _arr[0].trim().split("-");
        if (typeof _arr[1] !== "undefined") {
            var tarr = _arr[1].trim().split(".")[0].trim().split(":");
            darr = darr.concat(tarr);
        }
        for (var i = 0; i < darr.length; i++) {
            var v = parseInt(darr[i].trim());
            values[i] = v;
        }

        return new Date(values[0], values[1] - 1, values[2], values[3], values[4], values[5], values[6]);
    },

    diffDays( date1,date2 ) {        
        return Math.floor((Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate()) - Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate()) ) /(1000 * 60 * 60 * 24));
    }
}