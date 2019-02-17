+function ($) {
    'use strict';

    // OVERBOX CLASS DEFINITION
    // ======================

    var Overbox = function (source, options) {
        this.options             = options;
        this.$source             = source;
        this.$body               = $(document.body);
        this.$element            = null;
        this.$dialog             = null;
        this.$backdrop           = null
        this.isShown             = null
        this.originalBodyPad     = null
        this.scrollbarWidth      = 0
        this.ignoreBackdropClick = false

        if (this.options.remote) {
            this.$element
                .find('.overbox-content')
                .load(this.options.remote, $.proxy(function () {
                    this.$element.trigger('loaded.bs.overbox')
                }, this))
        }
    }

    Overbox.TRANSITION_DURATION = 300
    Overbox.BACKDROP_TRANSITION_DURATION = 150

    Overbox.DEFAULTS = {
        type: 'inline'
        ,backdrop: true
        ,keyboard: true
        ,show: true
        ,displayHeader: true
        ,displayBody: true
        ,displayFooter: true
        ,closeButton: false
        ,bodyClass: ''
        ,closeButtonClass: 'btn btn-default btn-sm btn-close'
        ,buttonCloseText: 'Zamknij'
        ,buttonConfirmText: 'Tak, potwierdzam'
        ,buttonConfirmClass: 'btn btn-warning btn-sm'
        ,buttonConfirmAjax: false
        ,wrapStyle: ''
        ,url: ''
        ,method: 'get'
        ,data: {}
        ,confirmButton: false
        ,content: null
        ,contentHeader: null
        ,contentBody: null
        ,contentFooter: null
        ,headerTitle: null
    }


    Overbox.prototype.createElement = function () {
        this.$element = $('<div class="overbox-contener" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"><div class="overbox-backdrop" data-dismiss="overbox"></div><div class="overbox-dialog" role="document"><div class="overbox-wrap" style="'+this.options.wrapStyle+'"><div class="overbox-header"></div><div class="overbox-body '+this.options.bodyClass+'"></div><div class="overbox-footer"></div></div></div></div>');

        if(this.options.displayBody){
            if(this.options.type == 'inline'){
                this.loadInline();
            }else if(this.options.type == 'ajax'){
                this.loadAjax();
            }
        }

    }

    Overbox.prototype.loadInline = function () {
        var $elementHeader = $();
        var $elementBody = $('<div class="overbox-body">'+$(this.options.contentBody).prop('innerHTML')+'</div>');
        var $elementFooter = $();

        if(this.options.displayHeader){
            $elementHeader = $('<div class="overbox-header">'+
                '<button type="button" class="close" data-dismiss="overbox" aria-label="Close"><i class="fa fa-close"></i></button>'+
            '</div>');

            if(this.options.headerTitle){
                $elementHeader.append($('<h1 class="overbox-title">'+this.options.headerTitle+'</h1>'));
            }
        }


        if(this.options.displayFooter){
            $elementFooter = $('<div class="overbox-footer"></div>');

            if(this.options.confirmButton) {
                var href = this.options.url;

                if(href.length == 0){ href = this.$source.attr('href'); }
                if(href.length == 0){ href = '#'; }

                var buttonConfirmClass = this.options.buttonConfirmClass;
                if(this.options.buttonConfirmAjax){
                    buttonConfirmClass = buttonConfirmClass+' ajax';
                }

                $elementFooter.append($(
                    '<a class="btn btn-default btn-sm btn-close" data-dismiss="overbox">' + this.options.buttonCloseText + '</a>' +
                    '<a href="'+href+'" data-overbox-replace="1" class="' + buttonConfirmClass + '">' + this.options.buttonConfirmText + '</a>'
                ));
            }

            if(this.options.closeButton) {
                $elementFooter.append($(
                    '<a class="'+this.options.closeButtonClass+'" data-dismiss="overbox">' + this.options.buttonCloseText + '</a>'
                ));
            }

            if(this.options.contentFooter){
                $elementFooter.append($(this.options.contentFooter).prop('innerHTML'));
            }
        }


        this.$element.find('.overbox-header').replaceWith($elementHeader);
        this.$element.find('.overbox-body').replaceWith($elementBody);
        this.$element.find('.overbox-footer').replaceWith($elementFooter);
    }

    Overbox.prototype.loadAjax = function () {
        this.options.displayHeader = false;
        this.options.displayFooter = false;

        this.$element.find('.overbox-wrap').addClass('preloader');

        if(this.options.url.length == 0){ this.options.url =  this.$source.attr('href'); }
        if(this.options.url.length == 0){ this.options.url = '#'; }

        var that = this;

        $.ajax({
            url: that.options.url,
            method: that.options.method,
            data: that.options.data,
            success : function(responseTxt) {
                that.$element.find('.overbox-wrap').replaceWith($('<div class="overbox-wrap" style="'+that.options.wrapStyle+'"></div>').append($(responseTxt)));
            }
        });
    }

    Overbox.prototype.show = function () {
        this.createElement();

        var that = this;
        this.$element.one('show.bs.overbox', function (showEvent) {
            //if (showEvent.isDefaultPrevented()) return // only register focus restorer if overbox will actually get shown
            that.$element.one('hidden.bs.overbox', function (event, type, data) {
                that.$source.is(':visible') && that.$source.closest('.overbox-contener').trigger('focus');
                that.$source.trigger('hidden.overbox.source', [type, data]);
            })
        })

        var e    = $.Event('show.bs.overbox', { relatedTarget: this.$source })

        this.$dialog = this.$element.find('.overbox-dialog');

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented()) return

        this.isShown = true
        this.escape();

        this.$element.on('click.dismiss.bs.overbox', '[data-dismiss="overbox"]', $.proxy(this.hide, this));
        this.$element.on('hide.overbox', $.proxy(this.hide, this));
        this.$element.on('reload.overbox', $.proxy(this.loadAjax, this));

        that.$element.appendTo(that.$body) // don't move overbox dom position
        that.$element.trigger('focus').trigger(e)
    }

    Overbox.prototype.hide = function (e, type, data) {
        if (e) e.preventDefault()

        e = $.Event('hide.bs.overbox')

        this.$element.trigger(e)

        if (!this.isShown || e.isDefaultPrevented()) return

        this.isShown = false;

        this.escape();

        $(document).off('focusin.bs.overbox')

        this.$element
            .removeClass('in')
            .off('click.dismiss.bs.overbox')
            .off('mouseup.dismiss.bs.overbox')

        this.$dialog.off('mousedown.dismiss.bs.overbox')

        this.$element.hide()
        this.$element.trigger('hidden.bs.overbox', [type, data]);

        this.$element.remove();
    }
    
    function Plugin(options) {
        if(options){
            options = $.extend({}, Overbox.DEFAULTS, options);
        }else{
            options = $.extend({}, Overbox.DEFAULTS, this.data());
        }

        var data = new Overbox(this, options);
        data.show();
    }

    var old = $.fn.overbox

    $.fn.overbox             = Plugin;
    $.fn.overbox.Constructor = Overbox;
        
    Overbox.prototype.escape = function () {
        this.$element.on('keydown.dismiss.bs.overbox', $.proxy(function (e) {
            e.which == 27 && this.hide()
        }, this))
    }
    

    $(document).on('click', '[data-toggle="overbox"]', function(e){
        var $this  = $(this);
        var options = $.extend({}, Overbox.DEFAULTS, $this.data());
        if ($this.is('a')) e.preventDefault();

        var modal =  new Overbox($this, options);
        modal.show();
    })

}(jQuery);
