$(document).ready(function(){
    $('body').on('click', ".button-search", searchFilter);
    $('body').on('click', ".deleteWidget", deleteWidget);

    $('body').on('click', 'input[type=submit],button[type=submit]', setSubmitActionForm);
    $('body').on('submit', 'form', sendingForm);
    $('.form-filter').formFilterSearch();

    $('.smoothScroll').on('click', smoothScroll);

    // obsługa dodania nowego rekordu do listy select
    $('body').on('click', '.initAddNewRecord', function() {
        addNewRecordSelector = $($(this).data('target'));
    });

    $('#mass-items-all-page').on('click', massItemsCheckAllPage);
    $('#mass-items-all').on('click', massItemsCheckAll);

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle*="popover"]').popover();

    $("table.colResizable").each(function() {
        var $t = $(this);
        var disabledColumns = [];

        $(this).colResizable({
            liveDrag:true,
            disabledColumns: disabledColumns,
            postbackSafe:true,
            minWidth: 20
        });
    });

    $('body').on('bootstrap-ajax:success', '[data-query-save]', function(){
        $.smallBox({
            title : "Zapisano",
            content : "<i class='fa fa-filter'></i> <i>Filtr wyszukiwania został zaktualizowany.</i>",
            color : "#659265",
            iconSmall : " fa fa-check fa-2x fadeInRight animated",
            timeout : 4000
        });
    });


    $('body').on('change', 'select[name*="[id_bill]"]', function(){
        var dataRequest = {};
        dataRequest.id_bill = $(this).val();

        $('select[name*="[id_bill_item]"]').trigger('reload.list', [$(this), dataRequest]);
    });

    $('body').on('reload.list', 'select[name*="[id_bill_item]"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }
        var firstOptionValue = '';

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();

                $.each( response, function( key, value ) {
                    var newOption = new Option(value['name'],value['id_bill_item'], false, false);
                    $this.append(newOption);
                    if(!firstOptionValue.length){ firstOptionValue = value['id_bill_item']; }
                });
            },
            complete: function(){

                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.val(firstOptionValue).trigger('change');
            }
        });
    });

    $('body').on('change', 'select[name*="id_client"]', function(){
        var dataRequest = {};
        dataRequest.id_client = $(this).val();

        $('select[name*="[id_project]"]').trigger('reload', [$(this), dataRequest]);
        $('select[name*="[id_bill]"]').trigger('reload', [$(this), dataRequest]);
        $('select[name*="[id_agreement]"]').trigger('reload', [$(this), dataRequest]);
    });

    $('body').on('reload', 'select[name*="id_bill"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();

                var newOption = new Option("","", false, false);
                $this.append(newOption);

                $.each( response, function( key, value ) {
                    var name = value['number']+': '+value['name'];
                    var newOption = new Option(name,value['id_bill'], false, false);
                    $this.append(newOption);
                });
            },
            complete: function(){
                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.val("").trigger('change');
            }
        });
    });

    $('body').on('reload', 'select[name*="id_project"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();

                var newOption = new Option("","", false, false);
                $this.append(newOption);

                $.each( response, function( key, value ) {
                    var newOption = new Option(value['name'],value['id_project'], false, false);
                    $this.append(newOption);
                });
            },
            complete: function(){
                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.val("").trigger('change');
            }
        });
    });

    $('body').on('reload', 'select[name*="id_agreement"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();

                $.each( response, function( key, value ) {
                    var newOption = new Option(value['name'],value['id_agreement'], false, false);
                    $this.append(newOption);
                });
            },
            complete: function(){
                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.val("").trigger('change');
            }
        });
    });


    $('body').on('change', 'select[name*="[id_agreement]"]', function(){
        var dataRequest = {};
        dataRequest.id_agreement = $(this).val();

        $('select[name*="[id_agreement_item]"]').trigger('reload.list', [$(this), dataRequest]);
    });

    $('body').on('reload.list', 'select[name*="[id_agreement_item]"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();

                $.each( response, function( key, value ) {
                    var newOption = new Option(value['name'],value['id_agreement_item'], false, false);
                    $this.append(newOption);
                });
            },
            complete: function(){

                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.trigger('change');
            }
        });
    });

    $('body').on('change', 'select[name*="[id_product_brand]"]', function(){
        var dataRequest = {};
        dataRequest.id_product_brand = $(this).val();

        $('select[name*="[id_product_brand_series]"]').trigger('reload.list', [$(this), dataRequest]);
    });

    $('body').on('reload.list', 'select[name*="[id_product_brand_series]"]', function(event, $el, dataRequest){
        var $this = $(this);
        if(!dataRequest){ dataRequest = {}; }

        $.ajax({
            url: $(this).data('url-load-list'),
            method: 'post',
            data: dataRequest,
            dataType: "json",
            beforeSend: function(){
                $('body').addClass('loading');
                $this.prop("disabled", true);
            },
            success: function(response){
                $this.find('option').remove();
                $.each( response, function( key, value ) {
                    var newOption = new Option(value['name'],value['id_product_brand_series'], false, false);
                    $this.append(newOption);
                });
            },
            complete: function(){
                $this.prop("disabled", false);
                $('body').removeClass('loading');
                $this.trigger('change');
            }
        });
    });

});

var addLoadSpinner = function(element){
    element.prepend('<div class="load-spinner"><svg class="spinner" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg></div>');
}

var smoothScroll = function()
{
    var href = $(this).attr('href').split('#');
    if(href[1]){
        var target = $("a[name='"+href[1]+"']");
        if(target){
            var top = target.offset().top - 100;
            if(top < 0){ top = 0; }

            $('body, html').animate({scrollTop:top}, 800);
            history.pushState({}, $(this).html(), "#"+href[1]);
            return false;
        }
    }

    return true;
}

var massItemsCheckAllPage = function(){
    $('input[type=checkbox][name^="mass-items["]').prop('checked', $(this).prop('checked'));
};

var massItemsCheckAll = function(){
    var $t = $(this);
    $('input[type=checkbox][name^="mass-items["]').prop('checked', $t.prop('checked'));

    var allPage = $('#mass-items-all-page');
    if(allPage){
        if($t.prop('checked')){
            $('.dt-toolbar-masspager').show();
        }else{
            $('.dt-toolbar-masspager').hide();
            allPage.prop('checked', false);
        }
    }
};

var setSubmitActionForm = function(){
    var inputAction = $(this).closest('form').find('input[type=hidden][name*="submit_action"]');
    if(inputAction){
        inputAction.val($(this).data('action'));
    }
}

// obsługa dodania nowego rekordu do listy select
var addNewRecordSelector;
function addNewSelectElement(id, name, element)
{
    var newOption = new Option(name,id, false, true);
    addNewRecordSelector.append(newOption);
    addNewRecordSelector.trigger('change');
}


var sendingForm = function()
{
    var button = $(this).find('input[type=submit],button[type=submit]');
    button.attr('disabled', 'disabled')
    button.html("<i class='fa fa-spinner fa-spin'></i> Wysyłam...");
};

var searchFilter = function() {
    var display = $('.filter-search').css('display');
    var icon = $(this).find('.search-direction');
    if(display == 'block'){
        $('.filter-search').hide();
        icon.removeClass('fa-minus-circle txt-color-red');
        icon.addClass('fa-plus-circle txt-color-green');
    }else{
        $('.filter-search').show();
        icon.removeClass('fa-plus-circle txt-color-green');
        icon.addClass('fa-minus-circle txt-color-red');
    }
};

var deleteWidget = function () {
    if(confirm('Czy na peweno chcesz usunąć element?')){
        $(this).parent().parent().remove();
    }
    return false;
};


/* ========================================================================
 * RiseNet: Obsługa masowych akcji
 * ======================================================================== */

$('#mass-action-btn').on('click', function(e){
    var $this = $(this);
    var massAction = $('#mass-action-select');
    var massActionVal = massAction.val();
    var massActionOptions = massAction.data('massOptions');
    var massActionData = {massItemAllPage: 0, massItems: '', countItems: 0};
    var massActionSet = false;
    var overboxOptions = {};

    // sprawdzamy czy została wybrana akcja
    if(massActionVal == ''){
        overboxOptions.headerTitle = 'Error';
        overboxOptions.contentBody = '#massActionNotSelect';
        overboxOptions.displayFooter = true;
        overboxOptions.closeButton = true;
        overboxOptions.closeButtonClass = 'btn btn-danger btn-sm';
        massActionSet = true;
    }

    // pobieramy zaznaczone rekordy
    if(!massActionSet){
        var allPage = $('#mass-items-all-page');
        if(allPage && allPage.prop('checked')){
            massActionData.massItemAllPage = 1;
        }

        if(massActionData.massItemAllPage){
            massActionData.countItems = allPage.data('count');
        }else{
            var massItems = $('input[type=checkbox][name^="mass-items["]');
            var massItemsElement = [];
            $.each(massItems, function(){
                if($(this).prop('checked')){
                    massItemsElement.push($(this).data('id'));
                }
            });
            massActionData.countItems = massItemsElement.length;
            massActionData.massItems = massItemsElement.join();
        }
    }

    // jeśli nie było zaznaczonych rekordów - komunikat błędu
    if(!massActionSet && !massActionData.massItemAllPage && !massActionData.massItems.length){
        overboxOptions.headerTitle = 'Error';
        overboxOptions.contentBody = '#massActionNotItems';
        overboxOptions.displayFooter = true;
        overboxOptions.closeButton = true;
        overboxOptions.closeButtonClass = 'btn btn-danger btn-sm';
        massActionSet = true;
    }

    // ustawiamy opcje dla inicjacji overboxa
    if(!massActionSet){

        // ujednolicenie danych z formularzami
        massActionData.value = massActionData.massItems
        massActionData.all = massActionData.massItemAllPage;
        massActionData.count = massActionData.countItems;

        overboxOptions.data = massActionData;
        if(massActionOverboxOptions[massActionVal]){
            overboxOptions = $.extend(overboxOptions, massActionOverboxOptions[massActionVal]);
        }else{
            overboxOptions.type = 'ajax';
            overboxOptions.method = 'post';
            overboxOptions.url = massActionOptions[massActionVal].url;
        }


        // jeśli jest gotowy content/formularz szukamy i podstawiany do forma zaznaczone parametry
        if(overboxOptions.contentBody){
            var contentBody = $(overboxOptions.contentBody);
            if(contentBody){
                var inputMassItems =  contentBody.find('input[type=hidden][name="massItems"]');
                var inputMassItemAllPage =  contentBody.find('input[type=hidden][name="massItemAllPage"]');
                var massOptionsCount = contentBody.find('.mass-options_count');

                if(inputMassItems){ inputMassItems.val(massActionData.massItems); }
                if(inputMassItemAllPage){ inputMassItemAllPage.val(massActionData.massItemAllPage); }
                if(massOptionsCount){ massOptionsCount.html(massActionData.countItems); }
            }
        }

    }

    // inicjacja overboxa
    $this.overbox(overboxOptions);
    e.preventDefault();
});
/* === OBSŁUGA MASOWYCH AKCJI =============================================


/* ========================================================================
 * RiseNet: FormFilterSearch
 * ======================================================================== */

+function ($) {
    'use strict';

    var FormFilterSearch = function (element, options) {
        this.$form  = $(element);
        this.$fieldOn = this.$form.find('.form_field_on');
        this.$fieldsAdd = this.$form.find('.form_field_add');
        this.formBelongTo = this.$form.data('belong-to');
        this.allFiltersField = this.$fieldsAdd.data('filter-fields');
        this.$fieldsAddRow = this.$fieldsAdd.closest('.form-group');
        var that = this;

        this.$form.on('click', '.add-filter-field-button', $.proxy(this.addField, this));
        this.$form.on('click', '.remove-filter-field', function(){
            that.removeField(that, $(this));
        });
        this.$fieldOn.on('change', $.proxy(this.refreshAddOptions, this));
        this.$form.on('click', '.filter-field-search-type a', function(){
            that.setFieldSearchType($(this));
        });

    };

    FormFilterSearch.prototype.addField = function () {
        var newFieldsOn = this.$fieldOn.val();
        if(!newFieldsOn){ newFieldsOn = []; }
        newFieldsOn.push(this.$fieldsAdd.val());

        this.$fieldOn.val(newFieldsOn);
        $('#'+this.formBelongTo+'-'+this.$fieldsAdd.val()).show();

        this.$fieldOn.trigger('change');
    };

    FormFilterSearch.prototype.setFieldSearchType = function ($this) {
        var value = $this.data('value');
        var inputGroup = $this.closest('.input-group-btn');

        inputGroup.find('.search-value').html($this.html());
        inputGroup.find('input[type=hidden]').val(value);
    };

    FormFilterSearch.prototype.removeField = function ($form, $this) {
        var fieldRow = $this.closest('.form-group');
        var fieldName = fieldRow.data('field-name');
        var field = fieldRow.find('#'+this.formBelongTo+ '-'+fieldName);

        if(field){ field.val('').trigger('change'); }

        var fieldOnVal = $form.$fieldOn.val();
        if(!fieldOnVal){ fieldOnVal = []; }

        fieldOnVal = jQuery.grep(fieldOnVal, function(value) {
            return value != fieldName;
        });

        $form.$fieldOn.val(fieldOnVal);
        fieldRow.hide();
        $form.$fieldOn.trigger('change');
    };

    FormFilterSearch.prototype.refreshAddOptions = function (e) {
        var selectedFields = this.$fieldOn.val();
        var availableFields = [];
        var $that = this;

        $.each(this.allFiltersField, function(i,e) {
            if ($.inArray(i, selectedFields) == -1) {
                availableFields.push(i);
            }
        });

        this.$fieldsAdd.html('');
        if(availableFields.length){
            this.$fieldsAddRow.show();
            $.each(availableFields, function( index, value ) {
                $that.$fieldsAdd.append('<option value="'+value+'">'+$that.allFiltersField[value]+'</option>');
            });
        }else{
            this.$fieldsAddRow.hide();
        }
    };


    // formFilterSearch PLUGIN DEFINITION
    // =======================
    var old = $.fn.formFilterSearch

    $.fn.formFilterSearch = function (option) {
        return this.each(function () {
            var $this = $(this);
            var data = new FormFilterSearch(this);

            if (typeof option == 'string') data[option].call($this)
        })
    };

    $.fn.formFilterSearch.Constructor = FormFilterSearch

    // SEARCH NO CONFLICT
    // =================

    $.fn.formFilterSearch.noConflict = function () {
        $.fn.formFilterSearch = old;
        return this
    }

}(jQuery);


$(document).ready(function(){



    var formQuickSearch = $('form[data-search-json]');

    formQuickSearch.on('submit', function(){
        return false;
    });

    formQuickSearch.on('keyup click', '#search-fld', function() {
        var that = $(this);
        var value = that.val();
        var searchResult = $('.header-search .search-result');

        if(value.length < 3) {
            searchResult.removeClass('loading').removeClass('loaded');
            searchResult.find('.search-body').html('');
        }
        delay(function(){
            if(value.length >= 3){
                $.ajax({
                    url: formQuickSearch.attr('action'),
                    method: 'post',
                    data: {'search': value},
                    beforeSend: function(){
                        searchResult.removeClass('loaded');
                        searchResult.addClass('loading');
                    },
                    success : function(response) {
                        searchResult.removeClass('loading').addClass('loaded');
                        searchResult.find('.search-body').html(response);
                    }
                });
            }
        }, 500);
    });
});

var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();