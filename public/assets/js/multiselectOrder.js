if (typeof jQuery === 'undefined') {
	throw new Error('multiselect requires jQuery');
}

;(function ($) {
	'use strict';

	var version = $.fn.jquery.split(' ')[0].split('.');

	if (version[0] < 2 && version[1] < 7) {
		throw new Error('multiselect requires jQuery version 1.7 or higher');
	}
})(jQuery);

;(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module depending on jQuery.
		define(['jquery'], factory);
	} else {
		// No AMD. Register plugin with global jQuery object.
		factory(jQuery);
	}
}(function ($) {
	'use strict';

	var MultiselectOrder = (function($) {

		/**	Multiselect object constructor
		 *
		 *	@class Multiselect
		 *	@constructor
		 **/
		function MultiselectOrder( $select, settings ) {
			this.field = $select;
			var id = this.field.prop('id');

			this.init(id);

			this.fieldSelected = this.field.find('#'+id);
			this.fieldAvalible = $('#' + id + '_optionsAvalible');

			this.actions = {
				buttonAddAll: $('#' + id + '_buttonAddAll'),
				buttonRemoveAll: $('#' + id + '_buttonRemoveAll'),
				buttonAdd:	$('#' + id + '_buttonAdd'),
				buttonRemove:	$('#' + id + '_buttonRemove'),
				buttonUp: $('#' + id + '_buttonUp'),
				buttonDown: $('#' + id + '_buttonDown'),
			};

			this.callbacks = settings;

			this.events( this.actions );
		}

		MultiselectOrder.prototype = {
			init: function(id)
			{
				var self = this;

				var contenerOptionsSelected = $('<div class="col-xs-5 multiselect_to"><span class="help-block">'+self.field.data('label-col-selected')+'</span></div>').append(self.field.prop('outerHTML'));

				var contenerOptionsAvalible = $('<div class="col-xs-5 multiselect_from"><span class="help-block">'+self.field.data('label-col-avalible')+'</span></div>');

				var selectField = $(self.field.prop('outerHTML'));
				selectField.html('');
				selectField.attr('id', id+'_optionsAvalible');
				selectField.attr('name', id+'_optionsAvalible');

				contenerOptionsAvalible.append(selectField);


				var buttonsMove = $('<div class="col-xs-1 multiselect_move" style="padding:0"><span class="help-block">&nbsp;</span></div>');
				buttonsMove.append($('<button type="button" id="'+id+'_buttonAddAll" class="btn btn-block"><i class="fa fa-forward"></i></button>'));
				buttonsMove.append($('<button type="button" id="'+id+'_buttonAdd" class="btn btn-block"><i class="fa fa-chevron-right"></i></button>'));
				buttonsMove.append($('<button type="button" id="'+id+'_buttonRemove" class="btn btn-block"><i class="fa fa-chevron-left"></i></button>'));
				buttonsMove.append($('<button type="button" id="'+id+'_buttonRemoveAll" class="btn btn-block"><i class="fa fa-backward"></i></button>'));

				var buttonsOrder = $('<div class="col-xs-1 multiselect_order" style="padding:0"><span class="help-block">&nbsp;</span></div>');
				buttonsOrder.append($('<button type="button" id="'+id+'_buttonUp" class="btn btn-block"><i class="fa fa-chevron-up"></i></button>'));
				buttonsOrder.append($('<button type="button" id="'+id+'_buttonDown" class="btn btn-block"><i class="fa fa-chevron-down"></i></button>'));

				var content = $('<div class="row multiselectOrderFiled"></div>');

				content.append(contenerOptionsAvalible);
				content.append(buttonsMove);
				content.append(contenerOptionsSelected);
				content.append(buttonsOrder);

				var optionsSelected = contenerOptionsSelected.find('option:not(:selected)');
				contenerOptionsAvalible.find('select').append(optionsSelected);

				self.field.replaceWith(content);
				self.field = content;
			},

			events: function( actions ) {
				var self = this;

				self.fieldAvalible.on('dblclick', 'option', function(e) {
					e.preventDefault();
					self.optionAdd(this, e);
				});

				self.fieldSelected.on('dblclick', 'option', function(e) {
					e.preventDefault();
					self.optionRemove(this, e);
				});

				// when submiting the parent form
				self.fieldSelected.closest('form').on('submit', function(e) {
					self.fieldAvalible.children().prop('selected', false);
					self.fieldSelected.children().prop('selected', true);
				});


				// dblclick support for IE
				if ( navigator.userAgent.match(/MSIE/i)  || navigator.userAgent.indexOf('Trident/') > 0 || navigator.userAgent.indexOf('Edge/') > 0) {
					self.fieldAvalible.dblclick(function(e) {
						actions.buttonAdd.trigger('click');
					});

					self.fieldSelected.dblclick(function(e) {
						actions.buttonRemove.trigger('click');
					});
				}

				actions.buttonAdd.on('click', function(e) {
					e.preventDefault();
					var options = self.fieldAvalible.find('option:selected');

					if ( options ) {
						self.optionAdd(options, e);
					}

					$(this).blur();
				});

				actions.buttonRemove.on('click', function(e) {
					e.preventDefault();
					var options = self.fieldSelected.find('option:selected');

					if ( options ) {
						self.optionRemove(options, e);
					}

					$(this).blur();
				});

				actions.buttonAddAll.on('click', function(e) {
					e.preventDefault();
					var options = self.fieldAvalible.find('option');

					if ( options ) {
						self.optionAdd(options, e);
					}

					$(this).blur();
				});

				actions.buttonRemoveAll.on('click', function(e) {
					e.preventDefault();

					var options = self.fieldSelected.find('option');

					if ( options ) {
						self.optionRemove(options, e);
					}

					$(this).blur();
				});

				actions.buttonUp.on('click', function(e) {
					e.preventDefault();
					var options = self.fieldSelected.find('option:selected');

					if ( options ) {
						self.moveUpSelectedOption(options, e);
					}

					$(this).blur();
				});

				actions.buttonDown.on('click', function(e) {
					e.preventDefault();
					var options = self.fieldSelected.find('option:selected');

					if ( options ) {
						self.moveDownSelectedOption(options, e);
					}

					$(this).blur();
				});

			},

			optionRemove: function( options, event, silent, skipStack ) {
				this.fieldAvalible.append(options);

				return this;
			},

			optionAdd: function( options, event, silent, skipStack ) {
				this.fieldSelected.append(options);

				return this;
			},


			moveUpSelectedOption: function(){
				var self = this;

				self.fieldSelected.find('option:selected').each( function() {
					var optionsAdd = self.fieldSelected.find('option');
					var newPos = optionsAdd.index(this) - 1;
					if (newPos > -1) {
						optionsAdd.eq(newPos).before(this);
					}
				});
			},

			moveDownSelectedOption: function(){
				var self = this;

				var countOptions = self.fieldSelected.find('option').size();
				var selectedCount = self.fieldSelected.find('option:selected').size();

				self.fieldSelected.find('option:selected').each( function() {
					var optionsAdd = self.fieldSelected.find('option');
					var newPos = optionsAdd.index(this) + selectedCount;
					if (newPos < countOptions) {
						optionsAdd.eq(newPos).after(this);
					}
				});
			},
		}

		return MultiselectOrder;
	})($);

	$.multiselectOrder = {
		defaults: {}
	};

	$.fn.multiselectOrder = function( options ) {
		return this.each(function() {
			var $this = $(this),
				data = $this.data();

			var settings = $.extend({}, $.multiselectOrder.defaults, data, options);

			return new MultiselectOrder($this, settings);
		});
	};
}));
