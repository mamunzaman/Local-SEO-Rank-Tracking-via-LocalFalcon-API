<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//require 'PHPMailer/src/Exception.php';
//require 'PHPMailer/src/PHPMailer.php';

// Translation access file ......
include 'languages/TranslationTexts.php';


// allow access....
include 'includes/ClassDatabase.php';
include 'includes/functions.php';
//include 'includes/PhantomJsCloudClass.php';
//include 'includes/SendEmail.php';
include 'includes/FalconClass.php';
include 'includes/FlaconPlaceSaveClass.php';
include 'includes/LogRecordClass.php';

//echo __DIR__;
if(isset($_GET['token'])){  // if token exist


//echo $_GET['token'];
//$returnFlacon = new LocalFalconGateWay();
$database_gateway = new DatabaseGateway();

/***************************
 * Find first the data requested with some conditions.
 */
$sql = "SELECT * FROM $database_gateway->tableName WHERE token= :token AND status = :status";

$data = [
    'token'     => $_GET['token'],
    "status"    => '0'
];

$response = $database_gateway->query($sql, $data);
$count = count((array)$response);

//make StdClass
$responseData = json_decode(json_encode($response,true));
$executionStatus = true;
} ?>
<!DOCTYPE html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Opt-In || Zeiten</title>

    <link href="<?php echo base_url(); ?>assets/fontawesome/css/fontawesome.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/fontawesome/css/brands.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/fontawesome/css/solid.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" />

</head>
<body>
<nav class="navbar navbar-expand-lg mm_custom_nav_bar p-3">
    <div class="container mm_container_custom p-3 rounded-3">
        <div class="row justify-content-between">
            <div class="col-12 col-md-6">
                <a href="#"> <img src="<?php echo base_url(); ?>/assets/images/boovis-logo-400px.png" class="mx-auto
                d-block col-12 col-md-5
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
<?php if( ($count != 0) && ($count <=1) ){
    Logger::log("Opt-In request check before execute:", "Allowed");
    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col col-12 col-md-10 col-lg-10 pt-5 pb-5">
                <p><img src="<?php echo base_url(); ?>assets/images/boovis-logo-400px.png" class="mx-auto d-block col-3" alt="" /> </p>
                <h1 class="text-center"><?php echo $confirm_message; ?></h1>
                <p class="text-center"><?php echo $confirm_message_2; ?></p>

            </div><!-- col-4 -->

        </div><!-- justify-content-center -->
    </div>
<?php }else{
    $executionStatus = false;
    Logger::log("Opt-In request check before execute:", "Not Allowed");
    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col col-12 col-md-10 col-lg-10 pt-5 pb-5">
                <p><img src="<?php echo base_url(); ?>assets/images/boovis-logo-400px.png" class="mx-auto d-block col-3" alt="" /> </p>
                <p><img src="<?php echo base_url(); ?>assets/images/not-allowed.png" class="mx-auto d-block img-fluid" alt=""
                    /></p>
                <h1 class="text-center"><?php echo $confirm_sorry_title_message; ?></h1>
                <p class="text-center lead mx-auto d-block col-9"><?php echo $confirm_sorry_message; ?></p>

            </div><!-- col-4 -->

        </div><!-- justify-content-center -->
    </div>
<?php } ?>
<?php include "sidebars/footer.php"; ?>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>assets/js/scripts.js" type="text/javascript"></script>
<!-- Option 2: Separate Popper and Bootstrap JS -->

<script src="<?php echo base_url(); ?>assets/js/popper.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>


<?php
//die();
if($executionStatus){
    //die();
    // this is execute when real process request found.
    $userId = escapeshellarg($responseData[0]->id);
    $browserLang = escapeshellarg(getBrowserLanguage());
    $webDomainAddress = base_url();
    $executeCommand = '/usr/bin/php '.__DIR__.'/ProcessRequest.php '
        .$userId . ' ' . $browserLang . ' ' . $webDomainAddress;
    exec($executeCommand . ' > /dev/null 2>&1 &');


    Logger::log("Requested Exec(): " . $executeCommand);
    // Immediately continue with the rest of your PHP code
}
?>