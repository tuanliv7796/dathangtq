// TODO(erikc): [post-launch] This is mostly ported from the legacy site. Add
// unittests and documentation.

var photosByPage;
var curPage = 1;
var map;

// Default values for the latitude, longitude, and zoom level, to be used if
// no such values are found in the URL. This is meant to be modified before the
// map is loaded. It can be left to 'null', to indicate that a hardcoded
// default value should be used, or be an object with fields
// - 'ln' (longitude in degrees East)
// - 'lt' (latitude in degrees North)
// - 'z' (zoom level in the Panoramio scale)
var defaultArgs = null;

// The currently displayed layer (an instance of GLayer) or 'null' if no
// layer is currently displayed.
var layer = null;


/**
 * Class for storing data about a tab (including its HTML node).
 *
 * @constructor
 * @param {Object} options Dictionary of options:
 *   - {Number} index Tab number (this is not its index in the array).
 *   - {String} name Name of the tab, to be shown.
 *   - {String} set Photo set.
 *   - {String} order Photo ordering.
 *   - {String} kmlText Text to use for the KML link.
 *   - {String} kmlLink URL to the KML.
 *   - {Boolean} showAsTab Whether this should be shown as a tab (e.g. the
 *     "all" tab) isn't.
 *   - {Boolean} hasCheckboxAll Whether to show a "Show all" checkbox.
 */
function Tab(options) {
  this.index = options.index;
  this.name = options.name;
  this.set = options.set;
  this.order = options.order;
  this.kmlText = options.kmlText;
  this.kmlLink = options.kmlLink;
  this.showAsTab = options.showAsTab;
  this.hasCheckboxAll = options.hasCheckboxAll;
  this.html = null;
}


/**
 * Array of all tabs in this page.
 * @type {Array.<Tab>}
 */
var tabs = new Array();


/**
 * The currently-active tab.
 * @type {Tab}
 */
var currentTab;


/**
 * Index of the initial open tab.
 * @type {Number}
 */
var initialTabIndex = null;

/**
 * The tab that was initially active in this page.
 * @type {Tab}
 */
var defaultTab;


/**
 * Adds a tab to the page.
 *
 * @param {Object} options Option dictionary to be passed to the Tab class
 *   constructor.
 */
function addTab(options) {
  var tab = new Tab(options);
  tab.html = $('#tab_li_' + options.index);
  tabs.push(tab);
}


/**
 * Returns the Tab object for a given tab index.
 *
 * This is not the 'index'-th tab in the page. Each tab has an "index", more
 * like an identifier.
 *
 * @param {Number} index The index of the tab to find.
 * @return {Tab?} The Tab, or undefined if no tab with that index exists.
 */
function findTabByIndex(index) {
  for (var i = 0, l = tabs.length; i < l; ++i) {
    if (tabs[i].index == index) {
      return tabs[i];
    }
  }
  return null;
}


function mapTypeToInt(type) {
  switch (type) {
    case G_NORMAL_MAP: return 0; break;
    case G_SATELLITE_MAP: return 1; break;
    case G_HYBRID_MAP: return 2; break;
    default: return 2;
  }
}


function historyChange(newLocation) {
  function extractDataFromLocation(location, outArgs) {
    if (location.length > 0) {
      var args = location.split('&');
      for (var i = 0; i < args.length; i++) {
        var arg = args[i].split('=');
        outArgs[arg[0]] = parseFloat(arg[1]);
      }
    }
  }
  function isClose(x, y) {
    return (Math.abs(x - y) < 0.000001);
  }

  var args = { ln: -33, lt: 28, z: 15, k: 1, tab: defaultTab.index };
  if (defaultArgs != null) {
    args.ln = defaultArgs.ln;
    args.lt = defaultArgs.lt;
    args.z = defaultArgs.z;
  }
  extractDataFromLocation(newLocation, args);

  var tabChanged = false;
  if (!currentTab || currentTab.index != args.tab) {
    activateTab(findTabByIndex(args.tab), false);
    tabChanged = true;
  }

  var center = map.getCenter();
  var k = mapTypeToInt(map.getCurrentMapType());
  var panoramioZoom = args.z;
  var mapsApiZoom = 17 - panoramioZoom;

  if (tabChanged ||
      !center ||
      !isClose(args.lt, center.lat()) ||
      !isClose(args.ln, center.lng()) ||
      mapsApiZoom != map.getZoom() ||
      args.k != k)
    map.setCenter(new GLatLng(args.lt, args.ln), mapsApiZoom,
                  map.getMapTypes()[args.k]);
}


function GPositionControl() {}


GPositionControl.prototype = new GControl(true, true);


function getCoordsCenter(map) {
  function dec2sex(dec, lat) {
    var letter = lat ? (dec >= 0 ? 'N' : 'S') : (dec >= 0 ? 'E' : 'W');
    dec = Math.abs(dec);
    var deg = Math.floor(dec);
    var min = Math.floor((dec - deg) * 60);
    var sec = (dec - deg - min / 60) * 3600;
    return deg + '\u00B0 ' + min + '\' ' + sec.toFixed(2) + '" ' + letter;
  }
  var center = map.getCenter();
  return dec2sex(center.lat(), true) + ' ' + dec2sex(center.lng(), false);
}


GPositionControl.prototype.initialize = function(map) {
  var container = document.createElement('div');
  var extra = document.createElement('div');
  extra.innerHTML = getCoordsCenter(map);
  extra.style.color = (map.getCurrentMapType() == G_NORMAL_MAP ?
                       'black' : 'white');
  extra.style.fontSize = '8pt';
  container.appendChild(extra);
  map.getContainer().appendChild(container);
  GEvent.addListener(map, 'move', function() {
    extra.innerHTML = getCoordsCenter(map) });
  GEvent.addListener(map, 'maptypechanged', function() {
    extra.style.color =
        map.getCurrentMapType() == G_NORMAL_MAP ? 'black' : 'white' });

  return container;
};


GPositionControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(70, 7));
};


function clamp(i, a, b) {
  return i <= a ? a : i >= b ? b : i
}


function centerMapAtLatLong(name, pt, mapsApiZoom) {
  map.setCenter(pt, mapsApiZoom, map.getCurrentMapType());
}

function resetMap() {
  map.setCenter(new GLatLng(28, -33), 15, G_SATELLITE_MAP);
}


/**
 * Sets up the KML link in the page according to a selected tab.
 *
 * @param {Tab} tab The tab that defines which KML link to use.
 */
function updateKmlLink(tab) {
  // Updates the kml link (for viewing the current set of photos in Earth)
  // according to the selected tab.
  var link = $('#kml-link');
  link.text(tab.kmlText);
  link.attr('href', tab.kmlLink);
}


/**
 * Configures a tab to be the initial one.
 *
 * @param {Tab} tab The tab to configure.
 */
function setUpInitialTab(tab) {
  currentTab = tab;
  currentTab.html.toggleClass('active');
  curPage = 1;
  if (currentTab.hasCheckboxAll) {
    if ($('#all_layer_checkbox').attr('checked')) {
      updateKmlLink(findTabByIndex(10));
    } else {
      updateKmlLink(currentTab);
    }
    $('#tab_checkbox_area').show();
  } else {
    updateKmlLink(currentTab);
    $('#tab_checkbox_area').hide();
  }
}


/**
 * Activates a tab.
 *
 * This is to be called when the user clicks on a tab. It should not be called
 * when the page is loaded, to make the initial tab active; for that, use
 * setUpInitialTab.
 *
 * @param {Tab} tab The tab to activate.
 * @param {Boolean} refresh Whether the map has to be refreshed.
 * @return {Boolean} False, so that event processing does not continue.
 */
function activateTab(tab, refresh) {
  if (currentTab) {
    currentTab.html.removeClass('active');
    currentTab.html.find('.total_photos').html('');
  }
  setUpInitialTab(tab);
  currentTab.html.find('.loading').css('display', 'inline');

  activateLayer();
  if (refresh) {
    onMoveEndMap();
  }

  return false;
}


function onLoad() {
  // Build the mail address like this to avoid scrapers.
  var x = 'questions';
  x += '@';
  x += 'panoramio.com';
  $('#m').attr({href: 'mailto:' + x});

  $.each(tabs, function() {
      var tab = this;
      tab.html.find('a').click(function(event) {
          event.preventDefault();
          event.stopPropagation();
          return activateTab(tab, true);
        });
    });


  $('#all_layer_checkbox').click(function() {
      var checkbox = $('#all_layer_checkbox');
      activateTab(currentTab, true);
      return true;
    });

  defaultTab = findTabByIndex(initialTabIndex);
  setUpInitialTab(defaultTab);

  onResize();
  setupGMap('map', {});
  $(window).resize(onResize);
}


function onResize() {
  if ($('#map').css('display') == 'none')
    return;
  // resize the map
  var m = $('#map').get(0);
  var d = getDimensions(m);
  var windowHeight = getWindowHeight();
  m.style.height = (windowHeight - d.top - 46) + 'px';
  if (map) map.checkResize();
  d = getDimensions(m);
}


function onMoveEndMap() {
  plotPanoramas();
  updateLinkToThisPage();
}


/**
 * Returns the layer name for the current view.
 *
 * @return {string} The layer name for the current view, i.e. the
 *   'public', 'recent', tag or single-user map views / tabs. If the current
 *   view is not recognized, the name for the public layer is returned.
 */
function getLayerName() {
  // Set up default options for the "public" layer
  var label = 'public';  // "public" photo set

  if (currentTab.hasCheckboxAll && $('#all_layer_checkbox').attr('checked')) {
    label = 'all';  // "all" photos (also rejected)
  } else if (currentTab.set == 'recent') {
    label = 'recent';  // "recent" photo set
  } else if (!isNaN(userId = parseInt(currentTab.set))) {
    label = 'user.' + userId;
  } else if (currentTab.set.substr(0, 4) == 'tag-') {
    label = 'tag.' + currentTab.set.substr(4);
  } else if (currentTab.set.substr(0, 6) == 'group-') {
    label = 'group.' + currentTab.set.substr(6);
  } else if (currentTab.set == 'public') {
  } else {
    // Unknown layer, default to the public layer
  }

  var sorting = 'p';  // sort all layers by popularity
  return 'lmc:com.panoramio.' + sorting + '.' + label;
}


/**
 * Activates the layer for the current view.
 *
 * Previously-active overlays (of all kinds, not just layers) are
 * removed.
 */
function activateLayer() {
  if (layer != null) {
    map.removeOverlay(layer);
  }
  layer = new GLayer(getLayerName());
  map.addOverlay(layer);
}


function setupGMap(m, opts) {
  // Initializing Google Map
  m = $('#'+m).get(0);
  map = new GMap2(m);
  activateLayer();

  photosByPage = Math.floor(
      (m.offsetHeight - $('.pages').get(0).offsetHeight) / 115) * 4;

  $.history.init(historyChange);

  // Adding controls to the maps
  map.addControl(new GMapTypeControl());
  map.addControl(new GLargeMapControl());
  map.addControl(new GPositionControl());
  map.enableScrollWheelZoom();
  map.enableDoubleClickZoom();
  map.enableContinuousZoom();

  var ads = new GAdsManager(map, 'ca-google-panoramio',
    { mode: 'experimental', channel: 16860543});
  ads.enable();

  plotPanoramas();
  GEvent.addListener(map, 'moveend', onMoveEndMap);
}


function updateLinkToThisPage() {
  var p = map.getCenter();
  var k = mapTypeToInt(map.getCurrentMapType());
  var mapsApiZoom = map.getZoom();
  var panoramioZoom = 17 - mapsApiZoom;
  var hash = 'lt=' + p.lat().toFixed(6) + '&ln=' + p.lng().toFixed(6) + '&z=' +
      panoramioZoom + '&k=' + k + '&a=1' + '&tab=' + currentTab.index;
  $.history.load(hash);
}


function plotPanoramas() {
  var p = curPage;
  var bounds = map.getBounds();
  var sw = bounds.getSouthWest();
  var ne = bounds.getNorthEast();

  urlParameters = {
    'order': currentTab.order,
    'set': currentTab.set,
    'size': 'thumbnail',
    'from': (p - 1) * photosByPage,
    'to': p * photosByPage,
    'minx': sw.lng(),
    'miny': sw.lat(),
    'maxx': ne.lng(),
    'maxy': ne.lat()};
  if (currentTab.hasCheckboxAll && $('#all_layer_checkbox').attr('checked')) {
    urlParameters.set = 'full';
  }

  var loading = $('#tabs > li.active > a > img.loading').css(
      'display', 'inline');
  setTimeout(function() {
      if ('undefined' != typeof loading) {
        loading.css('display', 'none');
      }
    }, 2000);


  // Fetch data for the preview panel through an AJAX call to the Panoramio API.
  $.getJSON('/map/get_panoramas', urlParameters, updatePreviews);
}


/**
 * Updates the left panel after the Panoramio API AJAX call returns.
 *
 * When the user changes the map, an AJAX call to /map/get_panoramas is done
 * to obtain information about the top N photos shown in the new map viewport.
 * When the AJAX call returns, this function is called.
 *
 * @param {Object} jsonResponse The JSON response from the AJAX call, already
 *   interpreted (i.e. not as a string, but as a JavaScript object).
 * @param {string} textStatus Status of the AJAX call, one of "timeout",
 *   "error", "notmodified", "success", or "parsererror".
 */
function updatePreviews(jsonResponse, textStatus) {
  if (textStatus != 'success') {
    return;
    // TODO(rogerts): [post-launch] For the moment, do nothing. We may want to
    // implement better error handling.
  }

  $('#preview').html('');
  var photos = jsonResponse.photos;
  var count = jsonResponse.count;
  var has_more = jsonResponse.has_more;
  $('#tabs > li.active span.total_photos').html(' (' + count + ')');

  var en = '#enabled_next_link';
  var dn = '#disabled_next_link';
  var ep = '#enabled_previous_link';
  var dp = '#disabled_previous_link';

  $(has_more ? en : dn).css('display', 'inline');
  $(has_more ? dn : en).css('display', 'none');

  $(curPage > 1 ? ep : dp).css('display', 'inline');
  $(curPage > 1 ? dp : ep).css('display', 'none');

  if (photos.length == 0) {
    $(currentTab.html).find('img.loading').css('display', 'none');
  }

  var points = [];
  var ids = [];
  for (var i = 0; i < photos.length; i++) {
    var photo = photos[i];
    points.push(new GLatLng(photo.latitude, photo.longitude));
    ids.push(photo.photo_id);
  }

  for (var i = 0; i < photos.length; i++) {
    $('#preview').append(getPreviewDOM(photos[i], i));
  }

  if (photos.length == 0 && curPage > 1) {
    curPage = 1;
  }
}


function getPreviewDOM(photo, i) {
  var id = photo.photo_id;

  var a = document.createElement('A');
  a.href = '/photo/' + id;

  var img = document.createElement('IMG');

  onHoverIn = function() {
    $(this).css('background', '#ff0000');
  };
  onHoverOut = function() {
    $(this).css('background', '#ffffff');
  };
  newAttrs = {
    title: photo.photo_title,
    id: 'r' + id,
    src: photo.photo_file_url,
    p_id: id
  };
  $(img).attr(newAttrs).hover(onHoverIn, onHoverOut);

  var div = document.createElement('DIV');
  div.appendChild(a);
  a.appendChild(img);
  return div;
}


function Dimensions(left, top, width, height) {
  this.left = left;
  this.top = top;
  this.width = width;
  this.height = height;
}


function getDimensions(control) {
  var tmp = control;
  var left = 0;
  var top = 0;

  while (tmp != null) {
    left += tmp.offsetLeft;
    top += tmp.offsetTop;
    tmp = tmp.offsetParent;
  }
  return new Dimensions(left, top, control.offsetWidth, control.offsetHeight);
}


function getWindowHeight() {
  var windowHeight = 0;
  if (typeof(window.innerHeight) == 'number')
    windowHeight = window.innerHeight;
  else if (document.documentElement && document.documentElement.clientHeight)
    windowHeight = document.documentElement.clientHeight;
  else if (document.body && document.body.clientHeight)
    windowHeight = document.body.clientHeight;

  return windowHeight;
}


function getWindowWidth() {
  var windowWidth = 0;
  if (typeof(window.innerWidth) == 'number')
    windowWidth = window.innerWidth;
  else if (document.documentElement && document.documentElement.clientWidth)
    windowWidth = document.documentElement.clientWidth;
  else if (document.body && document.body.clientWidth)
    windowWidth = document.body.clientWidth;

  return windowWidth;
}

