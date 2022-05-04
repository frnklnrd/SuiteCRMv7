var html_new = "";
$(document).ready(function() {
    $('#conditions_label').css('display','none');
    html_new = $('#condition_new').html();
    $('#condition_new').remove();

    $('#conditions_table > tbody').sortable({ 
      placeholder: 'condition_place_holder', 
      forceHelperSize: true,
      tolerance: 'pointer',
      handle: '.condition_handle',
      opacity: 0.5,
      helper: function(event, ui){
        var $clone =  $(ui).clone();
        $clone .css('position','absolute');
        return $clone.get(0);
      },
      start: function (e, ui) {
          ui.placeholder.html('<td colspan="8">&nbsp;</td>');
      },
      stop: function(e,ui) {
        $('#conditions_table > tbody > tr').each(function(index,value){
          var new_index = value.rowIndex - 1;
          var input_sort_index = $(this).find('#sort_index');
          input_sort_index.attr('value',new_index);
        });
      }
       }).disableSelection();
});



function newCondition(){
    var id_row = generateUUID();
    var html = html_new.replaceAll('new', id_row);
    html = '<tr id="condition_'+id_row+'">'+html+'</tr>';
    $('#conditions_table > tbody').append(html);
    $('#conditions_table > tbody > tr').each(function(index,value){
          var new_index = value.rowIndex - 1;
          var input_sort_index = $(this).find('#sort_index');
          input_sort_index.attr('value',new_index);
        });
}

function deleteCondition(id_row){
    $('#'+id_row).remove();
}


function generateUUID() {
   var d = new Date().getTime();
   var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
       var r = (d + Math.random()*16)%16 | 0;
       d = Math.floor(d/16);
       return (c=='x' ? r : (r&0x3|0x8)).toString(16);
   });
   return uuid;
}


String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};