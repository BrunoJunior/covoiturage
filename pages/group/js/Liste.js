var div_princ = $('#cov-group-list');

/**
 * Si la suppression du groupe est OK
 */
div_princ.find('.group-remove').data('callback', function (button, reponse) {
    // On cache le groupe
    button.closest('.cov-group').parent().fadeOut();
});

/**
 * Datepiker pour choisir la date du trajet
 */
$("input[name='prev_date']").datepicker({
    firstDay: 1,
    altField: "#datepicker",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy',
    beforeShow: function () {
        setTimeout(function () {
            $('.ui-datepicker').css('z-index', 9999);
        }, 0);
    }});