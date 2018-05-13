<?php

$editors['this.textareaEditor'] = "
textareaEditor : function(column){
        return new Ext.form.TextArea({
            allowBlank: true,
            listeners: {
                blur: {
                    scope: this,
                    fn: function(field){
                          //on blur without specialkey reset to old value
                          field.setValue(field.value);
                          //this.updateSelected(column,newValue);
                    }                
                },
                focus: {
                    scope: this,
                    fn: function(){
                        //remember currently selected records
                        this.setSelectedRecords();
                    }
                },
                specialkey: {
                    scope: this,
                    fn: function(field, e){
                        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
                        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
                        if (e.getKey() == e.ENTER || e.getKey() == e.TAB) {
                            //remember currently selected records
                            this.setSelectedRecords();
                            //update all selected records with the new value
                            this.updateSelected(column,field.getValue());
                        }
                    } 
                }                             
            }
        });        
    }
        
";

$editors['this.fileEditor'] = "
fileEditor : function(column){
		MODx.combo.BrowserEditor = function(config) {
		    config = config || {};
		    Ext.applyIf(config,{
		    	
		    });
		    MODx.combo.BrowserEditor.superclass.constructor.call(this,config);
		    this.config = config;
		};
		Ext.extend(MODx.combo.BrowserEditor,MODx.combo.Browser,{
		    onTriggerClick : function(btn){
		        if (this.disabled){
		            return false;
		        }
		
	            this.browser = MODx.load({
	                xtype: 'modx-browser'
	                ,closeAction: 'close'
	                ,id: Ext.id()
	                ,multiple: true
	                ,source: this.config.source || MODx.config.default_media_source
	                ,hideFiles: this.config.hideFiles || false
	                ,rootVisible: this.config.rootVisible || false
	                ,allowedFileTypes: this.config.allowedFileTypes || ''
	                ,wctx: this.config.wctx || 'web'
	                ,openTo: this.config.openTo || ''
	                ,rootId: this.config.rootId || '/'
	                ,hideSourceCombo: this.config.hideSourceCombo || false
	                ,listeners: {
	                    'select': {fn: function(data) {
	                    	this.show();
	                    	this.focus();
	                        this.setValue(data.relativeUrl);
	                        this.fireEvent('select',data,this);
	                    },scope:this}
	                    ,'close': {fn: function() {
	                        this.show();
	                        this.focus();
	                    },scope:this}
	                    ,'hide': {fn: function() {
	                    	this.show();
	                        this.focus();
	                    },scope:this}
	                }
	            });
		        this.browser.show(btn);
		        this.hide();
		        return true;
		    }
		
		    ,onDestroy: function(){
		        MODx.combo.BrowserEditor.superclass.onDestroy.call(this);
		    }
		});
		Ext.reg('modx-combo-browser-editor',MODx.combo.BrowserEditor);
		
		
		
        return new MODx.combo.BrowserEditor({
            allowBlank: true,
            hideSourceCombo: true,
            triggerClass: 'x-form-image-trigger',
			listeners: {
				blur: {
                    scope: this,
                    fn: function(field){
                    	  if(field.browser&&!field.browser.win.hidden)return false;
                    	  field.setValue(field.value);
                    }                
                },
				'select': {fn:function(data,field) {
					this.setSelectedRecords();
                    this.updateSelected(column,field.getValue());
                },scope:this}
                ,specialkey: {
                    scope: this,
                    fn: function(field, e){
                        if (e.getKey() == e.ENTER || e.getKey() == e.TAB) {
                            this.setSelectedRecords();
                            this.updateSelected(column,field.getValue());
                        }
                    } 
                }
			}
        });        
    }
        
";



$editors['this.dropdownEditor'] = "
dropdownEditor : function(column,row){
    //console.log(column);
    //console.log(this,column,row);
        
    box =  new MODx.combo.ComboBox({
        typeAhead: false
        ,forceSelection: false
        ,autoSelect: false
        ,editable: true
        ,triggerAction: 'all'
        ,url: '[[+config.connectorUrl]]'
        ,fields: ['combo_id','combo_name']
        ,displayField: 'combo_name'
        ,valueField: 'combo_id'    
        ,pageSize: 0
        ,lazyInit: false
        ,selectOnFocus: true
        ,baseParams: { 
            action: 'mgr/migxdb/process',
            processaction: 'geteditorcombo',
            configs: '[[+config.configs]]',
            resource_id: '[[+config.resource_id]]',
            field: column.dataIndex.replace('__rendered','')
        }			
        ,listeners: {
            'select': {
                fn: function(tf,nv,ov){
                	column.dataIndex=column.dataIndex.replace('__rendered','');
                    this.setSelectedRecords();
                    this.updateSelected(column,tf.getValue());
                }, 
                scope: this
            }
            ,'focus': {
                fn: function(tf){
                	tf.store.load();
                }, 
                scope: this
            }
        }
        ,listClass: 'x-combo-list-small'
 	})
     
     box.store.addListener('beforeload',function(s,o){
     	var record = this.getSelectionModel().getSelected();
     	o.params.record_json=Ext.util.JSON.encode(record.json);
     },this,{});
     /*box.store.addListener('load', function() {
        this.loaded = false;
    },box,{});*/
     
     return box;
}            
        
";








$renderer['this.renderTextarea'] = "
renderTextarea : function(val, md, rec, row, col, s) {
    return val.replace(/\\n/g,'<br>');
}
";
$renderer['this.renderBool'] = "
renderBool : function(val, md, rec, row, col, s) {
	var iconclass = (val) ? 'icon-dot-circle-o' : 'icon-circle-o';
	return '<div style=\"text-align:center;\"><i class=\"icon ' + iconclass + '\"></i></div>';
}
";



$gridactionbuttons['uploadfilestopath']['text'] = "'[[%migx.upload_directly]]'";
$gridactionbuttons['uploadfilestopath']['handler'] = 'this.uploadFilesToPath,this.addFromPath';
$gridactionbuttons['uploadfilestopath']['scope'] = 'this';
$gridactionbuttons['uploadfilestopath']['standalone'] = '1';




$g_columns = $this->customconfigs['columns'];
$g_item=array();
foreach ($g_columns as $c_key => $g_column) {
	$g_item[$g_column['dataIndex']]=isset($g_column['default']) ? $g_column['default'] : '';
}


$gridfunctions['this.uploadFilesToPath'] = "
    uploadFilesToPath: function(btn,e) {
    	var maxFileSize = parseInt(MODx.config['upload_maxsize'], 10);
	    var permittedFileTypes = MODx.config['upload_files'].toLowerCase().split(',');
	
	    FileAPI.debug = false;
	    FileAPI.staticPath = MODx.config['manager_url'] + 'assets/fileapi/';
	
	    var api = {
	        humanFileSize: function(bytes, si) {
	            var thresh = si ? 1000 : 1024;
	            if(bytes < thresh) return bytes + ' B';
	            var units = si ? ['kB','MB','GB','TB','PB','EB','ZB','YB'] : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
	            var u = -1;
	            do {
	                bytes /= thresh;
	                ++u;
	            } while(bytes >= thresh);
	            return bytes.toFixed(1)+' '+units[u];
	        },
	
	        getFileExtension: function(filename)
	        {
	            var result = '';
	            var parts = filename.split('.');
	            if (parts.length > 1) {
	                result = parts.pop();
	            }
	            return result;
	        },
	
	        isFileSizePermitted: function(size){
	            return (size <= maxFileSize);
	        },
	
	        formatBytes: function(size, unit){
	            unit = unit || FileAPI.MB;
	            return Math.round(((size / unit) + 0.00001) * 100) / 100;
	        }
	    };
    	MODx.util.MultiUploadDialog.MigxDialog = function(config) {
		    config = config || {};
		    Ext.applyIf(config,{});
		    MODx.util.MultiUploadDialog.MigxDialog.superclass.constructor.call(this,config);
		};
    	Ext.extend(MODx.util.MultiUploadDialog.MigxDialog,MODx.util.MultiUploadDialog.Dialog,{
    		uploaded_files:[],
    		startUpload: function(){
	            var dialog = this;
	            var files = [];
	            var params = Ext.apply(this.base_params, {
	                'HTTP_MODAUTH': MODx.siteId
	            });
	            var store = Ext.getCmp(this.filesGridId).getStore();
	
	            store.each(function(){
	                var file = this.get('file');
	                if(this.get('permitted') && !this.get('uploaded')){
	                    file.record = this;
	                    files.push(file);
	                }
	            });
	
				this.uploaded_files=[];
	            var xhr = FileAPI.upload({
	                url: this.url
	                ,data: params
	                ,files: { file: files }
	                ,fileprogress: function(evt, file){
	                    file.record.progressbar.updateProgress(
	                        evt.loaded/evt.total,
	                        _('upload.upload_progress', {
	                            'loaded': api.humanFileSize(evt.loaded),
	                            'total':  file.record.get('size')
	                        }),
	                        true);
	                }
	                ,filecomplete: function (err, xhr, file, options){
	                    if( !err ){
	                        var resp = Ext.util.JSON.decode(xhr.response);
	                        if(resp.success){
	                            file.record.set('uploaded', true);
	                            file.record.set('message', _('upload.upload.success'));
	                            dialog.uploaded_files = dialog.uploaded_files.concat(resp.object);
	                        }
	                        else{
	                            file.record.set('permitted', false);
	                            file.record.set('message', resp.message);
	                        }
	                    }
	                    else{
	                        if(xhr.status !== 401){
	                            MODx.msg.alert(_('upload.msg.title.error'), err);
	                        }
	                    }
	                }
	                ,complete: function(err, xhr){
	                    dialog.fireEvent('uploadsuccess');
	                }
	            });
	        }
    	});
    	Ext.reg('multiupload-window-migxdialog',MODx.util.MultiUploadDialog.MigxDialog);
    
    
    
    	path = '".$this->customconfigs['upload_path']."'||'/';
        if (!this.uploader) {
            this.uploader = new MODx.util.MultiUploadDialog.MigxDialog({
                url: MODx.config.connector_url
                ,base_params: {
                    action: 'browser/file/upload'
                    ,wctx: MODx.ctx || ''
                    ,source: [[+config.media_source_id]]
                    ,path:path
                    ,create_folders:true
                }
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('uploadsuccess',this.addFromPath,this);
            this.uploader.on('show',function(){
            	newpath = path;
            	path_field = Ext.getCmp('migx_uploader_path');
            	alias_field = Ext.getCmp('modx-resource-alias');
            	if(alias_field)
		    	{
		    		alias = alias_field.getValue();
		    		if(alias!=='')newpath+=alias+'/';
		    	}
		    	path_field.setValue(newpath);
		    	this.uploader.base_params.path = newpath;
            },this);
	        this.uploader.items.add('specialpath',new MODx.Panel({
	        	layout: 'form'
	        	,margins: '10 0 0 0'
	        	,bwrapStyle: 'margin-top:10px;'
	        	,items:[{
	        	allowBlank: true
	        	,fieldLabel: 'Загрузить в'
	        	,id: 'migx_uploader_path'
	            ,name: 'path'
	            ,anchor: '100%'
	            ,xtype: 'textfield'
	        	,value: path
	        	,listeners: {change:{scope:this,fn:function(field){
	        		this.uploader.base_params.path = field.getValue();
	        	}}}
	        	}]
	        }));
        }
        this.uploader.base_params.source = [[+config.media_source_id]];
        this.uploader.show(btn);
    } 	
";

$gridfunctions['this.addFromPath'] = "
    addFromPath: function(btn,e) {
    	this.uploader.hide();
    	for(var i in this.uploader.uploaded_files)
    	{
    		if(!this.uploader.uploaded_files.hasOwnProperty(i))continue;
    		var newitem = ".json_encode($g_item).";
    		newitem.image = this.uploader.base_params.path+this.uploader.uploaded_files[i];
    		this.addNewItem(newitem);
    	}
        //this.loadFromSource(btn,e,'path='+this.uploader.base_params.path+'&mode=add');
    } 	
";


$this->modx->loadClass('sources.modMediaSource');
$source = modMediaSource::getDefaultSource($this->modx,$this->config['media_source_id']);
$source_properties = $source->getProperties();
if($source_properties['basePathRelative']['value']==true)$source_base_path = MODX_BASE_PATH.$source_properties['basePath']['value'];
else $source_base_path = $source_properties['basePath']['value'];


$gridactionbuttons['fillfrompath']['text'] = "'Добавить из папки'";
$gridactionbuttons['fillfrompath']['handler'] = 'this.getImagesFromPath';
$gridactionbuttons['fillfrompath']['scope'] = 'this';
$gridactionbuttons['fillfrompath']['standalone'] = '1';

$gridfunctions['this.getImagesFromPath'] = "
    getImagesFromPath: function(btn,e) {
    	
    	var __migx=this;
    	
    	//Окно
    	MODx.window.getImagesFromPath=function(config){
    		config = config || {};
    		Ext.applyIf(config,{
				closeAction: 'close'
				,items: this.getItems(config)
				,width:500
				,layout: 'anchor'
				,title: 'Выбор из папки'
				,buttons: [{
		            text: config.cancelBtnText || _('cancel')
		            ,scope: this
		            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
		        },{
		            text: config.saveBtnText || _('save')
		            ,cls: 'primary-button'
		            ,scope: this
		            ,handler: this.submit
		        }]
			});
			MODx.window.getImagesFromPath.superclass.constructor.call(this,config);
    	};
    	Ext.extend(MODx.window.getImagesFromPath,Ext.Window,{
    		getItems: function(config) {
				var items = [];
				
				this.fullpath = new Ext.form.Hidden({
					name:'fullpath'
				});
				
				items.push(this.fullpath,{
	                xtype: 'modx-field-parent-change',
	                fieldLabel: 'Путь до папки с изображениями',
	                id: '',
	                win: this,
	                anchor: '100%',
	                name: 'target-combo',
	                end: function (p) {
	                    var t = Ext.getCmp('modx-file-tree');
	                    if (!t)return;
	                    if(!p.path)return;
	                    
	                    var that=this;
	                    t.items.each(function(tt){
						    tt.removeListener('click',that.handleChangeParent,that);
		                    tt.on('click',tt._handleClick,tt);
		                    tt.disableHref = false;
						});
						
						this.win.fullpath.setValue(p.attributes.path);
	                    this.setValue(p.value);
	                    this.oldValue = false;
	                }
	                ,onTriggerClick: function() {
					    if (this.disabled) { return false; }
					    if (this.oldValue) {
					        this.fireEvent('end',{v: this.oldValue,d: this.oldDisplayValue});
					        return false;
					    }

					    var t = Ext.getCmp('modx-file-tree');
					    if (!t) {
					        var tp = Ext.getCmp('modx-leftbar-tabpanel');
					        if (tp) {
					            tp.on('tabchange',function(tbp,tab) {
					                if (tab.id == 'modx-file-tree-ct') {
					                    this.disableTreeClick();
					                }
					            },this);
					            tp.activate('modx-file-tree-ct');
					        } else {
					            MODx.debug('no tabpanel');
					        }
					        return false;
					    }
					
					    this.disableTreeClick();
					}
					
					,disableTreeClick: function() {
					    t = Ext.getCmp('modx-file-tree');
					    if (!t) {
					        MODx.debug('No tree found in disableTreeClick!');
					        return false;
					    }
					    this.oldDisplayValue = this.getValue();
					    this.oldValue = Ext.getCmp(this.config.parentcmp).getValue();
					
					    this.setValue('Выберите папку из дерева слева');
						
						var that=this;
						t.items.each(function(tt){
							tt.removeListener('click',tt._handleClick);
						    tt.on('click',that.handleChangeParent,that);
						    tt.disableHref = true;
						});

					    return true;
					}
	                ,handleChangeParent: function (node, e) {
	                    var t = Ext.getCmp('modx-file-tree');
	                    if (!t) { return false;}
	                    
	                    t.items.each(function(tt){
						    tt.disableHref = true;
						});
	                    
	                    this.fireEvent('end', {
	                        path: node.attributes.type !== 'file' ? node.id : false,
	                        value: Ext.util.Format.stripTags(node.id),
	                        attributes: node.attributes
	                    });
	                    e.preventDefault();
	                    e.stopEvent();
	                    return true;
	                }
				});
				
				return items;
			}
			,submit:function(){
				var source_path = '".$source_base_path."';
				var insource_path = this.fullpath.getValue().replace(new RegExp('^'+source_path,'g'),'');
				
				
				MODx.Ajax.request({
					url: MODx.config.connector_url
					,params: {
						action: 'browser/directory/getfiles'
						,source: [[+config.media_source_id]]
						,dir: insource_path
					}
					,method: 'post'
					,scope: this
					,listeners: {
						'success':{fn:function(r) {
							if(!r.success)return;
							
							for(var i in r.results){
					    		if(!r.results.hasOwnProperty(i))continue;
					    		if(r.results[i].preview&&r.results[i].thumb){
						    		var newitem = ".json_encode($g_item).";
						    		newitem.image = r.results[i].pathRelative;
						    		__migx.addNewItem(newitem);
					    		}
					    	}
					    	this.closeAction !== 'close' ? this.hide() : this.close();
						},scope:this}
						,'failure':{fn:function(r) { MODx.msg.alert(_('error'),r.message)},scope:this}
					}
				});
				
			}
    	});
    	Ext.reg('window-fillfrompath',MODx.window.getImagesFromPath);
    	
    	var pathwin = new MODx.window.getImagesFromPath({});
        pathwin.show(e.target);
    } 	
";


