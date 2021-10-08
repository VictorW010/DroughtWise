! function (e) {
    var n = {};

    function t(r) {
        if (n[r]) return n[r].exports;
        var o = n[r] = {
            i: r,
            l: !1,
            exports: {}
        };
        return e[r].call(o.exports, o, o.exports, t), o.l = !0, o.exports
    }
    t.m = e, t.c = n, t.d = function (e, n, r) {
        t.o(e, n) || Object.defineProperty(e, n, {
            enumerable: !0,
            get: r
        })
    }, t.r = function (e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }, t.t = function (e, n) {
        if (1 & n && (e = t(e)), 8 & n) return e;
        if (4 & n && "object" == typeof e && e && e.__esModule) return e;
        var r = Object.create(null);
        if (t.r(r), Object.defineProperty(r, "default", {
                enumerable: !0,
                value: e
            }), 2 & n && "string" != typeof e)
            for (var o in e) t.d(r, o, function (n) {
                return e[n]
            }.bind(null, o));
        return r
    }, t.n = function (e) {
        var n = e && e.__esModule ? function () {
            return e.default
        } : function () {
            return e
        };
        return t.d(n, "a", n), n
    }, t.o = function (e, n) {
        return Object.prototype.hasOwnProperty.call(e, n)
    }, t.p = "", t(t.s = 0)
}([function (e, n) {
    console.log(wpm_news_api_vars.msg1),
        function (e) {
            e(function () {
                var n = function (e) {
                    for (var n = e + "=", t = decodeURIComponent(document.cookie).split(";"), r = 0; r < t.length; r++) {
                        for (var o = t[r];
                            " " == o.charAt(0);) o = o.substring(1);
                        if (0 == o.indexOf(n)) return o.substring(n.length, o.length)
                    }
                    return ""
                }("alerthelp1s");
                "off" == n && e(".alerthelp1").css("display", "none"), e(".wpminfo1").on("click", function () {
                    "off" != n && function (e, n, t) {
                        var r = new Date;
                        r.setFullYear(r.getFullYear() + t);
                        var o = "expires=" + r.toUTCString();
                        document.cookie = e + "=" + n + ";" + o + ";path=/"
                    }("alerthelp1s", "off", 10)
                })
            })
        }(jQuery)
}]);