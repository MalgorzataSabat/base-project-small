$(function() {
    $('body').on('change', '.addressAjax', autocompleteData);
});

var autocompleteData = function() {
    var me = $(this);
    var nextSelectorClass = me.data('address-selector');
    var nextSelector = $(nextSelectorClass);

    var requestData = {q: me.val()};
    parentSelector = $(me.data('address-selector-parent'));

    while(parentSelector.length > 0){
        requestData[parentSelector.data('name')] = parentSelector.val();
        parentSelector = $(parentSelector.data('address-selector-parent'));
    }

    $.ajax({
        url: me.data('url')
        ,dataType: 'json'
        ,data: requestData
        ,beforeSend: function() {
            nextSelector.closest('.loader-place').addClass('preloader-input');
            $('option', nextSelector).remove();
            nextSelector.append('<option label="" value=""></option>');

            nextSelector.select2({
                allowClear: true,
                width: '100%'
            });
        }
        ,complete: function() {
            nextSelector.closest('.loader-place').removeClass('preloader-input');
        }
        ,success: function(response) {
            if(nextSelector.is("select")){
                var placeholder = nextSelector.attr('placeholder');

                $.each(response.list, function(i, v) {
                    nextSelector.append('<option label="'+ v +'" value="'+ i +'">'+v+'</option>');
                });
                nextSelector.select2(
                    {
                        allowClear: true,
                        placeholder: placeholder,
                        width: '100%'
                    }
                );
            }

        }
    });
};