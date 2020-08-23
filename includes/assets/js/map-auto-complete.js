      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
      "use strict";

    function initMap() {
    var lat = parseFloat(document.getElementById('lat').value);
    var lng = parseFloat(document.getElementById('long').value);
        
        const map = new google.maps.Map(document.getElementById("map"), {
          center: {
            lat: lat,
            lng: lng
          },
          zoom: 18,
          mapTypeId: "roadmap"
        });

      var myLatLng = {lat: lat, lng: lng};
      var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Hello World!'
      });


        map.setTilt(45);
      }