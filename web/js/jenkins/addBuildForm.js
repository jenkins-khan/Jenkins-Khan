(function($){

  var methods = {
    /**
     * Initialisation du plugin
     * @param opts
     */
    init: function(opts) {
      var options = $.extend({}, $.fn.addBuildForm.defaults, opts);
      
      return this.each(function(){
        var $this = $(this);
        var data = $this.data('jenkins-khan.addBuildForm');
        
        if (!data) {
          //enregistrement des options
          $(this).data('jenkins-khan.addBuildForm', {
            target : $this,
            options: options
          });
          
          $(options.jobSelector, $this).change(function(){
            $this.addBuildForm('showJobParameters', $(this).val());
          });

          $this.addBuildForm('showJobParameters', $(options.jobSelector, $this).val());
        }   
      });
    },
    
    showJobParameters: function(jobName) {
      var options = $(this).data('jenkins-khan.addBuildForm').options;
      
      $(options.parametersSelector, this).hide();
      $(options.parametersSelector + '.' + jobName, this).show();
    }
  };

  /**
   * 
   * @param method
   */
  $.fn.addBuildForm = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Unknown ' +  method + ' in jenkins-khan.addBuildForm' );
    }
  };

  /**
   * 
   */
  $.fn.addBuildForm.defaults = {
    parametersSelector: '.parameters li',
    jobSelector: '#build_job'
  };

})( jQuery );
