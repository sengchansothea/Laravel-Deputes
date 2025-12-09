$(document).ready(function() {
  var engine, remoteHost, template, empty;

  $.support.cors = true;

  // Use local data instead of the remote demo proxy (avoids CORS/404 errors)
  remoteHost = '';
  template = Handlebars.compile($("#result-template").html());
  empty = Handlebars.compile($("#empty-template").html());

  // Helper function to build proper path for data files
  function getDataPath(relativePath) {
    var origin = window.location.origin || (window.location.protocol + '//' + window.location.host);
    var pathname = window.location.pathname;
    var segments = pathname.split('/').filter(Boolean);
    
    // Build base path - we're serving from /public/ folder
    var appBase = '/';
    if (segments.length > 0) {
      appBase = '/' + segments[0] + '/public/';
    }
    
    return origin + appBase + relativePath;
  }

    engine = new Bloodhound({
    identify: function(o) { return o.id_str; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name', 'screen_name'),
    dupDetector: function(a, b) { return a.id_str === b.id_str; },
    // load local sample data instead of remote demo proxy
    prefetch: getDataPath('assets/js/typeahead/data/repos.json')
  });

  // ensure default users are read on initialization
  engine.get('1090217586', '58502284', '10273252', '24477185')

  function engineWithDefaults(q, sync, async) {
    if (q === '') {
      sync(engine.get('1090217586', '58502284', '10273252', '24477185'));
      async([]);
    }

    else {
      engine.search(q, sync, async);
    }
  }

  $('#demo-input').typeahead({
    hint: $('.Typeahead-hint'),
    menu: $('.Typeahead-menu'),
    minLength: 0,
    classNames: {
      open: 'is-open',
      empty: 'is-empty',
      cursor: 'is-active',
      suggestion: 'Typeahead-suggestion',
      selectable: 'Typeahead-selectable'
    }
  }, {
    source: engineWithDefaults,
    displayKey: 'screen_name',
    templates: {
      suggestion: template,
      empty: empty
    }
  })
      .on('typeahead:asyncrequest', function() {
        $('.Typeahead-spinner').show();
      })
      .on('typeahead:asynccancel typeahead:asyncreceive', function() {
        $('.Typeahead-spinner').hide();
      });

});