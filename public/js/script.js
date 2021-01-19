/**
 * On peut faire une petite requête AJAX sur Symfony
 */
$('#ajax-properties').click(function () {
    // $.get équivaut à this.http.get en Angular
    $.get('/property.json').then(function (properties) {
        console.log(properties);
    });
});

// Dès qu'on arrive sur l'ajout d'annonce, on affiche tout de suite la valeur du input range
$('#real_estate_surface').after('<div id="result">'+$('#real_estate_surface').val()+' m²</div>');

/**
 * On écoute l'événement sur le range
 */
$('#real_estate_surface').on('input', function () {
    // alert('Toto');
    $('#result').remove(); // On supprime la div pour éviter les doublons
    // Je récupère la valeur du input et l'ajouter directement en dessous de celui-ci
    $(this).after('<div id="result">'+$(this).val()+' m²</div>');
});
