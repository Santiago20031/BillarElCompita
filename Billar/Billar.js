// script.js

// Función para cargar los valores guardados en el localStorage al cargar la página
function cargarValores() {
    for (let i = 1; i <= 6; i++) {
        document.getElementById(`horaInicio${i}`).value = localStorage.getItem(`horaInicio${i}`) || '';
        document.getElementById(`horaFinal${i}`).value = localStorage.getItem(`horaFinal${i}`) || '';
        document.getElementById(`precioHora${i}`).value = localStorage.getItem(`precioHora${i}`) || 30; // Valor por defecto
        document.getElementById(`totalHoras${i}`).value = localStorage.getItem(`totalHoras${i}`) || ''; // Cargar total horas
        document.getElementById(`precioPagar${i}`).value = localStorage.getItem(`precioPagar${i}`) || ''; // Cargar precio a pagar
    }
}

// Función para guardar los valores en el localStorage cuando se cambian
function guardarValores(mesaId) {
    localStorage.setItem(`horaInicio${mesaId}`, document.getElementById(`horaInicio${mesaId}`).value);
    localStorage.setItem(`horaFinal${mesaId}`, document.getElementById(`horaFinal${mesaId}`).value);
    localStorage.setItem(`precioHora${mesaId}`, document.getElementById(`precioHora${mesaId}`).value);
    localStorage.setItem(`totalHoras${mesaId}`, document.getElementById(`totalHoras${mesaId}`).value); // Guardar total horas
    localStorage.setItem(`precioPagar${mesaId}`, document.getElementById(`precioPagar${mesaId}`).value); // Guardar precio a pagar
}

// Modificar la función calcularPrecio para guardar los valores después de calcular
function calcularPrecio(mesaId) {
    const horaInicio = document.getElementById(`horaInicio${mesaId}`).value;
    const horaFinal = document.getElementById(`horaFinal${mesaId}`).value;
    const precioHora = parseFloat(document.getElementById(`precioHora${mesaId}`).value);
    
    if (horaInicio && horaFinal && !isNaN(precioHora)) {
        const inicio = new Date(`1970-01-01T${horaInicio}:00Z`);
        const final = new Date(`1970-01-01T${horaFinal}:00Z`);
        let diferenciaMinutos = (final - inicio) / 1000 / 60;

        if (diferenciaMinutos < 0) {
            diferenciaMinutos += 1440; // Si la diferencia es negativa, añadir 24 horas en minutos
        }

        const horas = Math.floor(diferenciaMinutos / 60);
        const minutos = Math.round(diferenciaMinutos % 60);
        const precioTotal = (diferenciaMinutos / 60) * precioHora;

        document.getElementById(`totalHoras${mesaId}`).value = `${horas}h ${minutos}m`;
        document.getElementById(`precioPagar${mesaId}`).value = `$${precioTotal.toFixed(2)}`;

        // Crear un objeto FormData para enviar solo los datos de la mesa correspondiente
        const formData = new FormData();
        formData.append("mesa_numero", mesaId);
        formData.append("hora_inicio", horaInicio);
        formData.append("hora_final", horaFinal);
        formData.append("total_horas", (diferenciaMinutos / 60).toFixed(2));
        formData.append("precio_por_hora", precioHora);
        formData.append("precio_a_pagar", precioTotal.toFixed(2));
        
        // Enviar solo los datos de la mesa correspondiente
        fetch("guardar.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Muestra el resultado de la operación
            const mensajeDiv = document.getElementById("mensaje");
            mensajeDiv.innerText = data; // Muestra el mensaje en el div
            mensajeDiv.style.display = "block"; // Asegúrate de que el mensaje sea visible
        
            // Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                mensajeDiv.style.display = "none";
            }, 3000);
        })
        .catch(error => {
            console.error("Error al guardar los datos:", error);
        });

    };
}

function enviarDatos(mesaId, horaInicio, horaFinal, totalHoras, precioHora, precioTotal) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "ConexIndex.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Puedes manejar la respuesta aquí si es necesario
        }
    };

    const params = `mesa_numero=${mesaId}&hora_inicio=${horaInicio}&hora_final=${horaFinal}&total_horas=${totalHoras}&precio_por_hora=${precioHora}&precio_a_pagar=${precioTotal.toFixed(2)}`;
    xhr.send(params);
}

// Añadir eventos de cambio a los inputs de hora
function agregarEventos() {
    for (let i = 1; i <= 6; i++) {
        document.getElementById(`horaInicio${i}`).addEventListener('change', () => guardarValores(i));
        document.getElementById(`horaFinal${i}`).addEventListener('change', () => guardarValores(i));
        document.getElementById(`precioHora${i}`).addEventListener('change', () => guardarValores(i));
    }
}

// Llamar a la función cargarValores al cargar la página
window.onload = () => {
    cargarValores();
    agregarEventos();
};