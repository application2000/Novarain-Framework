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
