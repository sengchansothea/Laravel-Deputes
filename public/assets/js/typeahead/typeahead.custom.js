(function($) {
  var substringMatcher = function(strs) {
    return function findMatches(q, cb) {
      var matches, substringRegex;
      matches = [];
      substrRegex = new RegExp(q, 'i');
      $.each(strs, function(i, str) {
        if (substrRegex.test(str)) {
          matches.push(str);
        }
      });
      cb(matches);
    };
  };
  var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
    'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
    'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
    'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
    'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
    'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
    'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
    'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
    'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
  ];
  $('#the-basics .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
      {
        name: 'states',
        source: substringMatcher(states)
      });
  var states = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: states
  });
  $('#bloodhound .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
      {
        name: 'states',
        source: states
      });
  var countries = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace
  });

  // Robust JSON loader with fallback URLs to avoid 404s/CORS errors
  function fetchJsonWithFallback(pathWithoutLeadingSlash, cb) {
    var origin = window.location.origin || (window.location.protocol + '//' + window.location.host);
    var pathname = window.location.pathname;
    var segments = pathname.split('/').filter(Boolean);
    
    // Build base path - we're serving from /public/ folder
    // So we need to construct path relative to app root with /public/
    var appBase = '/';
    if (segments.length > 0) {
      // Get everything except the last segments that are page/routes
      // The script is served from /disputes_server/public/assets/js/typeahead/
      // We need /disputes_server/public/
      appBase = '/' + segments[0] + '/public/';
    }

    var candidates = [
      origin + appBase + pathWithoutLeadingSlash,  // http://localhost/disputes_server/public/assets/...
      origin + '/' + pathWithoutLeadingSlash,       // http://localhost/assets/...
      appBase + pathWithoutLeadingSlash,            // /disputes_server/public/assets/...
      '/' + pathWithoutLeadingSlash,                // /assets/...
      '/public/' + pathWithoutLeadingSlash,         // /public/assets/...
      pathWithoutLeadingSlash                       // assets/...
    ];
    
    candidates = candidates.filter(function(v,i){return v && candidates.indexOf(v)===i;});

    var tried = 0;
    function tryNext() {
      if (tried >= candidates.length) {
        console.warn('All fallbacks failed for', pathWithoutLeadingSlash, 'tried:', candidates);
        return cb([]);
      }
      var url = candidates[tried++];
      $.getJSON(url).done(function(data){
        console.log('Successfully loaded', pathWithoutLeadingSlash, 'from:', url);
        cb(data);
      }).fail(function(){
        tryNext();
      });
    }
    tryNext();
  }

  // load countries and initialize typeahead when ready
  fetchJsonWithFallback('assets/js/typeahead/data/countries.json', function(data){
    countries.clear();
    if (data && data.length) {
      countries.local = data;
    }
    $('#prefetch .typeahead').typeahead(null, {
      name: 'countries',
      source: countries
    });
  });
  // bestPictures: load film data and use remote query files via safe fetch
  fetchJsonWithFallback('assets/js/typeahead/data/films/post_1960.json', function(data){
    var bestPictures = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      local: data || []
    });

    $('#remote .typeahead').typeahead(null, {
      name: 'best-pictures',
      display: 'value',
      source: function(q, sync, async) {
        bestPictures.search(q, sync);
        if (q && q.length) {
          var file = 'assets/js/typeahead/data/films/queries/' + encodeURIComponent(q) + '.json';
          fetchJsonWithFallback(file, function(qdata){
            if (qdata && qdata.length) {
              async(qdata);
            }
          });
        }
      }
    });
    // also initialize custom templates for best-pictures (uses bestPictures in this scope)
    $('#custom-templates .typeahead').typeahead(null, {
      name: 'best-pictures',
      display: 'value',
      source: bestPictures,
      templates: {
        empty: [
          '<div class="empty-message">',
          'unable to find any Best Picture winners that match the current query',
          '</div>'
        ].join('\n'),
        suggestion: Handlebars.compile('<div><strong>{{value}}</strong> – {{year}}</div>')
      }
    });
  });
  // load nfl teams
  fetchJsonWithFallback('assets/js/typeahead/data/nfl.json', function(nflData){
    var nflTeams = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      identify: function(obj) { return obj.team; },
      local: nflData || []
    });

    function nflTeamsWithDefaults(q, sync) {
      if (q === '') {
        sync(nflTeams.get('Detroit Lions', 'Green Bay Packers', 'Chicago Bears'));
      }
      else {
        nflTeams.search(q, sync);
      }
    }

    $('#default-suggestions .typeahead').typeahead({
          minLength: 0,
          highlight: true
        },
        {
          name: 'nfl-teams',
          display: 'team',
          source: nflTeamsWithDefaults
        });
  });
  // Note: bestPictures was initialized above in its fetch callback. To add the custom templates
  // we initialize the custom-templates input inside that same callback (so bestPictures is in scope).

  // load NBA and NHL teams then initialize the multiple-datasets typeahead
  var nbaTeamsObj = null;
  var nhlTeamsObj = null;
  function tryInitMultipleDatasets() {
    if (!nbaTeamsObj || !nhlTeamsObj) return;
    $('#multiple-datasets .typeahead').typeahead({
          highlight: true
        },
        {
          name: 'nba-teams',
          display: 'team',
          source: nbaTeamsObj,
          templates: {
            header: '<h3 class="league-name">NBA Teams</h3>'
          }
        },
        {
          name: 'nhl-teams',
          display: 'team',
          source: nhlTeamsObj,
          templates: {
            header: '<h3 class="league-name">NHL Teams</h3>'
          }
        });
  }

  fetchJsonWithFallback('assets/js/typeahead/data/nba.json', function(nbaData){
    nbaTeamsObj = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      local: nbaData || []
    });
    tryInitMultipleDatasets();
  });

  fetchJsonWithFallback('assets/js/typeahead/data/nhl.json', function(nhlData){
    nhlTeamsObj = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('team'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      local: nhlData || []
    });
    tryInitMultipleDatasets();
  });
  $('#scrollable-dropdown-menu .typeahead').typeahead(null, {
    name: 'countries',
    limit: 10,
    source: countries
  });
  var arabicPhrases = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: [
      "India",
      "USA",
      "Australia",
      "UEA",
      "China"
    ]
  });
  $('#rtl-support .typeahead').typeahead({
        hint: false
      },
      {
        name: 'arabic-phrases',
        source: arabicPhrases
      });
})(jQuery);