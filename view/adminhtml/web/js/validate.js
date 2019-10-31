require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
    ], function(validator, $){
        var upload = $(".file-uploader-area");
        console.log(upload);
        upload.css('dispaly','none');
        $('label#PVIBOKY').css("display","none");
        validator.addRule(
            'validate-custom-range',
            function (value) {
                var to = $('input[name="publish_date_to"]').val();
                var from = $('input[name="publish_date_from"]').val();
                from = from.split("-");
                to = to.split("-");
                var f = new Date(from[2], from[1] - 1, from[0]);
                var t= new Date(from[2], from[1] - 1, from[0]);

                if(to < from){
                    return true;
                }else{
                    return false;
                }

            }
            ,$.mage.__('Ngày FROM phải lớn hơn ngày TO')
        );

        validator.addRule(
            'validate-custom-date-from',
            function (value) {
                return true;
            }
            ,$.mage.__('Không đúng định dang date')
        );
        validator.addRule(
            'validate-custom-date-to',
            function (value) {
                // var to = $('input[name="publish_date_to"]').val();
                // var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;

                    return true;


            }
            ,$.mage.__('Không đúng định dang dateeê')
        );
    });