requirejs(['app', 'jquery_form'], function (App) {
    'use strict';

    App.Profile = {
        form: App.Form.extend({
            el: '#office-profile-form',
            events: {
                submit: 'submit',
                'change #office_profile_photo_file': 'pickPhoto',
                'click #office_profile_photo_remove': 'clearPhoto'
            },
            types: {
                images: /(jpeg|png)/,
            },

            initialize: function () {
                App.Form.prototype.initialize.call(this);

                this.$photo = $('#office_profile_photo');
                this.$remove_photo = $('#office_profile_photo_remove');
            },

            pickPhoto: function (e) {
                var $el = $(e.target);
                var $parent = $el.parent();
                var file = $el[0]['files'][0];
                if (file === undefined) {
                    return;
                }
                var img = $parent.find('img');
                if (file.type.match(this.types['images']) || file.type === '') {
                    var reader = new FileReader();
                    reader.onload = function () {
                        img.attr('src', reader.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    App.Message.error(App.Utils.lexicon('package_err_logo_type'));
                    $el.val('');
                }
            },

            clearPhoto: function (e) {
                e.preventDefault();
                var $new_photo = this.$el.find('input[name="newphoto"]');
                $new_photo.val('').replaceWith($new_photo.clone(true));
                this.$el.find('input[name="photo"]').attr('value', '');
                this.submit(e);
            },

            submit: function (e) {
                e.preventDefault();
                this.disable();
                this.$el.find('.error').removeClass('error');
                var form = this;
                form.$el.ajaxSubmit({
                    url: App.action_url,
                    method: 'post',
                    dataType: 'json',
                    success: function (res) {
                        form.enable();
                        form.success(res);
                        form.data = form.$el.serialize();
                        if (res.message) {
                            App.Message.success(res.message);
                        }
                    },
                    error: function (res) {
                        form.enable();
                        res = res.responseJSON;
                        form.failure(res);
                        if (res.message) {
                            App.Message.failure(res.message);
                        }
                    },
                })
            },

            success: function (res) {
                var data = res.object;
                var form = this;
                this.$el.find('[type="password"]').val('');
                for (var i in data) {
                    if (!data.hasOwnProperty(i)) {
                        continue;
                    }
                    this.$el.find('[name="' + i + '"]').each(function () {
                        var $this = $(this);
                        if ($this.attr('type') === 'text') {
                            $this.val(data[i]);
                        }
                        if (i == 'photo') {
                            $this.val(data[i]);
                            if (_.isEmpty(data['photo'])) {
                                form.$photo.prop('src', form.$photo.data('gravatar'));
                                form.$remove_photo.addClass('d-none');
                            } else {
                                form.$photo.prop('src', data[i]);
                                form.$remove_photo.removeClass('d-none');
                            }
                        }
                    })
                }
            },
        }),
    };

    new App.Profile.form();
});