<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


include 'includes/ClassDatabase.php';
include 'includes/functions.php';
include 'includes/FalconClass.php';
include 'includes/FlaconPlaceSaveClass.php';
include 'languages/TranslationTexts.php';
$get_html_language = getBrowserLanguage();
$preloading_txt = mmItTranslate('preloading_txt', $browser_lang);
$verify_search_txt = mmItTranslate('verify_search_txt', $browser_lang);
?><!DOCTYPE html>
<html lang="<?php echo $get_html_language; ?>">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HOME || Falcon SEO Ranking Reques</title>

    <!-- local font awasome -->
    <link href="<?php echo base_url(); ?>assets/fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/fontawesome/css/brands.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/fontawesome/css/solid.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js" type="text/javascript"></script>

    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/localization/messages_<?php echo $get_html_language; ?>.js" /></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/localization/methods_<?php echo $get_html_language; ?>.js" /></script>
    <script>
        // Load the Autocomplete API asynchronously
        function loadScript() {
            var script = document.createElement("script");
            script.src =
                "https://maps.googleapis.com/maps/api/js?key=AIzaSyAovl1rrAqjI_2mNkhoNhJkuQ9QbRclebA&libraries=places&callback=initAutocomplete";
            document.body.appendChild(script);
        }
        // Replace "YOUR_API_KEY" with your actual API key
        window.onload = loadScript;
    </script>


<style>
    #preloadOverlay{
        position: fixed;
        z-index: 99999999;
        display: none;
        background: rgba(0, 120, 73, 0.7);
        backdrop-filter: blur(5px);
    }

    .mm-preload-center {
        border: 2px solid rgba(0, 120, 73, 0.4);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        min-width: 260px;
        width: 400px;
        text-align: center;
        background: white;
    }

    .showMyLoadingDiv{
        display: block !important;
    }

    #closestatusbton{
        position: absolute;
        right: -12px;
        top: -12px;
    }

    #closestatusbton:hover{
        cursor: pointer;
    }

    #dataFalconForm label.error{
        border: 1px solid red;
        color: red;
        width: 100%;
        margin-top: 4px;
        padding: 2px 5px;
        font-size: 12px;
    }

    #formStatus{
        display: none;
        border: 1px solid red;
        color: red;
        width: 100%;
        margin-top: 4px;
        padding: 2px 5px;
        font-size: 12px; 
    }

</style>
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" />
</head>
<body>
<div id="preloadOverlay" class="w-100 h-100">
<div class="mm-preload-center"></div><!-- mm-preload-center -->
</div><!-- preloadOverlay -->
<nav class="navbar navbar-expand-lg mm_custom_nav_bar p-3">
    <div class="container mm_container_custom p-3 rounded-3">
        <div class="row justify-content-between">
            <div class="col-12 col-md-6">
                <a href="#"> <img src="<?php echo base_url(); ?>assets/images/boovis-logo-400px.png" class="mx-auto d-block col-12 col-md-5
                float-start"
                                  alt="" /></a>
            </div>
            <div class="col-12 col-md-6" style="align-content: center;">

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-lg mm-btn-custom">ANALYSE BUCHEN</button>
                </div>

            </div>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col col-12 col-sm-12 col-md-10 col-lg-8 col-xl-6 pt-5 pb-5">
            <h1 class="text-center" style="color: #007849;"><?php echo $form_title; ?></h1>
            <h6 class="text-center"><?php echo $form_sub_title; ?></h6>
            <!-- hidden reload localization text -->
            <input type="hidden" id="preloading_txt_input" value="<?php echo $preloading_txt; ?>" />
            <input type="hidden" id="verify_search_text" value="<?php echo $verify_search_txt; ?>" />
            <form id="dataFalconForm" method="post" action="" name="falconForm">

                <input type="hidden" name="mm_google_company_name" id="mm_google_company_name" />
                <input type="hidden" name="mm_google_formatted_address" id="mm_google_formatted_address" />
                <input type="hidden" name="mm_primary_cat" id="mm_primary_cat" />
                <input type="hidden" name="mm_web_url" id="mm_web_url" />
                <input type="hidden" name="mm_phone_no" id="mm_phone_no" />

                <!-- address Inputs -->
                <input type="hidden" name="bigin_street_address" id="bigin_street_address" />
                <input type="hidden" name="bigin_city" id="bigin_city" />
                <input type="hidden" name="bigin_state" id="bigin_state" />
                <input type="hidden" name="bigin_country" id="bigin_country" />
                <input type="hidden" name="bigin_postal_code" id="bigin_postal_code" />
                <!-- end -->

                <div class="mb-3">
                    <select class="form-select form-select-lg mb-3" id="mm_select_title" name="mm_select_title" aria-label="Default select example">
                        <?php
                        // this function came from "includes/function.php"
                        echo mm_dropdonw_select(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg" id="mm_gmb" name="mm_gmb" placeholder="GMB" readonly>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg" id="mm_nachname" name="mm_nachname" placeholder="<?php echo $surname; ?>">
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control form-control-lg" id="mm_email" name="mm_email" placeholder="<?php echo $eMailAddress; ?>">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg" id="mm_autocomplete" name="mm_autocomplete" placeholder="<?php echo $autocomplete_address; ?>">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control form-control-lg" id="mm_keyword" name="mm_keyword" placeholder="<?php echo $search_key; ?>">
                </div>
                <div id="formStatus"></div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg submit-custom-falcon"><?php echo $search_button; ?></button>
                </div>
            </form>
        </div><!-- col-4 -->

    </div><!-- justify-content-center -->
</div>

<?php include "sidebars/footer.php"; ?>

<script>
    function initAutocomplete() {
        // Create the autocomplete object for the search input field
        var input = document.getElementById("mm_autocomplete");
        var autocomplete = new google.maps.places.Autocomplete(input);

        // When a place is selected from the autocomplete dropdown
        autocomplete.addListener("place_changed", function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // Handle invalid place selected
                window.alert("Invalid place selected");
                return;
            }

            console.log(place);
            // Use the place object as needed
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            var formatted_phone_number = place.formatted_phone_number;
            var website = place.website;
            var business_types = place.types;
            var company_name = place.name;
            var formatted_address = place.formatted_address;
            console.log("Latitude: " + lat);
            console.log("Longitude: " + lng);
            console.log("Phone: " + formatted_phone_number);
            console.log("Website: " + website);
            console.log("Business Type: " + business_types);
            console.log("Map Company Name: " + company_name);

            console.log("Formatted Address: " + formatted_address);

            jQuery('#mm_phone_no').val(formatted_phone_number);
            jQuery('#mm_web_url').val(website);
            jQuery('#mm_primary_cat').val(business_types[0]);
            jQuery('#mm_google_formatted_address').val(formatted_address);
            jQuery('#mm_google_company_name').val(company_name);

            // Extract address components
            place.address_components.forEach(function(component) {
                switch (component.types[0]) {
                    case 'street_number':
                        jQuery('#bigin_street_address').val(component.long_name);
                        break;

                    case 'route':
                        jQuery('#bigin_street_address').val(component.long_name);
                        break;

                    case 'administrative_area_level_4':
                        jQuery('#bigin_street_address').val(component.long_name);
                        break;
                    case 'locality':
                        jQuery('#bigin_city').val(component.long_name);
                        break;

                    case 'administrative_area_level_2':
                        jQuery('#bigin_city').val(component.long_name);
                        break; 

                    case 'administrative_area_level_1':
                        jQuery('#bigin_state').val(component.long_name);
                        break;

                    case 'country':
                        jQuery('#bigin_country').val(component.long_name);
                        break;
                    case 'postal_code':
                        jQuery('#bigin_postal_code').val(component.long_name);
                        break;
                }
            });


        });
    }
</script>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js" type="text/javascript" ></script>

<script src="<?php echo base_url(); ?>assets/js/scripts.js" ></script>
<!-- Option 2: Separate Popper and Bootstrap JS -->

<script src="<?php echo base_url(); ?>assets/js/popper.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"  type="text/javascript" ></script>
</body>
</html>
