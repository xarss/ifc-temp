$(document).ready(function () {
    $('.contact-form').on('submit', function (e) {
        e.preventDefault();
        var name    = $(this).find('[name="name"]').val();
        var email   = $(this).find('[name="email"]').val();
        var message = $(this).find('[name="message"]').val();
        var body    = 'Nome: ' + name + '\nEmail: ' + email + '\n\n' + message;
        window.location.href = 'mailto:resgatesifc@gmail.com'
            + '?subject=' + encodeURIComponent('Formulário de contato Site Instituto Fica Comigo')
            + '&body='    + encodeURIComponent(body);
    });
});
