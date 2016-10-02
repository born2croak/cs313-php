
function drawCaptChart (captData) {
  var ctx = document.getElementById("captChart");
  var myCaptChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        "James T Kirk", "Prince \"Nemo\" Dakkar", "Steve Rodgers", "Jack Sparrow", "Malcolm Reynolds"
      ],
      datasets: [{
        label: '# of Votes',
        data: [captData.Kirk, captData.Nemo, captData.Rogers, captData.Sparrow, captData.Reynolds],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    }
  });
}
