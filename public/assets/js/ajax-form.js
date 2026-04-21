(function($) {

    function CaribeAjaxForm($form, options) {
        var defaultOptions = {
            notify: null,
            validate: null,
            redirect: null,
            custom: null,
            stopMultiple: true,
            includeCsrfToken: true,
            disableButtons: true,
            loadingButton: true,
            beforeInit: null,
            beforeSerialize: null,
            beforeSubmit: null,
            onCancel: null,
            onSuccess: null,
            onError: null,
            onComplete: null
        };

        this.get = function(option) { return $form.data('caribeAjaxForm')[option]; };
        this.set = function(option, value) { $form.data('caribeAjaxForm')[option] = value; };

        function notify(type, title, text) {
            if ($form.data('caribeAjaxForm').notify) {
                $form.data('caribeAjaxForm').notify(type, title, text);
            } else {
                alert(text ? text : title);
            }
        }

        function validate(validationErrors) {
            if ($form.data('caribeAjaxForm').validate) {
                $form.data('caribeAjaxForm').validate(validationErrors);
            } else {
                var msg = "Please correct the following errors:\n";
                $.each(validationErrors, function(field, errors) {
                    msg += "- " + field + ": " + errors[0] + "\n";
                });
                alert(msg);
            }
        }

        function redirect(url, delay) {
            if ($form.data('caribeAjaxForm').redirect) {
                $form.data('caribeAjaxForm').redirect(url, delay);
            } else {
                setTimeout(function() { window.location = url; }, delay);
            }
        }

        function custom(data) {
            if ($form.data('caribeAjaxForm').custom) {
                $form.data('caribeAjaxForm').custom(data);
            }
        }

        function processResponse(response) {
            if (response.validation)  { validate(response.validation); }
            if (response.message)     { notify(response.message.type, response.message.title, response.message.text); }
            if (response.redirection) { redirect(response.redirection.url, response.redirection.delay); }
            if (response.custom)      { custom(response.custom); }
        }

        function started() {
            if ($form.data('caribeAjaxForm').disableButtons) {
                $form.find('button, input[type=submit], input[type=button]').each(function() {
                    var $t = $(this);
                    $t.data('was-disabled', $t.prop('disabled'));
                    $t.prop('disabled', true);
                });
            }
            $form.data('caribeAjaxForm').processing = true;
        }

        function finished(completed) {
            if ($form.data('caribeAjaxForm').disableButtons) {
                $form.find('button, input[type=submit], input[type=button]').each(function() {
                    var $t = $(this);
                    $t.prop('disabled', $t.data('was-disabled'));
                });
            }
            $form.data('caribeAjaxForm').processing = false;
            $form.data('caribeAjaxForm').lastButtonClicked = null;

            if (completed && $form.data('caribeAjaxForm').onComplete) {
                $form.data('caribeAjaxForm').onComplete();
            } else if (!completed && $form.data('caribeAjaxForm').onCancel) {
                $form.data('caribeAjaxForm').onCancel();
            }
        }

        if (!$form.data('caribeAjaxForm')) {
            $form.data('caribeAjaxForm', $.extend({}, defaultOptions, options));

            $form.on('click', 'button, input[type=submit]', function() {
                $form.data('caribeAjaxForm').lastButtonClicked = $(this);
            });

            $form.on('submit', function(e) {
                var submit = function() {
                    $form.ajaxSubmit({
                        dataType: 'json',
                        beforeSerialize: function($formElement, opts) {
                            if ($form.data('caribeAjaxForm').beforeSerialize) {
                                var proceed = $form.data('caribeAjaxForm').beforeSerialize($formElement, opts);
                                if (proceed === false) { finished(false); return false; }
                            }
                        },
                        beforeSubmit: function(formData) {
                            if ($form.data('caribeAjaxForm').includeCsrfToken) {
                                var $csrf = $('meta[name="csrf-token"]');
                                if ($csrf.length > 0) {
                                    formData.push({ name: '_token', value: $csrf.attr('content') });
                                }
                            }
                            if ($form.data('caribeAjaxForm').lastButtonClicked) {
                                var fieldName = $form.data('caribeAjaxForm').lastButtonClicked.attr('name');
                                if (fieldName) {
                                    formData.push({
                                        name: fieldName,
                                        value: $form.data('caribeAjaxForm').lastButtonClicked.val()
                                    });
                                }
                            }
                            if ($form.data('caribeAjaxForm').beforeSubmit) {
                                var proceed = $form.data('caribeAjaxForm').beforeSubmit(formData);
                                if (proceed === false) { finished(false); return false; }
                            }
                        },
                        success: function(response) {
                            if ($form.data('caribeAjaxForm').onSuccess) {
                                var proceed = $form.data('caribeAjaxForm').onSuccess(response);
                                if (proceed === false) { finished(false); return; }
                            }
                            processResponse(response);
                            finished(true);
                        },
                        error: function(xhr) {
                            if ($form.data('caribeAjaxForm').onError) {
                                var proceed = $form.data('caribeAjaxForm').onError(xhr);
                                if (proceed === false) { finished(false); return; }
                            }
                            var response = null;
                            try { response = JSON.parse(xhr.responseText); } catch (e) {
                                notify('error', 'Error', 'An unknown error occurred');
                            }
                            if (response) { processResponse(response); }
                            finished(true);
                        }
                    });
                };

                if ($form.data('caribeAjaxForm').stopMultiple && $form.data('caribeAjaxForm').processing) {
                    return false;
                }

                started();

                if ($form.data('caribeAjaxForm').beforeInit) {
                    $form.data('caribeAjaxForm').beforeInit().then(
                        function() { submit(); },
                        function() { finished(false); }
                    );
                } else {
                    submit();
                }

                e.preventDefault();
                return false;
            });
        }
    }

    $.caribeAjaxForm = function(selector, options) {
        return new CaribeAjaxForm($(selector), options);
    };

    $.fn.caribeAjaxForm = function(options) {
        return this.each(function() {
            $.caribeAjaxForm(this, options);
        });
    };

})(jQuery);
