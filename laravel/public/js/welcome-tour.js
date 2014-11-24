/* globals hopscotch: false */

function setCookie(key, value) {
    var expires = new Date();
    expires.setFullYear(expires.getFullYear() + 1);
    document.cookie = key + '=' + value + ';path=/' + ';expires=' + expires.toUTCString();
};

function getCookie(key) {
    var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
    return keyValue ? keyValue[2] : null;
};

var tour = {
  id: 'welcome-tour',
  steps: [
    {
      target: $('#searchvalue')[0],
      title: 'Welcome to Botangle!',
      content: 'Hey there! To start off, do a search for something you need help with.',
      placement: 'bottom',
      arrowOffset: 60,
      multipage: true,
      onNext: function() {
        window.location = "/users/search"
      }
    },
    {
      target: $('.search-result-img:eq(0)')[0],
      title: 'Find an expert',
      content: 'Browse through the various experts and find one you like',
      placement: 'right',
      yOffset: 20,
      multipage: true,
      onNext: function() {
        window.location = $('.search-result-img:eq(0) a').attr('href')
      }
    },
    {
      target: 'booklesson',
      placement: 'left',
      title: 'Book a class',
      content: 'Once you are sure you want to meet with this expert, book a lesson here.'
    }
  ],
  showPrevButton: false,
  scrollTopMargin: 100,
  onEnd: function() {
      setCookie('welcome-tour', 'toured');
  },
  onClose: function() {
      setCookie('welcome-tour', 'toured');
  }
},

/* ========== */
/* TOUR SETUP */
/* ========== */
addClickListener = function(el, fn) {
  if (el.addEventListener) {
    el.addEventListener('click', fn, false);
  }
  else {
    el.attachEvent('onclick', fn);
  }
},

init = function() {
  var startBtnId = 'searchvalue',
      calloutId = 'startTourCallout',
      mgr = hopscotch.getCalloutManager(),
      state = hopscotch.getState();

    if(!getCookie('welcome-tour')) {

        if (state && state.indexOf('welcome-tour:') === 0) {
            // Already started the tour at some point!
            hopscotch.startTour(tour);
        }
        else {
            // Looking at the page for the first(?) time.
            setTimeout(function() {
                mgr.createCallout({
                    id: calloutId,
                    target: startBtnId,
                    placement: 'left',
                    title: 'Take a brief tour',
                    content: 'Click here for a quick overview of how to use Botangle',
                    yOffset: -25,
                    arrowOffset: 20,
                    width: 240
                });
            }, 2000);
        }

        if($("#" + startBtnId).length > 0) {
            addClickListener(document.getElementById(startBtnId), function() {
                if (!hopscotch.isActive) {
                    mgr.removeAllCallouts();
                    hopscotch.startTour(tour);
                }
            });
        }
        else {
            hopscotch.startTour(tour);
        }
    }
};

$('.home-banner #searchuser').on('submit', function() {
    if(hopscotch.isActive) {
        hopscotch.nextStep();
    }
});

init();

//document.cookie = 'welcome-tour=; path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
//hopscotch.endTour();