var sortWidgets = function(){
    $(".placeholder_widgets").sortable({
        cancel: ".close-handler"
        ,handle: ".widget-move"
        ,placeholder: "ui-state-highlight"
        ,connectWith: "div.placeholder_widgets"
        ,items: ".widget-box"
    });

    var target_from = null;
    $(".placeholder_widgets").on('sortstart', function (event, ui) {
        ui.item.addClass('sort-handler');
        ui.item.css('width', ui.item.width());
        target_from = event.target;
        //$.each($(ui.item).find('textarea[aria-role="tinymce"]'), function () {
        //    removeTiny($(this));
        //});
    });

    $(".placeholder_widgets").on('sortstop', function (event, ui) {
        ui.item.removeClass('sort-handler');
        ui.item.css('width', '100%');
        //trigger_loaded(ui.item);
        //initLoaded.init(ui.item);
    });

    $(".placeholder_widgets").on('sortreceive', function (event, ui) {
        var target = event.target;
        var holder = $(target_from).data('placeholder');
        var new_holder = $(target).data('placeholder');
        var ms = +new Date;

        $(ui.item).find("textarea,input,select").each(function () {
            var regex = /(\w+)(\[\w+\])(\[\w+\])(\[\d+\])(\[\w+\])/;
            $(this).attr('name', $(this).attr('name').replace(regex, "$1" + "["+new_holder+"]" + "$3" + "["+ms+"]" + "$5"));
        });
    });

}


$(function() {
    sortWidgets();
});


$(document).ready(function(){
    $('body').on('change', '.task_widget_task_data', function(){
        var value = $(this).val();
        var widget = $(this).closest('.widget-box');

        if(value == 'next' || value == 'overdue') {
            widget.find('.task_widget_task_other_days').show();
        } else {
            widget.find('.task_widget_task_other_days').hide();
        }
    });
    $('.task_widget_task_data').trigger('change');

});