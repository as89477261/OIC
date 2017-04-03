/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.override(Ext.form.FileUploadField,{onRender:function(ct,position){Ext.form.FileUploadField.superclass.onRender.call(this,ct,position);this.wrap=this.el.wrap({cls:'x-form-field-wrap x-form-file-wrap'});this.el.addClass('x-form-file-text');this.el.dom.removeAttribute('name');this.createFileInput();var btnCfg=Ext.applyIf(this.buttonCfg||{},{text:this.buttonText});this.button=new Ext.Button(Ext.apply(btnCfg,{renderTo:this.wrap,cls:'x-form-file-btn'+(btnCfg.iconCls?' x-btn-icon':'')}));if(this.buttonOnly){this.el.hide();this.wrap.setWidth(this.button.getEl().getWidth());}
this.addFileListener();},createFileInput:function(){this.fileInput=this.wrap.createChild({id:this.getFileInputId(),name:this.name||this.getId(),cls:'x-form-file',tag:'input',type:'file',size:1});},addFileListener:function(){this.fileInput.on({change:function(){var v=this.fileInput.dom.value;this.setValue(v);this.fireEvent('fileselected',this,v);},mouseover:function(){this.button.addClass(['x-btn-over','x-btn-focus'])},mouseout:function(){this.button.removeClass(['x-btn-over','x-btn-focus','x-btn-click'])},mousedown:function(){this.button.addClass('x-btn-click')},mouseup:function(){this.button.removeClass(['x-btn-over','x-btn-focus','x-btn-click'])},scope:this});},reset:function(){this.fileInput.removeAllListeners();this.fileInput.remove();this.createFileInput();this.addFileListener();Ext.form.FileUploadField.superclass.reset.call(this);}});