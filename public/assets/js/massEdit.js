$(document).ready(function(){
    if(editMode === undefined) var editMode = false;
    if(checkedAll === undefined) var checkedAll = false;

    $('.get-edit-mode').on('click', function(){
        var label_title = $(this).data('gritter-title');
        var label_text;

        if(editMode == true) editMode = false; else editMode = true;

        if(editMode) label_text = $(this).data('gritter-text-on');
        else label_text = $(this).data('gritter-text-off');

        parent.jQuery.gritter.add({
            title: label_title,
            text:  label_text
        });

        // set mass-edit mode
        if(editMode) $("#mass-edit").val("1"); else $("#mass-edit").val("0");
        if(editMode) $(".mass-edit").addClass("mass-edit-on");
        else $(".mass-edit").removeClass("mass-edit-on");

        return false;
    });

    $('.mass-edit-button-select-all').on('click', function(){
        if(checkedAll == true) checkedAll = false; else checkedAll = true;
        if(checkedAll) $('.selectOne').iCheck('check');
        else $('.selectOne').iCheck('uncheck');
    });

    $('.mass-edit-checkbox').on('ifChecked', function(){
        $(this).val(1);
    });

    $('.mass-edit-checkbox').on('ifUnchecked', function(){
        $(this).val('0');
    });
});