$(document).ready(function(){
    $preloading_txt_input = jQuery('#preloading_txt_input').val();
    $verify_search_text = jQuery('#verify_search_text').val();

    let $mainSearchTest ='<p style="text-align: center; width: 40px; margin: auto">\n' +
        '            <svg version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">\n' +
        ' <path fill="#007849" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3\n' +
        '  c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z">\n' +
        '     <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>\n' +
        ' </path>\n' +
        '                <path fill="#007849" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7\n' +
        '  c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z">\n' +
        '                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"></animateTransform>\n' +
        '                </path>\n' +
        '                <path fill="#007849" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5\n' +
        '  L82,35.7z">\n' +
        '                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>\n' +
        '                </path>\n' +
        '</svg>\n' +
        '        </p>\n' +
        '        <p style="text-align: center; font-weight: 600;">\n' +
        '            '+$verify_search_text+'\n' +
        '        </p>\n' +
        '        <p class="mm-info-text-weight-p">'+$preloading_txt_input+'</p>';
    $(document).on("click","#closestatusbton",function() {
        jQuery('#preloadOverlay').hide();
    });

    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });


    $("form[name='falconForm']").validate({
        // Specify validation rules
        rules: {
            // The key name on the left side is the name attribute
            // of an input field. Validation rules are defined
            // on the right side
            mm_vorname: "required",
            mm_nachname: "required",
            mm_keyword: "required",
            mm_autocomplete: "required",
            mm_email: {
                required: true,
                email: true
            }
        },
        // Specify validation error messages
        messages: {
            /*mm_vorname: "Please enter your firstname",
            mm_nachname: "Please enter your lastname",
            mm_email: "Please enter a valid email address"*/
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form, event) {
            //form.submit();
            //return false;
            let $closeClick = '<p id="closestatusbton"><i' +
                ' class="fa-solid' +
                ' fa-circle-xmark fa-xl"></i></p>';

            //event.preventDefault();
            /*let $mm_google_company_name = jQuery('#mm_google_company_name').val();
            if($mm_google_company_name === ''){
                //alert("Must add proper way company information otherwise you can submit request");
                jQuery('#preloadOverlay .mm-preload-center').empty();
                jQuery('#preloadOverlay').show();
                jQuery('#preloadOverlay .mm-preload-center').empty().append($closeClick).append('<p class="mm-info-text-weight-p">'+response.message + '</p>');

                return false;
            }*/



            var allData = jQuery('#dataFalconForm').serialize();
            $.ajax({
                url: './ajaxProcessing.php', // PHP script to handle data saving
                type: 'POST',
                //async:false,
                data: {
                    allFormData :allData
                },
                beforeSend: function(data) {
                    //alert('Test 2');


                    jQuery('#preloadOverlay .mm-preload-center').empty().append($mainSearchTest);
                    jQuery('#preloadOverlay').show();
                    // setting a timeout
                    console.log(data);
                    //return false;
                },
                success: function(response){
                    //jQuery('#preloadOverlay .mm-preload-center').html(response);
                    //$('#response').html(response); // Display response from PHP script

                    //var statusObj = jQuery.parseJSON(response);
                    console.log(response);
                    if(!response.status){
                        jQuery('#preloadOverlay .mm-preload-center').empty().append($closeClick).append('<p class="mm-info-text-weight-p">'+response.message + '</p>');
                    }else{
                        //jQuery('#preloadOverlay').hide();
                        jQuery('#preloadOverlay .mm-preload-center').empty().append($closeClick).append('<p class="mm-info-text-weight-p">'+response.message + '</p>');
                    }
                },
                error: function(xhr, status, error){
                    console.error(xhr.status);
                    //console.error(xhr.error);
                    //alert('Error starting background process. Please try again later.');
                    jQuery('#preloadOverlay').hide();

                }
            });

            return false;

        }
    });


});