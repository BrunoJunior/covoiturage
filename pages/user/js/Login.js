// Login OK --> Redirection
$('form').data('callback', function (json) {
    window.location.replace("/");
});