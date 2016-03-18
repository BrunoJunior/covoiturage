var form = $('#cov-group-trajet form');

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
    div_refresh.find('.panel-title .badge').html('<span class="glyphicon glyphicon-refresh spin"></span>');
    div_refresh.load(url);
}

form.data('callback', function () {
    refreshList($('#cov_list_trajets'));
});

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

$('#cov-group-trajet').on('click', 'form button.cov_remove_pass', function (e) {
    var button = $(this);
    var tuile = button.closest('.cov_passager_tuile');
    var input_pass = $('#cov_passagers');
    var final_passagers = [];
    var passagers = input_pass.val().split(',');
    for (i = 0; i < passagers.length; i++) {
        var passager = passagers[i];
        var id_tuile = tuile.data('param-id');
        if (passager != '' && passager != id_tuile) {
            final_passagers.push(passagers[i]);
        }
    }
    input_pass.val(final_passagers.join());
    tuile.fadeOut();
});

$('#cov_add_pass').on('click', function (e) {
    var select_passager = $('#cov_pass');
    var id_passager = select_passager.val();
    if (id_passager == '') {
        afficherErr('Veuillez sélectionner un passager !');
        return;
    }
    var input_pass = $('#cov_passagers');
    var passagers = input_pass.val().split(',');
    for (i = 0; i < passagers.length; i++) {
        if (passagers[i] == id_passager) {
            afficherErr('Passager déjà présent !');
            return;
        }
    }
    passagers.push(id_passager);
    input_pass.val(passagers.join());
    var lib_passager = select_passager.find('option:selected').text();
    var div_tuile = $('#cov_passager_tuile_hidden');
    var div_tuile_pass = div_tuile.clone();
    var dest_tuile = $('#cov_passagers_visu');
    div_tuile_pass.removeAttr('id');
    div_tuile_pass.removeClass('hidden');
    div_tuile_pass.attr('data-param-id', id_passager);
    div_tuile_pass.data('param-id', id_passager);
    div_tuile_pass.find('.cov_passager_lib').text(lib_passager);
    dest_tuile.append(div_tuile_pass);
});