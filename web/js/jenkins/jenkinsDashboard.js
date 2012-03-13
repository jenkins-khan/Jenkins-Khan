(function($){

  var methods = {
    /**
     * Initialisation du plugin
     * @param opts
     */
    init : function(opts) {
      var options = $.extend({}, $.fn.jenkinsDashboard.defaults, opts);
      
      return this.each(function(){
        var $this = $(this);
        var data = $this.data('pmsipilot.jenkinsDashboard');
        
        if (!data) {
          //enregistrement des options
          $(this).data('pmsipilot.jenkinsDashboard', {
            target : $this,
            options: options
          });
          
          $(options.deleteGroupSelector, $this).click(function(){
            return confirm('Are you sure you want to delete this build branch?');
          });

          $(options.removeBuild, $this).click(function(){
            return confirm('Are you sure you want to remove this build from build branch?');
          });
          
          window.setInterval(
            function(){
              $this.jenkinsDashboard('reloadGroup', options.urlReloadListGroupRun, $(options.contentGroupSelector, $this));
            }, 
            options.reloadDelay
          );
        }
      });
    },

    /**
     * 
     * @param url
     * @param groupContent
     */
    reloadGroup: function (url, groupContent) {
      $.ajax({
        url: url,
        context: groupContent,
        success: function (data) {
          $(this).html(data);
        }
      })
    } 
  };

  
  
  /**
   * 
   * @param method
   */
  $.fn.jenkinsDashboard = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Unknown ' +  method + ' in pmsipilot.jenkinsDashboard' );
    }
  };

  /**
   * 
   */
  $.fn.jenkinsDashboard.defaults = {
    deleteGroupSelector: 'a.delete-group-run',
    removeBuild: 'a.remove-build',
    contentGroupSelector: '.content',
    urlReloadListGroupRun: null,
    reloadDelay: 60000
  };

})( jQuery );
