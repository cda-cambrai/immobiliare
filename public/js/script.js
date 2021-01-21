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

// On va corriger l'affichage du label pour l'upload des images
$('[type="file"]').on('change', function () {
    let label = $(this).val().split('\\').pop(); // C:\fakepath\5.png devient 5.png
    // On ajoute le label dans l'élément suivant le input
    $(this).next().text(label);

    // On va afficher un aperçu de l'image avant l'upload
    let reader = new FileReader();
    // On doit écouter un événement pour faire quelque chose avec cette image
    reader.addEventListener('load', function (file) {
        // Cleaner les anciennes images
        $('.custom-file img').remove();
        // Je réupère l'image au format base64
        let base64 = file.target.result;
        // Je crée une balise img en JS
        let img = $('<img class="img-fluid mt-5" width="250" />');
        // Je mets le base64 dans le src de l'img
        img.attr('src', base64);
        // Afficher l'image dans la div .custom-file
        $('.custom-file').prepend(img);
    });

    // Le JS va charger l'image en mémoire
    reader.readAsDataURL(this.files[0]);
}); // Fin du on('change')
