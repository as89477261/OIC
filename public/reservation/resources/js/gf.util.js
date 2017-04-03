function openModal(url, title, onSuccess)
{
	var defaults = {
		url: url,
		title: title,
		width: false,
		height: false,
		resizable: false,
		closeButton: false,
		complete: function() {
			$.modal.current.centerModal();
		}
	};

//	var buttons = [
//	{
//		title: 'OK',
//		handler: function(win) {
//			//alert($(win).find('form').serialize());
//			//alert(document.getElementById('booking.goto').value);
//			//alert($('#booking_goto').val());
//			//return fale;
//			$.ajax(
//			{
//				type: 'POST',
//				data: $(win).find('form').serialize(),
//				url: $(win).find('form').get(0).action,
//				success: function() {
//					onSuccess.call();
//					win.closeModal();
//				},
//				error: function() {
//
//				}
//			}
//			);
//		}
//	},
//	{
//		title: 'Cancel',
//		handler: function(win)
//		{
//			win.closeModal();
//		}
//	}
//	]
        var buttons = [
	{
		title: 'OK',
		handler: function(win) {
			form = $(win).find('form')[0];
			if (typeof form.onsubmit == 'function')
			{
				validatePassed = form.onsubmit();
			}
			else
			{
				validatePassed = true;
			}
			if ((typeof validatePassed == 'undefined') || validatePassed)
			$.ajax(
			{
				type: 'POST',
				data: $(win).find('form').serialize(),
				url: $(win).find('form').get(0).action,
				success: function() {
					onSuccess.call();
					win.closeModal();
				},
				error: function() {

				}
			}
			);
		}
	},
	{
		title: 'Cancel',
		handler: function(win)
		{
			win.closeModal();
		}
	}
	]

	defaults.buttons = buttons;

	$.modal(defaults);
}

function openForm(url, title, idContainer)
{
	var onSuccess = function()
	{
		document.getElementById(idContainer).reload();
		messageAlert('∫—π∑÷°¢ÈÕ¡Ÿ≈‡√’¬∫√ÈÕ¬·≈È«','Save Complete');
	}

	openModal(url, title, onSuccess);
}

function openFormTree(url, title, idContainer)
{
	var onSuccess = function()
	{
		$(this).documentTreeLoad();
	}
	openModal(url, title, onSuccess);
}

function openFormTest(url, title)
{
	var onSuccess = function()
	{
	//document.getElementById(idContainer).reload();
	}

	openModal(url, title,onSuccess);
}

function deleteData(url, idContainer)
{
    messageConfirm('¬◊π¬—π°“√≈∫¢ÈÕ¡Ÿ≈', 'Confirm Delete', function(flag)
    {
        var modal = this;

        if (flag)
        {
            $.ajax(
            {
                type: 'POST',
                url: url,
                success: function()
                {
                    messageAlert('√–∫∫‰¥È∑”°“√≈∫‡√’¬∫√ÈÕ¬·≈È«','Delete Complete');
                    modal.closeModal();
                    document.getElementById(idContainer).reload();
                }
            }
            );
        }
        else
		{
			modal.closeModal();
		}
    });
	
}

function deleteDataNew(url, callback)
{
	messageConfirm('‡∏?‡∏£‡∏∏‡∏?‡∏≤‡∏¢‡∏∑‡∏?‡∏¢‡∏±‡∏?‡∏?‡∏≤‡∏£‡∏•‡∏?', 'Confirm Delete', function(flag)
    {
        var modal = this;

        if (flag)
        {
            $.ajax(
            {
                type: 'POST',
                url: url,
                success: function()
                {
                    messageAlert('‡∏£‡∏∞‡∏?‡∏?‡π?‡∏?‡π?‡∏?‡∏≥‡∏?‡∏≤‡∏£‡∏•‡∏?‡π?‡∏£‡∏µ‡∏¢‡∏?‡∏£‡π?‡∏≠‡∏¢‡π?‡∏•‡π?‡∏ß','Delete Complete');
					modal.closeModal();
					
					if (true == $.isFunction(callback))
					{
						callback.call(modal);
					}
                }
            });
        }
        else
		{
			modal.closeModal();
		}
    });
}

function changeData(url, idContainer)
{
    messageConfirm('‡∏?‡∏£‡∏∏‡∏?‡∏≤‡∏¢‡∏∑‡∏?‡∏¢‡∏±‡∏?‡∏?‡∏≤‡∏£‡π?‡∏?‡∏•‡∏µ‡π?‡∏¢‡∏?‡π?‡∏?‡∏•‡∏?', 'Confirm Edit', function(flag)
    {
        var modal = this;
        if (flag)
        {
            $.ajax(
            {
                type: 'POST',
                url: url,
                success: function()
                {
                    messageAlert('‡∏£‡∏∞‡∏?‡∏?‡π?‡∏?‡π?‡∏?‡∏≥‡∏?‡∏≤‡∏£‡π?‡∏?‡∏•‡∏µ‡π?‡∏¢‡∏?‡π?‡∏?‡∏•‡∏?‡π?‡∏£‡∏µ‡∏¢‡∏?‡∏£‡π?‡∏≠‡∏¢‡π?‡∏•‡π?‡∏ß','Edit Complete');
                    modal.closeModal();
                    document.getElementById(idContainer).reload();
                }
            }
            );
        }
        else{
                modal.closeModal();
            }
    });
}

/**
 * Move row up.
 *
 * @param object element
 * @param mixed key
 */
function rowMoveUp(element, key)
{
	var current = $(element).parents('tr');

	if (0 < current.prev('tr').size())
	{
		var prev = current.prev('tr');
		current.insertBefore(prev);

		if ('' != key)
		{
			swapInputValue(document.getElementById(current.attr('id') + '.' + key), document.getElementById(prev.attr('id') + '.' + key));
		}
	}
}

/**
 * Move row down.
 *
 * @param object element
 * @param mixed key
 */
function rowMoveDown(element, key)
{
	var current = $(element).parents('tr');

	if (0 < current.next('tr').size())
	{
		var next = current.next('tr');
		current.insertAfter(next);

		if ('' != key)
		{
			swapInputValue(document.getElementById(current.attr('id') + '.' + key), document.getElementById(next.attr('id') + '.' + key));
		}
	}
}

/**
 * ???
 *
 * @param object element1
 * @param object element2
 */
function swapInputValue(element1, element2)
{
    var temp = element1.value;
	element1.value = element2.value;
	element2.value = temp;
}

/**
 * Show message dialog.
 *
 * @param object options
 */
function messageDialog(options)
{
	var defaults = {
		content: '',
		width: false,
		height: false,
		resizable: false,
		closeButton: false
	};

	$.extend(defaults, options);
	$.modal(defaults);
}

/**
 * Show alert message.
 *
 * @param string message
 * @param string title
 * @param funciton callback
 */
function messageAlert(message, title, callback)
{
	callback = 'undefined' != typeof(callback) ? callback : function() {this.closeModal()};

	var header = '<div class="block-header">' + title + '</div>';
	var content = '<p>' + message + '</p>';
	var defaults = {
		content: header + content,
		buttons: {
			close: {
				title: 'Close',
				handler: function(win) {
					callback.call(win);
				}
			}
		}
	};

	messageDialog(defaults);
}


/**
 * Show confirm message.
 *
 * @param string message
 * @param string title
 * @param funciton callback
 */
function messageConfirm(message, title, callback)
{
	callback = 'undefined' != typeof(callback) ? callback : function() {this.closeModal()};

	var header = '<div class="block-header">' + title + '</div>';
	var content = '<p>' + message + '</p>';
	var defaults = {
		content: header + content,
		buttons: {
			yes: {
				title: 'Yes',
				handler: function(win) {
					callback.call(win, true);
				}
			},
			no: {
				title: 'No',
				handler: function(win) {
					callback.call(win, false);
				}
			}
		}
	};

	messageDialog(defaults);
}

/**
 * Show prompt message.
 *
 * @param string message
 * @param string title
 * @param funciton callback
 * @param string defaultText
 */
function messagePrompt(message, title, callback, defaultText)
{
	callback = 'undefined' != typeof(callback) ? callback : function() {this.closeModal()};
	defaultText = 'undefined' != typeof(defaultText) ? defaultText : '';

	var id = new Date().getTime();
	var header = '<div class="block-header">' + title + '</div>';
	var content = '<p>' + message + ':&nbsp;<input id="' + id + '" type="text" value="' + defaultText + '" /></p>';
	var defaults = {
		content: header + content,
		buttons: {
			OK: {
				title: 'OK',
				handler: function(win) {
					callback.call(win, $('#' + id).val());
				}
			},
			Cancel: {
				title: 'Cancel',
				handler: function(win) {
					callback.call(win);
				}
			}
		}
	};

	messageDialog(defaults);
}

/**
 * Show prompt message.
 *
 * @param string message
 * @param object list
 * @param string title
 * @param funciton callback
 * @param string defaultSelect
 */
function messageList(message, list, title, callback, defaultSelect)
{
	callback = 'undefined' != typeof(callback) ? callback : function() {this.closeModal()};
	defaultSelect = 'undefined' != typeof(defaultSelect) ? defaultSelect : '';

	var options = '';

	$.each(list, function(index, value)
	{
		if ('string' == typeof(defaultSelect) && index == defaultSelect)
		{
			options += '<option value="' + index + '" selected="selected">' + value + '</option>';
		}
		else
		{
			options += '<option value="' + index + '">' + value + '</option>';
		}
	});

	var id = new Date().getTime();
	var header = '<div class="block-header">' + title + '</div>';
	var content = '<p>' + message + ':&nbsp;<select id="' + id + '">' + options + '</select></p>';
	var defaults = {
		content: header + content,
		buttons: {
			OK: {
				title: 'OK',
				handler: function(win) {
					callback.call(win, $('#' + id).val());
				}
			},
			Cancel: {
				title: 'Cancel',
				handler: function(win) {
					callback.call(win);
				}
			}
		}
	};

	messageDialog(defaults);
}
//Select Go Date
var datepickDefaults = {
	alignment: 'bottom',
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'dd-mm-yyyy',
	yearTextOffset: 543,
	altField: '#altGoDateFormat',
	altFormat: 'yyyy-mm-dd',
	showTrigger: '<a href="javascript:void(0);" style="margin-left:2px;"><img height="16" width="16" src="resources/images/icons/fugue/calendar-month.png" alt="" style="cursor:pointer;vertical-align:text-top;" /></a>',
	renderer: {
		picker: '<div class="datepick block-border clearfix form"><div class="mini-calendar clearfix">' +
		'{months}</div></div>',
		monthRow: '{months}',
		month: '<div class="calendar-controls">' +
		'{monthHeader:M yyyy}' +
		'</div>' +
		'<table cellspacing="0">' +
		'<thead>{weekHeader}</thead>' +
		'<tbody>{weeks}</tbody></table>',
		weekHeader: '<tr>{days}</tr>',
		dayHeader: '<th>{day}</th>',
		week: '<tr>{days}</tr>',
		day: '<td>{day}</td>',
		monthSelector: '.month',
		daySelector: 'td',
		rtlClass: 'rtl',
		multiClass: 'multi',
		defaultClass: 'default',
		selectedClass: 'selected',
		highlightedClass: 'highlight',
		todayClass: 'today',
		otherMonthClass: 'other-month',
		weekendClass: 'week-end',
		commandClass: 'calendar',
		commandLinkClass: 'button',
		disabledClass: 'unavailable'
	}

};
//Select Back Date
var datepickDefault = {
	alignment: 'bottom',
	showOtherMonths: true,
	selectOtherMonths: true,
	dateFormat: 'dd-mm-yyyy',
	yearTextOffset: 543,
	altField: '#altBackDateFormat',
	altFormat: 'yyyy-mm-dd',
	showTrigger: '<a href="javascript:void(0);" style="margin-left:2px;"><img height="16" width="16" src="resources/images/icons/fugue/calendar-month.png" alt="" style="cursor:pointer;vertical-align:text-top;" /></a>',
	renderer: {
		picker: '<div class="datepick block-border clearfix form"><div class="mini-calendar clearfix">' +
		'{months}</div></div>',
		monthRow: '{months}',
		month: '<div class="calendar-controls">' +
		'{monthHeader:M yyyy}' +
		'</div>' +
		'<table cellspacing="0">' +
		'<thead>{weekHeader}</thead>' +
		'<tbody>{weeks}</tbody></table>',
		weekHeader: '<tr>{days}</tr>',
		dayHeader: '<th>{day}</th>',
		week: '<tr>{days}</tr>',
		day: '<td>{day}</td>',
		monthSelector: '.month',
		daySelector: 'td',
		rtlClass: 'rtl',
		multiClass: 'multi',
		defaultClass: 'default',
		selectedClass: 'selected',
		highlightedClass: 'highlight',
		todayClass: 'today',
		otherMonthClass: 'other-month',
		weekendClass: 'week-end',
		commandClass: 'calendar',
		commandLinkClass: 'button',
		disabledClass: 'unavailable'
	}
};

function convertThaiDateDMY(thai_date)
{
    if (thai_date == '')
        return '';
    var dd = thai_date.split("-");	
    return  dd[0]+'-'+dd[1]+'-'+(parseInt(dd[2]) + 543);
}