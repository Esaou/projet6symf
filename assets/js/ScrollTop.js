$(window).scroll(function () { //Fonction appelée quand on descend la page
    if ($(this).scrollTop() > 1500 ) {  // Quand on est à 200pixels du haut de page,
        $('.arrowPaginator').css('right','50px'); // Replace à 10pixels de la droite l'image
    } else {
        $('.arrowPaginator').removeAttr( 'style' ); // Enlève les attributs CSS affectés par javascript
    }
});

