/**
 * Update selected comments status
 * @param status_id
 */
function setMarketStatus(status_id, el)
{
    $.ajax('/admin/store/market/updateIsActive', {
        type:"post",
        data:{
            YII_CSRF_TOKEN: $(el).attr('data-token'),
            ids: $.fn.yiiGridView.getSelection('marketsListGrid'),
            status:status_id
        },
        success: function(data){
            $.fn.yiiGridView.update('marketsListGrid');
            $.jGrowl(data);
        },
        error:function(XHR, textStatus, errorThrown){
            var err='';
            switch(textStatus) {
                case 'timeout':
                    err='The request timed out!';
                    break;
                case 'parsererror':
                    err='Parser error!';
                    break;
                case 'error':
                    if(XHR.status && !/^\s*$/.test(XHR.status))
                        err='Error ' + XHR.status;
                    else
                        err='Error';
                    if(XHR.responseText && !/^\s*$/.test(XHR.responseText))
                        err=err + ': ' + XHR.responseText;
                    break;
            }
            alert(err);
        }
    });
    return false;
}