//Función para calcular el presupuesto
function calcularPresupuesto() {
    //Leemos los valores seleccionados
    const servicio = parseFloat(document.getElementById('servicio').value);
    const comensales = parseInt(document.getElementById('comensales').value) || 0;

    //Calculamos los extras
    const extrasCheckboxes = document.querySelectorAll('.extras:checked');
    let extrasTotal = 0;
    extrasCheckboxes.forEach(checkbox => {
        extrasTotal += parseFloat(checkbox.value);
    });

    //Calculamos el total inicial
    let totalInicial = servicio + extrasTotal;

    //Indicamos los descuentos a utilizar
    let descuentoPorcentaje = 0;
    if (comensales >= 1 && comensales <= 4) {
        descuentoPorcentaje = 5;
    } else if (comensales >= 5 && comensales <= 9) {
        descuentoPorcentaje = 10;
    } else if (comensales >= 10 && comensales <= 15) {
        descuentoPorcentaje = 15;
    } else if (comensales > 15) {
        descuentoPorcentaje = 20;
    }

    const descuento = servicio * (descuentoPorcentaje / 100);
    const totalFinal = totalInicial - descuento;

    //Actualizamos los detalles en el documento HTML para que se cambien solos según los datos
    document.getElementById('detalle-servicio').innerHTML = `${servicio.toFixed(2)}€`;
    document.getElementById('detalle-extras').innerHTML = `${extrasTotal.toFixed(2)}€`;
    document.getElementById('detalle-comensales').innerHTML = `${comensales}`;
    document.getElementById('detalle-inicial').innerHTML = `${totalInicial.toFixed(2)}€`;
    document.getElementById('detalle-descuento').innerHTML = `${descuentoPorcentaje}% (-${descuento.toFixed(2)}€)`;
    document.getElementById('detalle-total').textContent = `${totalFinal.toFixed(2)}€`;
}


//Añadimos los eventos para que se haga en tiempo real
document.getElementById('servicio').addEventListener('change', calcularPresupuesto);
document.getElementById('comensales').addEventListener('input', calcularPresupuesto);
document.querySelectorAll('.extras').forEach(checkbox => {
    checkbox.addEventListener('change', calcularPresupuesto);
});


calcularPresupuesto();
