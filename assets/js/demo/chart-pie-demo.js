// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Direct", "Referral", "Social"],
    datasets: [{
      data: [55, 30, 15],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#6f42c1', '#20c997', '#e83e8c', '#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40', '#c9cbcf', '#7b8d8e', '#a2b9bc', '#87a330', '#037d9a', '#564235', '#a39171', '#b85d6b', '#5a5b9f', '#f7cac9', '#f7786b', '#cbeaa6', '#5b5b5b'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b600', '#e02d2d', '#6d6e7a', '#e66a00', '#5a32a3', '#1a9b77', '#d6267a', '#ff4a6b', '#2a8cdb', '#b24de7', '#e6b847', '#3aa9a9', '#804dff', '#e68a2d', '#b0b2b5', '#6a7c7d', '#8fa2a5', '#728c28', '#026a82', '#4a372c', '#8c7d61', '#9e4f5c', '#4c4d8a', '#e0b8b7', '#e06a5c', '#b2d690', '#4d4d4d'],
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
