<div class="rp_horizontal_graphic_bar_box bar_box form-group">
    <h5>Departamentos Visitados</h5>
    <canvas id="my_chart_bar" width="400" height="300"></canvas>
</div>
<script>
const horizontal_graphic_bar = document.getElementById('my_chart_bar').getContext('2d');
const horizontalGraphicBar = new Chart(horizontal_graphic_bar, {
    type: 'bar',
    data: {
        labels: ['DTIC', 'Despacho', 'Comunicaciones', 'Juridico', 'Compras'],
        datasets: [{
            label: '',            
            data: [12, 19, 3, 5, 2,],
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
        indexAxis: 'y',
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>