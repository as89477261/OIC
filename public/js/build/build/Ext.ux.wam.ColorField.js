/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.ux.ColorField=Ext.extend(Ext.form.TriggerField,{invalidText:"'{0}' is not a valid color - it must be in a the hex format (# followed by 3 or 6 letters/numbers 0-9 A-F)",triggerClass:'x-form-color-trigger',defaultAutoCreate:{tag:"input",type:"text",size:"10",maxlength:"7",autocomplete:"off"},maskRe:/[#a-f0-9]/i,validateValue:function(value){if(!Ext.ux.ColorField.superclass.validateValue.call(this,value)){return false;}
if(value.length<1){this.setColor('');return true;}
var parseOK=this.parseColor(value);if(!value||(parseOK==false)){this.markInvalid(String.format(this.invalidText,value));return false;}
this.setColor(value);return true;},setColor:function(color){if(color==''||color==undefined)
{if(this.emptyText!=''&&this.parseColor(this.emptyText))
color=this.emptyText;else
color='transparent';}
if(this.trigger)
this.trigger.setStyle({'background-color':color});else
{this.on('render',function(){this.setColor(color)},this);}},validateBlur:function(){return!this.menu||!this.menu.isVisible();},getValue:function(){return Ext.ux.ColorField.superclass.getValue.call(this)||"";},setValue:function(color){Ext.ux.ColorField.superclass.setValue.call(this,this.formatColor(color));this.setColor(this.formatColor(color));},parseColor:function(value){return(!value||(value.substring(0,1)!='#'))?false:(value.length==4||value.length==7);},formatColor:function(value){if(!value||this.parseColor(value))
return value;if(value.length==3||value.length==6){return'#'+value;}
return'';},menuListeners:{select:function(e,c){this.setValue(c);},show:function(){this.onFocus();},hide:function(){this.focus.defer(10,this);var ml=this.menuListeners;this.menu.un("select",ml.select,this);this.menu.un("show",ml.show,this);this.menu.un("hide",ml.hide,this);}},onTriggerClick:function(){if(this.disabled){return;}
if(this.menu==null){this.menu=new Ext.menu.ColorMenu();}
this.menu.on(Ext.apply({},this.menuListeners,{scope:this}));this.menu.show(this.el,"tl-bl?");}});Ext.reg('colorfield',Ext.ux.ColorField);