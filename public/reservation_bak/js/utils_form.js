
SUFFIX_DAY = 'Day';
SUFFIX_MONTH = 'Month';
SUFFIX_YEAR = 'Year';
DATE_DELIMITER = '-';

function gotoFirstControl(aform)
{
    if (typeof(aform.elements.length) != 'undefined')
        for (var i = 0; i < aform.elements.length; i++)
        {
            obj = aform.elements[i];
            if (obj.type != 'hidden') {
                obj.focus();
                break;
            }
        }
}

function gotoControl(aform, control)
{
    // control maybe its name or its order in the form
    obj = aform.elements[control];
    if ((typeof(obj) != 'undefined') && (obj.type != 'hidden'))
        obj.focus();
}

function setReadOnly(obj, readonly)
{
    if (readonly)
        obj.setAttribute('readOnly', 'readonly');      // IE is case-sensitive, Firefox is not
    else
        obj.removeAttribute('readOnly');
}

function setDateValue(aform, date_name, val)
{
    date_array = val.split(DATE_DELIMITER, 3);
    setControlValue(aform.elements[date_name + SUFFIX_YEAR], date_array[0]);
    setControlValue(aform.elements[date_name + SUFFIX_MONTH], date_array[1]);
    setControlValue(aform.elements[date_name + SUFFIX_DAY], date_array[2]);
}

function autochangeDateSelect(aform, source, target, offset)
{
    checkin = getDateStringFromSelect(aform, source);
    setDateValue(aform, target, relativeDate(checkin, offset));
}

function setControlValueByName(aform, name, val)
{
    obj = aform.elements[name];
    setControlValue(obj, val);
}

function setControlValue(obj, val)
{
    if (obj == null) return false;
    if (typeof(obj.type) == 'undefined')
        if (obj.length > 0)
            objtype = obj[0].type;
        else
            objtype = 'unknown';
    else
        if (obj.type == 'radio')
            objtype = 'checkbox';
        else
            objtype = obj.type;
    switch (objtype) {
        case 'select-one':
            for (var i = 0; i < obj.options.length; i++) {
                if (obj.options[i].value == val) {
                    obj.selectedIndex = i;
                    break;
                }
            }
            break;
        case 'checkbox':
            if (typeof(obj.length) != 'undefined')
            {
                for (var i = 0; i < obj.length; i++)
                {
                    if (obj[i].value == val)
                        obj[i].checked = true;
                    else if (val == '')             // if set to blank, then unset the checked one.
                        obj[i].checked = false;
                }
            }
            if (obj.value == val)
                obj.checked = true;
            else
                obj.checked = false;
            break;
        case 'radio':
            for (var i = 0; i < obj.length; i++) {
                if (obj[i].value == val) {
                    obj[i].checked = true;
                    break;
                }
            }
            break;
        default:
            obj.value = val;
    }
    return true;
}

function isControlExistent(aform, name)
{
    if (typeof(aform.elements[name]) == 'object')
        return true;
    else
        return false;
}

function copyFormValues(src, dest, names)
{
    for (var i = 0; i < names.length; i++)
    {
        obj = src.elements[names[i]];
		val = '';
        if (typeof(obj) != 'undefined')
        {
            if ((obj.type == 'text') || (obj.type == 'textarea') || (obj.type == 'hidden'))
                val = obj.value;
            else if (obj.type == 'checkbox')
                val = (obj.checked ? obj.value : '');
            else if (obj.type == 'select-one')
            {
                if (obj.selectedIndex > -1)
                    val = obj.options[obj.selectedIndex].value;
            }
            setControlValueByName(dest, names[i], val);
        }
    }
}

function onClickRadioCheckboxValue(obj)         // can merge with setControlValue
{
    if ((obj == null) || (obj.form == null))
        return false;
    if (!obj.checked)
        return true;                            // nothing to change
    arrCb = obj.form.elements[obj.name];
    if ((typeof(arrCb.type) != 'undefined') && (arrCb.length <= 0))
        return false;                           // not array of checkbox
    for (var i = 0; i < arrCb.length; i++)
        if (arrCb[i].value != obj.value)
            arrCb[i].checked = false;
    return true;
}

function selectAll(obj, isSelected)
{
    if (typeof(obj.length) == 'undefined')
    {
        if (obj.type == 'checkbox')
            obj.checked = isSelected;
    }
    else
    {
        for (var i = 0; i < obj.length; i++)
        {
            if ((obj[i].type == 'checkbox') || (obj[i].type == 'radio'))
                obj[i].checked = isSelected;
            else if(obj.type == 'select-multiple')
                obj[i].selected = isSelected;
        }
    }
}
