const vegetarian = document.getElementById('vegetarian');
const vegan = document.getElementById('vegan');

const baseUrl = window.location.origin.includes('github.io')
  ? `${window.location.origin}/queervegancooks`
  : window.location.origin;

  
fetch(`${baseUrl}/data/database.json`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al cargar JSON: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        // Verifica el contenido del JSON en consola
        console.log(data);

        // Asegúrate de que las claves existen en el JSON
        if (data.vegetarian && data.vegan) {
            data.vegetarian.slice(0, 50).forEach(ingrediente => {
                vegetarian.innerHTML += `<span>${ingrediente} - </span>`;
            });
            data.vegan.slice(0, 40).forEach(ingrediente => {
                vegan.innerHTML += `<span>${ingrediente} - </span>`;
            });
        } else {
            console.error('Claves no encontradas en el JSON.');
        }
    })
    .catch(error => console.error('Error al cargar los datos:', error));