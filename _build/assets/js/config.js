requirejs.config({
    baseUrl: '/assets/components/modxpro/js/web/',
    urlArgs: 'v=' + document.head.querySelector('meta[name="assets-version"]').content,
    waitSeconds: 30,
    paths: {
        jquery: 'lib/jquery.min',
        bootstrap: 'lib/bootstrap.min',
        tether: 'lib/tether.req',
        backbone: 'lib/backbone.min',
        backbone_syphon: 'lib/backbone.syphon.min',
        backbone_epoxy: 'lib/backbone.epoxy.min',
        underscore: 'lib/underscore.min',
        alertify: 'lib/alertify.min',
        fontawesome: 'lib/fontawesome.min',
        cookies: 'lib/js.cookie.min',
        prism: 'lib/prism.min',
        markitup: 'lib/markitup.min',
        fancybox: 'lib/jquery.fancybox.min',
        moment: 'lib/moment-with-locales.min',
        numeral: 'lib/numeral.min',
        pdopage: 'lib/pdopage.min',
    },
    shim: {
        bootstrap: {
            deps: ['jquery']
        },
        underscore: {
            exports: '_'
        },
        backbone: {
            deps: ['underscore', 'jquery'],
            exports: 'Backbone'
        },
        alertify: {
            exports: 'Alertify'
        },
        cookies: {
            exports: 'Cookies'
        },
        prism: {
            exports: 'Prism'
        },
        fancybox: {
            deps: ['jquery'],
            exports: 'Prism'
        },
        pdopage: {
            deps: ['jquery'],
            exports: 'pdoPage'
        },
        app: {
            deps: ['jquery', 'backbone', 'alertify', 'cookies', 'moment', 'numeral', 'bootstrap', 'fontawesome', 'backbone_epoxy', 'backbone_syphon'],
            exports: 'App'
        },
    }
});

requirejs.onError = function (err) {
    if (err.requireType === 'timeout') {
        if (typeof App === 'object') {
            App.Message.alert('Could not load javascript. Try to reload page.', function () {
                document.location.reload();
            })
        } else {
            alert('Could not load javascript. Try to reload page.');
            console.log(err);
        }
    } else {
        throw err;
    }
};

FontAwesomeConfig = {
    searchPseudoElements: true,
};
// Disable javascript links before initialize
AppInitialized = false;
for (var i in document.links) {
    if (!document.links.hasOwnProperty(i)) {
        continue;
    }
    document.links[i].onclick = function (e) {
        if (!AppInitialized && (typeof this.href === 'undefined' || this.href === '' || this.href === '#' || this.getAttribute('data-toggle'))) {
            e = e || window.event;
            e.preventDefault();
        }
    }
}