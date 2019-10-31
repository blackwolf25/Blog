require([
    "jquery",
    "mage/calendar"
], function($){

    $("#date_range").dateRange({
        buttonText:"<?php echo __('Select Date') ?>",
        from:{
            id:"date_from"
        },
        to:{
            id:"date_to"
        }
    });

});
