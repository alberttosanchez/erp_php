<?php // agregue aqui las dependencias ?>

    <link rel="stylesheet" href="<?="../" . CV_ASSETS_DIRECTORY . '/css/daterangepicker.css'; ?>" ></link>
    <link rel="stylesheet" href="<?="../" . CV_ASSETS_DIRECTORY . '/css/style.css'; ?>" ></link>

    <script src="<?="../" . CV_ASSETS_DIRECTORY . '/js/jquery-3.6.0.min.js';?>"></script>
    <script src="<?="../" . CV_ASSETS_DIRECTORY . '/js/moment.min.js';?>"></script>
    <script src="<?="../" . CV_ASSETS_DIRECTORY . '/js/daterangepicker.js';?>"></script>

    <script src="<?='../' . CV_SCRIPTS_DIRECTORY . '/config.js'; ?>"></script>
    <script src="<?='../' . CV_SCRIPTS_DIRECTORY . '/functions.js';?>"></script>

    <script src="<?= '../' .  CV_ASSETS_DIRECTORY . '/js/zebra/JavaScriptFiles/BrowserPrint-3.0.216.min.js';?>"></script>

    <div>
        <h2>No cierre esta pagina... mientra desee imprimir la cola de impresión.</h2>
    </div>

    <script>       

        var visitant_name;
        var visitant_last_name;
        var co_worker_dpto;
        var dpto_floor;
        var visit_date;
        var identification;
        
        var visitant_info;
        var visit_info;
        var selected_device;

        writeToSelectedPrinter = ( visitant_data = {} ) => {
            
            // Codigo que imprime la etiqueta vertical.
            let verticalDataToWriteOnZebraTag  = `^XA`;
                verticalDataToWriteOnZebraTag += `^FWR`; // ^FWR: ^FW:orientacion, R:90Grados, I:180grafos, B:270grados
                verticalDataToWriteOnZebraTag += `~DYR:cupula.PNG,p,p,554,13,:B64:iVBORw0KGgoAAAANSUhEUgAAAGAAAABoAQMAAAA0BWcWAAAABlBMVEUAAAD///+l2Z/dAAAB30lEQVR42nWUO27bQBCG/yUJewvBYulCQFimMAyWLohwkZPkCClTJBYTu3CZI+gkCWWoUGfBJ6CDFCqpQAUliNosxd2dIWCz+zAP/vPYERr0BeCw2L1lAZYcdjF32wxiEg5/ObSD1KqHSMcNUDlLcwOsvdt5hT3FvINmCSoUDh4xwawH0fyWIWrvtivKhmJ+pgeC7ejItN1ppm1WFgR1wizbuCRoRxVr1Y+awZwVh9UXW1xu0jaZc5uWOCQ+JsPxwkNYamlhXuAGd6dsolkqTEEK7kX5h0pQqSAFyxGTsw8aAv2dFYdZxmB9zWAvV2ZIFnTEl+KjqQmRhWojhLeYn2oP3U8LB33nH3sInzRykyA/1TIXqPm+PXO4CgaLyBOYaZ/isRFQzm0gVHIF8ViH3pJ0HXfwi8WIA4OzJjXttnD5zCyf2B6I6T3BxVESfN7S7oTFauzhq1an0UetNA3b4YO15N1L+8ZmKmhyCwT/XIVmhlE16SyBFGaectGvf93NNn1gQnP+nG9jgpAtH0ZrRZBWbENKxUpoBcFkG7DXuIo8jKEkbHHhoUXqLJm5B4pfCv+C8QJB1yXBmYVAvjd9t4MV9XrmzovVxuCcX5dLDC4FmDaCcc0gfv3yRdlb1/I/xr2OxsyQG0wAAAAASUVORK5CYII=:0a2a`;            
                verticalDataToWriteOnZebraTag += `^FO300,20 ^XGR:cupula.PNG ^FS`;
                verticalDataToWriteOnZebraTag += `^FO300,130 ^A0,70,60 ^FD Ministerio de la Juventud ^FS`;
                verticalDataToWriteOnZebraTag += `^FO200,30 ^A0,60,40 ^FD Visitante: ^FS`;
                verticalDataToWriteOnZebraTag += `^FO200,200 ^A0,60,40 ^FD ${visitant_name} ${visitant_last_name}^FS`;
                verticalDataToWriteOnZebraTag += `^FO175,200 ^A0,35,30 ^FD ${identification}^FS`;
                verticalDataToWriteOnZebraTag += `^FO120,30 ^A0,40,35 ^FD Dpto: ^FS`;
                verticalDataToWriteOnZebraTag += `^FO120,200 ^A0,40,35 ^FD ${co_worker_dpto}^FS`;
                verticalDataToWriteOnZebraTag += `^FO80,30 ^A0,40,35 ^FD Piso: ^FS`;
                verticalDataToWriteOnZebraTag += `^FO80,200 ^A0,40,35 ^FD ${dpto_floor}^FS`;
                verticalDataToWriteOnZebraTag += `^FO40,30 ^A0,40,35 ^FD Registro: ^FS`;
                verticalDataToWriteOnZebraTag += `^FO40,200 ^A0,40,35 ^FD ${visit_date}^FS`;
                verticalDataToWriteOnZebraTag += `^FO350, 200^ADN, 11, 7 `;
                verticalDataToWriteOnZebraTag += `^XZ`;

            // Codigo que imprime la etiqueta horizontal.
            let horizontalDataToWriteOnZebraTag  = `^XA`;
                horizontalDataToWriteOnZebraTag += `^FWI`; // ^FW:orientacion, R:90Grados, I:180grafos, B:270grados        
                horizontalDataToWriteOnZebraTag += `^FO730,300 ^GFA,891,891,11,,:G3gFG8::,H0G3GCH0GFG8H0G3GCH0G1GEH0G7G8H0:::,:G3gFG8H0G3GCH0GFG8H0G3GCH0G1GEH0G7G8H0H0G3GCH0G7I0G3GCH0G1GEH0G7G8H0,G0GFGCG3HFG0IFGCG3HFGEG1HFG8G7GEG0G0GFGCG3HFG0G7HFGCG3HFGEG1HFG8G7GEG0G0G7GCG3HFG0G7HFGCG3HFGEG1HFG8G7GEG0G0G7GEG1HFG0G7HFGCG3HFGEG1HFG8G7GEG0G0G7GEG1HFG8G7HFGCG3HFGEG1HFG8G7GEG0:G0G7GEG1HFG8G7HFGCG3HFGEG1HFG0G7GCG0G0G7GEG1HFG8G7HFGCG3HFGEG1HFG0GFGCG0G0G3GEG1HFG8G7HFGCG3HFGCG1HFG0GFGCG0G0G3GFG0HFG8G7HFGCG3HFGCG1HFG0GFGCG0G0G3GFG0HFG8G7HFGCG3HFGCG3HFG0GFG8G0G0G3GFG0HFG8G7HFGCG3HFGCG3GFGEG1GFG8G0G0G1GFG0G7GFG8G3HFGCG3HFGCG3GFGEG1GFG8G0G0G1GFG8G7GFGCG3HFGCG3HFGCG3GFGEG1GFH0G0G1GFG8G7GFGCG3HFGCG3HFG8G3GFGCG3GFH0H0GFG8G3GFGCG3HFGCG3HFG8G3GFGCG3GFH0H0GFGCG3GFGCG1HFGCG3HFG8G7GFG8G3GEH0H0G7GCG1GFGCG1HFGCG3HFG8G7GFG8G7GEH0H0G7GEG1GFGEG1HFGCG3HFG0G7GFG8G7GCH0H0G3GEG0GFGEG1HFGCG3HFG0HFG0GFGCH0H0G3GFG0GFGEG0HFGCG3HFG0GFGEG0GFG8H0H0G1GFG0G7GFG0HFGCG3GFGEG0GFGEG1GFI0I0GFG8G7GFG0G7GFGCG3GFGEG1GFGCG1GFI0I0GFG8G3GFG0G7GFGCG3GFGEG1GFGCG3GEI0I0G7GCG1GFG8G7GFGCG3GFGCG1GFG8G3GCI0I0G3GCG1GFG8G3GFGCG3GFGCG3GFG0G7GCI0I0G1GEG0GFGCG3GFGCG3GFG8G3GFG0G7G8I0J0GFG0G7GCG1GFGCG3GFG8G7GEG0GFJ0J0G7G0G3GEG1GFGCG3GFG0G7GCG1GEJ0J0G3G8G3GEG0GFGCG3GEG0GFG8G3GCJ0J0G1GCG1GFG0G7GCG3GEG0GFG0G3G8J0K0GEG0G7G0G7GCG3GCG1GEG0G7K0K0G7G0G3G8G3GCG3G8G3GCG0GCK0K0G3G8G1GCG1GCG3G0G3G8G1G8K0L0GCG0GEG0GCG3G0G6G0G3L0L0G2G0G2G0G4G2G0GCG0GCL0L0G1M0G1M0,::O0JFO0,O0G1H9G8O0:::::::O0I1P0O0IFGEO0,O0G3HFGCO0:O0G3HFG8O0O0G1HFG8O0O0G1HFP0P0G7GEP0,:::::^FS`;
                horizontalDataToWriteOnZebraTag += `^FO80,300 ^A0,70,60 ^FD Ministerio de la Juventud ^FS`;
                horizontalDataToWriteOnZebraTag += `^FO650,200 ^A0,60,40 ^FD Visitante: ^FS`;
                horizontalDataToWriteOnZebraTag += `^FO330,200 ^A0,60,40 ^FD ${visitant_name} ${visitant_last_name}^FS`;
                horizontalDataToWriteOnZebraTag += `^FO450,175 ^A0,35,30 ^FD ${identification}^FS`;
                horizontalDataToWriteOnZebraTag += `^FO650,120 ^A0,40,35 ^FD Dpto: ^FS`;
                horizontalDataToWriteOnZebraTag += `^FO${co_worker_dpto.length > 20 ? "80" : "260"},120 ^A0,40,${co_worker_dpto.length > 20 ? "17" : "35"} ^FD ${co_worker_dpto}^FS`;
                horizontalDataToWriteOnZebraTag += `^FO650,80 ^A0,40,35 ^FD Piso: ^FS`;
                horizontalDataToWriteOnZebraTag += `^FO360,80 ^A0,40,35 ^FD ${dpto_floor}^FS`;
                horizontalDataToWriteOnZebraTag += `^FO650,40 ^A0,40,35 ^FD Registro: ^FS`;
                horizontalDataToWriteOnZebraTag += `^FO300,40 ^A0,40,35 ^FD ${visit_date}^FS`;
                horizontalDataToWriteOnZebraTag += `^FO200,350 ^ADN, 11, 7 `;
                horizontalDataToWriteOnZebraTag += `^XZ`;
            
            
            // Codigo que imprime la etiqueta. -> ver config.js
            selected_device.send(horizontalDataToWriteOnZebraTag, undefined, errorCallback);

            setTimeout(() => {
                location.reload();
            }, 5000);
        }

        errorCallback = (errorMessage) => {
            console.log("Error: " + errorMessage);	
            alert("Error: Asegurese que la impresora esta encendida y el programa [Zebra Browser Print] este abierto.");
        }

        get_from_print_tail = async () => {

            let body_data = JSON.stringify({                
                                target : "print_tail-read",                            
                                info_data: {                                
                                    file_name       : "print_tail_zd421.txt"
                                },
                                session_token: localStorage.session_token,
                                user_id: this.state.data.fetched.user_id
                            });

            console.log(body_data);

            try {

                let url = API_INDEX_URL;
                
                const server = await fetch( url, {
                    method: "POST",
                    headers: {                                
                        'Content-Type': 'application/json'       
                    },
                    body: body_data, 
                });

                const response = await server.json();

                console.log(response);
                
                //loading();

                switch (server.status) {
                    case 200:
                        
                        visitant_name       = response.data[0];
                        visitant_last_name  = response.data[1];
                        co_worker_dpto      = response.data[2];
                        identification      = response.data[3];
                        dpto_floor          = response.data[4];
                        visit_date          = response.data[5];

                        writeToSelectedPrinter(); break;                
                    case 401:
                        console.log(response.message.capitalize());

                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                        break;
                
                    default:
                        console.log(response.message.capitalize());
                        break;
                }
            } catch (error) {

                console.log(error);

            }

        }

        window.addEventListener('load', () => {

            BrowserPrint.getDefaultDevice("printer", function(printer)
            {   
                selected_device = printer;  
    
                if ((printer != null) && (printer.connection != undefined)) {
                    
                    get_from_print_tail();
                    
                }
    
            }, function(error){ });

        });


    </script>
