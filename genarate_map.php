<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// allow access....
include 'includes/LogRecordClass.php';
include 'includes/functions.php';
// allow access....
include 'includes/ClassDatabase.php';
//include 'includes/PhantomJsCloudClass.php';
//include 'includes/SendEmail.php';
include 'includes/FalconClass.php';
include 'includes/FlaconPlaceSaveClass.php';
include 'includes/CreatePhantomJsonClass.php';

//echo $_GET['falcon_file_name'];
//echo $_GET['path']. DIRECTORY_SEPARATOR.$_GET['falcon_file_name'];

//$current_time = date("Y-m-d H:i:s");
//echo "<br/><br/>" . "Current time: " . $current_time . "<br/><br/>";

//$centerLatPoint = "52.1230546";
//$centerLngPoint = "8.6619835";

//$falcon_render_file = "phantom_i05l71_falconPlace_g2hcg7.json";
//$falcon_render_file_dataset = "phantom_request/phantom_i05l71_falconPlace_g2hcg7.json";

/// aqctive after test run
$centerLatPoint = $_GET['lat'];
$centerLngPoint = $_GET['lng'];

//$centerLatPoint = "53.0784299";
//$centerLngPoint = "8.804174";

/// must active after test run.
$fileData = file_get_contents($_GET['path']. DIRECTORY_SEPARATOR .$_GET['falcon_file_name']);

//$fileData = file_get_contents("dataset/falconPlace_ny737s.json");

//echo $_GET['path']. DIRECTORY_SEPARATOR.$_GET['falcon_file_name']."<br/>";
//echo $_GET['requested_json']."<br/>";

$phpGetmContet = json_decode($fileData,false);
/*echo '<pre>';
print_r($phpGetmContet);
echo '</pre>';*/
//die();
$totalResults = json_encode($phpGetmContet->data->results,true);
$totalResults = json_decode($totalResults,true);

/*echo '<pre>';
print_r($totalResults);
echo '</pre>';*/
$z= 0;

/*echo '<pre>';
print_r($totalResults);
echo '</pre>';*/

$phpGetmContet = array();
$arrayDataset = array(
    "icon"   => "",
    "lat"   => "",
    "lng"   => "",
    "rank"  => ""
);

foreach($totalResults as $singeResult):

    $ifFound = $singeResult['found'];

    if($ifFound){

        //echo $singeResult['rank'];
        $arrayDataset = array(
            "lat"   => $singeResult['lat'],
            "lng"   => $singeResult['lng'],
            "rank"  => $singeResult['rank'],
            "count" => $singeResult['count']
        );
        $phpGetmContet['universities'][] = $arrayDataset;
    }else{
        //echo $singeResult['rank'];
        $arrayDataset = array(
            "lat"   => $singeResult['lat'],
            "lng"   => $singeResult['lng'],
            "rank"  => "20+",
            "count" => $singeResult['count']
        );
        $phpGetmContet['universities'][] = $arrayDataset;
    }

endforeach;

//$phpGetmConte['universities'][];
/*echo '<pre>';
print_r($phpGetmContet);
echo '</pre>';*/

/*
foreach($totalResults as $singeResult):
    if($singeResult['found']){
        echo $singeResult['rank']. ' --- counter = ' . $z . '<br/>';
        //$getRank = $singeResult['rank'];
        $phpGetmContet[0][0][] =array("51.4931511",
            "-0.18245690000003378");


    }
$z++;
endforeach;
print_r($phpGetmContet);*/
//echo base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap HTML Skeleton</title>
    <!-- Bootstrap CSS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAovl1rrAqjI_2mNkhoNhJkuQ9QbRclebA"></script>
    <!--<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>-->

    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js" type="text/javascript" ></script>
    <script src="<?php echo base_url(); ?>assets/js/popper.min.js" type="text/javascript" ></script>
    <script src="<?php echo base_url(); ?>assets/js/html2canvas.min.js" type="text/javascript" ></script>
    <style>
        body,html{
            margin: 0;
            padding: 0;
        }

        #map {
            height: 670px;
            width: 640px;
            margin: 0px auto;
            padding: 0px;
        }

        .div-for-watermark{
            position: absolute;
            left: 0;
            top: 0;
            z-index: 999999;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            background-image: url('<?php echo base_url(); ?>assets/images/watermark-logo.png');
            background-repeat: no-repeat;
            background-size: auto;
            background-position: center center;
        }
    </style>
</head>
<body>

<div class="container" style="margin: 0; padding: 0; width: 100%;">

    <div class="row">
        <div class="col col-8">
            <div id="map" style="border:0px solid #3872ac;"></div>
        </div>
    </div>
</div>

<?php
/*$phpGetmContet = array(
        array(
                array(
                        "51.5010095",
                        "-0.1932793999999376"
                ),
                array(
                    "51.5089037",
                    "-0.19502280000006067"
                ),
            array(
                "51.4931511",
                "-0.18245690000003378"
            )
        )
);*/

///$json = json_encode($phpGetmContet);

$json=json_encode($phpGetmContet);
//echo $json;
//echo base_url();
?>
<script>
    var map;
    //var icon = "http://path/to/icon.png";
    //var json = "http://path/to/universities.json";
    var infowindow = new google.maps.InfoWindow();


    let $getSiteUrl = "<?php echo base_url(); ?>icons/";
    let arrayCircle = {
        "clr1":"clr1.png",
        "clr2":"clr2.png",
        "clr3t5":"clr3t5.png",
        "clr6t7":"clr6t7.png",
        "clr8t9":"clr8t9.png",
        "clr20":"clr20.png"
    }


    let image = {
        url: "<?php echo base_url(); ?>icons/clr20.png",
        scaledSize: new google.maps.Size(40, 40), // size
    };

    function initialize() {

        var mapProp = {
            center: new google.maps.LatLng(<?php echo $centerLatPoint; ?>, <?php echo $centerLngPoint; ?>), //LLANDRINDOD WELLS
            zoom: 11,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            draggable: false,
            zoomControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: false,
        };

        map = new google.maps.Map(document.getElementById("map"), mapProp);

        //  $.getJSON(json, function(json1) {
        var json1 = <?php echo $json; ?>;
        //console.log(json1);
        //console.log(json1);
        let labels = "20+";
        //json1 = json2;
        $.each(json1.universities, function(key, data) {

            var latLng = new google.maps.LatLng(data.lat, data.lng);

            labels = data.rank;

            let $imageUrl = "";
            if(data.rank === 1){
                $imageUrl = arrayCircle['clr1'];
            }else if(data.rank === 2){
                $imageUrl = arrayCircle['clr2'];
            }else if((data.rank > 2) && (data.rank <= 5)){
                $imageUrl = arrayCircle['clr3t5'];
            }else if((data.rank > 5) && (data.rank <= 7)){
                $imageUrl = arrayCircle['clr6t7'];
            }else if((data.rank > 7) && (data.rank <= 9)){
                $imageUrl = arrayCircle['clr8t9'];
            }else{
                $imageUrl = arrayCircle['clr20'];
            }

            image = {
                url: $getSiteUrl+$imageUrl,
                scaledSize: new google.maps.Size(40, 40), // size
            };

            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                icon: image,
                label: {text: labels.toString(), color: "white"},
                // icon: icon,
                //title: data.title
            });

            var details = data.website + ", " + data.phone + ".";

            bindInfoWindow(marker, map, infowindow, details);

            //    });

        });
        //alert('Done Load');
        jQuery('#map').append('<div class="div-for-watermark"></div>');
    }

    function bindInfoWindow(marker, map, infowindow, strDescription) {
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(strDescription);
            infowindow.open(map, marker);
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);


</script>
<!-- Bootstrap JS and dependencies -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.5.1.slim.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js" type="text/javascript" ></script>

</body>
</html>