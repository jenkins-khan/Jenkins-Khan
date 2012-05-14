(function($){

  var methods = {
    /**
     * Initialisation du plugin
     * @param opts
     */
    init: function(opts) {
      var options = $.extend({}, $.fn.createGroupRunForm.defaults, opts);
      
      return this.each(function(){
        var $this = $(this);
        var data = $this.data('jenkins-khan.createGroupRunForm');
        
        if (!data) {
          //enregistrement des options
          $(this).data('jenkins-khan.createGroupRunForm', {
            target : $this,
            options: options
          });
          
          //si le nom du groupe est vide ===> on met celui de la branche
          $(options.inputGitBranchSelector, $this).change(function(){
            if (0 == $(options.inputGroupNameSelector, $this).val().length) {
              $(options.inputGroupNameSelector, $this).val($(this).val());
            }
          });
          
          
          $this.createGroupRunForm('displayJobsForView', $this.createGroupRunForm('currentView'));

          //gestion du panier
          var cart = $(options.cartSelector, $this);
          cart.bind({
            'reinit': function(event) {
              $(this).trigger('changeValue', [$(options.jobsSelector + ' :checkbox:checked', $this).size()])
            },
            'changeValue': function (event, nb) {
              $('a', this).html(nb);
              $(this).attr(options.jobCounterAttribute, nb);
            },
            'add': function(event) {
              $(this).trigger('changeValue', [parseInt($(this).attr(options.jobCounterAttribute)) + 1])
            },
            'remove': function(event) {
              $(this).trigger('changeValue', [parseInt($(this).attr(options.jobCounterAttribute)) - 1])
            }
          });
          
          //branchement des évènements
          $(options.viewTabSelector, $this).not(options.cartSelector).click(function() {
            var viewName = $(this).attr(options.viewAttributeName);
            $(this).parents('ul').find('li').removeClass(options.tabLiActiveClass);
            $(this).addClass(options.tabLiActiveClass);
            $this.createGroupRunForm('displayJobsForView', viewName);
          });

          $(options.removeViewAllJobSelector, $this).click(function() {
            $this.createGroupRunForm('removeViewAllJob');
            cart.trigger('reinit');
          });
          $(options.addViewAllJobSelector, $this).click(function() {
            $this.createGroupRunForm('addViewAllJob');
            cart.trigger('reinit');
          });
          
          $(options.cartSelector).click(function() {
            $this.submit();
          });

          cart.trigger('reinit');
          
          //ajout d'un job, on met à jour le panier
          $(':checkbox', $(options.jobsContainerSelector, $this)).change(function(){
            if ($(this).attr('checked')) {
              cart.trigger('add');
            } else {
              cart.trigger('remove');
            }
          })
        }
      });
    },


    /**
     *  @return void
     */
    removeViewAllJob: function() {
      var options = $(this).data('jenkins-khan.createGroupRunForm').options;

      $(options.jobsSelector + ' :visible:checkbox', this).removeAttr('checked');
    },
    
    
    /**
     *  @return void
     */
    addViewAllJob: function() {
      var options = $(this).data('jenkins-khan.createGroupRunForm').options;

      $(options.jobsSelector + ' :visible:checkbox', this).attr('checked','checked');
    },

    /**
     * @return string
     */
    currentView: function() {
      var options = $(this).data('jenkins-khan.createGroupRunForm').options;
      return $(options.viewTabSelector + '.' + options.tabLiActiveClass, this).attr(options.viewAttributeName);
    },

    /**
     * 
     * @param view
     */
    displayJobsForView: function(view) {
      var options = $(this).data('jenkins-khan.createGroupRunForm').options;
      $(options.jobsSelector, this).hide();
      $(options.jobsSelector + '.' + view, this).css('display', 'block');
    }
  };

  /**
   * 
   * @param method
   */
  $.fn.createGroupRunForm = function( method ) {

    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Unknown ' +  method + ' in jenkins-khan.createGroupRunForm' );
    }
  };

  /**
   * 
   */
  $.fn.createGroupRunForm.defaults = {
    viewTabSelector:         '.jenkins-view li',
    inputGitBranchSelector:  ':input[name*="git_branch"]',
    inputGroupNameSelector:  ':input[name*="label"]',
    viewAttributeName:       'view',
    tabLiActiveClass:        'active',
    jobsSelector:            '.jobs',
    jobsContainerSelector:   '.jobs',
    removeViewAllJobSelector:'#removeViewAllJob',
    addViewAllJobSelector:   '#addViewAllJob',
    cartSelector:            '.cart',
    jobCounterAttribute:     'jobscounter'
  };

})( jQuery );
