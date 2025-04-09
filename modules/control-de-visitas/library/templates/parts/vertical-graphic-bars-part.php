<div class="rp_vertical_graphic_bar_box bar_box form-group">
    <h5>Visitas de la Semana</h5>
    <canvas id="vertical_graphic_bar" width="400" height="300"></canvas>
</div>
<script>
const vertical_graphic_bar = document.getElementById('vertical_graphic_bar').getContext('2d');
const verticalGraphicBar = new Chart(vertical_graphic_bar, {
    type: 'bar',
    data: {
        labels: ['Domingo','Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes','Sábados'],
        datasets: [{
            label: '# Visitas',
            data: [0,12, 19, 3, 5, 2,0],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',                
                'rgba(255, 159, 64, 1)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',                
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
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>