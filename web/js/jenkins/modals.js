$(document).ready(function(){

  $(".modal").on('show', function(){
    var header = $('.modal-header', this);
    var title_in_content = $(".modal-body [data-modal-title='add']", this);

    $("*", $(this)).show();
    
    if ($(title_in_content).size() > 0) {
      //inject title in modal header
      $(title_in_content).appendTo(header);
    }
  });
});
