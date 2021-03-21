
var appFoetus = {


    initFoetus: function () {

        console.log('initFoetus');

        /**
       * *****************************
       * L I S T E N E R S
       * *****************************
       */
        $('.foetus-enter-link').on('click', appFoetus.enterFoetus);

    },

    enterFoetus: function (params) {
        console.log('enter foetus');
        $('.foetus-home-img').fadeOut('slow');
    }
}

// AppWitch Loading
document.addEventListener('DOMContentLoaded', appFoetus.initFoetus)
