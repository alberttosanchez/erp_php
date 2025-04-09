<div class="rp_gun_graphic_pie_box pie_box form-group">
    <span>Porte de Armas</span>
    <canvas id="gun_graphic_pie" width="200" height="200"></canvas>
</div>
<script>
const gun_graphic_pie = document.getElementById('gun_graphic_pie').getContext('2d');
const gunGraphicPie = new Chart(gun_graphic_pie, {
    type: 'pie',
    data: {
        labels: ['Si', 'No'],
        datasets: [{
            label: 'Armas:',
            data: [3, 5],
            backgroundColor: [                
                'rgba(15, 102, 255,1)',
                'rgba(25, 159, 64,1)'
            ],
            borderColor: [                
                'rgba(153, 102, 255,1)',
                'rgba(255, 159, 64,1)'
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