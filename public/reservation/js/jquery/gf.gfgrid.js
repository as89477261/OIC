(function($) {
	// Set default values.
	var defaults = {
		url: '',
		params: ''
	};

	$.fn.GfGrid = function(options)
	{
		return this.each(function() {
			init(this, options);
		});
	};

	var init = function(container, options)
	{
		container.settings = $.extend({}, defaults, options);

		container.reload = function()
		{
			reload(this);
		}
	};

	var reload = function(container)
	{
		if (!container.settings.url)
		{
			throw new Error('The url not found.');
		}

		$(container).loadWithEffect(
			container.settings.url,
			function()
			{
				//To apply the template setup.
				$(this).applyTemplateSetup();

				//Prevents the event from bubbling up the DOM tree.
				stopPropagation(this);

				if (container.settings.assoc) {
					$.each(container.settings.assoc, function() {
						if (!this.gridId)
						{
							alert('The gridId not specified.');
						}
						else
						{
							if (0 == $('#' + this.gridId).length)
							{
								alert('The element id ' + this.gridId + ' not exists.');
							}
						}

						var options = this;

						$(container).find('tbody tr').click(function() {
							var url = options.url;

							if ($(this).attr('param'))
							{
								url += '&' + $(this).attr('param');
							}

							$('#' + options.gridId).GfGrid({
								url: url
							}).get(0).reload();
						});
					});
				}
			}
		);
	};

	var stopPropagation = function(container)
	{
		$(container).find('tbody tr').each(function()
		{
			$(this).find('input[type=radio].switch, input[type=checkbox].switch, span.switch-replace').click(function(event)
			{
				event.stopPropagation();
			});

			$(this).find('input[type=radio].mini-switch, input[type=checkbox].mini-switch, span.mini-switch-replace').click(function(event)
			{
				event.stopPropagation();
			});

			$(this).find('a').click(function(event)
			{
				event.stopPropagation();
			});
		});

		
	}
})(jQuery)