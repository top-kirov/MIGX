<input id="tv{$tv->id}" name="tv{$tv->id}" type="hidden" class="textfield" value="{$tv->get('value')|escape}"{$style} tvtype="{$tv->type}" />
<div id="tvpanel{$tv->id}" style="width:100%">
</div>

<br/>

<script type="text/javascript">
    // <![CDATA[
    {$grid}
    
    {$updatewindow}
    
    {$iframewindow}

    {literal}



/*
Ext.ux.IFrameComponent = Ext.extend(Ext.BoxComponent, {
     onRender : function(ct, position){
          this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});
     }
});
*/
/*
var MiPreviewPanel = new Ext.Panel({
     id: 'MiPreviewPanel',
     title: 'MIGX - Preview',
     closable:true,
     // layout to fit child component
     layout:'fit', 
     // add iframe as the child component
     items: [ new Ext.ux.IFrameComponent({ id: id, url: 'http://www.gitrevo.webcmsolutions.de/manager' }) ]
});
*/
/*
Ext.ux.IFrameComponent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout:'fit'
        ,id: 'modx-iframe-mi-preview'
        ,url: 'http://www.gitrevo.webcmsolutions.de/preview1.html' 
    });
    Ext.ux.IFrameComponent.superclass.constructor.call(this,config);
};
Ext.extend(Ext.ux.IFrameComponent,Ext.BoxComponent,{
     onRender : function(ct, position){
          this.el = ct.createChild({tag: 'iframe', id: 'iframe-'+ this.id, frameBorder: 0, src: this.url});
     }
});
Ext.reg('modx-iframe-mi-preview',Ext.ux.IFrameComponent);
*/     


MODx.loadMIGXdbGridButton{/literal}{$tv->id}{literal} = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        handler: function() { this.loadGrid(); }
    });
    MODx.loadMIGXdbGridButton{/literal}{$tv->id}{literal}.superclass.constructor.call(this,config);
    this.options = config;
    this.config = config;
};

Ext.extend(MODx.loadMIGXdbGridButton{/literal}{$tv->id}{literal},Ext.Button,{

    loadGrid: function(init) {
	    var resource_id = '{/literal}{$resource.id}{literal}';
        var object_id = '{/literal}{$object_id}{literal}';
        
        if ('{/literal}{$customconfigs.check_resid|default}{literal}' == '1'){
        if (object_id != ''){
            if (object_id == 'new'){
                if (!init){
                    alert (_('migx.save_object'));
                }
                
                return;
            }
        }        
        else{
            if (resource_id == 0){
                if (!init){
                    alert (_('migx.save_resource'));
                }
                return;
            }            
        }
        }        

        MODx.load({
            xtype: 'modx-grid-multitvdbgrid-{/literal}{$win_id}{literal}'
            ,renderTo: 'tvpanel{/literal}{$tv->id}{literal}'
            ,tv: '{/literal}{$tv->id}{literal}'
            ,cls:'tv{/literal}{$tv->id}{literal}_items'
            ,id:'tv{/literal}{$tv->id}{literal}_items'
			,columns:Ext.util.JSON.decode('{/literal}{$columns}{literal}')
			,pathconfigs:Ext.util.JSON.decode('{/literal}{$pathconfigs}{literal}')
            ,fields:Ext.util.JSON.decode('{/literal}{$fields}{literal}')
            ,wctx: '{/literal}{$myctx}{literal}'
            ,url: '{/literal}{$config.connectorUrl}{literal}'
            ,tv_type: '{/literal}{$tv_type}{literal}'
            ,configs: '{/literal}{$properties.configs}{literal}'
            ,auth: '{/literal}{$auth}{literal}'
            ,resource_id: '{/literal}{$resource.id}{literal}' 
            ,co_id: '{/literal}{$connected_object_id}{literal}' 
            ,pageSize: 10
            ,object_id : '{/literal}{$object_id}{literal}' 		
        });
        this.hide();
        
        panel = Ext.getCmp("modx-content");
		tabs = Ext.getCmp("modx-resource-tabs");
		grid = Ext.getCmp('tv{/literal}{$tv->id}{literal}_items');
		autoresize = function(that,tab)
		{
			this.getView().refresh();
			if(this.topToolbar){this.topToolbar.container.setSize("auto");this.topToolbar.setSize("auto");}
			if(this.bottomToolbar){this.bottomToolbar.container.setSize("auto");this.bottomToolbar.setSize("auto");}
			if(this.footerToolbar){this.footerToolbar.container.setSize("auto");this.footerToolbar.setSize("auto");}
		}
		tabs.on('tabchange',autoresize,grid);
		panel.on('bodyresize',autoresize,grid);
	}	

});
Ext.reg('modx-button-load-migxdb-grid{/literal}{$tv->id}{literal}',MODx.loadMIGXdbGridButton{/literal}{$tv->id}{literal});


//load migx-lang into modx-lang
Ext.onReady(function() {
var lang = {/literal}{$migx_lang}{literal};
for (var name in lang) {
    MODx.lang[name] = lang[name];
}
  
  loadGridButton{/literal}{$tv->id}{literal} = MODx.load({
        xtype: 'modx-button-load-migxdb-grid{/literal}{$tv->id}{literal}'
        ,renderTo: 'tvpanel{/literal}{$tv->id}{literal}'
        ,text: '{/literal}{$i18n_migx_loadgrid}{literal}'
	});   
	
	if ('{/literal}{$customconfigs.gridload_mode}{literal}' == '2'){
	    loadGridButton{/literal}{$tv->id}{literal}.loadGrid(true);
	}
  
});





{/literal}
</script>