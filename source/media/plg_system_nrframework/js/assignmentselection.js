jQuery(function($) {
    $(".assignmentselection").each(function() {

        var input = $(this);
        var container = $(this).closest(".control-group").parent();

        // Backwards compatibility fix
        fix = input.parent().hasClass("well-assign");
        if (fix) {
            container = $(this).closest(".well-assign");
            container.children(":last-child").addClass("assign-options");
            container.children().not(":last-child").wrapAll('<div class="control-group assignmentselection">');
            container.find(".assignmentselection label").wrap("<div class=\"control-label\"></div>")
            container.find(".assignmentselection .control-label").css({
                'padding-right' : '20px'
            })
        } else {
            container.children().not(":first-child").wrapAll('<div class="assign-options">');
            container.children().filter(":first-child").addClass("assignmentselection");
        }

        // Add missing class
        if (!container.hasClass("assign")) {
            container.addClass("assign");
        }

        // Setup Events
        input.on("change", function() {
            container.removeClass("alert-success").removeClass("alert-error");

            if ($(this).val() > 0) {
                container.find(".assign-options").slideDown("fast");
                class_ = ($(this).val() == "1") ? "alert-success" : "alert-error";
                container.addClass(class_);
            } else {
                container.find(".assign-options").slideUp("fast");
                container.removeClass("alert-success").removeClass("alert-error");
            }      
        }).trigger("change"); 
    })
})