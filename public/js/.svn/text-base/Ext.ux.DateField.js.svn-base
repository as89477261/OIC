/**
 * @author Zelda
 */
// This function gets called when the end-user clicks on some date.
function selected(cal, date){
    //Ext.getCmp(cal.id).setRawValue(date);
    // just update the date in the input field.  
    /*
    if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))  {
        cal.callCloseHandler();
    }
    */
        // if we add this call we close the calendar on single-click.    
        // just to exemplify both cases, we are using this only for the 1st    
        // and the 3rd field, while 2nd and 4th will still require double-click.    
}

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
function isDisabled(date) {    
	//console.log("#7");
	return false;
}

function flatSelected(cal, date) {    
	//console.log("#6");
	var el = cal.valueField;    
	
	Ext.getCmp(cal.id).setRawValue(date);
	
	//cal.menu.show(el); 
	//showFlatCalendar.call(cal.id, cal.formatInt, cal.showsTime, cal.showsOtherMonths)
	//console.log(cal.getDateFormat());
	//el.value = date;
	
	cal.show();
	if (cal.dateClicked)  {
        //closeHandler(cal);
		//alert(Ext.getCmp(cal.id));
		//alert(Ext.getCmp(cal.id).getEl().child('x-form-date-trigger'));//('DateTimeField'));
		//alert(Ext.getCmp(cal.id).getEl().menu);//('DateTimeField'));
		//alert(Ext.getCmp(cal.id).getName());//('DateTimeField'));
		//alert(Ext.getCmp(cal.id).findParentByType('TriggerField'));
		///alert(Ext.getCmp(cal.id).findParentByType('Menu'));
		//cal.menu.hide();
		//cal.parent.hide();
        //alert('AAAA');
		//Ext.getCmp(cal.id).hideTrigger=true;
		//Ext.getCmp(cal.id).hideTrigger();
		//Ext.getCmp(cal.id).fireEvent('blur');//,Ext.getCmp(cal.id).getEl());//
		//Ext.getCmp(cal.id).trigger.hide();
		//Ext.getCmp(cal.id).fireEvent('click');//,Ext.getCmp(cal.id).getEl());//
		//Ext.getCmp(cal.id).click();
		//Ext.getCmp(cal.id).focus();
		
		//alert(Ext.getCmp(cal.id).trigger);
		//alert(Ext.getCmp(cal.id).trigger.hide());
		//Ext.getCmp(cal.id).trigger.setDisplayed(false);
    }
	
	//cal.callCloseHandler();
}
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
  //cal.destroy();
  _dynarch_popupCalendar = null;
}

function showCalendar(id, format, showsTime, showsOtherMonths,el) {
  //var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, flatSelected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  //_dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = id;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  //console.log(Ext.getCmp(id).getEl());
  //console.log(Ext.getCmp(id));
  _dynarch_popupCalendar.showAt(100,100);        // show the calendar

  return false;
}

function showFlatCalendar(id, format, showsTime, showsOtherMonths) {  
	var el = document.getElementById(id);  
	// construct a calendar giving only the "selected" handler.  
	var cal = new Calendar(0, null, flatSelected);  
	if (typeof showsTime == "string") {      
		cal.showsTime = true;      
		cal.time24 = (showsTime == "24");  
	}  
	if (showsOtherMonths) {      
		cal.showsOtherMonths = true;  
	}  
	// hide week numbers
	//  cal.weekNumbers = false;  
	// We want some dates to be disabled; see function isDisabled above
	//  cal.setDisabledHandler(isDisabled);  
	cal.setDateFormat(format);  
	cal.formatInt = format;
	// this call must be the last as it might use data initialized above; if  
	// we specify a parent, as opposite to the "showCalendar" function above,  
	// then we create a flat calendar -- not popup.  Hidden, though, but...  
	cal.create(this);  
	cal.valueField = el;  
	cal.id = id;
	// ... we can show it here.  
	//console.log("#5");
	cal.show();
}

// jscalendar by Mihai Bazon integrated with ext2.0
// please note, modifications to calendar.js are required
// format specification is not supported (yet?), due to differences between jscalendar and ext date formatting
Ext.namespace('Ext.ux');                                                    
Ext.ux.DateTimePicker = Ext.extend (Ext.Component, {    
	initComponent: function() {        
		Ext.ux.DateTimePicker.superclass.initComponent.call(this);    
		//showFlatCalendar.call (container.dom, this.fieldId, '%d/%m/%T', '12', true); 

	},    
	onRender: function(container, position) {        
		showFlatCalendar.call (container.dom, this.fieldId, '%d %b %T', '12', false);    
		//showFlatCalendar.call (container.dom, this.fieldId, '%d/%m/%T', '12', true);    
	}/*,    
	focus : function(selectText, delay){        
		return this;    
	}*/
});

Ext.ux.DateTimeItem = function(config) {    
	Ext.ux.DateTimeItem.superclass.constructor.call(this, new Ext.ux.DateTimePicker(config), config);
}        

Ext.extend(Ext.ux.DateTimeItem, Ext.menu.Adapter, {});       
Ext.ux.DateTimeMenu = function(config) {   
 	Ext.ux.DateTimeMenu.superclass.constructor.call(this,config);    
	this.plain = true;    
	this.ignoreParentClicks = true;
	var di = new Ext.ux.DateTimeItem(config);    
	this.add(di);
	//var xy = this.getEl().getAlignToXY(this.getEl(), [10,10] || "tl-bl?", [0,0]);
	//alert(xy);
	//this.getEl().setLocation(10,0);
	//this.showAt(xy);
}                
	
Ext.extend (Ext.ux.DateTimeMenu, Ext.menu.Menu, {    
	cls:'ux-datetime-menu',
	ignoreParentClicks: true,
	onClick : function( thisObj, menuItem, e ) {
		//console.log('x1');
	}
}); 

Ext.ux.DateTimeField = function(config) {    
	if (!config.value) {        
		//config.value = (new Date).dateFormat('d/m/Y')  ;    
		config.value = '' ;    
	}    
	Ext.ux.DateTimeField.superclass.constructor.call(this,config);
}             
           
Ext.extend (Ext.ux.DateTimeField, Ext.form.TriggerField, {    
	triggerClass : 'x-form-date-trigger',    
	onTriggerClick : function(){        
		
		if(this.disabled){            
			return;        
		}        
		if(this.menu == null){            
			this.menu = new Ext.ux.DateTimeMenu({fieldId:this.id});        
		}        
		//var xy = this.el.getAlignToXY(el, pos || this.defaultAlign, this.defaultOffsets);
        //this.el.setXY(xy);
        //Ext.ux.vorne.FloatingContainer.superclass.onShow.call(this);
		this.menu.show(this.el, "tl-bl?");    
		
		//console.log(this.id);
		//console.log(Ext.getCmp(this.id).getEl());
		//console.log(Ext.getCmp(this.id).getEl());
		//showCalendar(this.id, '%d %b %T', '24', true,Ext.getCmp(this.id).getEl());
	}
});
