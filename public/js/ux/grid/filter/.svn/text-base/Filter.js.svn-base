/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.namespace("Ext.ux.grid.filter");Ext.ux.grid.filter.Filter=function(config){Ext.apply(this,config);this.events={'activate':true,'deactivate':true,'update':true,'serialize':true};Ext.ux.grid.filter.Filter.superclass.constructor.call(this);this.menu=new Ext.menu.Menu();this.init();if(config&&config.value){this.setValue(config.value);this.setActive(config.active!==false,true);delete config.value;}};Ext.extend(Ext.ux.grid.filter.Filter,Ext.util.Observable,{active:false,dataIndex:null,menu:null,init:Ext.emptyFn,fireUpdate:function(){this.value=this.item.getValue();if(this.active)
this.fireEvent("update",this);this.setActive(this.value.length>0);},isActivatable:function(){return true;},setActive:function(active,suppressEvent){if(this.active!=active){this.active=active;if(suppressEvent!==true)
this.fireEvent(active?'activate':'deactivate',this);}},getValue:Ext.emptyFn,setValue:Ext.emptyFn,serialize:Ext.emptyFn,validateRecord:function(){return true;}});