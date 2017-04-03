/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


function selected(cal,date){cal.sel.value=date;}
function isDisabled(date){return false;}
function flatSelected(cal,date){var el=cal.valueField;el.value=date;}
function showFlatCalendar(id,format,showsTime,showsOtherMonths){var el=document.getElementById(id);var cal=new Calendar(0,null,flatSelected);if(typeof showsTime=="string"){cal.showsTime=true;cal.time24=(showsTime=="24");}
if(showsOtherMonths){cal.showsOtherMonths=true;}
cal.setDateFormat(format);cal.create(this);cal.valueField=el;cal.show();}
Ext.namespace('Ext.ux');Ext.ux.DateTimePicker=Ext.extend(Ext.Component,{initComponent:function(){Ext.ux.DateTimePicker.superclass.initComponent.call(this);},onRender:function(container,position){showFlatCalendar.call(container.dom,this.fieldId,'%d %b %T','12',true);},focus:function(selectText,delay){return this;}});Ext.ux.DateTimeItem=function(config){Ext.ux.DateTimeItem.superclass.constructor.call(this,new Ext.ux.DateTimePicker(config),config);}
Ext.extend(Ext.ux.DateTimeItem,Ext.menu.Adapter,{});Ext.ux.DateTimeMenu=function(config){Ext.ux.DateTimeMenu.superclass.constructor.call(this,config);this.plain=true;var di=new Ext.ux.DateTimeItem(config);this.add(di);}
Ext.extend(Ext.ux.DateTimeMenu,Ext.menu.Menu,{cls:'ux-datetime-menu'});Ext.ux.DateTimeField=function(config){if(!config.value){config.value='';}
Ext.ux.DateTimeField.superclass.constructor.call(this,config);}
Ext.extend(Ext.ux.DateTimeField,Ext.form.TriggerField,{triggerClass:'x-form-date-trigger',onTriggerClick:function(){if(this.disabled){return;}
if(this.menu==null){this.menu=new Ext.ux.DateTimeMenu({fieldId:this.id});}
this.menu.show(this.el,"tl-bl?");}});