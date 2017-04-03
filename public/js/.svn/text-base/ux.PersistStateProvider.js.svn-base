/*
 * New Project 1
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */

Ext.namespace('Ext.ux', 'Ext.ux.state');

Ext.ux.state.PersistStateProvider = function(config) {
	Ext.ux.state.PersistStateProvider.superclass.constructor.call(this);
	this.store = new Persist.Store('pstore');
	Ext.apply(this, config);
};

Ext.extend(Ext.ux.state.PersistStateProvider, Ext.state.Provider, {
	set : function(name, value) {
		//console.log('[Ext.state.Manager]:Set');
		try {
			if (typeof value == "undefined" || value === null) {
				this.clear(name);
				return;
			}
			var val = this.encodeValue(value);
			this.store.set(name, val);
			this.fireEvent("statechange", this, name, value);
		} catch (err) {
			console.log('[Ext.state.Manager] Set :'+err.description);
		}
	},
	get : function(name, defaultValue) {
		//console.log('[Ext.state.Manager]:Get');
		try {
			var val = null;
			this.store.get(name, function(k, v) {
				if (k) {
					val = v;
				}
			});
			return this.decodeValue(val);
		} catch (err) {
			console.log('[Ext.state.Manager] Get:'+err.description);
		}
	},
	clear : function(name) {
		//console.log('[Ext.state.Manager]:Clear');
		try {
			//this.store.remove(name);
			//console.log(name);
			//this.store.remove(name,function(){},this.store);
			this.fireEvent("statechange", this, name, null);
		} catch (err) {
			console.log('[Ext.state.Manager] Clear:'+err.description);
		}
	}
});
