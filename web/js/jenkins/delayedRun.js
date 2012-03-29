(function($){

  var methods = {
    /**
     * Initialisation du plugin
     * @param opts
     */
    init: function(opts) {
      var options = $.extend({}, $.fn.delayedRun.defaults, opts);
      
      return this.each(function(){
        var $this = $(this);
        var data = $this.data('pmsipilot.delayedRun');
        
        if (!data) {
          //enregistrement des options
          $(this).data('pmsipilot.delayedRun', {
            target : $this,
            options: options
          });
          
          $(options.delayedRunsSelector, $this).sortable({
            opacity: 0.7
          });

          $(options.timePickerSelector, $this).datetimepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
            hour: 18,
            minute: 15,
            defaultDate: new Date()
          });
          
          //select all jobs
          $(options.addViewAllRunSelector).click(function(){
            $(options.delayedRunsSelector + ' :checkbox').each(function(){
              if (!$(this).attr('checked')) {
                $this.delayedRun('toggleCheckbox', this);
              }
            });
          });

          //unselect jobs
          $(options.removeViewAllRunSelector).click(function(){
            $(options.delayedRunsSelector + ' :checkbox').each(function(){
              if ($(this).attr('checked')) {
                $this.delayedRun('toggleCheckbox', this);
              }
            });
          });

          //click on li > check the checkbox
          $(options.delayedRunsSelector).delegate(options.delayedRunSelector, 'click', function(event){
            var checkbox = $(':checkbox', this);
            //ca c'est moche ==> si deja la checkbox, on ne toggle pas => ca annule
            if ($(event.target).get(0) == checkbox.get(0)) {
              return;
            }
            
            if ($(event.target).parents('.input-append').size() > 0)  {
              return;
            }
            
            $this.delayedRun('toggleCheckbox', checkbox);
          });
          
          //clear "scheluded at"'s input
          $($this).delegate(options.clearTimeSelector, 'click', function(event) {
            $(this).parents('.input-append').find(':input').val('');
          });
          
        }   
      });
    },
    
    toggleCheckbox: function(checkbox) {
      if ($(checkbox).attr('checked')) {
        $(checkbox).removeAttr('checked');
      } else {
        $(checkbox).attr('checked', 'checked')
      }
    }
  };

  /**
   * 
   * @param method
   */
  $.fn.delayedRun = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Unknown ' +  method + ' in pmsipilot.delayedRun' );
    }
  };

  /**
   * 
   */
  $.fn.delayedRun.defaults = {
    removeViewAllRunSelector:'#removeViewAllRun',
    addViewAllRunSelector:   '#addViewAllRun',
    delayedRunsSelector: '.delayed-runs',
    delayedRunSelector: '.delayed-run',
    timePickerSelector: '.timepicker',
    clearTimeSelector: '.jk-icon-clock-delete'
  };

})( jQuery );
