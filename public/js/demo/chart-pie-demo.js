function generarGraficarea(mantenimiento, almacen, logistica, rh, gestoria, contabilidad, sistemas, ventas) {

  // Asegúrate de que mantenimiento sea un número flotante válido
  mantenimiento = parseFloat(mantenimiento) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  almacen = parseFloat(almacen) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  logistica = parseFloat(logistica) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  rh = parseFloat(rh) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  gestoria = parseFloat(gestoria) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  contabilidad = parseFloat(contabilidad) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  sistemas = parseFloat(sistemas) || 0; // Convierte a flotante o usa 0 como valor predeterminado
  ventas = parseFloat(ventas) || 0; // Convierte a flotante o usa 0 como valor predeterminado

  // Pie Chart Example
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ["Mantenimiento", "Almacen", "Logistica", "RH", "Gestoría", "Contabilidad", "Sistemas", "Ventas"],
      datasets: [{
        data: [
          mantenimiento, 
          almacen,
          logistica,
          rh,
          gestoria,
          contabilidad,
          sistemas,
          ventas
        ],
        backgroundColor: ['#007bff', '#28a745', '#17a2b8', '#dc3545', '#ffc107', '#6c757d', '#343a40', '#ff00ff'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });
}
