var app = {

    init: function () {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        $('.home-img').on('dblclick', app.goToAdmin)
        $('.main-title-logo').on('click', app.reload);
        $('.delete-img-form').on('submit', app.deleteImage);

        /* PARENTAL ADVISORY */
        $('.main-container').data('route') === 'galerie' ? app.parentalAdvisory() : console.log('Coucou');
    },

    parentalAdvisory: function () {
        $('.parental-button').trigger('click');
        $('.enter-parental-button').on('click', function () {
            $('.main-container').removeClass('opacity');
        })
    },

    goToAdmin: function () {
        window.location.href = Routing.generate('admin');
    },

    reload: function () {
        location.reload()
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
                    app.reload();
                } else {
                    console.log('Problème');
                }
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    },
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', app.init)
