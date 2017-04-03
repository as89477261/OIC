(function($)
{
	var methods = {

		// Constructor method.
		_init: function(options)
		{
			var defaults = {
				url: '',
				params: '',
				loaded: function() {}
			}

			return this.each(function()
			{
				var settings = $.extend(defaults, options);
				$.data(this, 'GfTree', settings);
				methods._load.call(this, settings);
			});
		},
		
		// Load child node.
		_load: function(options)
		{
			var container = $(this);

			$.ajax({
				type: 'POST',
				url: options.url,
				data: options.params,
				beforeSend: function() {
					container.find('ul').remove();
					container.append('<ul><li><span class="loading">Loading...</span></li></ul>');
				},
				success: function(response) {
					container.find('ul').remove();

					if ('' != $.trim(response))
					{
						container.append(response).find('ul span.toggle').bind('click', options, methods.click);

						if (options.loaded)
						{
							options.loaded.call(container);
						}
					}
					else
					{
						container.append('<ul><li><span class="empty">Empty</span></li></ul>');
					}
				},
				error: function(jqXHR) {
					container.find('ul').remove();
					container.append('<ul><li><span class="empty">' + jqXHR.statusText + '</span></li></ul>');
				}
			})
		},

		// Reload child node.
		reload: function(options)
		{
			return this.each(function()
			{
				var settings = $.data(this, 'GfTree');
				settings = $.extend({}, settings, options);
				methods._load.call(this, settings);
			});
		},

		// Click event
		click: function(event)
		{
			if (0 == $(this).siblings('ul').size())
			{
				var url = $(this).attr('href');

				$(this).closest('li').GfTree({
					url: url,
					loaded: event.data.loaded
				});
			}

			$(this).closest('li').toggleClass('closed');
		}
	};

	$.fn.GfTree = function(method)
	{
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if ('object' === typeof method || !method) {
			return methods._init.apply(this, arguments);
		} else {
			$.error('Method ' +  method + ' does not exist on jQuery.GfTree');
		}
	}
})(jQuery);