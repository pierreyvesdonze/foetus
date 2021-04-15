var appFoetus = {

    initFoetus: function () {

        console.log('initFoetus');

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        //$('.foetus-enter-link').on('click', appFoetus.enterFoetus);
        $('.main-title-logo').on('click', appFoetus.reload);
        $('.delete-img-form').on('submit', appFoetus.deleteImage);
        //$('.admin-link').on('click', appFoetus.changeBodyColor);

        // Constantes
        appFoetus.currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

        // Application du thème courant
        if (appFoetus.currentTheme) {
            document.documentElement.setAttribute('data-theme', appFoetus.currentTheme);
        }

    },

    reload: function () {
        location.reload()
    },

    enterFoetus: function () {
        $('.foetus-home-img').fadeOut('slow');
        $('.home-foetus-container').fadeOut('slow');
        $('.social').css('top', '2%')
        $('.main-title-logo').css('top', '2%')
    },

    deleteImage: function (e) {
        route = "delete_gallery";
        $.ajax(
            {
                url: Routing.generate(route, {'type': 'pouet'}),
                method: "POST",
            }).done(function (response) {
                e.preventDefault();
                if (null !== response) {
                    $(e.target).closest('.itemx').remove();
                    appFoetus.reload();
                } else {
                    console.log('Problème');
                }
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    },

    // changeBodyColor: function (evt) {
    //     console.log(appFoetus.currentTheme)
    //     //$('.hiddenCheckbox').triggerHandler("click");
    //     if (evt.target.checked) {
    //         document.documentElement.setAttribute('data-theme', 'admin');
    //         localStorage.setItem('theme', 'admin');
    //     }
    //     else {
    //         document.documentElement.setAttribute('data-theme', 'home');
    //         localStorage.setItem('theme', 'home');
    //     }
    // }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', appFoetus.initFoetus)
