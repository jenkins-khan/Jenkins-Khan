var shortCuts = {

  openLink : function (link) {
    var url = link.attr('href');
    if (typeof url != 'undefined')
    {
      window.location = url;
    }
  },

  init : function () {

    key('shift+/', function () {
      $('#shortcuts-help').modal('show')
    });

    key('c', function () {
      shortCuts.openLink($('a.add-run'));
    });

    key('a', function () {
      shortCuts.openLink($('a.add-build'));
    });

    key('j', function () {
      shortCuts.openLink($('a.group-run', $('a.group-run.active').parent().next()));
    });

    key('k', function () {
      shortCuts.openLink($('a.group-run', $('a.group-run.active').parent().prev()));
    });

  }

}

shortCuts.init();
