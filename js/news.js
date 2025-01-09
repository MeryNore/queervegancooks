const vegetarian = document.getElementById('vegetarian')
const vegan = document.getElementById('vegan')

fetch('../data/Database.json')
fetch('../data/database.json')
    .then(response => response.json())
    .then(data => {
        data.vegetarian.slice(0, 50).forEach(ingredientes => {
            vegetarian.innerHTML += `<span>${ingredientes} - </span>`
        });
        data.vegan.slice(0, 40).forEach(ingredientes => {
            vegan.innerHTML += `<span>${ingredientes} - </span>`
        });
    })

.catch(error => console.error('Error al cargar los datos', error))
