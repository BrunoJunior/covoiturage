var div_princ = $('#cov-group-list');
div_princ.find('.group-remove').data('callback', function (button, reponse) {
    // On cache le groupe
    button.closest('.cov-group').parent().fadeOut();
});