const calendarDays = document.getElementById('calendarDays');
const monthYear = document.getElementById('monthYear');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');
const proposeEventButton = document.getElementById('proposeEventButton');
const eventList = document.getElementById('eventList');
const eventModal = document.getElementById('eventModal');
const closeModal = document.getElementById('closeModal');
const eventForm = document.getElementById('eventForm');
const contactLink = document.getElementById('contactLink');
const contactModal = document.getElementById('contactModal');
const closeContactModal = document.getElementById('closeContactModal');

const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
let currentDate = new Date();
let selectedDay = null;
let events = {}; // Estructura de eventos: { "YYYY-MM-DD": [eventos] }
//Nos faltaría algo para que los eventos se cargaran de bbdd y se metieran en el events{}
//ahora, otra opción es crear un json desde php
// y que js lea ese json y cargue los eventos desde un bucle

// Permite seleccionar una imagen
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.selectable-image');

    images.forEach(image => {
        image.addEventListener('click', function() {
            // Deseleccionar todas las imágenes
            images.forEach(img => img.classList.remove('selected'));
            
            // Seleccionar la imagen clickeada
            this.classList.add('selected');
        });
    });
});
    
function renderCalendar() {
    calendarDays.innerHTML = '';
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    monthYear.textContent = `${months[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    const daysOfWeek = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.classList.add('day', 'header');
        dayElement.textContent = day;
        calendarDays.appendChild(dayElement);
    });

    for (let i = 0; i < firstDay; i++) {
        const blankDay = document.createElement('div');
        blankDay.classList.add('day');
        calendarDays.appendChild(blankDay);
    }

    for (let i = 1; i <= lastDate; i++) {
        const day = document.createElement('div');
        day.classList.add('day');
        day.textContent = i;
        const dayDate = new Date(year, month, i);
        if (dayDate.toDateString() === new Date().toDateString()) {
            day.classList.add('active');
        }
        day.addEventListener('click', () => {
            if (selectedDay) {
                selectedDay.classList.remove('selected');
            }
            day.classList.add('selected');
            selectedDay = day;

            const selectedDate = dayDate.toISOString().split('T')[0];
            renderEvents(selectedDate);
        });
        calendarDays.appendChild(day);
    }

    const totalCells = Math.ceil((firstDay + lastDate) / 7) * 7;
    const extraCells = totalCells - (firstDay + lastDate);
    for (let i = 0; i < extraCells; i++) {
        const blankDay = document.createElement('div');
        blankDay.classList.add('day');
        calendarDays.appendChild(blankDay);
    }
}

function renderEvents(date) {
    eventList.innerHTML = ''; // Limpiar lista de eventos
    if (events[date]) {
        events[date].forEach(event => {
            const eventElement = document.createElement('div');
            eventElement.classList.add('<event>');
            eventElement.innerHTML = `
            <div class="noticia">
                <img src="/img/Diseño sin título (1).png" alt="Noticia">
                <div class="contenido">
                    <div class="categorias">
                        <p class="categoria2"> ${event.target}</p>
                        <p class="categoria2">${event.location}</p>
                    </div>
                    <h2 class="titulo">${event.title}</h2>
                    <h3 class="description">${event.description}</h3>
                    <p class="description">${date} ${event.time ? event.time : ''}</p>
                </div>
            </div>
        `;
            eventList.appendChild(eventElement);
        });
    } else {
        eventList.innerHTML = '<p>No hay eventos para este día.</p>';
    }
}

function addEvent(eventData,selectedDate) 
{
    //const selectedDate = selectedDay ? new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(selectedDay.textContent)) : new Date();
    const date = selectedDate.toISOString().split('T')[0];

    if (!events[date]) {
        events[date] = [];
    }
    //console.log(typeof(eventData));
    events[date].push(eventData);
    //Volcar a un json -> 
    //Llamada al modulo de Fran (creo que es aqui, por lo que sea)
    //eventData.set("fechaSeleccionada",selectedDate);
    saveEventToFile(eventData);
    renderEvents(date);
}

//Crear JSON y enviarlo por POST para el PHP y guardarlo en la carpeta que toca
//No se donde va la llamada
function saveEventToFile(eventData) 
{
    const eventJSON = JSON.stringify(eventData, null, 2); //TODO añadir selectedDate al JSON
    
    // Verificar el JSON antes de enviarlo
    console.log("JSON a enviar:", eventJSON);


    fetch('../php/crearEvento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: eventJSON
        })
        .then(response=> response.text())
        .then (data => { try {
            console.log(JSON.parse(data));
        } catch (e) {
            console.error('Error parsing JSON:', data);
        }
    });

    
    showEvents();
};

//funcion que muestra la lista de eventos en la pagina principal
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
             eventoElemento.classList.add('<event>');
             eventoElemento.innerHTML = `
             <div class="noticia">
                 <img src="../img/Diseño sin título (1).png" alt="Noticia">
                 <div class="contenido">
                     <div class="categorias">
                         <p class="categoria2"> ${evento.categoria}</p>
                         <p class="categoria2">${evento.ubicacion}</p>
                     </div>
                     <h2 class="titulo">${evento.nombre}</h2>
                     <h3 class="description">${evento.descripcion}</h3>
                     <p class="description">${evento.hora}</p>
                     <div class="botone">
                    <input type="submit" class="boton" value="Apuntarme"> 
                </div>
                 </div>
             </div>
         `;
         //<p class="description">${date} ${evento.time ? evento.time : ''}</p>
             // Agregar el elemento a la página
             eventosContainer.appendChild(eventoElemento);
         });
     })
     .catch(error => {
         console.error("Error al cargar el archivo JSON:", error);
     });
 }

proposeEventButton.addEventListener('click', () => {
    eventModal.style.display = 'flex';
});

closeModal.addEventListener('click', () => {
    eventModal.style.display = 'none';
});

//Esta llamada es la que recibe la información del formulario y crea el evento en el calendario
eventForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const selectedDate = selectedDay ? new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(selectedDay.textContent)) : new Date();
    const eventData = {
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        location: document.getElementById('eventLocation').value,
        target: document.getElementById('eventTarget').value,
        time: document.getElementById('eventTime').value,
        timeEnd: document.getElementById('eventTimeEnd').value,
        creator: 1,
        dateSelected: selectedDate,
    };
    addEvent(eventData,selectedDate);
    //showEvents();
    eventModal.style.display = 'none';
    eventForm.reset();
});

prevButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextButton.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

contactLink.addEventListener('click', (e) => {
    e.preventDefault();
    contactModal.style.display = 'flex';
});

closeContactModal.addEventListener('click', () => {
    contactModal.style.display = 'none';
});

renderCalendar(); // Inicializar el calendario
showEvents();

 