<div class="rp_sex_doughnut_graphic_box pie_box form-group">
    <span>Sexo Biológico</span>
    <canvas id="sex_graphic_doughnut" width="400" height="300"></canvas>
</div>
<script>
const sex_graphic_doughnut = document.getElementById('sex_graphic_doughnut').getContext('2d');
const sexGraphicDoughnut = new Chart(sex_graphic_doughnut, {
    type: 'doughnut',
    data: {
        labels: ['Masculino', 'Femenino'],
        datasets: [{
            label: 'Sexo:',
            data: [12, 19],
            backgroundColor: [                
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderColor: [                
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