// console.log (wpm_news_api_vars.msg1);

/* start jQuery loqic here */
(function($) {
    $(function(){
        /* add code here that needs to wait for page to be loaded */

        /*
        * close the alert info for good when the close button pressed
        */
        var alerthelp1status = getCookie("alerthelp1s");
        /* don't show the info1 pannel if already closed */
        if(alerthelp1status == "off") $(".alerthelp1").css("display","none");
        $(".wpminfo1").on("click", function(){
            /* check if cookie already set */
            if(alerthelp1status != "off") setCookie("alerthelp1s", "off", 10);
        });

        /**
         * Collapse all tabs when one of them clicked
         */
        $('.dropdown-toggle').on('click', function() {
            /**
             * check if clicked an opened tab
             * if yes close it
             */
            if($( this ).hasClass( 'selected' )) {
                $( this ).removeClass( 'selected' );
            } else {
                /**
                 * close all opened tabs and leave only the clicked open
                 */
                $('.collapse').removeClass( 'show' );
                $('.dropdown-toggle').removeClass( 'selected' );
                $(this).addClass('selected');
            }
        });

        /**
         * AJAX script for manual fetched News
         */
        $(".fetch").click(function(){ 
            var fetchLoadingTxt = $(".fetchloadingtxt").html();
            var helpUrl         = $(".helpurltxt").html();
            var apikey          = $(".apikey").html();
            var apiurl          = $(".apiurl").html();
            /**
             * show the fancy circle working
             */
            $("#div1").html("<div class=\"loader\"></div> " + fetchLoadingTxt);
            
            jQuery.ajax({
                type: "GET",
                url: ajaxurl,
                data: { action: 'my_action', apikey: apikey, apiurl: apiurl } // param: 'st1' - just for ex.
              }).done(
                function(result){
                    var news = JSON.stringify(result); 
                    // check for errors
                    var err = 0;
                    err = news.indexOf("error");
                    if( err > 0 ) { window.location.href = "?page=" + helpUrl }
                    else location.reload();
            });
        });

    });

    /* add code here that don't need to wait for page to be loaded */
    /*
     * Function to Set a Cookie
     */
    function setCookie(cname, cvalue, expyears) {
        var CookieDate = new Date;
        CookieDate.setFullYear(CookieDate.getFullYear() + expyears);
        var expires = "expires=" + CookieDate.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    /*
     * Function to Get a Cookie
     */
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
        }

        return "";
    }

})(jQuery);