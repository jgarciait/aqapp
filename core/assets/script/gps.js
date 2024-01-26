function loadMapScenario() {
    const getLocationBtn = document.getElementById("getLocationBtn");
    const coordinatesElement = document.getElementById("coordinates");
    const addressElement = document.getElementById("address");

    getLocationBtn.addEventListener("click", function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const coordinates = `Latitude: ${position.coords.latitude}, Longitude: ${position.coords.longitude}`;
                    const { latitude, longitude } = position.coords;

                    // Use Bing Maps REST Services to get the address based on coordinates
                    const bingMapsApiUrl = `https://dev.virtualearth.net/REST/v1/Locations/${latitude},${longitude}?o=json&key=AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc`;

                    fetch(bingMapsApiUrl)
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.resourceSets && data.resourceSets.length > 0) {
                                const address = data.resourceSets[0].resources[0].address.formattedAddress;
                                coordinatesElement.textContent = coordinates;
                                addressElement.textContent = `Address: ${address}`;

                                // Show the location information section
                                document.getElementById("locationSection").style.display = "block";

                                // Initialize and show the map
                                const map = new Microsoft.Maps.Map('#mapContainer', {
                                    credentials: 'AkdPaHyrL21_U0E9Gdvkl74fuoBSnbl6QyWT6WuLUrT0COJryzTbcprcqceoP9Pc',
                                    center: new Microsoft.Maps.Location(latitude, longitude),
                                    zoom: 15,
                                });

                                // Add a pushpin (marker) at the user's location
                                const pushpin = new Microsoft.Maps.Pushpin(map.getCenter(), {
                                    title: 'Your Location',
                                });

                                map.entities.push(pushpin);
                            } else {
                                alert("Unable to retrieve address information.");
                            }
                        })
                        .catch((error) => {
                            console.error("Error fetching Bing Maps data:", error);
                            alert("Error fetching Bing Maps data. Please try again.");
                        });
                },
                function (error) {
                    console.error("Error getting location:", error);
                    alert("Error getting location. Please try again.");
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    });
}