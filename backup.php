<?php
/*$query_parameter = "lat=52.1815584&lng=8.4244690&grid_size=7&radius=10&measurement=km";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.localfalcon.com/v1/scan/?api_key=fdbb0655203292ae26df32405afd3797&place_id=ChIJ9WAZYnkcukcRaEykv0cGVBY&keyword=werbeagentur&lat=52.1815584&lng=8.4244690&grid_size=7&radius=20&measurement=km',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        $allPosts_grids = json_decode($response, true);
*/
/*echo '<pre>';
        print_r($allPosts_grids);
        echo '</pre>';*/

/*
// Convert JSON data from an array to a string
$jsonString = json_encode($allPosts_grids, JSON_PRETTY_PRINT);
// Write in the file
$fp = fopen("result_data.json", 'w');
fwrite($fp, $jsonString);
fclose($fp);
*/
?>





<html>
<head>
    <title>Info Windows</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

</head>
<style>
    html,body{
        margin: 0;
        padding: 0;
    }
</style>
<body>
<div id="map" style="width: 800px; height: 800px; overflow: hidden; margin: auto;"></div>

<!--
  The `defer` attribute causes the callback to execute after the full HTML
  document has been parsed. For non-blocking uses, avoiding race conditions,
  and consistent behavior across browsers, consider loading using Promises.
  See https://developers.google.com/maps/documentation/javascript/load-maps-js-api
  for more information.
  -->




<!--<script>
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "AIzaSyAovl1rrAqjI_2mNkhoNhJkuQ9QbRclebA",
        v: "weekly",
        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
        // Add other bootstrap parameters as needed, using camel case.
    });
</script>-->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAovl1rrAqjI_2mNkhoNhJkuQ9QbRclebA&callback=initMap&v=weekly"
    defer
></script>
<script>
    /* let map;
     // initMap is now async
     async function initMap() {
         // Request libraries when needed, not in the script tag.
         const { Map } = await google.maps.importLibrary("maps");
         // Short namespaces can be used.
         map = new Map(document.getElementById("map"), {
             center: { lat: 52.1815584, lng: 8.4244690 },
             zoom: 9,
             disableDefaultUI: true,
         });
     }

     initMap();*/

    /**
     * @license
     * Copyright 2019 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    const size_circle = 10*12;
    const citymap = {
        chicago: {
            center: {
                "lat": 52.09172687158805,
                "lng": 8.277963469386135 },
            population: size_circle,
            text: "1"
        },
        chicago1: {
            center: {
                "lat": 52.136642635794026,
                "lng": 8.277963469386135 },
            population: size_circle,
            text: "1"
        },
        chicago2: {
            center: {
                "lat": 52.1815584,
                "lng": 8.277963469386135 },
            population: size_circle,
            text: "1"
        },
        chicago3: {
            center: {
                "lat": 52.226474164205975,
                "lng": 8.277963469386135 },
            population: size_circle,
            text: "1"
        },
        chicago4: {
            center: {
                "lat": 52.27138992841195,
                "lng": 8.277963469386135 },
            population: size_circle,
            text: "1"
        },
        chicago5: {
            center: {
                "lat": 52.09172687158805,
                "lng": 8.351216234693068 },
            population: size_circle,
            text: "1"
        },
        chicago6: {
            center: {
                "lat": 52.136642635794026,
                "lng": 8.351216234693068 },
            population: size_circle,
            text: "1"
        },
        chicago7: {
            center: {
                "lat": 52.1815584,
                "lng": 8.351216234693068 },
            population: size_circle,
            text: "1"
        },
        chicago8: {
            center: {
                "lat": 52.226474164205975,
                "lng": 8.351216234693068 },
            population: size_circle,
            text: "1"
        },
        chicago9: {
            center: {
                "lat": 52.27138992841195,
                "lng": 8.351216234693068 },
            population: size_circle,
            text: "1"
        },
        chicago10: {
            center: {
                "lat": 52.09172687158805,
                "lng": 8.424469 },
            population: size_circle,
            text: "1"
        }
        /*newyork: {
            center: { lat: 40.714, lng: -74.005 },
            population: 8405837,
        },
        losangeles: {
            center: { lat: 34.052, lng: -118.243 },
            population: 3857799,
        },
        vancouver: {
            center: { lat: 49.25, lng: -123.1 },
            population: 603502,
        },*/
    };



    function initMap() {
        // Create the map.
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 11,
            center: new google.maps.LatLng(52.1815584, 8.4244690),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
        });



        // Construct the circle for each value in citymap.
        // Note: We scale the area of the circle based on the population.
        for (const city in citymap) {

            /*var myLatLng = new google.maps.LatLng(citymap[city].center);

            var marker = new google.maps.Marker({

                position: myLatLng,
                label: '5',
                map: map,

            });*/

            // Add the circle for this city to the map.
            let cityCircle = new google.maps.Circle({
                clickable: false,
                strokeColor: "#a90000",
                strokeOpacity: 1,
                strokeWeight: 1,
                fillColor: "#FF0000",
                fillOpacity: 1,
                map,
                center: citymap[city].center,
                radius: Math.sqrt(citymap[city].population) * 100,
            });




            //cityCircle.bindTo('center', marker, 'position');
            //marker.setVisible(true);

            //cityCircle.setMap(map);

        }
    }


    window.initMap = initMap;








</script>

</body>
</html>