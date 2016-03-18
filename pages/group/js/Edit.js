var div_princ = $('#main-page form');
/**
 * Si dissociation utilisateur OK
 */
div_princ.find('.cov-ug-remove').data('callback', function (button, reponse) {
    // On cache la ligne
    button.closest('.form-group').fadeOut();
});

/**
 * Paramètres à envoyer au service d'association d'utilisateur
 */
div_princ.find('.cov-ug-add').data('define-params', function (button, params) {
    var form_group = button.closest('.form-group');
    form_group.find('input, select').each(function () {
        var name = $(this).attr('name');
        params[name] = $(this).val();
    });
});