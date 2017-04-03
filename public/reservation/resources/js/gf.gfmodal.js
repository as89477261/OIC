(function($)
{
	var defaults = {
		/**
		 * Content of the window: HTML or jQuery object
		 * @var string|jQuery
		 */
		content: '',

		/**
		 * Url for loading content
		 * @var string|boolean
		 */
		url: '',

		/**
		 * Title of the window, or false for none
		 * @var string|boolean
		 */
		title: false,

		/**
		 * Initial content width, or false for fluid size
		 * @var int|boolean
		 */
		width: false,

		/**
		 * Initial content height, or false for fluid size
		 * @var int|boolean
		 */
		height: false,

		/**
		 * Enable window resizing (only work if border is true)
		 * @var boolean
		 */
		resizable: false,

		/**
		 * Wether or not to display the close window right tab
		 * @var boolean
		 */
		closeButton: false,

		/**
		 * Default close button
		 * @var object
		 */
		buttons: {
			close: {
				title: 'Close',
				handler: function(win) {
					win.closeModal();
				}
			}
		}
	};

	var methods = {
		_init: function(options)
		{
			return this.each(function()
			{
				var settings = $.extend({}, defaults, options);
				
				$(this).bind('click', function()
				{
					$.modal(settings);
				});
			});
		}
	};

	$.fn.GfModal = function(method)
	{
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if ('object' === typeof method || !method) {
			return methods._init.apply(this, arguments);
		} else {
			$.error('Method ' +  method + ' does not exist on jQuery.GfModal');
		}
	}

	$.extend({
		GfModal: function(options) {
			var settings = $.extend(defaults, options);
			$.modal(settings);
		}
	});
})(jQuery);

function openFormModal(url, title, callback)
{
	var settings = {
		url: url,
		title: title,
		buttons: {
			ok: {
				title: 'OK',
				handler: function(win) {
					$.ajax({
						type: 'POST',
						data: $(win).find('form').serialize(),
						url: $(win).find('form').get(0).action,
						success: function(jqXHR) {
							if ($.isFunction(callback))
							{
								callback.call(win, true, jqXHR);
							}
						},
						error: function(jqXHR) {
							if ($.isFunction(callback))
							{
								alert(jqXHR.statusText);
							}
						}
					});
				}
			},
			cancel: {
				title: 'Cancel',
				handler: function(win) {
					if ($.isFunction(callback))
					{
						callback.call(win, false);
					}
				}
			}
		}
	}

	$.GfModal(settings);
}