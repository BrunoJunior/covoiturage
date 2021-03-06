var form = $('#cov-group-trajet form');

/**
 * Rafraichissement de la liste des trajets
 * @param {jQuery Object} div_refresh
 * @param {int} page
 * @returns {void}
 */
function refreshList(div_refresh, page) {
    var div_pagination = div_refresh.find('.cov_pag');
    var url = div_refresh.data('refresh');
    var actual = parseInt(div_pagination.find('li.active a').attr('href'));

    if (div_pagination.length < 1) {
        page = 1;
    } else if (page == undefined) {
        page = actual;
    }

    if (page === '+1') {
        page = actual + 1;
    } else if (page === '-1') {
        page = actual - 1;
    }
    url += 'num_page=' + page + '&';
    div_refresh.find('.panel-title .badge').html('<i class="fa fa-refresh fa-spin"></i>');
    div_refresh.load(url, function () {
        $('#cov_list_trajets').find('.cov-trajet-del').data('callback', function (button, reponse) {
            // On cache le trajet
            button.closest('tr').fadeOut();
        });
    });
}

/**
 * Si l'ajout d'un trajet est OK
 * On rafraichit la liste des trajets conducteur
 */
form.data('callback', function () {
    refreshList($('#cov_list_trajets'));
});

/**
 * Si la suppression du trajet est OK
 */
$('#cov_list_trajets').find('.cov-trajet-del').data('callback', function (button, reponse) {
    // On cache le trajet
    button.closest('tr').fadeOut();
});

/**
 * Validation ou suppression passager prévisionnel, on efface la tuile
 */
$('#trajp-liste .trajp-pass-valid, #trajp-liste .trajp-pass-delete').data('callback', function (button, reponse) {
    // On cache le passager
    button.closest('.trajp-passager-tuile').fadeOut();
    // On rafraichi la liste des trajets
    refreshList($('#cov_list_trajets'));
});

/**
 * Validation ou suppression trajet prévisionnel, on efface la ligne
 */
$('#trajp-liste .trajp-valid, #trajp-liste .trajp-delete').data('callback', function (button, reponse) {
    // On cache le trajet
    button.closest('tr').fadeOut();
    // On rafraichi la liste des trajets
    refreshList($('#cov_list_trajets'));
});

/**
 * Quand on a nettoyé les trajets prévisionnels passés
 */
$('#trajp-liste .trajp-clear').data('callback', function (button, reponse) {
    // On rafraichi la liste des trajets
    refreshList($('#trajp-liste'));
});

/**
 * Gestion de la pagination
 */
$('#cov-group-trajet').on('click', '.cov_pag a', function (e) {
    var lien = $(this);
    var li = lien.closest('li');
    if (!li.hasClass('disabled') && !li.hasClass('active')) {
        var div_refresh = lien.closest('div[data-refresh]');
        var page = lien.attr('href');
        refreshList(div_refresh, page);
    }
    e.preventDefault();
    e.stopPropagation();
    return false;
});

/**
 * Datepiker pour choisir la date du trajet
 */
$("#cov_date").datepicker({
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