var frontTabs = {
    $doc : $(document),
    config : {
        wrapper : '#frontTabs',
        tab : '#frontTabs .ft-tab',
        content : '#frontTabs .ft-content',
        fadeSpeed : 400,
        expires : 365 * 24 * 60 * 60
    },
    initialize : function() {
        this.$doc
            .on('click', this.config.tab, function(e){
                e.preventDefault();

                var index = $(this).index();
                $(this).addClass(frontTabsConfig.activeClass).siblings().removeClass(frontTabsConfig.activeClass);
                $(frontTabs.config.content).hide().eq(index).fadeIn(frontTabs.config.fadeSpeed);

                if (frontTabsConfig.rememberTab) {
                    frontTabs.Utils.deleteCookie('activeTab');
                    frontTabs.Utils.setCookie('activeTab', index, {expires : frontTabs.config.expires, path : window.location.pathname});
                }
            });
        this.afterLoad();
        return true;
    },
    afterLoad : function() {
        var index = this.Utils.getCookie('activeTab');
        console.log(typeof index);
        if (frontTabsConfig.rememberTab && (typeof index != 'undefined')) {
            $(frontTabs.config.tab).eq(index).trigger('click');
        } else {
            $(frontTabs.config.tab).eq(0).trigger('click');
        }
    },
    Utils : {
        setCookie : function(name, value, options) {
            options = options || {};

            var expires = options.expires;

            if (typeof expires == "number" && expires) {
                var d = new Date();
                d.setTime(d.getTime() + expires * 1000);
                expires = options.expires = d;
            }
            if (expires && expires.toUTCString) {
                options.expires = expires.toUTCString();
            }

            value = encodeURIComponent(value);

            var updatedCookie = name + "=" + value;

            for (var propName in options) {
                updatedCookie += "; " + propName;
                var propValue = options[propName];
                if (propValue !== true) {
                    updatedCookie += "=" + propValue;
                }
            }
            document.cookie = updatedCookie;
        },
        deleteCookie : function(name) {
            frontTabs.Utils.setCookie(name, "", {
                expires: -1
            })
        },
        getCookie : function(name) {
            var matches = document.cookie.match(new RegExp(
                "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
            ));
            return matches ? decodeURIComponent(matches[1]) : undefined;
        }
    }
};

$('document').ready(function(){
     frontTabs.initialize();
});