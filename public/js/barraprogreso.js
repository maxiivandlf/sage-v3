// Función para mostrar el preloader
function showPreloader() {
    $('#preloader').fadeIn();
}

// Función para ocultar el preloader
function hidePreloader() {
    $('#preloader').fadeOut();
}

// Función para actualizar la barra de progreso
function updateProgressBar(progress) {
    $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
}

// Simular el tiempo de carga total (para pruebas)
$(window).on('load', function() {
    var totalTime = 3000; // Tiempo total de carga en milisegundos
    var increment = 10; // Incremento para la barra de progreso en milisegundos

    var progress = 0; // Progreso inicial
    var incrementValue = 100 / (totalTime / increment); // Valor de incremento de la barra de progreso

    var interval = setInterval(function() {
        progress += incrementValue;
        updateProgressBar(progress);
        if (progress >= 100) {
            clearInterval(interval);
            hidePreloader(); // Ocultar el preloader cuando se completa la carga
        }
    }, increment);
});
