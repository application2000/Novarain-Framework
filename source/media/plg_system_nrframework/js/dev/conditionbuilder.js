jQuery(function($) {

    var app_ajax_url = "?option=com_ajax&format=raw&plugin=nrframework&task=ConditionBuilder"
        system_url = $(".cb").attr("data-root")
        token = $(".cb").attr("data-token");

    // Setup Events
    $(document).on("click", ".addCondition", function(event) {
        event.preventDefault();
        
        var $app = $(".cb")
            $element = $(event.target).closest(".cb-item")
            $controlGroup = $element.closest(".cb-group")

        if ($controlGroup.length) {
            groupKey = parseInt($controlGroup.attr("data-key"));
            conditionKey = parseInt($controlGroup.attr("data-max-index")) + 1;
        } else {
            groupKey = parseInt($app.attr("data-max-index")) + 1;
            conditionKey = 0;
        }

        addCondition($element, $app.data("control-group"), groupKey, conditionKey);
    })

    $(document).on("click", ".removeCondition", function(event) {
        event.preventDefault();
        var $el = $(event.target).closest(".cb-item");
        deleteCondition($el);
    })

    $(document).on("change", ".condition_selector", function(event) {
        event.preventDefault();

        var $el = $(event.target).closest(".cb-item")
            condition_name = $(this).val();

        loadConditionSettings($el, condition_name);
    });

    $(document).on("afterConditionSettings", function(event, $el, condition_name) {
        loadConditionAssets(condition_name, $el);
    });

    // Methods
    function addCondition(element, controlGroup, groupKey, conditionKey) {
        var addNewGroup = (element.closest(".cb-group").length == 0);
        var options = {
            controlgroup: controlGroup,
            groupKey: groupKey,
            conditionKey: conditionKey,
            conditionsList: $(".cb").attr("data-conditionslist")
        };

        call('add', options, function(response) {
            if (addNewGroup) {
                $('<div/>')
                    .addClass("cb-group")
                    .attr("data-key", options.groupKey)
                    .attr("data-max-index", 0)
                    .appendTo(".cb-items")
                    .html(response);

                $(".cb").attr("data-max-index", options.groupKey);
            } else {
                element.after(response);
                element.closest(".cb-group").attr("data-max-index", conditionKey);
            }
        });
    }

    function deleteCondition(element) {
        if ($(".cb-item").length == 1) {
            alert("You can't remove the last item");
            return;
        }

        $group = element.closest(".cb-group");

        element.remove();

        if ($group.children().length == 0) {
            $group.remove();
        }
    }

    function loadConditionSettings(element, condition_name) {
        var $app = element.closest(".cb")
            groupKey = parseInt(element.closest(".cb-group").attr("data-key"))
            conditionKey = parseInt(element.closest(".cb-item").attr("data-key"))
            options = {
                controlgroup: $app.attr("data-control-group") + "[" + groupKey + "][" + conditionKey + "]",
                name: condition_name
            };

        call('options', options, function(response) {
            element.find(".cb-item-content").html(response);
            $(document).trigger("afterConditionSettings", [element, condition_name]);
        })
    }

    /**
     * Handles the AJAX requests
     * 
     * @param   string    endpoint 
     * @param   object    payload 
     * @param   function  callback 
     */
    function call(endpoint, payload, callback) {
        $.ajax({ 
            url: system_url + app_ajax_url + "&subtask=" + endpoint + "&" + token + "=1",
            data: payload,
            success: function(response) {
                callback(response);
                
                // re-initialize chosen and tooltips
                $(".cb")
                    .find("select").chosen({"disable_search_threshold":10})
                    .end()
                    .find(".hasPopover").popover({"html": true, "trigger": "hover focus", "container": "body"});

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            },
        });
    }

    function loadConditionAssets(condition_name, $el) {

        // All assets must be loaded from the front-end
        var base_url = system_url.replace("/administrator", "");

        switch (condition_name) {
            case 'usergroup':
            case 'menu':
            case 'category':
            case 'k2category':
                NRHelper.loadStyleSheet(base_url + "/media/plg_system_nrframework/css/treeselect.css");
                NRHelper.loadScript(base_url + "/media/plg_system_nrframework/js/treeselect.js", function() {
                    NRTreeselect.init($el.get(0));
                });
                break;
            case 'date':
                NRHelper.loadStyleSheet(base_url + "/media/system/css/fields/calendar.css");
                NRHelper.loadScript(base_url + "/media/system/js/fields/calendar-locales/en.js");
                NRHelper.loadScript(base_url + "/media/system/js/fields/calendar-locales/date/gregorian/date-helper.min.js");
                NRHelper.loadScript(base_url + "/media/system/js/fields/calendar.min.js", function() {
                    var elements = $el.get(0).querySelectorAll(".field-calendar");
                    for (i = 0; i < elements.length; i++) {
                        JoomlaCalendar.init(elements[i]);
                    }
                });
                break;
            case 'time':
                NRHelper.loadStyleSheet(base_url + "/media/plg_system_nrframework/css/jquery-clockpicker.min.css");
                NRHelper.loadScript(base_url + "/media/plg_system_nrframework/js/jquery-clockpicker.min.js", function() {
                    $el.find(".clockpicker").clockpicker();
                });
                break;
        }

        // Hack to re-initialize showOn function
        if (condition_name != 'date') {
            $(document).trigger("subform-row-add");
        }
    }
})