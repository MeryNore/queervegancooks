function initMap() {

    //nuestra dirección
    const destino = { lat: 43.46071979600298, lng: -3.81734191646179 };
    // div donde irá el mapa
    const mapa = document.getElementById('mapa');

    // Creamos el mapa.
    const map = new google.maps.Map(mapa, {
        zoom: 17,
        center: destino,
    });

    // Creamos la marca
    const marca = new google.maps.Marker({
        position: destino,
        map: map,
        //para poder arrastrar/mover la marca
        draggable: false,
    });

    //Creamos la marca donde mostraremos nuestros datos y el contenido
    const contenidomarca = '<h5>Queer Vegan Cooks</h5><br>C/Vargas, 17 - Santander<br>Tfno: 942 000 000<br>Email: queervegancooks@gmail.com'
    const infomarca = new google.maps.InfoWindow({
        content: contenidomarca
    })
    //Creamos el evento cuando se cliquee en la marca
    marca.addListener('click', () => {
        infomarca.open({
            anchor: marca,
            mapa: mapa,
            shouldFocus: false
        })
    })

    const directionsService = new google.maps.DirectionsService(); //Realiza el cálculo de la ruta
    const directionsRenderer = new google.maps.DirectionsRenderer();
    //indicaciones pintadas en el mapa
    directionsRenderer.setMap(map);
    //indicaciones escritas en un DIV
    indicaciones = document.getElementById('divIndicaciones');
    directionsRenderer.setPanel(indicaciones);

    // Obtener la ubicación actual del cliente.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const origen = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                // Solicitar la ruta desde la ubicación actual al destino.
                directionsService.route(
                    {
                        origin: origen,
                        destination: destino,
                        travelMode: google.maps.TravelMode.DRIVING,
                    },
                    (response, status) => {
                        if (status === google.maps.DirectionsStatus.OK) {
                            directionsRenderer.setDirections(response);
                        } else {
                            console.error("No se pudo mostrar la ruta: " + status);
                        }
                    }
                );
            },
            () => {
                alert("No se pudo obtener la ubicación actual.");
            }
        );
    } else {
        alert("La geolocalización no está soportada por este navegador.");
    }
};

// Inicializar el mapa cuando se cargue la página.
window.onload = initMap
