<html>
<head><?php echo $map['js']; ?></head>
<body><?php echo $map['html']; ?></body>
</html>


<!--
<p>Loading Map</p>
<p>&nbsp;</p>

<script>
$("#find_btn").click(function () { //user clicks button
    if ("geolocation" in navigator){ //check geolocation available
        //try to get user current location using getCurrentPosition() method
        navigator.geolocation.getCurrentPosition(function(position){
                $("#result").html("Found your location <br />Lat : "+position.coords.latitude+" </br>Lang :"+ position.coords.longitude);
            });
    }else{
        console.log("Browser doesn't support geolocation!");
    }
});
</script>

<button id="find_btn">Find Me</button>
<div id="result"></div>
-->
