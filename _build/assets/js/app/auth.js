define(['app'], function (App) {
    'use strict';

    App.Auth = {
        callbacks: {
            login: function (data, $form) {
                $form[0].reset();
                if (!_.isEmpty(data.refresh)) {
                    window.location = data.refresh;
                }
            },

            register: function (data, $form) {
                $form[0].reset();
                App.Modal.hide();
            },

            reset: function (data, form) {
                form[0].reset();
            },
        }
    };

    App.Router.route('auth/:action', 'auth', function (action) {
        if (!App.Modal.isOpen()) {
            App.Modal.load('office/get', function () {
                $('a[data-target="#auth-' + action + '"]').tab('show');
            });
        } else {
            $('a[data-target="#auth-' + action + '"]').tab('show');
        }
    });

    return App;
});