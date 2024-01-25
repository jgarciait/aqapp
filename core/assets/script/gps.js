function loadMapScenario() {
    const getLocationBtn = document.getElementById("getLocationBtn");
    const coordinatesElement = document.getElementById("coordinates");
    const addressElement = document.getElementById("address");
    const mapContainer = document.getElementById("mapContainer");
    const loadingIndicator = document.getElementById("loadingIndicator");

    getLocationBtn.addEventListener("click", function () {
        if (navigator.geolocation) {
            loadingIndicator.style.display = "block"; // Show loading indicator

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const coordinates = `Latitude: ${position.coords.latitude}, Longitude: ${position.coords.longitude}`;
                    const { latitude, longitude } = position.coords;

                    // Use Bing Maps REST Services to get the address based on coordinates
                    const bingMapsApiUrl = `https://dev.virtualearth.net/REST/v1/Locations/${latitude},${longitude}?o=json&key=YOUR_BING_MAPS_API_KEY`;

                    fetch(bingMapsApiUrl)
                        .then((response) => response.json())
                        .then((data) => {
                            loadingIndicator.style.display = "none"; // Hide loading indicator

                            if (data.resourceSets && data.resourceSets.length > 0) {
                                const address = data.resourceSets[0].resources[0].address.formattedAddress;
                                coordinatesElement.textContent = coordinates;
                                addressElement.textContent = `Address: ${address}`;

                                // Show the location information section
                                document.getElementById("locationSection").style.display = "block";

                                // Initialize and show the map
                                const map = new Microsoft.Maps.Map(mapContainer, {
                                    credentials: 'YOUR_BING_MAPS_API_KEY',
                                    center: new Microsoft.Maps.Location(latitude, longitude),
                                    zoom: 16, // Adjust zoom level as needed
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
                            loadingIndicator.style.display = "none"; // Hide loading indicator
                            console.error("Error fetching Bing Maps data:", error);
                            alert("Error fetching Bing Maps data. Please try again.");
                        });
                },
                function (error) {
                    loadingIndicator.style.display = "none"; // Hide loading indicator
                    console.error("Error getting location:", error);
                    alert("Error getting location. Please try again or check your geolocation settings.");
                }
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    });
}
