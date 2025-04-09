<style>
    .vt_name_modal_group_wrapper {
        width: 100%;
        max-width: calc(100% - 20px);
    }
    .vt_name_modal_content_box {
        border-radius: unset;
    }
    .vt_name_modal_title_bar_box{
        display: flex;
        justify-content: space-between;
        padding: 3px;
        /* border: 1px solid #dbdbdb; */
        background-color: lightblue;
        align-items: center;
    }
    .vt_name_modal_title_bar_box p{
        margin: 0;
        font-size: 12px;
        font-weight: bold;        
    }
    .vt_name_modal_header_box > h4, .vt_name_modal_header_box > p, .vt_btn_print_ticket {
        text-align: center;
        font-size: 21px;
        padding: 10px 20px;
        margin: 20px auto;
        display: block;
    }
    .vt_name_modal_bars_graphic_wrapper {
        display: flex;
        justify-content: space-around;
    }
    .vt_name_modal_pie_graphic_wrapper {
        display: flex;
        justify-content: space-around;
    }
    .vt_name_modal_graphic_data_wrapper {
        display: flex;
        justify-content: center;
    }
</style>
<!-- Modal -->
<div class="vt_name_modal_wrapper modal fade" id="vt_name_modal_wrapper" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="vt_name_modalLabel" aria-hidden="true">
    <div class="vt_name_modal_group_wrapper modal-dialog">
        <div class="vt_name_modal_content_box modal-content">

            <div class="vt_name_modal_title_bar_box form-group">
                <p> Imprimiendo nombre del visitante: <span class="visitant_name_span"></span></p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="vt_name_modal_header_box form-group">
                <p>Visitante:</p>
                <h4></h4>

                <button class="vt_btn_print_ticket btn btn-primary" type="button" onclick="send_to_print_tail()">Reimprimir Ticket</button>
            </div>
            
        </div>
    </div>
</div>