var div_princ = $('#main-page');
/**
 * Si dissociation utilisateur OK
 */
div_princ.find('form .cov-ug-remove').data('callback', function (button, reponse) {
    // On cache la ligne
    button.closest('.form-group').fadeOut();
});
/**
 * Recharge la page
 */
div_princ.find('form').data('callback', function (reponse) {
    div_princ.find('form').remove();
    var form = $(reponse.group);
    div_princ.append(form);
});
/**
 * Paramètres à envoyer au service d'association d'utilisateur
 */
div_princ.find('form .cov-ug-add').data('define-params', function (button, params) {
    var form_group = button.closest('.form-group');
    form_group.find('input, select').each(function () {
        var name = $(this).attr('name');
        params[name] = $(this).val();
    });
});
/**
 * Rechargement de la page
 */
div_princ.find('form .cov-ug-add').data('callback', function () {
    location.reload();
});