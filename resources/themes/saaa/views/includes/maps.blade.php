{{--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>--}}
<script type="text/javascript">
    var markers = [
        {
            "title": 'Diep in die Berg',
            "lat": '-25.784627',
            "lng": '28.335843',
            "description": 'Street Address: 929 Disselboom Ave, Pretoria, 0050'
        },
        {
            "title": 'Lord Charles Hotel',
            "lat": '-34.066419',
            "lng": '18.824505',
            "description": 'Street Address: Broadway Boulevard & Main Rd, Heldervue, Somerset West, 7130'
        },
        {
            "title": 'The Wanderers Club',
            "lat": '-26.135432',
            "lng": '28.053800',
            "description": 'Street Address: 21 North St, Johannesburg, 2196'
        },
        {
            "title": 'Riverside Hotel',
            "lat": '-29.806802',
            "lng": '31.032911',
            "description": 'Street Address: 10 Kenneth Kaunda Road, Durban North, Durban, 4065'
        }
    ];
    window.onload = function () {
        LoadMap();
    }
    function LoadMap() {
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 4,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);

        //Create and open InfoWindow.
        var infoWindow = new google.maps.InfoWindow();

        for (var i = 0; i < markers.length; i++) {
            var data = markers[i];
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title
            });

            //Attach click event to the marker.
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent("<div style = 'width:200px;min-height:40px'>" + data.description + "</div>");
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
    }
</script>