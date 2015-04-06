/*  Datatable version 1.0.0
 *  Normosa Technologies JavaScrnt Framework
 *  (c) 2011 Normosa Technologies
 *  @autor Norman Osaruyi
 *
 *  For details contact js-support@normosa.com
 *
 *--------------------------------------------------------------------------*/

normosa.prototype.datatable = function(e){
    var  instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.objectCode = instance.ui.id;
    instance.dialog = null;
    instance.newDialog = null;
    instance.working = false;
    instance.isEditing = false;
    instance.isSelected = false;
    instance.pageInfo = null;
    instance.forms = {};

    function _init(){
        instance.dataRows = {};
        instance.inherit(NT.Core.events);
        instance.inherit(NT.Core.ui);
        instance.events.onEditing = new Array();
        instance.events.onSaved = new Array();
        instance.events.onScrolling = new Array();
        instance.isAllSelected = false;
        instance.addEventListener('onScrolling', onScrolling);
        $('#'+ instance.ui.id+ ' div.content div.tr').each(function(){
            instance.InitRow(this);
        });
        $('#'+ instance.ui.id+ ' div.info').each(function(){
            instance.pageInfo = $(this);
        });
        $('#'+ instance.ui.id+ ' div.content div.th span.select').click(function(){
            if(instance.isAllSelected){
                instance.isAllSelected = false;
                instance.UnSelectAll();
            }
            else{
                instance.SelectAll();
                instance.isAllSelected = true;
            }
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=new]').click(function(e){
            e.preventDefault();
            instance.New.call(this);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=edit]').click(function(e){
            e.preventDefault();
            instance.Edit.call(this);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=delete]').click(function(e){
            e.preventDefault();
            instance.Delete.call(this);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=reload]').click(function(e){
            e.preventDefault();
            instance.Reload.call(this);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=fullscreen]').click(function(e){
            e.preventDefault();
            instance.toggleFullScreen();
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[execute]').click(function(e){
            e.preventDefault();
            eval(this.getAttribute('execute')+'(this, instance)');
        });
        $('#'+ instance.ui.id+ ' div.scrollpane').scroll(function(){
            instance.trigger('onScrolling', null);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] div[class=pagination] a[action=back]').click(function(e){
            e.preventDefault();
            instance.Back.call(this);
        });
        $('#'+ instance.ui.id+ ' div[class=toolbar] div[class=pagination] a[action=next]').click(function(e){
            e.preventDefault();
            instance.Next.call(this);
        });
        $('#'+ instance.ui.id+ ' div.content div.tr span[expand]').click(function(e){
            e.preventDefault();
            instance.Expand.call(this);
        });
    }

    instance.InitRow = function(row){
        instance.dataRows[row.id] = $n(row).dataRow({
            parent:instance
        });
    }

    instance.New = function(){
        instance.newDialog = $n('').dialog({
            title:$(this).attr('title'),
            url:$(this).attr('href')+'&ajax=true',
            parent:instance
        });
    }

    instance.Expand = function(){
        if(this.innerHTML == 'Expand'){
            this.innerHTML = 'Collapse';
            $('#expand_'+$(this).attr('expand')).show();
        }
        else{
            this.innerHTML = 'Expand';
            $('#expand_'+$(this).attr('expand')).hide();
        }
    }

    instance.forms.submit = function(e){
        e.preventDefault();
        var caller = this;
        instance.doWaiting(true, "Inserting Items...");
        var data = {};
        for(var i = 0; i < this.elements.length; i++){
            data[this.elements[i].id] = this.elements[i].val();
        }
        $.ajax({
            type:'POST',
            url:$(caller.ui).attr('action')+'&ajax=true',
            data:data,
            success:function(data){
                if(!isError(data)){
                    caller.dispose();
                    instance.newDialog.close();
                    var div = document.createElement('div');
                    div.innerHTML = data;
                    $(div).children('div.tr').each(function(){
                        $('#'+ instance.ui.id+ ' div.content').append(this);
                        instance.InitRow(this);
                    });
                }
                else{
                    
                }
                instance.doWaiting(false);
            }
        });
    }

    instance.Reload = function(){
        $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=edit]').each(function(){
            this.innerHTML = 'Edit';
        });
        instance.isEditing = false;
        instance.doWaiting(true, 'Reloading...');
        clearRows();
        $.ajax({
            type:'GET',
            url:$(this).attr('href')+'&action=reload&ajax=true',
            success:function(data){
                if(!isError(data)){
                    var div = document.createElement('div');
                    div.innerHTML = data;
                    $(div).children('div.info').each(function(){
                        instance.pageInfo = $(this);
                    });
                    $(div).children('div.tr').each(function(){
                        $('#'+ instance.ui.id+ ' div.content').append(this);
                        instance.InitRow(this);
                    });
                    instance.doWaiting(false);
                    instance.trigger('onScrolling', null);
                }
                else{
                    instance.doWaiting(false);
                }
            }
        });
        return true;
    }

    instance.Back = function(){
        var btn_back = $(this);
        var url =  btn_back.attr('href');
        var btn_reload = $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=reload]');
        var index = url.indexOf('page=', 0) + 5;
        var page = $('#'+ instance.ui.id+ ' div[class=toolbar] input[type=text]');
        if(parseInt(page.val()) < 1){
            page.val(2);
        }
        var backPage = parseInt(page.val()) - 1;
        if(backPage > 0){
            url = url.substring(0, index) + backPage;
            btn_reload.attr('href', url);
            instance.parent.changeUrl(url);
            page.val(backPage);
            instance.Reload.call(btn_reload[0]);
            if(backPage > 1){
                btn_back.attr('href', url.substring(0, index) + (backPage - 1));
            }
        }  
    }

    instance.Next = function(){
        var btn_next = $(this);
        var url =  btn_next.attr('href');
        var btn_reload = $('#'+ instance.ui.id+ ' div[class=toolbar] a[action=reload]');
        var index = url.indexOf('page=', 0) + 5;
        var page = $('#'+ instance.ui.id+ ' div[class=toolbar] input[type=text]');
        if(parseInt(page.val()) > btn_next.attr('max')){
            page.val(btn_next.attr('max') - 1);
        }
        var nextPage = parseInt(page.val()) + 1;
        if(nextPage <=  btn_next.attr('max')){
            url = url.substring(0, index) + nextPage;
            btn_reload.attr('href', url);
            instance.parent.changeUrl(url);
            page.val(nextPage);
            instance.Reload.call(btn_reload[0]);
            if(nextPage < btn_next.attr('max')){
                btn_next.attr('href', url.substring(0, index) + (nextPage + 1));
            }
        }
    }

    instance.Delete = function(){
        if(!window.confirm("Do you really want to Perform this Operation"))
            return;
        instance.doWaiting(true, 'Removing Items...');
        var jsonData = new Array();
        $('#'+instance.ui.id+ ' div.content div.tr.selected').each(function(){
            jsonData[jsonData.length] = instance.dataRows[this.id].objectCode;
        });
        if(jsonData.length == 0){
            instance.doWaiting(false);
            return;
        }
        $.ajax({
            type:'POST',
            url:$(this).attr('href')+'&ajax=true',
            data:'data='+JSON.stringify(jsonData),
            success:function(data){
                if(data.length == 0){
                    $('#'+instance.ui.id+ ' div.content div.tr.selected').each(function(){
                        instance.RemoveRow(this);
                    });
                }
                else{
                    $n('').dialog({
                        title:'Error',
                        content:data
                    });
                }
                instance.doWaiting(false);
            }
        });
    }

    instance.Edit = function(){
        if(instance.isEditing){
            instance.Save.call(this);
        }
        else{
            instance.isEditing = true;
            instance.trigger('onEditing', null);
            this.innerHTML = 'Save';
        }
        return true;
    }

    instance.doWaiting = function(e, text){
        if(instance.dialog == null){
            instance.dialog = $n('').dialog({
                title:text
            });
        }
        if(e == true){
            instance.dialog.animate();
            instance.isWorking = true;
        }
        else{
            instance.dialog.close();
            instance.dialog = null;
            instance.isWorking = false;
        }
    }
    
    instance.toggleFullScreen = function(){
        var _ui = $(instance.ui);
        var _scroll = $('#'+instance.ui.id+' div.scrollpane');
        var _header = $('#'+instance.ui.id+' div.th');
        var _header_fix = $('#'+instance.ui.id+' div.th_fix');
        if(_ui.css('position') == 'fixed'){
            _ui.css('position','static');
            _header.css('position', 'static');
            _header_fix.hide();
            _scroll.css('height', _scroll.attr('dheight'));
            instance.trigger('onScrolling', null);
        }
        else{
            _ui.css('right', '0');
            _ui.css('position', 'fixed');
            _header.css('position', 'fixed');
            _header.css('min-width', 'inherit');
            _header_fix.show();
            _ui.css('left', '0');
            _ui.css('top', '0');
            _ui.css('bottom', '0');
            _scroll.attr('dheight', _scroll.css('height'));
            _scroll.css('width', '100%');
            _scroll.css('height', $(window).height() - $('#'+instance.ui.id+' div.toolbar').height());
            instance.trigger('onScrolling', null);
        }
    }

    instance.Save = function(){
        var caller = this;
        instance.doWaiting(true, 'Saving Items...');
        var jsonData = null;
        $.each(instance.dataRows, function(){
            if(this.isDirty){
                if(jsonData == null)
                    jsonData = {};
                jsonData[this.objectCode] = this.val();
            }
        });
        if(jsonData != null){
            $.ajax({
                type:'POST',
                url:$(this).attr('href')+'&ajax=true',
                data:'data='+JSON.stringify(jsonData),
                success:function(data){
                    if(!isError(data)){
                        instance.trigger('onSaved', null);
                        var div = document.createElement('div');
                        $(div).children('div.tr').each(function(){
                            var index = 0;
                            var row = this;
                            $('#'+row.id).children('span').each(function(){
                                if($(row.childNodes[index]).html() != null){
                                    instance.dataRows[row.id].isDirty = false;
                                    this.innerHTML = row.childNodes[index].innerHTML;
                                    index++;
                                }
                            });
                        });
                        caller.innerHTML = 'Edit';
                        instance.isEditing = false;
                        instance.doWaiting(false);
                    }
                    else{
                        instance.doWaiting(false);
                    }
                }
            });
        }
        else{
            instance.trigger('onSaved', null);
            caller.innerHTML = 'Edit';
            instance.doWaiting(false);
            instance.isEditing = false;
        }
    }

    function onScrolling(){
        if(instance.isWorking){
            return;
        }
        var scroll = $('#'+ instance.ui.id+ ' div.scrollpane')[0];
        var header = $('#'+ instance.ui.id+ ' div.th');
        if(header.css('position') == 'fixed'){
            $('#'+ instance.ui.id+ ' div.th').css('left','-'+$(scroll).scrollLeft()+'px');
        }
    }

    function isError(e){
        var div = document.createElement('div');
        div.innerHTML = e;
        if($(div).children('span.error').length > 0){
            $n('').dialog({
                title:'Error',
                content:$(div).children('span.error')[0].innerHTML
            });
            return true;
        }
        return false;
    }

    instance.SelectAll = function(){
        $('#'+ instance.ui.id + ' div.tr').each(function(){
            if($(this).hasClass('notselected'))
                $(this).removeClass('notselected');
            $(this).addClass('selected');
        });
        instance.isSelected = true;
    }

    instance.UnSelectAll = function(){
        $('#'+ instance.ui.id + ' div.tr').each(function(){
            if($(this).hasClass('selected'))
                $(this).removeClass('selected');
            $(this).addClass('notselected');
        });
        instance.isSelected = false;
    }

    instance.SelectRow = function(obj){
        if(!NT.Keys.ctrl.isPress)
            instance.UnSelectAll();
        if($(obj.parentNode).hasClass('notselected')){
            $(obj.parentNode).removeClass('notselected');
            $(obj.parentNode).addClass('selected');
        }
        else{
            $(obj.parentNode).removeClass('selected');
            $(obj.parentNode).addClass('notselected');
        }
    }

    instance.RemoveRow = function(row){
        instance.dataRows[row.id].RemoveRow();
    }

    function clearRows(){
        instance.isSelected = false;
        $('#'+ instance.ui.id+ ' div.content div.tr').each(function(){
            instance.RemoveRow(this);
        });
    }

    instance.inherit(NT.Core.base);

    return instance;
}

normosa.prototype.dataRow = function(e){
    var instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.isEditing = false;
    instance.isDirty = false;
    instance.dataColumns = {};
    instance.objectCode = instance.ui.id;

    function _init(){
        instance.inherit(NT.Core.events);
        instance.inherit(NT.Core.ui);
        var index = 0;
        var cols = $('#'+ instance.parent.ui.id+ ' div.content div.th span');
        $($(instance.ui).children('span')[0]).click(function(){
            instance.parent.SelectRow(this);
        });
        $(instance.ui).children('span').each(function(){
            var col = $(cols[index]);
            if(col.attr('datacolumn') != null){
                var datacolumn = col.attr('datacolumn');
                instance.dataColumns[index] = eval('$n(this).'+datacolumn+'({parent:instance,col:col})');
            }
            index++;
        });
        instance.toggleEdit();
        instance.addEventListener('disposing', onDispose);
        instance.parent.addEventListener('onEditing', instance.toggleEdit);
        instance.parent.addEventListener('onScrolling', instance.toggleEdit);
        instance.parent.addEventListener('onSaved', instance.toggleSave);
    }

    function onDispose(){
        delete instance.parent.dataRows[instance.objectCode];
    }

    instance.RemoveRow = function(){
        instance.ui.parentNode.removeChild(instance.ui);
        instance.dispose();
    }

    instance.canEdit = function(){
        if(instance.parent.isEditing){
            var ui = $('#'+ instance.parent.ui.id+ ' div.scrollpane')[0];
            var top = instance.getDimension().top - (instance.getDimension(ui).top + $(ui).scrollTop());
            var height = instance.getDimension(ui).height;
            if(top < height){
                return true;
            }
        }
        return false;
    }

    instance.toggleEdit = function(){
        if(!instance.isEditing && instance.canEdit()){
            instance.isEditing = true;
            $.each(instance.dataColumns, function(){
                this.toggleEdit();
            });
        }
    }

    instance.toggleSave = function(){
        if(instance.isEditing){
            instance.isEditing = false;
            instance.isDirty = false;
            $.each(instance.dataColumns, function(){
                this.toggleSave();
            });
        }
    }

    instance.val = function(){
        var rowData = {};
        $.each(instance.dataColumns, function(){
            rowData[this.col.attr('name')] = this.val();
        });
        return rowData;
    }

    instance.inherit(NT.Core.base);
    
    return instance;
}

normosa.prototype.DataColumnTextbox = function(e){
    var instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.col = e.col;
    instance.txt = document.createElement('input');
    instance.txt.type = 'text';
    instance.objectCode = 'datacolumntextbox_'+(Math.floor(Math.random() * (999999999 - 100000000 + 1)) + 100000000);
    instance.ui.id = instance.objectCode;

    function _init(){
        instance.inherit(NT.Core.events);
        instance.inherit(NT.Core.ui);
        instance.col = $(e.col);
        instance.value = instance.ui.innerHTML;
        instance.error = '';
        var _ui = $(instance.ui);
        if(instance.col.attr('validation') != null)
            _ui.attr('validation', instance.col.attr('validation'));
        if(instance.col.attr('min') != null)
            _ui.attr('min', instance.col.attr('min'));
        if(instance.col.attr('max') != null)
            _ui.attr('max', instance.col.attr('max'));
        if(instance.col.attr('plugin') != null)
            _ui.attr('plugin', instance.col.attr('plugin'));
        if(_ui.attr('validation') != null)
            eval('instance.inherit(NT.Core.Validation.'+_ui.attr('validation')+')');
        else
            instance.inherit(NT.Core.Validation.String);
        $(instance.txt).blur(_blur);
    }

    instance.toggleEdit = function(){
        instance.txt.value = instance.ui.innerHTML;
        $(instance.ui).empty().append(instance.txt);
    }

    instance.toggleSave = function(){
        instance.ui.innerHTML = instance.txt.value;
        instance.value = instance.ui.innerHTML;
    }

    function _blur(){
        var old = instance.value;
        var isValid = instance.validate();
        if(isValid != true){
            instance.txt.value = old;
        }
        else if(instance.value != instance.txt.value){
            instance.parent.isDirty = true;
            instance.value = instance.txt.value;
        }
        
    }

    instance.val = function(){
        return instance.txt.value;
    }

    instance.showError = function(e){
        alert(e);
    }

    instance.inherit(NT.Core.base);
    return instance;
}

normosa.prototype.DataColumnSelect = function(e){
    var instance = this;
    instance.init = _init;
    instance.parent = e.parent;
    instance.input = document.createElement('select');
    instance.objectCode = 'datacolumnselect_'+(Math.floor(Math.random() * (999999999 - 100000000 + 1)) + 100000000);

    function _init(){
        instance.inherit(NT.Core.events);
        instance.inherit(NT.Core.ui);
        instance.col = $(e.col);
        instance.value = instance.ui.innerHTML;
        instance.error = '';
    }

    instance.toggleEdit = function(){
        $(instance.input).change(onChange);
        if(instance.input.options.length > 0){
            $(instance.ui).empty().append(instance.input);
            return;
        }
        $(instance.input).empty();
        $('#'+instance.parent.parent.ui.id+' div.datasource').children().each(function(){
            if(this.id == $(instance.col).attr('datasource')){
                for(var i = 0; i < this.options.length; i++){
                    var opt = document.createElement('option');
                    opt.innerHTML = this.options[i].innerHTML;
                    if(this.options[i].getAttribute('value') != null){
                        opt.value = this.options[i].getAttribute('value');
                    }
                    if(opt.innerHTML == instance.ui.innerHTML){
                        opt.setAttribute('selected', true);
                        instance.value = opt.value;
                    }
                    $(instance.input).append(opt);
                }
            }
        });
        $(instance.ui).empty().append(instance.input);
    }

    instance.toggleSave = function(){
        $(instance.ui).empty().remove(instance.input);
        for(var i = 0; i < instance.input.options.length; i++){
            if(instance.input.options[i].selected){
                instance.ui.innerHTML = instance.input.options[i].innerHTML;
            }
        }
        instance.value = instance.ui.innerHTML;
    }

    function onChange(){
        if(instance.value != $(instance.input).val()){
            instance.value = $(instance.input).val();
            instance.parent.isDirty = true;
        }
    }

    instance.val = function(){
        return $(instance.input).val();
    }

    instance.showError = function(e){
        alert(e);
    }

    instance.inherit(NT.Core.base);
    return instance;
}