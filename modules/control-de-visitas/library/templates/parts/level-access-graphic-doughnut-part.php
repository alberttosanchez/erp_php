<div class="rp_level_access_graphic_doughnut_box pie_box form-group">
    <span>Nivel de Acceso</span>
    <canvas id="level_access_graphic_doughnut" width="400" height="300"></canvas>
</div>
<script>
const level_access_graphic_doughnut = document.getElementById('level_access_graphic_doughnut').getContext('2d');
const levelAccessGraphicDoughnut = new Chart(level_access_graphic_doughnut, {
    type: 'doughnut',
    data: {
        labels: ['A', 'B', 'C'],
        datasets: [{
            label: 'Nive de Acceso:',
            data: [5, 2, 3],
            backgroundColor: [                
                'rgba(54, 162, 235, 1)',                
                'rgba(75, 192, 192, 1)',                
                'rgba(255, 159, 64, 1)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',                
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },        
        /* scales: {
            y: {
                beginAtZero: true
            }
        } */
    }
});
</script>