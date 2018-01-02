define('app/users', ['app', 'backbone'], function (App, Backbone) {
    'use strict';

    var BindingModel = Backbone.Epoxy.Model.extend({
        defaults: {
            query: '',
            work: false,
            rating: false,
        },
        computeds: {
            reset: function () {
                return _.isEmpty(this.get('query'));
            }
        }
    });

    App.Users = {
        Collection: Backbone.Collection.extend({
            url: App.action_url,
            total: 0,

            fetch: function (data) {
                data['action'] = 'user/getlist';
                var options = {
                    type: 'post',
                    data: data
                };

                return Backbone.Collection.prototype.fetch.call(this, options);
            },

            parse: function (res) {
                if (res.success) {
                    this.total = parseInt(res['total']);
                    this.reset(res['results']);
                }
                else {
                    App.Message.failure(res['message']);
                }
            }
        }),

        Form: Backbone.Epoxy.View.extend({
            el: '#app-grid-users-toolbar',
            model: new BindingModel(),
            defaults: {},
            events: {
                'change': 'change',
                'click [type="reset"]': 'reset',
            },
            bindings: {
                'input.query': 'value:query,events:["change"]',
                'input.filter-rating': 'checked:rating,events:["change"]',
                'input.filter-work': 'checked:work,events:["change"]',
                'button[type="reset"]': 'disabled:reset',
            },
            initialize: function () {
                this.defaults = _.clone(this.model.attributes);

                var hash = App.Hash.get();
                history.replaceState(hash, '', document.location.href);
                this.model.set(hash);

                $(window).on('popstate', function (e) {
                    var data = e.originalEvent.state;
                    _.each(App.Users.Form.model.attributes, function (value, key) {
                        if (!_.has(data, key) && _.has(App.Users.Form.defaults, key)) {
                            data[key] = App.Users.Form.defaults[key];
                        }
                    });
                    App.Users.Form.model.set(data);
                    if (!_.has(data, 'page')) {
                        data.page = 1;
                    }
                    App.Users.Grid.load(data, false);
                });
            },
            change: function () {
                this.model.set('page', 1);
                App.Users.Grid.load(this.model.attributes);

                var hash = {};
                delete(this.model.attributes.page);
                _.each(this.model.attributes, function (value, key) {
                    if (value && (_.isBoolean(value) || _.isNumber(value))) {
                        hash[key] = Number(value);
                    } else if (!_.isEmpty(value)) {
                        hash[key] = value.trim();
                    }
                });
                App.Hash.set(hash);
            },
            reset: function () {
                this.model.set({'query': ''});
                this.change();
            }
        }),

        View: Backbone.View.extend({
            tagName: 'tr',
            className: 'user',
            template: _.template($('#RowView').html()),

            render: function () {
                var data = this.model.toJSON();

                data.createdon = App.Utils.formatDate(data.createdon);
                data.visitedon = App.Utils.formatDate(data.visitedon);
                data.topics = App.Utils.formatNumber(data.topics);
                data.comments = App.Utils.formatNumber(data.comments);
                data.rating = App.Utils.formatNumber(data.rating, '0,0.0');

                this.$el.html(this.template(data));

                return this;
            },
        }),
    };

    App.Users.Grid = App.Grid.extend({
        el: '#app-grid-users',
        emptyTemplate: _.template($('#GridEmpty').html()),
        max_limit: 20,

        initialize: function () {
            App.Grid.prototype.initialize.apply(this, arguments);

            this.defaults = _.extend(this.defaults, {
                limit: 20,
                sort: 'rating',
                dir: 'desc'
            });

            this.params = _.extend(this.params, App.Hash.get());
        },

        initializeModel: function () {
            this.model = new App.Users.Collection();
        },

        renderRows: function () {
            this.$table.html('');
            this.model.each(function (data) {
                var view = new App.Users.View({model: data});
                var $html = $(view.render().el);

                $html.attr('id', 'grid-row-' + data.id);
                if (data.attributes.rating > 0) {
                    $html.addClass('positive');
                } else if (data.attributes.rating < 0) {
                    $html.addClass('negative');
                }

                this.$table.append($html);
            }, this);
        },
    });
    App.Users.Form = new App.Users.Form();
    App.Users.Grid = new App.Users.Grid();

    return App;
});