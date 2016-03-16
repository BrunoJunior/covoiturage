var form = $('#cov-group-trajet form');

form.data('callback', function () {
    location.reload();
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
    dateFormat: 'dd/mm/yy'});

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