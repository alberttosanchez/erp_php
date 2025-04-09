<div class="rp_gun_graphic_pie_box pie_box form-group">
    <span>Motivos de Visitas</span>
    <canvas id="visit_type_graphic_pie" width="200" height="200"></canvas>
</div>
<script>
const visit_type_graphic_pie = document.getElementById('visit_type_graphic_pie').getContext('2d');
const visitTypeGraphicPie = new Chart(visit_type_graphic_pie, {
    type: 'pie',
    data: {
        labels: ['Cita', 'Promoción', 'Técnica', 'Personal', 'Otros'],
        datasets: [{
            label: 'Motivo:',
            data: [19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',                
                'rgba(153, 102, 255,1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',                
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
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