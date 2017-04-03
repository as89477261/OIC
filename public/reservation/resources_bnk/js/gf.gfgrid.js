(function($) {
	//Set default values.
	var defaults = {
		url: '',
		params: '',
		queryString: ''
	};

	$.fn.GfGrid = function(options)
	{
		return this.each(function() {
			init(this, options);
		});
	};

	function init(container, options)
	{
		container.settings = $.extend({}, defaults, options);

		container.reload = reload;
		container.addParams = addParams;
		container.removeParams = removeParams;
		container.getParam = getParam;
		container.setQueryString = setQueryString;
		container.removeQueryString = removeQueryString;
		container.getQueryString = getQueryString;
	}

	function reload(params)
	{
		if (!this.settings.url)
		{
			throw new Error('The url not found.');
		}

		var data = $.param(this.settings.params);

		if ('undefined' != typeof(params))
		{
			if ('object' == typeof(params))
			{
				params = $.param(params);
			}
		}

		if ('' != data)
		{
			data = data + '&' + params;
		}
		else
		{
			data = params;
		}

		if (('undefined' != typeof(this.settings.queryString)) && ('' != this.settings.queryString))
		{
			data = data + '&' + this.settings.queryString;
		}

		$(this).load(
			this.settings.url,
			data,
			function()
			{
				//To apply the template setup.
				$(this).applyTemplateSetup();

				//Prevents the event from bubbling up the DOM tree.
				stopPropagation(this);

				if (this.settings.assoc) {
					var container = this;
					
					$.each(this.settings.assoc, function() {
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

							// set remove & set highlight row
							$(this).siblings('tr.current').removeClass('current');
							$(this).addClass('current');

							if ($(this).attr('param'))
							{
								url += '&' + $(this).attr('param');
							}

							$('#' + options.gridId).GfGrid({
								url: url
							}).get(0).reload();
						});

						// auto selection
						$(container).find('tbody tr.current').click();
					});
					
				}
			}
		);
	}

	function stopPropagation(container)
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

	function addParams(params)
	{
		this.settings.params = $.extend(this.settings.params, params);
	}

	function removeParams(params)
	{
		if ($.isArray(params))
		{
			var container = this;

			$.each(params, function() {
				delete container.settings.params[this];
			});
		}
		else
		{
			delete this.settings.params[params];
		}
	}

	function getParam(paramName)
	{
		if (typeof this.settings.params[paramName] != 'undefined')
		{
			return this.settings.params[paramName];
		}
		else
		{
			return null;
		}
	}

	function setQueryString(str)
	{
		this.settings.queryString = str;
	}

	function removeQueryString()
	{
		this.settings.queryString = '';
	}

	function getQueryString()
	{
		return this.settings.queryString;
	}
})(jQuery);

function goToPage(grid, pageNo)
{
	grid.addParams({'PageAt': pageNo});
	grid.reload();
}

function toggleSortColumn(col, dir)
{
	obj1 = document.getElementById('sort_' + col + '_' + dir);
	obj2 = document.getElementById('sort_' + col + '_' + (dir == 'ASC' ? 'DESC' : 'ASC'));
	paramName = 'sort_by[' + col + ']';
	grid = $(obj1).parents('.gf-grid')[0];

	currentStatus = grid.getParam(paramName);
	if (currentStatus == dir)
	{
		$(obj1).removeClass('active');
		grid.removeParams(paramName);
	}
	else
	{
		param = new Object()
		param[paramName] = dir;
		grid.addParams(param);
		$(obj1).addClass('active');
		$(obj2).removeClass('active');
	}
	grid.reload();
}
