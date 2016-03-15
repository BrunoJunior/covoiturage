$(function () {
    // Lors d'une déconnexion -> Redirection
    $('#cov-deco').data('callback', function (button, reponse) {
        window.location.replace("/");
    });

    // Gestion retour AJAX
    var div_alert_err = $('#cov-alert-error');
    $('body').on('click', "button[url$='serv']", function () {
        var button = $(this);
        var url = button.attr('url');
        var callback = button.data('callback');
        $.getJSON(url)
                .done(function (json) {
                    if (json.isErr) {
                        div_alert_err.find('.message').text(json.message);
                    } else if (callback !== undefined) {
                        callback(button, json.reponse);
                    }
                })
                .fail(function (jqxhr, textStatus, error) {
                    var err = textStatus + ", " + error;
                    div_alert_err.find('.message').text(err);
                });
    });
});