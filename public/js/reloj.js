function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeString = padZero(hours) + ":" + padZero(minutes) + ":" + padZero(seconds);
    document.getElementById("clock").innerHTML = timeString;
  }
  
  function padZero(num) {
    return (num < 10 ? "0" : "") + num;
  }
  
  // Actualizar el reloj cada segundo
  setInterval(updateClock, 1000);
  