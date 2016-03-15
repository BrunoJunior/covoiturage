var div_princ = $('#main-page form');
div_princ.find('.cov-ug-remove').data('callback', function (button, reponse) {
    // On cache la ligne
    button.closest('.form-group').fadeOut();
});