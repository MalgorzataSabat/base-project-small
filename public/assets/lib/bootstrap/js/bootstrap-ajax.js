/* ====================================================================
 * bootstrap-ajax.js
 * ==================================================================== */

!function ($) {

    'use strict';

    var Ajax = function () {}

    Ajax.prototype.click = function (e) {
        var $this = $(this)
            , url = $this.attr('href')
            , method = $this.attr('data-method')
            , dataParams = $this.attr('data-params')

        if (!method) {
            method = 'get'
        }

        if (dataParams) {
            dataParams = JSON.parse(dataParams);
        }else{
            dataParams = [];
        }

        e.preventDefault();

        $.ajax({
            url: url,
            type: method,
            data: dataParams,
            dataType: 'json',
            beforeSend: function(){
                $('body').addClass('loading');
                $this.trigger('bootstrap-ajax:before-send', [$this]);
            },
            statusCode: {
                200: function(data) {
                    processData(data, $this)
                },
                500: function() {
                    $this.trigger('bootstrap-ajax:error', [$this, 500])
                },
                404: function() {
                    $this.trigger('bootstrap-ajax:error', [$this, 404])
                }
            },
            complete : function(response) {
                $('body').removeClass('loading');
            },
        })
    }

    Ajax.prototype.submit = function (e) {
        var $this = $(this)
            , url = $this.attr('action')
            , method = $this.attr('method')
            , data = new FormData($this[0])
            , button = $this.find('input[type=submit],button[type=submit]')

        e.preventDefault()

        $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $this.trigger('bootstrap-ajax:before-send', [$this]);
                $('body').addClass('loading');
            },
            statusCode: {
                200: function(data) {
                    //$this.find('input[type=text],textarea').val('');
                    processData(data, $this);
                },
                500: function() {
                    $this.trigger('bootstrap-ajax:error', [$this, 500])
                },
                404: function() {
                    $this.trigger('bootstrap-ajax:error', [$this, 404])
                }
            },
            complete: function() {
                $('body').removeClass('loading');
                button.removeAttr('disabled')
            }
        })
    }

    Ajax.prototype.cancel = function(e) {
        var $this = $(this)
            , selector = $this.attr('data-cancel-closest')

        e.preventDefault()

        $this.closest(selector).remove()
    }

    function processData(data, $el) {
        if($el.attr('data-overbox-replace')){
            $(".overbox-contener:last").find('.overbox-wrap').html(data.responseText); // Robert: usunięto 2017-05-19 -> ładowanie części contentu ajaxowo w overboxie
        }

        $el.trigger('bootstrap-ajax:success', [data, $el]);
        $('body').removeClass('loading');
    }

    $(function () {
        $('body').on('click', 'a.ajax', Ajax.prototype.click)
        $('body').on('submit', 'form.ajax', Ajax.prototype.submit)
        $('body').on('click', 'a[data-cancel-closest]', Ajax.prototype.cancel)
    })
}(window.jQuery);