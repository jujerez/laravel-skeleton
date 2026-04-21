var CommonFunctions = {};

// Shows a SweetAlert2 notification
CommonFunctions.notification = function(type, title, text) {
    var options = {
        type: type,
        showConfirmButton: false,
        timer: 5000
    };
    if (title) { options['title'] = title; }
    if (text)  { options['text']  = text; }
    swal(options);
};

// Shows a success notification and redirects (no escape until confirmed)
CommonFunctions.notificationSuccessRedirect = function(text, url) {
    swal({
        type: 'success',
        title: 'Success',
        text: text,
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false,
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                window.location = url;
            });
        }
    });
};

// Shows a success with two buttons: "Keep editing" or "Back to listing"
CommonFunctions.notificationSuccessStayOrBack = function(text, stayUrl, backUrl) {
    var $html = $(
        '<div>' + text +
        '<div class="swal2-custombuttons mt-3">' +
        '<button class="btn btn-lg btn-success mx-2" data-url="' + stayUrl + '">Keep editing</button>' +
        '<button class="btn btn-lg btn-success mx-2" data-url="' + backUrl  + '">Back to listing</button>' +
        '</div></div>'
    );
    $(document).on('click', '.swal2-custombuttons button', function() {
        $(this).addClass('clicked');
        swal.clickConfirm();
    });
    swal({
        type: 'success',
        title: 'Success',
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false,
        customClass: 'swal2-custom',
        showLoaderOnConfirm: true,
        showConfirmButton: false,
        html: $html,
        preConfirm: function() {
            return new Promise(function(resolve) {
                var url = $('.swal2-custombuttons button.clicked').data('url');
                window.location = url;
                if (url === null) {
                    resolve();
                }
                else {
                    window.location = url;
                }
            });
        }
    });
};

CommonFunctions.notificationConfirmDelete = function(text, buttonText, url, callback) {
    swal({
        type: 'warning',
        title: 'Attention',
        text: text,
        allowEscapeKey: false,
        allowOutsideClick: false,
        allowEnterKey: false,
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: buttonText,
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                $('.swal2-buttonswrapper > button:not(:first)').remove();

                var $form = $('<form method="POST"><input type="hidden" name="_method" value="DELETE" /></form>');
                $form.prop('action', url);
                $form.data('callback', callback);
                $form.appendTo('body');
                CommonFunctions.setupAjaxForm($form);
                $form.submit();
            });
        }
    });
};

// Marks Bootstrap form fields as invalid and displays error messages
CommonFunctions.validation = function(validationErrors) {
    function parseField(field) {
        var parts = field.split('.');
        if (parts.length === 1) { return parts[0]; }
        var name = parts.shift();
        return name + parts.map(function(p) { return '[' + p + ']'; }).join('');
    }

    $.each(validationErrors, function(field, errors) {
        var fieldName = parseField(field);
        var $field = $('[name="' + fieldName + '"]');
        if ($field.length > 1)   { $field = $field.filter(':first'); }
        if ($field.length === 0) { $field = $('[name="' + fieldName + '[]"]'); }

        if ($field.length === 1) {
            var $target    = $field;
            var $container = $field.closest('.form-group');
            var $parent    = $field.parent();
            if ($parent.hasClass('input-group') || $parent.hasClass('form-control')) {
                $target = $parent;
            }

            $target.addClass('is-invalid');

            var $error = $container.children('.invalid-feedback:first');
            if ($error.length === 0) {
                $error = $('<div class="invalid-feedback" />');
                $container.append($error);
            }
            $error.html(errors[0]);

            if (!$target.hasClass('was-invalid-before')) {
                $field.on('change', function() {
                    $target.removeClass('is-invalid');
                    $error.html('');
                });
                $target.addClass('was-invalid-before');
            }
        }
    });

    // Scroll to the first error
    var $scrollTo = $('.is-invalid:first').closest('.row');
    if ($scrollTo.length > 0) {
        $('html, body').animate({ scrollTop: $scrollTo.offset().top - 100 }, 500);
    }
};

// Initializes an AjaxForm reading its data-attributes
CommonFunctions.setupAjaxForm = function(form, options) {
    options = $.extend({}, {
        notify:   CommonFunctions.notification,
        validate: CommonFunctions.validation
    }, options);

    var $form = $(form);

    if ($form.data('callback'))         { options['custom']          = window[$form.data('callback')]; }
    if ($form.data('custom'))           { options['custom']          = window[$form.data('custom')]; }
    if ($form.data('notify'))           { options['notify']          = window[$form.data('notify')]; }
    if ($form.data('validate'))         { options['validate']        = window[$form.data('validate')]; }
    if ($form.data('redirect'))         { options['redirect']        = window[$form.data('redirect')]; }
    if ($form.data('before-submit'))    { options['beforeSubmit']    = window[$form.data('before-submit')]; }
    if ($form.data('before-serialize')) { options['beforeSerialize'] = window[$form.data('before-serialize')]; }

    $form.caribeAjaxForm(options);
};
