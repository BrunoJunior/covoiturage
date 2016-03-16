function afficherErr(message) {
    var div_alert_err = $('#cov-alert-error');
    div_alert_err.find('.message').text(message);
    div_alert_err.removeClass('hidden');
    div_alert_err.fadeIn();
}

function afficherOK(message) {
    var div_alert_suc = $('#cov-alert-success');
    div_alert_suc.find('.message').text(message);
    div_alert_suc.removeClass('hidden');
    div_alert_suc.fadeIn();
}

$(function () {
    var div_alert_err = $('#cov-alert-error');
    var div_alert_suc = $('#cov-alert-success');
    // Lors d'une déconnexion -> Redirection
    $('#cov-deco').data('callback', function (button, reponse) {
        window.location.replace("/");
    });

    // Gestion retour AJAX
    $('body').on('click', "button[url$='serv']", function () {
        var button = $(this);
        var url = button.attr('url');
        var callback = button.data('callback');
        div_alert_err.fadeOut();
        div_alert_suc.fadeOut();
        $.getJSON(url)
                .done(function (json) {
                    if (json.isErr) {
                        afficherErr(json.message);
                    } else if (callback !== undefined) {
                        callback(button, json.reponse);
                    } else {
                        afficherOK(json.message);
                    }
                })
                .fail(function (jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    afficherErr(err);
                });
    });

    $('form button[type=submit]').on('click', function (e) {
        var button = $(this);
        e.preventDefault();
        e.stopPropagation();
        var form = button.closest('form');
        var callback = form.data('callback');
        div_alert_err.fadeOut();
        div_alert_suc.fadeOut();
        var data = form.serialize() + '&submit=' + button.attr('value');
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: data,
            dataType: 'json', // JSON
            success: function (json) {
                debugger;
                if (json.isErr) {
                    afficherErr(json.message);
                } else if (callback !== undefined) {
                    callback(json.reponse);
                } else {
                    afficherOK(json.message);
                }
            }
        }).fail(function (jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            afficherErr(err);
        });
        return false;
    });
});