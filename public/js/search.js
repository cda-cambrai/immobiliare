/**
 * On va écouter la saisie au clavier dans la recherche
 */
$('#search').keyup(function () {
   let value = $(this).val(); // Valeur saisie

   // console.log(value);
   // On doit faire un appel AJAX sur une route de Symfony
   // On va récupérer un résultat en JSON de Symfony
   $.ajax('/api/search/'+value, { type: 'GET' }).then(function (response) {
      console.log(response);
      /*let ul = $('<ul></ul>');
      for (let property of response.results) {
         let li = $('<li>'+property.title+'</li>');
         ul.append(li);
      }*/
      // L'API nous renvoie le code HTML tout fait pour la liste des
      // annonces
      $('#real-estate-list').html(response.html);
   });
});
