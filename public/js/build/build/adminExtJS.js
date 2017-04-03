/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.BLANK_IMAGE_URL='./images/icons/s.gif';Ext.ECM=function(){var msgCt;function createBox(t,s){return['<div class="msg">','<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>','<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>',t,'</h3>',s,'</div></div></div>','<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>','</div>'].join('');}
return{msg:function(title,format){if(!msgCt){msgCt=Ext.DomHelper.insertFirst(document.body,{id:'msg-div'},true);}
msgCt.alignTo(document,'t-t');var s=String.format.apply(String,Array.prototype.slice.call(arguments,1));var m=Ext.DomHelper.append(msgCt,{html:createBox(title,s)},true);m.slideIn('t').pause(1).ghost("t",{remove:true});},init:function(){var t=Ext.get('exttheme');if(!t){return;}
var theme=Cookies.get('exttheme')||'aero';if(theme){t.dom.value=theme;Ext.getBody().addClass('x-'+theme);}
t.on('change',function(){Cookies.set('exttheme',t.getValue());setTimeout(function(){window.location.reload();},250);});var lb=Ext.get('lib-bar');if(lb){lb.show();}}};}();function logout(){Ext.MessageBox.confirm('Confirm Logout','Logout ?',performLogout);}
function performLogout(btn){if(btn=='yes'){Ext.MessageBox.show({msg:'Logging out, please wait...',progressText:'Processing...',width:300,wait:true,waitConfig:{interval:200},icon:'ext-mb-download'});document.forms.logoutForm.confirmLogout.value=btn;document.forms.logoutForm.submit();}};Ext.ECM.shortBogusMarkup='';Ext.ECM.bogusMarkup='';Ext.onReady(Ext.ECM.init,Ext.ECM);function addAdminTabByURL(idForTab,title,iconClass,urlPage,urlParam){if(!tpAdmin.findById(idForTab)){tpAdmin.add({id:idForTab,title:title,iconCls:iconClass,autoLoad:{url:urlPage,params:urlParam},closable:true}).show();}else{tpAdmin.findById(idForTab).show();}}
function adminOpenTabIframe(idForTab,title,iconClass,urlPage){if(!tpAdmin.findById(idForTab)){tpAdmin.add({id:idForTab,title:title,iconCls:iconClass,html:'<iframe src="'+urlPage+'" border="0" width="100%" height="100%"></iframe>',closable:true}).show();}else{tpAdmin.findById(idForTab).show();}}
function addAdminTabByHTML(idForTab,title,iconClass,HTMLBody){var index=0;if(!tpAdmin.findById(idForTab)){tpAdmin.add({id:idForTab,title:title,iconCls:iconClass,html:'Tab Body '+'<br/><br/>'
+Ext.ECM.bogusMarkup,closable:true}).show();}else{tpAdmin.findById(idForTab).show();}}
function addSetupPasswordTab(){if(!tpAdmin.findById('setupPasswordTab')){var adminSubPanelx1=new Ext.Panel({title:'SubPanel 1',tools:tools,width:225,height:225,collapsible:true,html:'test'});tpAdmin.add({id:'setupPasswordTab',title:'Set Setup Password',iconCls:'settings',width:350,height:350,items:[{xtype:'portal',id:'AdminTabPanel',contentEl:'adminHome',title:'Administrator Home',autoScroll:true,layout:'column',items:[new Ext.ux.PortalColumn({autoWidth:true,border:false,style:'padding:10px 0 10px 10px',items:[adminSubPanelx1]}),new Ext.ux.PortalColumn({autoWidth:true,border:false,style:'padding:10px 0 10px 10px'}),new Ext.ux.PortalColumn({autoWidth:true,border:false,style:'padding:10px 0 10px 10px'})]}],closable:true}).show();}else{tpAdmin.findById('setupPasswordTab').show();}}