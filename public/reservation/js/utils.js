
if (!Number.toFixed) {
    Number.prototype.toFixed = function(x) {
        var temp = this;
        temp = Math.round(temp * Math.pow(10, x)) / Math.pow(10, x);
        return temp;
    };
}

function textRoundDecimal(num, x)
{
    if (isNaN(num))
        num = 0;
    val = new String(Math.round(num * Math.pow(10, x)));
    if (val.length <= x)
    {
        zeroPad = (new String(Math.pow(10, (x + 1 - val.length)))).substring(1);
        val = zeroPad + val;
    }
    p = val.length - x;
    d = val.substring(0, p) + ((x > 0) ? '.' + val.substring(p) : '');
    return d;
}

// params is window parameter string to concat with calculated string from width, height, and isCenter
// do not put any parameter about window dimension into params, otherwise the result will depend on browser's translation
// also for isCenter is specified to true, do not put any parameter about window location
function popupWindow(strUrl, strName, width, height, isCenter, params)
{
    if (strUrl != '')
    {
        regexpUrl = /^\w+:\/\//;
        if (! regexpUrl.test(strUrl))
            strUrl = 'http://' + strUrl;
    }
  strParam = 'width=' + width + ',height=' + height + ',innerWidth=' + width + ',innerHeight=' + height;
  if (isCenter)
  {
    x = (screen.availWidth - width) / 2;
    y = (screen.availHeight - height) / 2;
    strParam = strParam + ',screenX=' + x + ',screenY=' + y + ',left=' + x +',top=' + y;
  }
  strParam = params + ',' + strParam;
  return window.open(strUrl, strName, strParam);
}

function closeThisPopup(result)
{
    if ((typeof(window.opener.callbackClosePopup) == 'function') ||
    (typeof(window.opener.callbackClosePopup) == 'object'))     // for IE referencing across window
      window.opener.callbackClosePopup(result);
    window.opener.focus();
    window.close();
}

function resizeWindow(width, height, isCenter)
{
    window.innerWidth = width;
    window.innerHeight = height;
    window.width = width;
    window.height = height;
  if (isCenter)
  {
    x = (screen.availWidth - width) / 2;
    y = (screen.availHeight - height) / 2;
        window.screenX = x;
        window.screenY = y;
        window.left = x;
        window.top = y;
    }
}

function refreshOpener(strUrl)
{
  window.opener.location.replace(strUrl);
}

function printToPrinter()
{
    bV = parseInt(navigator.appVersion)
    if (bV >= 4)
        window.print();
    else
        alert('Your browser does not support printing from within web page. Click menu File > Print...');
}

function parseDelimitFloat(val)
{
  if ((typeof(val) == 'undefined') || (val == ''))
    return 0.00;
    sval = new String(val);
    sval = parseFloat((sval.split(',')).join(''));
    if (isNaN(sval))
        return val;
    return sval;
}

function delimitThousand(val)
{
    val = new String(val);
    if (val.length <= 3)
        return val;
    i = val.length % 3;
    result = val.substring(0, i);
    for (; i + 3 <= val.length; i += 3) {
        result += (',' + val.substring(i, i + 3));
    }
    return result;
}

function getFormattedPrice(val, delimited)      // do not accept value less than zero
{
    val = parseDelimitFloat(val);
    if (isNaN(val) || (val < 0))
        return false;
    if (typeof(val) == 'number')
        val = new String(val);
    p = val.indexOf('.');
    if (p >= 0) {
        if (val.length < p+3)
            val = val + '00';
        d = val.substring(0, p) + val.substring(p+1, p+3) + '.' + val.substring(p+3);
        val = new String(Math.round(d));
    } else
        val = val.concat('00');
    val = parseInt(val, 10);
    if (val == 0)
        total = '000';
    else
        total = new String(val);
    if (total.length == 1)
        total = '00'.concat(total);
    else
        if (total.length == 2)
            total = '0'.concat(total);
    if (delimited)
        return delimitThousand(total.substring(0, total.length-2)) + '.' + total.substring(total.length-2);
    else
        return total.substring(0, total.length-2) + '.' + total.substring(total.length-2);
}

function swapImage(name, img)
{
    document.images[name].src = img.src;
}

function preloadImage(nameObj, srcImg)
{
    eval(nameObj + ' = new Image(); ' + nameObj + '.src = \'' + srcImg + '\';');
}

function getSuffixedFileName(fname, suffix)
{
    posDot = fname.lastIndexOf('.');
    return fname.substr(0, posDot) + suffix + fname.substr(posDot);
}

function $id(idEl)
{
	return document.getElementById(idEl);
}

function idjq(myid){ return '#'+myid.replace(/:/g,"\\:").replace(/\./g,"\\.");}

function getCaretPosition (ctrl) {
	var CaretPos = 0;	// IE Support
	if (document.selection) {
	ctrl.focus ();
		var Sel = document.selection.createRange ();
		Sel.moveStart ('character', -ctrl.value.length);
		CaretPos = Sel.text.length;
	}
	// Firefox support
	else if (ctrl.selectionStart || ctrl.selectionStart == '0')
		CaretPos = ctrl.selectionStart;
	return (CaretPos);
}

function setCaretPosition(ctrl, pos){
	if(ctrl.setSelectionRange)
	{
		ctrl.focus();
		ctrl.setSelectionRange(pos,pos);
	}
	else if (ctrl.createTextRange) {
		var range = ctrl.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);
		range.select();
	}
}