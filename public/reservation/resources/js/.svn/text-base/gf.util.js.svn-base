function openModal(url, title, onSuccess)
{
	var defaults = {
		url: url,
		title: title,
		width: false,
		height: false,
		resizable: false,
		closeButton: false
	};

	var buttons = [
		{
			title: 'Save',
			handler: function(win) {
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
			title: 'Close',
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
	}

	openModal(url, title, onSuccess);
}

function deleteData(url, idContainer)
{
	$.ajax(
		{
			type: 'POST',
			url: url,
			success: function()
			{
				document.getElementById(idContainer).reload()
			}
		}
	);
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