// Carrusel de opiniones (testimonios)
// - Controla la navegación entre slides y los indicadores (dots)
// - Auto-rotación con interval y pausa/reinicio al interactuar

// Espera a que el DOM esté listo para buscar elementos
document.addEventListener('DOMContentLoaded', () => {
    // Contenedor que se desplaza con transform: translateX(...)
    const track = document.getElementById('opinionesTrack');
    if (!track) return; // No estamos en la página que contiene el carrusel

    // Slides y puntos indicadores
    const slides = document.querySelectorAll('[data-opinion-slide]');
    const dots = document.querySelectorAll('[data-opinion-dot]');
    const total = slides.length;
    let index = 0; // índice de slide actual
    let intervalId = null; // id del timer de auto-rotación

    // Muestra la slide i (gestiona wrap-around con modulo)
    function goToSlide(i) {
        index = (i + total) % total;
        // Mueve el track en porcentaje: cada slide ocupa 100% del ancho
        track.style.transform = `translateX(-${index * 100}%)`;

        // Actualiza los estilos de los indicadores (dot activo/inactivo)
        dots.forEach((dot, j) => {
            if (j === index) {
                dot.classList.add('bg-azul');
                dot.classList.remove('bg-beige2');
            } else {
                dot.classList.add('bg-beige2');
                dot.classList.remove('bg-azul');
            }
        });
    }

    // Inicia la auto-rotación: avanza cada X milisegundos
    function startAuto() {
        intervalId = setInterval(() => {
            goToSlide(index + 1);
        }, 10000); // 10s por slide
    }

    // Detiene la auto-rotación
    function stopAuto() {
        if (intervalId) clearInterval(intervalId);
    }

    // Permite al usuario saltar a una slide haciendo clic en un dot.
    // Al hacerlo, pausamos y reiniciamos el auto-scroll para mejor UX.
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            stopAuto();
            goToSlide(i);
            startAuto();
        });
    });

    // Inicialización: mostrar primera slide y arrancar auto-rotación
    goToSlide(0);
    startAuto();
});
