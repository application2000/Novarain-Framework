jQuery(function($) {
    $(".assignmentselection").each(function() {

        input = $(this).find("input:radio");
        input.on("change", function() {
          
            well = $(this).closest("div.well");
            obj = well.children(":not(.assignmentselection)") 

            well.removeClass("alert-success").removeClass("alert-error")

            if ($(this).val() > 0) {
                obj.slideDown("fast");
                class_ = ($(this).val() == "1") ? "alert-success" : "alert-error";
                well.addClass(class_);
            } else {
                obj.slideUp("fast");
                well.removeClass("alert-success").removeClass("alert-error");
            }      
        })

        input.filter(":checked").trigger("change"); 
    })
})