/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */

/**
 * @author Zelda
 */
// This function gets called when the end-user clicks on some date.
function selected(cal, date){
    cal.sel.value = date;
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
	return false;
}

function flatSelected(cal, date) {    
	var el = cal.valueField;    
	el.value = date;
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
	// this call must be the last as it might use data initialized above; if  
	// we specify a parent, as opposite to the "showCalendar" function above,  
	// then we create a flat calendar -- not popup.  Hidden, though, but...  
	cal.create(this);  
	cal.valueField = el;  
	// ... we can show it here.  
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
		showFlatCalendar.call (container.dom, this.fieldId, '%d %b %T', '12', true);    
		//showFlatCalendar.call (container.dom, this.fieldId, '%d/%m/%T', '12', true);    
	},    
	focus : function(selectText, delay){        
		return this;    
	}
});

Ext.ux.DateTimeItem = function(config) {    
	Ext.ux.DateTimeItem.superclass.constructor.call(this, new Ext.ux.DateTimePicker(config), config);
}        

Ext.extend(Ext.ux.DateTimeItem, Ext.menu.Adapter, {});       
Ext.ux.DateTimeMenu = function(config) {   
 	Ext.ux.DateTimeMenu.superclass.constructor.call(this,config);    
	this.plain = true;    
	var di = new Ext.ux.DateTimeItem(config);    
	this.add(di);
}                
	
Ext.extend (Ext.ux.DateTimeMenu, Ext.menu.Menu, {    
	cls:'ux-datetime-menu'
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
		this.menu.show(this.el, "tl-bl?");    
	}
});
