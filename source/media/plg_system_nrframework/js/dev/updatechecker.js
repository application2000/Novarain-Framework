/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

!(function() {
    'use strict';

    // 1 second after page load check for updates
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() { updateChecker(); }, 1000); 
    });

    // Ask framework to check for extension updates
    function updateChecker() {
        var el        = document.querySelector('.nr_updatechecker');
        var params    = el.dataset;
        var endpoint  = '?option=com_ajax&format=raw&plugin=nrframework&task=updatenotification&element=' + params.element + "&" + params.token + "=1";

        // Setup AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', params.base + endpoint);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300 && xhr.response.indexOf("nr-updatechecker") > -1) {
                el.innerHTML = xhr.response;
            }
        };
            
        // Check for updates
        xhr.send();
    }
})();