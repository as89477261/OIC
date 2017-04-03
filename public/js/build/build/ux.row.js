/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


Ext.ns('Ext.ux.layout');Ext.ux.layout.CenterLayout=Ext.extend(Ext.layout.FitLayout,{setItemSize:function(item,size){this.container.addClass('ux-layout-center');item.addClass('ux-layout-center-item');if(item&&size.height>0){if(item.width){size.width=item.width;}
item.setSize(size);}}});Ext.Container.LAYOUTS['ux.center']=Ext.ux.layout.CenterLayout;var centerLayout={id:'center-panel',layout:'ux.center',items:{title:'Centered Panel: 75% of container width and fit height',layout:'ux.center',autoScroll:true,width:'75%',bodyStyle:'padding:20px 0;',items:[{title:'Inner Centered Panel',html:'Fixed 300px wide and auto height. The container panel will also autoscroll if narrower than 300px.',width:300,frame:true,autoHeight:true,bodyStyle:'padding:10px 20px;'}]}};Ext.ux.layout.RowLayout=Ext.extend(Ext.layout.ContainerLayout,{monitorResize:true,isValidParent:function(c,target){return c.getEl().dom.parentNode==this.innerCt.dom;},onLayout:function(ct,target){var rs=ct.items.items,len=rs.length,r,i;if(!this.innerCt){target.addClass('ux-row-layout-ct');this.innerCt=target.createChild({cls:'x-row-inner'});}
this.renderAll(ct,this.innerCt);var size=target.getViewSize();if(size.width<1&&size.height<1){return;}
var h=size.height-target.getPadding('tb'),ph=h;this.innerCt.setSize({height:h});for(i=0;i<len;i++){r=rs[i];if(!r.rowHeight){ph-=(r.getSize().height+r.getEl().getMargins('tb'));}}
ph=ph<0?0:ph;for(i=0;i<len;i++){r=rs[i];if(r.rowHeight){r.setSize({height:Math.floor(r.rowHeight*ph)-r.getEl().getMargins('tb')});}}}});Ext.Container.LAYOUTS['ux.row']=Ext.ux.layout.RowLayout;var rowLayout={id:'row-panel',bodyStyle:'padding:5px',layout:'ux.row',title:'Row Layout',items:[{title:'Height = 25%, Width = 50%',rowHeight:.25,width:'50%'},{title:'Height = 100px, Width = 300px',height:100,width:300},{title:'Height = 75%, Width = fit',rowHeight:.75}]};