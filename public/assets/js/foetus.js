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
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', appFoetus.initFoetus)
