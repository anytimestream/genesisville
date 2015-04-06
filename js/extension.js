function prepaid_user_form(data){
    var val = $('#user').val();
    $('#submit').hide();
    $('#wait').show();
    if(data.length > 0){
        if(val.length > 0){
            $('#prepaid_new_user').hide();
            $('#name').val('####');
            $('#password').val('00000000000');
            $('#phone').val('00000000000');
            $('#email').val('info@example.com');
            $('#address').val('##########');
            $('#city').val('####');
        }
        else{
            $('#prepaid_new_user').show();
            $('#name').val('');
            $('#password').val('');
            $('#phone').val('');
            $('#email').val('');
            $('#address').val('');
            $('#city').val('');
        }
    }
    var action = $('.prepaid_user').attr('action').substr(0, $('.prepaid_user').attr('action').indexOf("?", 0));
    $.ajax({
        type:'GET',
        url:action+'?action=get-start-date&user='+$('#user').val()+"&plan="+$('#plan').val()+"&tenure="+$('#tenure').val(),
        success:function(data){
            var row = JSON.parse(data);
            if(row['start'] != null){
                $('#start').val(row['start']);
                $('#amount').val(row['amount']);
            }
            $('#submit').show();
            $('#wait').hide();
        }
    });
}

function print_ticket(e,handle){
    $('#'+handle.objectCode+ ' div.content div.tr.selected').each(function(){
        window.open($(e).attr('href')+'&id='+this.id, 'Receipt', 'toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=no, resizable=no, copyhistory=no, width=380, height='+(screen.height - 100)+', top=0, left=0');
    });
}

function phone2(){
    if(this.val().length > 0 && this.val().length < 11){
        return 'Phone Must be 11 digits';
    }
    return true;
}

function select_cust(){
    if(this.val() == '__default__'){
        return 'Please select a customer';
    }
    return true;
}

function get_cust_payment(){
    var data = {};
    $.each($('#form_payment')[0].elements, function(){
        var element = $(this);
        data[element.attr('id')] = element.val();
    });
    $.ajax({
        type:'POST',
        url:_base+'backend/wireless/customers/payments?action=get_cust_payment&ajax=true',
        data:data,
        success:function(data){
            var row = JSON.parse(data);
            if(row['expires'] != null){
                $('#expires').val(row['expires']);
                $('#amount').val(row['amount']);
            }
        }
    });
}

function send_sms(e,handle){
    $('#'+handle.objectCode+ ' div.content div.tr.selected').each(function(){
        var id = this.id;
        var dialog = null;
        var instance = {
            init: function(){
                dialog = $n('').dialog({
                    title:$(e).attr('title'),
                    url:$(e).attr('href')+'&id='+id,
                    parent:instance
                });
            },
            registerComponents: function(ui){

            }

        }
        instance.init();
    });
}

