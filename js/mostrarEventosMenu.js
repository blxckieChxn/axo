//TODO antes de hacerlo una funcion en un fichero aparte funcionaba (captura)
//¿Porue ahora no?

function showEvents() {   
   // Función para cargar el archivo eventos.json
    fetch('../JSON/mostrarEventos.json')
    .then(response => response.json())  // Parseamos el JSON
    .then(data => {
        
        //Esto de aqui debe ser igual que el id del DIV que lo encapsula
        const eventosContainer = document.getElementById('eventList');
        data.forEach(evento => {
            // Crear un nuevo elemento para cada evento
            const eventoElemento = document.createElement('div');
            eventoElemento.innerHTML = `
                <h2>${evento.nombre}</h2>
                <p>Ubicacion: ${evento.ubicacion}</p>
                <p>Fecha: ${evento.fecha}</p>
            `;
            // Agregar el elemento a la página
            eventosContainer.appendChild(eventoElemento);
        });
    })
    .catch(error => {
        console.error("Error al cargar el archivo JSON:", error);
    });
}


showEvents();