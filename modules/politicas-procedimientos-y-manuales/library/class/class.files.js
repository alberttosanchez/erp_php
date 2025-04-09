
/**
 * @param {*}
 * @method constructor : display a log console message of new instance.
 * 
 * This class validate formData object.
 */
export class Files {
    //#private_field = "este variable es privada dentro de la clase";
    //this.#private_field = "esta es la manera de invocar los parametros privados".
    #instance;
    
    constructor(param = ""){
        //dentro del constructor se declaran los parametros internos
        this.param = "this palabra para indicar que el parametro pertenece a esta clase.";
        console.log('New Instance of ValidateForms created');
        this.#instance = true;
        
    };



    get_file_extension(file = "")
        {

            let array = file.split(".");

            for (let i=0; i < array.length; i++) {
                
                if ( i == ( array.length - 1) )
                {
                    return array[i];
                }
                
            }
            return false;            
    }

}