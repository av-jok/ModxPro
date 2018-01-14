define('app/user', ['app'], function (App) {
    'use strict';

    App.User = {
        callbacks: {
            message: function () {
                App.Modal.hide();
            }
        }
    };

    App.Router.route('message', 'author-message', function () {
        var user = $('.user-header').data('username');
        App.Modal.load({action: 'user/message/get', user: user});
    });

    return App;
});