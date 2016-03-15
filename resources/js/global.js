$(function () {

    var div_alert_err = $('#cov-alert-error');
    var div_alert_suc = $('#cov-alert-success');

    function afficherErr(message) {
        div_alert_err.find('.message').text(message);
        div_alert_err.removeClass('hidden');
        div_alert_err.fadeIn();
    }

    function afficherOK(message) {
        div_alert_suc.find('.message').text(message);
        div_alert_suc.removeClass('hidden');
        div_alert_suc.fadeIn();
    }

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

    $('form').on('submit', function (e) {
        debugger;
        e.preventDefault();
        var form = $(this);
        var callback = form.data('callback');
        div_alert_err.fadeOut();
        div_alert_suc.fadeOut();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
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
            debugger;
            var err = textStatus + ", " + error;
            afficherErr(err);
        });
        ;
    });
});