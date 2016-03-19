// Login OK --> Redirection
$('form').data('callback', function (json) {
    window.location.replace("/");
});

/**
 * Oublie de mot de passe
 */
$('#user_forgot').data('define-params', function(button, parameters) {
    parameters.email = $('#user_email').val();
});

$('#user_forgot').data('callback', function(button, reponse) {
    console.log(button);
    console.log(reponse);
});