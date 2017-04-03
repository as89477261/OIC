/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.ux.grid.filter.ListFilter=Ext.extend(Ext.ux.grid.filter.Filter,{labelField:'text',loadingText:'Loading...',loadOnShow:true,value:[],loaded:false,phpMode:false,init:function(){this.menu.add('<span class="loading-indicator">'+this.loadingText+'</span>');if(this.store){if(this.loadOnShow)
this.menu.on('show',this.onMenuLoad,this);}else if(this.options){var options=[];for(var i=0,len=this.options.length;i<len;i++){var value=this.options[i];switch(Ext.type(value)){case'array':options.push(value);break;case'object':options.push([value.id,value[this.labelField]]);break;case'string':options.push([value,value]);break;}}
this.store=new Ext.data.Store({reader:new Ext.data.ArrayReader({id:0},['id',this.labelField])});this.options=options;this.menu.on('show',this.onMenuLoad,this);}
this.store.on('load',this.onLoad,this);this.bindShowAdapter();},bindShowAdapter:function(){var oShow=this.menu.show;var lastArgs=null;this.menu.show=function(){if(arguments.length==0){oShow.apply(this,lastArgs);}else{lastArgs=arguments;oShow.apply(this,arguments);}};},onMenuLoad:function(){if(!this.loaded){if(this.options)
this.store.loadData(this.options);else
this.store.load();}},onLoad:function(store,records){var visible=this.menu.isVisible();this.menu.hide(false);this.menu.removeAll();var gid=this.single?Ext.id():null;for(var i=0,len=records.length;i<len;i++){var item=new Ext.menu.CheckItem({text:records[i].get(this.labelField),group:gid,checked:this.value.indexOf(records[i].id)>-1,hideOnClick:false});item.itemId=records[i].id;item.on('checkchange',this.checkChange,this);this.menu.add(item);}
this.setActive(this.isActivatable());this.loaded=true;if(visible)
this.menu.show();},checkChange:function(item,checked){var value=[];this.menu.items.each(function(item){if(item.checked)
value.push(item.itemId);},this);this.value=value;this.setActive(this.isActivatable());this.fireEvent("update",this);},isActivatable:function(){return this.value.length>0;},setValue:function(value){var value=this.value=[].concat(value);if(this.loaded)
this.menu.items.each(function(item){item.setChecked(false,true);for(var i=0,len=value.length;i<len;i++)
if(item.itemId==value[i])
item.setChecked(true,true);},this);this.fireEvent("update",this);},getValue:function(){return this.value;},serialize:function(){var args={type:'list',value:this.phpMode?this.value.join(','):this.value};this.fireEvent('serialize',args,this);return args;},validateRecord:function(record){return this.getValue().indexOf(record.get(this.dataIndex))>-1;}});