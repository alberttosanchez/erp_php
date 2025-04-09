<style>
    .rp_modal_group_wrapper {
        width: 100%;
        max-width: calc(100% - 20px);
    }
    .rp_modal_content_box {
        border-radius: unset;
    }
    .rp_modal_title_bar_box{
        display: flex;
        justify-content: space-between;
        padding: 3px;
        /* border: 1px solid #dbdbdb; */
        background-color: lightblue;
        align-items: center;
    }
    .rp_modal_title_bar_box p{
        margin: 0;
        font-size: 12px;
        font-weight: bold;        
    }
    .rp_modal_header_box > h4 {
        text-align: center;
        font-size: 20px;
        padding: 20px;
    }
    .rp_modal_bars_graphic_wrapper {
        display: flex;
        justify-content: space-around;
    }
    .rp_modal_pie_graphic_wrapper {
        display: flex;
        justify-content: space-around;
    }
    .rp_modal_graphic_data_wrapper {
        display: flex;
        justify-content: center;
    }
</style>
<!-- Modal -->
<div class="rp_modal_wrapper modal fade" id="report_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="report_modalLabel" aria-hidden="true">
    <div class="rp_modal_group_wrapper modal-dialog">
        <div class="rp_modal_content_box modal-content">

            <div class="rp_modal_title_bar_box form-group">
                <p>Imprimir Reporte - Período: <span class="rp_start_date">15/02/2021</span> - <span class="rp_end_date">21/02/2021</span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="rp_modal_header_box form-group">
                <h4><?php echo APP_NAME; ?><br>INFORME DE VISITAS, PERIODO <span class="rp_start_date">15/02/2021</span> AL <span class="rp_end_date">21/02/2021</span></h4>
            </div>
            
            <div class="rp_modal_body_group_wrapper form-group">

                <div class="rp_modal_graphic_wrapper form-group">

                    <div class="rp_modal_bars_graphic_wrapper form-group">
                        <div>bars vertical graphic</div>
                        <div>bars horizontal graphic</div>
                    </div>

                    <div class="rp_modal_pie_graphic_wrapper form-group">
                        <div>pie graphic 1</div>
                        <div>pie graphic 2</div>
                        <div>pie graphic 3</div>
                        <div>pie graphic 4</div>
                    </div>

                </div>

                <div class="rp_modal_graphic_data_wrapper form-group">

                    <div class="rp_modal_graphic_data_box form-group">
                        <div>Area de Detalles</div>
                    </div>

                </div>

            </div>
            
        </div>
    </div>
</div>