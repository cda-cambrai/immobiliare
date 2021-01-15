/**
 * On peut faire une petite requête AJAX sur Symfony
 */
$('#ajax-properties').click(function () {
    // $.get équivaut à this.http.get en Angular
    $.get('/property.json').then(function (properties) {
        console.log(properties);
    });
});
