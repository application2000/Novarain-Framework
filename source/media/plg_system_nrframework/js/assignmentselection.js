jQuery(function($) {
    $(".assignmentselection").each(function() {

        var input = $(this);
        var container = $(this).closest(".control-group").parent();

        // ACF Fix
        if (!container.hasClass("assign")) {
            container.addClass("assign");
        }

        container.children().not(":first-child").wrapAll('<div class="assign-options">');
        container.children().filter(":first-child").addClass("assignmentselection");

        var options = container.find(".assign-options");
        
        input.on("change", function() {

            container.removeClass("alert-success").removeClass("alert-error")

            if ($(this).val() > 0) {
                options.slideDown("fast");
                class_ = ($(this).val() == "1") ? "alert-success" : "alert-error";
                container.addClass(class_);
            } else {
                options.slideUp("fast");
                container.removeClass("alert-success").removeClass("alert-error");
            }      
        })

        input.trigger("change"); 
    })
})