define('app', [
    'jquery', 'backbone', 'alertify', 'cookies', 'moment', 'numeral'
], function ($, Backbone, Alertify, Cookies, Moment, Numeral) {
    'use strict';

    /**
     * @module app
     * @property Router
     * @property Auth
     * @property Profile
     * @property Users
     * @property User
     */
    var App = {
        action_url: '/assets/components/modxpro/action.php',

        domain: '.' + window.location.host.replace('en.', ''),

        Utils: {
            _lexicon: {},

            lexicon: function (key, pls) {
                var string = this._lexicon[key] !== undefined
                    ? this._lexicon[key]
                    : '';
                if (typeof(pls) === 'object') {
                    for (var i in pls) {
                        if (!pls.hasOwnProperty(i)) {
                            continue;
                        }
                        string = string.replace('[[+' + i + ']]', pls[i]);
                    }
                }
                string = string.replace(/\[\[.*?]]/g, '');

                return string;
            },

            highlight: function () {
                $('[class^="language-"]').each(function () {
                    var $this = $(this);
                    var type = $this.attr('class').replace(/language-/, '');
                    requirejs(['prism'], function (Prism) {
                        if (type !== 'markup') {
                            requirejs(['prism/' + type + '.min'], function () {
                                Prism.highlightElement($this[0]);
                            });
                        } else {
                            Prism.highlightElement($this[0]);
                        }
                    });
                });
                requirejs(['fancybox'], function () {
                    $('a[rel="fancybox"]').fancybox();
                });
            },

            formatDate: function (string, format) {
                if (string && string !== '0000-00-00 00:00:00' && string !== '0') {
                    Moment.locale(window.navigator.userLanguage || window.navigator.language);
                    var date = Moment(string, 'YYYY-MM-DD HH:mm:ss');

                    return date.format(format || 'L') + ' <small>' + date.format('HH:mm') + '</small>';
                } else {
                    return '';
                }
            },

            formatNumber: function(number, format) {
                Numeral.localeData().delimiters.thousands = ' ';

                return Numeral(number).format(format || '0,0[.]0');
            },

            request: function (data, success, error, $form) {
                if (_.isString(data)) {
                    data = {action: data};
                }
                $.ajax(App.action_url, {
                    method: 'post',
                    data: data,
                    dataType: 'json',
                    success: function (res) {
                        var clbk;
                        if (_.isFunction(success)) {
                            clbk = success(res);
                        }
                        if (clbk !== false) {
                            if (!_.isEmpty(res.message)) {
                                App.Message.success(res.message);
                            }
                        }
                        if (!_.isEmpty(res.object) && !_.isEmpty(res.object['callback'])) {
                            var path = res.object['callback'].split('.');
                            var callback = App;
                            for (var i = 0; i < path.length; i++) {
                                if (callback[path[i]] == undefined) {
                                    return false;
                                }
                                callback = callback[path[i]];
                            }
                            callback(res.object, $form);
                        }
                    },
                    error: function (res) {
                        res = res.responseJSON;
                        var clbk;
                        if (_.isFunction(error)) {
                            clbk = error(res);
                        }
                        if (clbk !== false) {
                            if (!_.isEmpty(res.message)) {
                                App.Message.failure(res.message);
                            }
                        }
                    },
                });
            },
        },

        Modal: {
            id: 'modal',
            isOpen: function () {
                var $modal = $('#' + this.id);
                return $modal.hasClass('show');
            },
            load: function (data, callback) {
                if (_.isString(data)) {
                    data = {action: data};
                }
                App.Utils.request(data, function (res) {
                    App.Modal.show(res.object.html, callback);
                }, function () {
                    App.Router.clear();
                });
            },
            show: function (content, callback) {
                var $modal = $('#' + this.id);
                if (!$modal.length) {
                    $modal = $('<div>');
                    $modal.addClass('modal fade').attr('id', this.id).appendTo('body');
                }
                $modal.html(content || '<div class="modal-dialog"><div class="modal-content"><div class="modal-body"></div></div></div>');
                if (!$modal.hasClass('show')) {
                    $modal.modal('show');
                }
                if (_.isFunction(callback)) {
                    callback(content, $modal);
                }
            },
            hide: function () {
                var $modal = $('#' + this.id);
                $modal.html('');
                if ($modal.hasClass('show')) {
                    $modal.modal('hide');
                }
            },
        },

        Message: {
            initialize: function () {
                return true;
            },
            success: function (message, delay) {
                if (message !== '') {
                    Alertify.notify(message, 'success', delay || 5);
                }
            },

            failure: function (message, delay) {
                if (message !== '') {
                    Alertify.notify(message, 'failure', delay || 5);
                }
            },

            error: function (message, delay) {
                this.failure(message, delay);
            },

            info: function (message, delay) {
                if (message !== '') {
                    Alertify.notify(message, 'info', delay || 5);
                }
            },

            close: function () {
                Alertify.dismissAll();
            },

            alert: function (message, ok) {
                Alertify.alert(message).set({
                    transition: 'fade',
                    closable: false,
                    movable: false,
                    pinnable: false,
                    modal: true,
                    onok: ok,
                    labels: {
                        ok: 'OK',
                    }
                }).setHeader('');

                $('.ajs-ok').addClass('btn btn-primary');
            },

            confirm: function (message, ok, cancel, onclose) {
                if (onclose === undefined) {
                    onclose = function () {
                        App.Router.clear();
                    };
                }
                Alertify.confirm(message).set({
                    transition: 'fade',
                    closable: false,
                    movable: false,
                    pinnable: false,
                    modal: true,
                    onok: ok,
                    oncancel: cancel,
                    onclose: onclose,
                    labels: {
                        ok: 'OK',
                        cancel: $('meta[name="page-context"]').attr('content') === 'web'
                            ? 'Отмена'
                            : 'Cancel',
                    },
                }).setHeader('');
                $('.ajs-ok').addClass('btn btn-primary');
                $('.ajs-cancel').addClass('btn btn-secondary');
            },
        },

        init: function () {
            $.ajaxSetup({
                headers: {
                    'X-Csrf-Token': $('meta[name="csrf-token"]').attr('content'),
                    'X-Page-Context': $('meta[name="page-context"]').attr('content'),
                    //'X-Page-Id': $('meta[name="page-id"]').attr('content'),
                },
                error: function (res) {
                    var data = res.responseJSON;
                    if (data.message !== '') {
                        App.Message.error(data.message);
                    }
                    if (data.data.reload !== undefined && data.data.reload === true) {
                        document.location.reload();
                    }
                }

            });
            Alertify.defaults.maintainFocus = false;

            var Router = Backbone.Router.extend({
                routes: {},
                old_browser: !(typeof window.history.replaceState === 'function'),
                clear: function () {
                    if (!this.old_browser) {
                        history.replaceState({}, '', window.location.href.replace(/#.*$/, ''));
                    }
                    App.Router.navigate('');
                },
            });
            App.Router = new Router();
            $(document).on('ready', function () {
                Backbone.history.start({root: document.location.pathname});
            });

            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Modal
            $(document).on('hide.bs.modal', function () {
                App.Router.clear();
            });
            $(document).on('shown.bs.modal', '#modal', function () {
                $(this).find('input:visible:first').focus();
                $(this).find('[data-toggle="tooltip"]').tooltip();
            }).on('shown.bs.tab', '#modal', function () {
                $(this).find('.tab-pane:visible').find('input:visible:first').focus();
            });
            /*
            // Tabs
            $(document).on('shown.bs.tab', function (e) {
                var $link = $(e.target);
                var $parent = $link.parents('.nav');
                if ($parent.length) {
                    var id = $parent.attr('id');
                    if (id !== '') {
                        var tab = $link.attr('href').substr(1);
                        Cookies.set($parent.attr('id'), tab, {expires: 7, path: '/'});
                    }
                }
            });
            // Select
            $(document).on('click', '.btn-group.select .dropdown-item', function (e) {
                e.preventDefault();
                var $this = $(this);
                var $parent = $this.parents('.select');
                $parent.find('.mutable').text($this.text());

                $this.parent().find('.dropdown-item:not(.disabled)').removeClass('hidden');
                $this.addClass('hidden');

                $(document).trigger('dropdown-select', [$this.attr('value'), $parent]);
            });
            */
            // Forms
            $(document).on('submit', 'form.ajax-form', function (e) {
                e.preventDefault();
                var $form = $(this);
                var data = Backbone.Syphon.serialize($form);
                data.action = $form.attr('action');
                $form.find('input, button').attr('disabled', true);
                App.Utils.request(data, function () {
                    $form.find('input, button').attr('disabled', false);
                }, function () {
                    $form.find('input, button').attr('disabled', false);
                    $form.find('input:visible:first').focus();
                }, $form);
            });

            // Links
            $(document).on('click', 'a.language', function (e) {
                var host = document.location.host;
                if (!host.match('^id.')) {
                    e.preventDefault();
                    if ($(this).hasClass('en')) {
                        host = 'en.' + host;
                    } else {
                        host = host.replace(/^en\./, '');
                    }
                    var href = document.location.protocol + '//' + host + document.location.pathname;
                    if (!_.isEmpty(document.location.search)) {
                        href += document.location.search;
                    }
                    document.location.href = href;
                }
            });

            // Lexicon
            $.post(App.action_url, {action: 'lexicon/get'}, function (res) {
                if (res.success) {
                    App.Utils._lexicon = res.object;
                    $.holdReady(false);
                    // noinspection JSUnresolvedVariable
                    AppInitialized = true;
                    $(document).trigger('ready');
                }
            }, 'json');
        }
    };

    App.Hash = {
        get: function () {
            var vars = {}, hash, splitter, hashes;
            if (!this.oldbrowser()) {
                var pos = window.location.href.indexOf('?');
                hashes = (pos != -1) ? decodeURIComponent(window.location.href.substr(pos + 1)).replace('+', ' ') : '';
                splitter = '&';
            }
            else {
                hashes = decodeURIComponent(window.location.hash.substr(1)).replace('+', ' ');
                splitter = '/';
            }

            if (hashes.length == 0) {
                return vars;
            }
            else {
                hashes = hashes.split(splitter);
            }

            var matches, key;
            for (var i in hashes) {
                if (hashes.hasOwnProperty(i)) {
                    hash = hashes[i].split('=');
                    if (typeof hash[1] == 'undefined') {
                        vars['anchor'] = hash[0];
                    }
                    else {
                        matches = hash[0].match(/\[(.*?|)\]$/);
                        if (matches) {
                            key = hash[0].replace(matches[0], '');
                            if (!vars.hasOwnProperty(key)) {
                                // Array
                                if (matches[1] == '') {
                                    vars[key] = [];
                                }
                                // Object
                                else {
                                    vars[key] = {};
                                }
                            }
                            if (vars[key] instanceof Array) {
                                vars[key].push(hash[1]);
                            }
                            else {
                                vars[key][matches[1]] = hash[1];
                            }
                        }
                        // String, bool or number
                        else {
                            if (hash[1] == 'true') {
                                vars[hash[0]] = true;
                            } else if (hash[1] == 'false') {
                                vars[hash[0]] = false;
                            } else {
                                vars[hash[0]] = hash[1].match(/^\d+/)
                                    ? Number(hash[1])
                                    : hash[1];
                            }
                        }
                    }
                }
            }
            return vars;
        },

        set: function (vars) {
            var hash = '';
            for (var i in vars) {
                if (vars.hasOwnProperty(i)) {
                    if (typeof vars[i] == 'object') {
                        for (var j in vars[i]) {
                            if (vars[i].hasOwnProperty(j)) {
                                // Array
                                if (vars[i] instanceof Array) {
                                    hash += '&' + i + '[]=' + vars[i][j];
                                }
                                // Object
                                else {
                                    hash += '&' + i + '[' + j + ']=' + vars[i][j];
                                }
                            }
                        }
                    }
                    // String or numeric
                    else {
                        hash += '&' + i + '=' + vars[i];
                    }
                }
            }

            if (!this.oldbrowser()) {
                if (hash.length != 0) {
                    hash = '?' + hash.substr(1);
                }
                window.history.pushState(vars, '', document.location.pathname + hash);
            }
            else {
                window.location.hash = hash.substr(1);
            }
        },

        add: function (key, val) {
            var hash = this.get();
            hash[key] = val;
            this.set(hash);
        },

        remove: function (key) {
            var hash = this.get();
            delete(hash[key]);
            this.set(hash);
        },

        clear: function () {
            this.set({});
        },

        oldbrowser: function () {
            return !(window.history && history.pushState);
        }
    };

    App.Form = Backbone.View.extend({
        el: 'form',
        data: null,
        events: {
            submit: 'submit',
            reset: 'reset',
            keydown: 'keyDown',
            'click [name="preview"]': 'preview',
            'change input': function () {
                this.dirty = true;
            },
        },

        initialize: function () {
            this.$el = $(this.el);
            this.$submit = this.$el.find('[type="submit"]');
            this.$reset = this.$el.find('[type="reset"]');
            this.$preview = this.$el.find('[name="preview"]');
            this.$preview_elem = this.$el.find('.preview');
            this.$preview_close = this.$el.find('.close-preview');

            this.$submit.removeAttr('disabled');
            this.data = this.$el.serialize();

            var $this = this;
            var editor = this.$el.find('textarea.markitup');
            if (editor.length > 0) {
                requirejs(['markitup'], function (MarkItUp) {
                    editor.each(function () {
                        MarkItUp(this, {
                            tab: '    ',
                            toolbar: [{
                                name: "Bold",
                                icon: "bold",
                                shortcut: "Ctrl B, Meta B",
                                before: '{A:}<strong>{OR}<b>{:A}',
                                after: '{A:}</strong>{OR}</b>{:A}',
                            }, {
                                name: "Italic",
                                icon: "italic",
                                shortcut: "Ctrl I, Meta I",
                                before: '{A:}<em>{OR}<i>{:A}',
                                after: '{A:}</em>{OR}</i>{:A}',
                            }, {
                                name: "Underline",
                                icon: "underline",
                                shortcut: "Ctrl U, Meta U",
                                before: '<u>',
                                after: '</u>'
                            }, {
                                name: "Stroke through",
                                icon: "strikethrough",
                                //shortcut: "Ctrl S, Meta S",
                                before: "<s>",
                                after: "</s>"
                            }, {
                                separator: true
                            }, {
                                name: "Bulleted List",
                                icon: "list-ul",
                                before: "{T}<li>",
                                after: "</li>",
                                multiline: true,
                                beforeBlock: "<ul>\n",
                                afterBlock: "\n</ul>"
                            }, {
                                name: "Ordered List",
                                icon: "list-ol",
                                before: "{T}<li>",
                                after: "</li>",
                                multiline: true,
                                beforeBlock: "<ol>\n",
                                afterBlock: "\n</ol>"
                            }, {
                                separator: true
                            }, {
                                name: "Quote",
                                icon: "quote",
                                before: "<blockquote>",
                                after: "</blockquote>"
                            }, {
                                name: "Code",
                                icon: "code",
                                before: "<code>",
                                after: "</code>"
                            }, {
                                name: "Kbd",
                                icon: "font",
                                shortcut: "Ctrl K, Meta K",
                                before: "<kbd>",
                                after: "</kbd>"
                            }, {
                                separator: true
                            }, {
                                name: "Link",
                                icon: "link",
                                content: '<a href="{VAR link}">{S:}{VAR placeholder}{:S}</a>',
                                dialog: {
                                    header: App.Utils.lexicon('office_support_link'),
                                    body: '<div class="form-group"><input type="text" name="link" placeholder="http://" class="form-control"></div>',
                                    ok: App.Utils.lexicon('office_support_ok'),
                                    cancel: App.Utils.lexicon('office_support_cancel'),
                                }
                            }, {
                                name: "Picture",
                                icon: "picture",
                                content: '<img src="{VAR url}" />',
                                dialog: {
                                    header: App.Utils.lexicon('office_support_picture'),
                                    body: '<div class="form-group"><input type="text" name="url" placeholder="http://" class="form-control"></div>',
                                    ok: App.Utils.lexicon('office_support_ok'),
                                    cancel: App.Utils.lexicon('office_support_cancel'),
                                }
                            }]
                        });
                        $(this).removeAttr('disabled');
                    });
                });
                $(document).on('click touchstart', '.markitup-button, .preview a', function () {
                    return false;
                });
                this.$preview_close.on('click', function () {
                    $(this).hide();
                    $this.$preview_elem.hide().html('');
                });
            }
        },

        disable: function () {
            this.$submit.attr('disabled', true);
            this.$reset.attr('disabled', true);
            this.$preview.attr('disabled', true);
        },

        enable: function () {
            this.$submit.attr('disabled', false);
            this.$reset.attr('disabled', false);
            this.$preview.attr('disabled', false);
        },

        submit: function (e, data) {
            e.preventDefault();
            if (this.editor) {
                this.$el.find('textarea.codemirror').val(this.editor.getValue());
            }
            if (data === undefined) {
                data = Backbone.Syphon.serialize(this);
            }
            if (_.isEmpty(data.action)) {
                data.action = this.$el.attr('action');
            }

            this.disable();
            this.$el.find('.error').removeClass('error');
            var form = this;
            App.Utils.request(data, function (res) {
                form.enable();
                form.success(res);
                form.data = form.$el.serialize();
                if (res.redirect) {
                    window.location = res.redirect;
                }
            }, function (res) {
                form.enable();
                if (res.redirect) {
                    window.location = res.redirect;
                }
            });
        },

        reset: function () {
        },

        preview: function (e, data) {
            e.preventDefault();
            if (data === undefined) {
                data = Backbone.Syphon.serialize(this);
            }
            data.action = data.action.replace(/(create|update)/, 'preview');

            this.disable();
            var form = this;
            App.Utils.request(data, function (res) {
                form.enable();
                if (res.object && res.object.data) {
                    form.$preview_elem.show().html(res.object.data);
                    form.$preview_close.show();
                    App.Utils.highlight();
                }
            }, function () {
                form.enable();
                form.$preview_elem.hide().html('');
                form.$preview_close.hide();
            });
        },

        keyDown: function (e) {
            // Ctrl + S
            // Ctrl + Shift + Enter
            if ((e.metaKey || e.ctrlKey) && ((e.shiftKey && e.keyCode === 13) || e.keyCode === 83)) {
                e.preventDefault();
                this.submit(e);
            }
            // Ctrl + Enter
            else if ((e.metaKey || e.ctrlKey) && e.keyCode === 13 && this.$preview.length) {
                e.preventDefault();
                this.preview(e);
            }
            // Enter
            else if (e.keyCode === 13) {
                if (e.target.tagName === 'INPUT') {
                    //e.preventDefault();
                    //this.submit(e);
                }
            }
        },

        success: function () {
        },

        failure: function (res) {
            for (var i in res.errors) {
                if (!res.errors.hasOwnProperty(i)) {
                    continue;
                }
                var err = res.errors[i];
                var $el = this.$el.find('[name="' + err['id'] + '"]');
                if ($el.length) {
                    if ($el.attr('type') === 'hidden' || $el.attr('type') === 'file') {
                        $el.one('change', function () {
                            $(this).parent().removeClass('error').attr('title', '').tooltip('dispose');
                        });
                        $el.parent().addClass('error')
                            .attr('title', err['msg']).attr('data-animation', false).tooltip('show');
                    } else {
                        $el.addClass('error')
                            .one('change', function () {
                                $(this).removeClass('error').attr('title', '').tooltip('dispose');
                            })
                            .attr('title', err['msg']).attr('data-animation', false).tooltip('show');
                    }
                }

            }
        },

        _split: function (val) {
            return val.split(/,\s*/);
        },

        _extractLast: function (val) {
            return this._split(val).pop();
        },

        _array_unique: function (arr) {
            var tmp_arr = [];
            for (i = 0; i < arr.length; i++) {
                if (tmp_arr.indexOf(arr[i]) === -1) {
                    tmp_arr.push(arr[i]);
                }
            }
            return tmp_arr;
        },
    });

    App.Grid = Backbone.View.extend({
        el: '#app-grid',
        emptyTemplate: _.template('empty'),
        initialized: false,
        events: {
            'change .limit': 'changeLimit',
            'change .page': 'changePage',
            'click .prev': 'prevPage',
            'click .next': 'nextPage',
            'click .reload': 'reload',
            'click .sortable': 'sort',
            'focus .query': function (e) {
                $(e.currentTarget).parent().addClass('active');
            },
            'blur .query': function (e) {
                $(e.currentTarget).parent().removeClass('active');
            },
        },
        defaults: {
            limit: 10,
            page: 1,
            query: '',
            sort: 'id',
            dir: 'asc',
        },
        params: {},
        total: 0,
        min_limit: 2,
        max_limit: 100,

        initialize: function () {
            this.initializeModel();
            this.initializeElements();
            this.initializeListeners();
            this.onReady();

            var cookie = Cookies.get(this.$el.attr('id'));
            if (cookie) {
                cookie = JSON.parse(cookie);
                for (var i in cookie) {
                    if (cookie.hasOwnProperty(i)) {
                        this.params[i] = cookie[i];
                    }
                }
            }
            var hash = App.Hash.get();
            if (hash.page) {
                this.params.page = hash.page;
            }

            this.initialized = true;
            this.load();
        },

        initializeModel: function () {
            this.model = new Backbone.Model();
        },

        initializeElements: function () {
            this.$table = $('.rows', this.$el);
            this.$tbar = $('.top-bar', this.$el);
            this.$bbar = $('.bottom-bar', this.$el);
            this.$thead = $('thead', this.$el);
            this.$limit = $('.limit', this.$el);
            this.$prev = $('.prev', this.$el);
            this.$next = $('.next', this.$el);
            this.$total = $('.total', this.$el);
            this.$pages = $('.pages', this.$el);
            this.$page = $('.page', this.$el);
        },

        initializeListeners: function () {
            this.listenTo(this, 'beforeLoad', this.beforeLoad);
            this.listenTo(this.model, 'reset', this.afterLoad);
        },

        onReady: function () {
        },

        load: function (data, setHash) {
            this.trigger('beforeLoad', this);

            if (data != null) {
                this.params = _.extend(_.clone(this.params), data);
            }
            var params = _.extend(_.clone(this.defaults), _.clone(this.params));
            params['start'] = params.page * params.limit - params.limit;
            this.model.fetch(params);

            Cookies.set(this.$el.attr('id'), JSON.stringify({
                limit: params.limit,
                sort: params.sort,
                dir: params.dir,
            }), {expires: 3650, path: '/', domain: App.domain});

            if (setHash !== false) {
                this.setHash(params);
            }
            if (_.has(data, 'page')) {
                $('html, body').animate({
                    scrollTop: this.$el.position().top || 0
                }, 200);
            }
        },

        setHash: function (params) {
            if (params.page > 1) {
                App.Hash.add('page', params.page);
            } else {
                var hash = App.Hash.get();
                if (_.has(hash, 'page')) {
                    App.Hash.remove('page');
                }
            }
        },

        renderRows: function () {
            this.$table.html('');
            this.model.each(function (row) {
                var view = new Backbone.Model({model: row});
                this.$table.append(view.render().el);
            }, this);
        },

        beforeLoad: function () {
            if (this.initialized) {
                this.$table.css({opacity: .5});
            }
        },

        afterLoad: function () {
            this.renderRows();
            var limit = this.params.limit || this.defaults.limit;
            var page = this.params.page || this.defaults.page;
            this.total = this.model.total;
            this.pages = limit > 0
                ? Math.ceil(this.total / limit)
                : 0;
            this.$total.text(App.Utils.formatNumber(this.total));
            this.$pages.text(App.Utils.formatNumber(this.pages));
            this.$limit.val(limit);
            this.$page.val(page);

            if (page == this.pages) {
                this.$next.addClass('disabled');
            }
            else if (this.$next.hasClass('disabled')) {
                this.$next.removeClass('disabled');
            }

            if (page == 1) {
                this.$prev.addClass('disabled');
            }
            else if (this.$prev.hasClass('disabled')) {
                this.$prev.removeClass('disabled');
            }

            var sort = this.params.sort || this.defaults.sort;
            var dir = this.params.dir || this.defaults.dir;
            $('.sortable', this.el).each(function () {
                var $this = $(this);
                $this.removeClass('asc desc');
                if ($this.data('sort') == sort) {
                    $this.addClass(dir);
                }
            });
            this.$table.css({opacity: 1});

            if (!this.total) {
                this.$table.html(this.emptyTemplate());
                this.$bbar.css({opacity: 0});
                this.$thead.css({opacity: 0});
            }
            else {
                this.$bbar.css({opacity: 1});
                this.$thead.css({opacity: 1});
            }

            this.$el.find('[data-toggle="tooltip"]').tooltip();
        },

        changeLimit: function (e) {
            e.preventDefault();

            var limit = parseInt(this.$limit.val().trim());
            if (limit < this.min_limit) {
                limit = this.min_limit;
            } else if (limit > this.max_limit) {
                limit = this.max_limit;
            }
            if (limit != this.params.limit) {
                this.load({limit: limit});
            }
            this.$limit.val(limit);
        },

        changePage: function (e) {
            e.preventDefault();

            var page = parseInt(this.$page.val().trim());
            if (page < 1) {
                page = 1;
            } else if (page > this.pages) {
                page = this.pages;
            }
            this.load({page: page});
            this.$page.val(page);
        },

        nextPage: function (e) {
            e.preventDefault();

            var page = (this.params.page || this.defaults.page) + 1;
            if (page <= this.pages) {
                this.load({page: page});
            }
        },

        prevPage: function (e) {
            e.preventDefault();

            var page = (this.params.page || this.defaults.page) - 1;
            if (page > 0) {
                this.load({page: page});
            }
        },

        reload: function (e) {
            e.preventDefault();
            this.load();
        },

        sort: function (e) {
            var $el = $(e.currentTarget);
            var sort = $el.data('sort');
            var current = this.params.sort || this.defaults.sort;
            var dir;
            if (sort == current) {
                dir = (this.params.dir || this.defaults.dir) == 'asc'
                    ? 'desc'
                    : 'asc';
            }
            else {
                dir = this.defaults.dir;
            }

            this.load({
                sort: sort,
                dir: dir
            });
        },
        /*
        _getLimit: function () {
            var limit = parseInt(this.$limit.val().trim());

            return limit > 0
                ? limit
                : this.params.limit || this.defaults.limit;
        },

        _getPage: function () {
            var page = parseInt(this.$page.val().trim());

            return page > 0
                ? page
                : this.params.page || this.defaults.page;
        },
        */
    });

    App.init();
    window.App = App;

    return App;
});