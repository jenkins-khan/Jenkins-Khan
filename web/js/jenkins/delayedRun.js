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
            opacity: 0.7,
          });

          //selection en masse
          $(options.addViewAllRunSelector).click(function(){
            $(options.delayedRunsSelector + ' :checkbox').each(function(){
              if (!$(this).attr('checked')) {
                $this.delayedRun('toggleCheckbox', this);
              }
            });
          });

          //déselection en masse
          $(options.removeViewAllRunSelector).click(function(){
            $(options.delayedRunsSelector + ' :checkbox').each(function(){
              if ($(this).attr('checked')) {
                $this.delayedRun('toggleCheckbox', this);
              }
            });
          });

          //click sur le li => propagation à la checkbox
          $(options.delayedRunsSelector).delegate(options.delayedRunSelector, 'click', function(event){
            var checkbox = $(':checkbox', this);
            //ca c'est moche ==> si deja la checkbox, on ne toggle pas => ca annule
            if ($(event.target).get(0) == checkbox.get(0)) {
              return;
            }
            $this.delayedRun('toggleCheckbox', checkbox);
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
    delayedRunSelector: '.delayed-run'
  };

})( jQuery );
