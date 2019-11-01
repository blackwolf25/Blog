require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'mage/translate'
    ], function(validator, $){

        function isDate(txtDate) {
            var currVal = txtDate;
            if(currVal == ''){
                return false;
            }


            var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
            console.log(currVal);
            var dtArray = currVal.match(rxDatePattern); // is format OK?

            if (dtArray == null)
                return false;

            //Checks for mm/dd/yyyy format.
            dtMonth = dtArray[1];
            dtDay= dtArray[3];
            dtYear = dtArray[5];

            if (dtMonth < 1 || dtMonth > 12)
                return false;
            else if (dtDay < 1 || dtDay> 31)
                return false;
            else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
                return false;
            else if (dtMonth == 2)
            {
                var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
                if (dtDay> 29 || (dtDay ==29 && !isleap))
                    return false;
            }
            return true;
        }

        $('label#PVIBOKY').css("display","none");
        validator.addRule(
            'validate-custom-range',
            function (value) {
                var to = $('input[name="publish_date_to"]').val();
                var from = $('input[name="publish_date_from"]').val();


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

                var from = $('input[name="publish_date_from"]').val();

                if(!isDate(from)){
                    return false;
                }else{
                    return true;
                }
            }
            ,$.mage.__('Không đúng định dang date')
        );
        validator.addRule(
            'validate-custom-date-to',
            function (value) {
                var to = $('input[name="publish_date_to"]').val();
                if(!isDate(to)){
                    return false;
                }else{
                    return true;
                }

            }
            ,$.mage.__('Không đúng định dang dateeê')
        );
    });