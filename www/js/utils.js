Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function redirect(loc){
    if(loc){
        window.location = loc;
    } else {
        window.location.reload();
    }


    return false;
}

function showErrors(errors){
    for(field in errors){
        $('[name='+field+']').each(function(i,v){
            $v= $(v);

            if(!$v.parent().hasClass('controls')) {
                $v.wrap("<div class='control-group error inline' style='margin: 0'><div class='controls'/></div>");
                $v.after("<span class='help-inline' style='margin-top: -11px; margin-left: 10px' >"+errors[field]+"</span>");
            }

        });
    }
}

function sendForm(selector, errorCallBack, successCallback){

    selector.ajaxSubmit({
        dataType:'json',
        success: function(data){
            data = data.data;
            if(data.errors==undefined || Object.size(data.errors)){
                errorCallBack(data.errors);
            } else {
                successCallback(data)
            }
        }
    })
}

function _confirm(options) {
    if (!options) { options = {}; }

    var show = function(el, text) {
        if (text) { el.html(text); el.show(); } else { el.hide(); }
    }

    var url = options.url ? options.url : '';
    var data = options.data ? options.data : '';
    var ok = options.ok ? options.ok : 'Ok';
    var cancel = options.cancel ? options.cancel : 'Cancel';
    var title = options.title
    var text = options.text;
    var dialog = $('#confirm-dialog');

    if(!dialog.html()) dialog=$('<div class="modal hide fade" id="confirm-dialog">'+
                         '<div class="modal-header">'+
            '<a class="close" data-dismiss="modal">×</a>'+
        '<h3>Подтверждение</h3>'+
    '</div><div class="modal-body">&nbsp;</div>'+
        '<div class="modal-footer">'+
            '<a href="#" class="btn btn-danger">Ok</a>'+
            '<a href="#" class="btn btn-cancel" data-dismiss="modal">Cancel</a></div></div>');

    var header = dialog.find('.modal-header');
    var footer = dialog.find('.modal-footer');

    show(dialog.find('.modal-body'), text);
    show(dialog.find('.modal-header h3'), title);
    footer.find('.btn-danger').unbind('click').html(ok);
    footer.find('.btn-cancel').unbind('click').html(cancel);
    dialog.modal('show');

    var $deferred = $.Deferred();
    var is_done = false;
    footer.find('.btn-danger').on('click', function(e) {
        is_done = true;
        dialog.modal('hide');
        if (url) {
            $.ajax({
                url: url,
                data: data,
                type: 'POST'
            }).done(function(result) {
                    $deferred.resolve(result);
                }).fail(function() {
                    $deferred.reject();
                });
        } else {
            $deferred.resolve();
        }
    });
    dialog.on('hide', function() {
        if (!is_done) { $deferred.reject(); }
    })

    return $deferred.promise();
}

function generateFormFields(data){
   var html = '<div class="form-group">';
   for (var key in data){
      if(data[key]['label']){
         html+='<label>'+data[key]['label']+'</label>'
      }
      if(data[key]['options']){
         html+='<select class="form-control" name="params['+key+']">';
         for (var val in data[key]['options']){
            html+='<option value="'+val+'">'+data[key]['options'][val]+'</option>'
         }
         html+='</select>';
      }
   }
   return html+"</div>";
}