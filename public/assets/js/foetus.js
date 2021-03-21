
var appFoetus = {


    initFoetus: function () {

        console.log('initFoetus');

        /**
       * *****************************
       * L I S T E N E R S
       * *****************************
       */
        $('.foetus-enter-link').on('click', appFoetus.enterFoetus);
        $('.main-title-logo').on('click', appFoetus.reload);

    },

    reload: function () {
        location.reload()
    },

    enterFoetus: function () {
        $('.foetus-home-img').fadeOut('slow');
        $('.social').css('top', '2%')
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', appFoetus.initFoetus)
