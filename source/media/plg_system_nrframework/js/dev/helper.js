/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

!(function(window, document) {
    'use strict';

    var helper = {};

    /**
     * Serialize Form Data
     * 
     * Credits to: https://codepen.io/influxweb/pen/ozoYqa
     * 
     * @param {selector} form 
     */
    helper.serializeform = function(form) {
        var field,
            l,
            s = [];
    
        if (typeof form == 'object' && form.nodeName == "FORM") {
            var len = form.elements.length;
    
            for (var i = 0; i < len; i++) {
                field = form.elements[i];
                if (field.name && !field.disabled && field.type != 'button' && field.type != 'file' && field.type != 'hidden' && field.type != 'reset' && field.type != 'submit') {
                    if (field.type == 'select-multiple') {
                        l = form.elements[i].options.length;
    
                        for (var j = 0; j < l; j++) {
                            if (field.options[j].selected) {
                                s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                            }
                        }
                    }
                    else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                        s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                    }
                }
            }
        }

        return s.join('&').replace(/%20/g, '+');
    };

    /**
     * Make the first letter of a string uppercase
     * 
     * @param string string
     */
    helper.capitalizeFirstLetter = function(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    /**
     * Load an external script file
     * 
     * @param   string      url         The script file URL
     * @param   function    callback    The callback function to call once the script is loaded 
     */
    helper.loadScript = function(url, callback) {

        // Initially, check if script is already loaded.
        var alreadyLoaded = document.querySelectorAll('script[src="' + url + '"]').length > 0;

        if (alreadyLoaded) {
            if (typeof callback === "function") {
                callback(); 
            }

            return;
        }

        var script = document.createElement('script');
        script.src = url;

        document.head.appendChild(script);

        if (typeof callback === "function") {
            script.onload = function () {
                callback(); 
            };
        }
    };

    /**
     * Load an external stylesheet file
     * 
     * @param   string      url         The stylesheet URL
     * @param   function    callback    The callback function to call once the script is loaded 
     */
    helper.loadStyleSheet = function(url, callback) {

        // Initially, check if stylesheet is already loaded.
        var alreadyLoaded = document.querySelectorAll('link[href="' + url + '"]').length > 0;

        if (alreadyLoaded) {
            if (typeof callback === "function") {
                callback(); 
            }

            return;
        }

        var link  = document.createElement("link");
        link.href = url;
        link.type = "text/css";
        link.rel  = "stylesheet";
        
        document.getElementsByTagName("head")[0].prepend(link);
    }
    
    window.NRHelper = helper;

})(window, document);
