
/**
 * @param {*}
 * @method constructor : display a log console message of new instance.
 * 
 * This class validate formData object.
 */
export class ValidateForms {
    //#private_field = "este variable es privada dentro de la clase";
    //this.#private_field = "esta es la manera de invocar los parametros privados".
    #instance;
    
    constructor(param = ""){
        //dentro del constructor se declaran los parametros internos
        this.param = "this palabra para indicar que el parametro pertenece a esta clase.";
        console.log('New Instance of ValidateForms created');
        this.#instance = true;
        
    };

    /**
     * Verifica si es un objeto FormData
     * @param {object} formData 
     * @returns true o false
     */
    #isformDataInstance(formData){

        if (formData instanceof FormData){
            return true;
        }

        return false;            
    }
    /**
     * Devuelve un array con los nombre de los campos datos en un objeto.
     * @param {*} object 
     * @returns array con nombre de los campos o array[]
     */
    #getFieldNamesFromObjectInArray(object){

        if ( typeof object == "object" )
        {
            let fieldNames = []; let counter = 0;
            for ( let field in object ){
                fieldNames[counter] = field;
                counter++;
            }
            return fieldNames;
        }

        return [];
    }

    #getFieldNamesFromFormDataInArray(formData){

        if ( this.#isformDataInstance(formData) )
        {
            let fieldNames = []; counter = 0;
            for ( const pair of formData.entries() ){
                fieldNames[counter] = pair[0];
                counter++;
            }
            return fieldNames;
        }

        return [];
    }
    #getFieldValuesFromFormDataInArray(formData){

        if ( this.#isformDataInstance(formData) )
        {
            let fieldValues = []; let counter = 0;
            for ( const pair of formData.entries() ){
                fieldValues[counter] = pair[1];
                counter++;
            }
            return fieldValues;
        }

        return [];
    }

    /**
     * Recorre el objeto Form y devuelve un array con los pares de valores
     * @param {object} formData 
     * @returns array con pares de valores o array[]
     */
    #getFieldNamesAndValuesFromFormDataInArray(formData){

        if ( this.#isformDataInstance(formData) )
        {
            let fieldNamesAndValues = []; let counter = 0;
            for ( const pair of formData.entries() ){
                fieldNamesAndValues[counter] = pair;
                counter++;
            }
            return fieldNamesAndValues;
        }

        return [];
    }

    // Data Type Methods

    #filterDataType(data_type){

        switch (data_type) {
            /* english validation type */                
            case 'str':         return 'string';
            case 'char':        return 'string';
            case 'text':        return 'string';
            case 'varchar':     return 'string';
            case 'date':        return 'string';
            case 'timestamp':   return 'string';
            case 'blob':        return 'string';

            case 'int':         return 'number';                
            case 'integer':     return 'number';
            case 'double':      return 'number';
            case 'float':       return 'number';
            case 'long':        return 'number';

            case 'bint':        return 'bigint';
            case 'obj':         return 'object';
            case 'bool':        return 'boolean';
            case 'sym':         return 'symbol';
            case 'und':         return 'undefined';

            //case 'string':    return 'string';
            //case 'number':    return 'number';
            //case 'bigint':    return 'bigint';
            //case 'object':    return 'object';
            //case 'boolean':   return 'boolean';
            //case 'symbol':    return 'symbol';
            //case 'undefined': return 'undefined';
          
            /* spanish validation type */
            case 'entero':          return 'number';
            case 'numero':          return 'number';
            case 'doble':           return 'number';
            case 'punto_decimal':   return 'number';
            case 'num':             return 'number';
            case 'flotante':        return 'number';
            case 'num_gde':         return 'bigint';
            case 'numero_grande':   return 'bigint';
            case 'cadena':          return 'string';
            case 'caracter':        return 'string';
            case 'boleano':         return 'boolean';
            case 'indefinido':      return 'undefined';

            default:
                break;
        }

        return data_type;
    }

    #IsValidDataType(data_type,value_from_field)
    {
        //console.log(typeof value_from_field);
        //console.log(data_type);
        //console.log(typeof value_from_field == data_type);
        if ( typeof value_from_field == data_type ){
            return true;
        }
        return false;
    }
    
    // String methods 
    
    #ValidateLength(value_to_eval,rule_value){

        let result = {
            length : false,
        };

        if ( value_to_eval.length == rule_value )
        {
            result['length'] = true; 
        }            
        //console.log(result);
        return result;
    }

    #ValidateMaxLength(value_to_eval,rule_value){

        let result = {
            max_length : false,
        };

        if ( value_to_eval.length <= rule_value )
        {
            result['max_length'] = true; 
        }            
        //console.log(result);
        return result;
    }

    #ValidateMinLength(value_to_eval,rule_value){

        let result = {
            min_length : false,
        };

        if ( value_to_eval.length >= rule_value )
        {
            result['min_length'] = true; 
        }            
        //console.log(result);
        return result;
    }

    #ValidateRequired(value_from_field){
        let result = {
            required : false,
        };
        
        if ( typeof value_from_field !== "undefined" && 
                null !== value_from_field && value_from_field.toString().length > 0 
            )
        {
            result['required'] = true;
        }
                    
        //console.log(result);
        return result;
    }

    #ValidateMinYearsOldDate(value_from_field,rule_value){
        let result = {
            min_years_old : false,
        };
        
        let currentDate = new Date();

        let compare_date = stringToDate(value_from_field);
        console.log(compare_date);

        let current_year = currentDate.getFullYear();
        let compare_year = compare_date.getFullYear();

        console.log( "current_year",current_year);
        console.log( "compare_year",compare_year);
        console.log( "rule_value",rule_value);
        console.log( current_year - compare_year >= rule_value );
        if ( current_year - compare_year >= rule_value )
        {
            result['min_years_old'] = true;
        }
        
        return result;
    }

    #stringRulesValidation(value_from_field,array_with_string_rules){
        //console.log(array_with_string_rules);
        let object_with_rule_results = {
            result : []
        }; let counter=0; let rules = [];
        array_with_string_rules.forEach(rules => {
        
            rules = rules.split(":");
            
            console.log(rules);
            switch (rules[0]) {
                case 'length':                        
                    object_with_rule_results['result'][counter] = this.#ValidateLength(value_from_field,rules[1]);
                    break;
                case 'max-length':
                    object_with_rule_results['result'][counter] = this.#ValidateMaxLength(value_from_field,rules[1]);
                    break;
                case 'min-length':                        
                    object_with_rule_results['result'][counter] = this.#ValidateMinLength(value_from_field,rules[1]);
                    break;
                case 'required':                        
                    object_with_rule_results['result'][counter] = this.#ValidateRequired(value_from_field);
                    break;
                case 'max-years-old':                        
                    //object_with_rule_results['result'][counter] = this.#ValidateMaxDate(value_from_field,rules[1]);
                    break;
                case 'min-years-old':                        
                    object_with_rule_results['result'][counter] = this.#ValidateMinYearsOldDate(value_from_field,rules[1]);
                    break;
                case 'years-old':                        
                    //object_with_rule_results['result'][counter] = this.#ValidateLastYearDate(value_from_field,rules[1]);
                    break;
                    
                default:
                    break;
            }
            counter++;
        });

        //console.log(object_with_rule_results);
        return object_with_rule_results;


    }

    #numberRulesValidation(value_from_field,array_with_string_rules){
        //console.log(array_with_string_rules);
        let object_with_rule_results = {
            result : []
        }; let counter=0; let rules = [];
        array_with_string_rules.forEach(rules => {
        
            rules = rules.split(":");
            
            console.log(rules);
            switch (rules[0]) {
                case 'length':                        
                    object_with_rule_results['result'][counter] = this.#ValidateLength(value_from_field,rules[1]);
                    break;
                case 'max-length':
                    object_with_rule_results['result'][counter] = this.#ValidateMaxLength(value_from_field,rules[1]);
                    break;
                case 'min-length':                        
                    object_with_rule_results['result'][counter] = this.#ValidateMinLength(value_from_field,rules[1]);
                    break;
                case 'required':                        
                    object_with_rule_results['result'][counter] = this.#ValidateRequired(value_from_field);
                    break;
                default:
                    break;
            }
            counter++;
        });

        //console.log(object_with_rule_results);
        return object_with_rule_results;


    }

    /**
     * Comprueba que la regla enviada por cadena sea una regla valida
     * y valida segun el caso
     * @param {*} value_from_field 
     * @param {string} data_type 
     * @param {array} array_with_rules 
     * @returns retorna la validacion o false
     */
    #validateDataRules(value_from_field,data_type,array_with_rules){
        //console.log(data_type);
        /* object_with_validate_rules = {
            names                   : "string|min-length:3",
            last_name_one           : "string|min-length:3",
            last_name_two           : "string|min-length:3",
            gender_id               : "number|required",
            birth_date              : "date|length:10|min-years-old:18",
            arch_photo           : "blob|required",
            id_code                 : "number|required",
            id_type                 : "number|required",
            id_issue_entity         : "number|required",
            nationality_id          : "number|required",
            issue_date              : "string|length:10",
            expire_date             : "string|length:10",
            country_of_residency_id : "number|required",
            estate_id               : "number|required",
            city_id                 : "number|required",
            address_one             : "string|required",
            //address_two             : "",
            //zip_code                : "",
            //movil_phone             : "",
            //home_phone              : "",
        }; */

        let result = false;
        switch (data_type) {            
          
            case 'string':
                result = this.#stringRulesValidation(value_from_field,array_with_rules);                    
                break;
            case 'number':    
                result = this.#numberRulesValidation(value_from_field,array_with_rules);  
                break;
            case 'bigint':    
                return 'bigint';
                break;
            case 'object':    
                return 'object';
                break;
            case 'boolean':   
                return 'boolean';
                break;
            case 'symbol':    
                return 'symbol';
                break;
            case 'undefined': 
                return 'undefined';                            
                break;
            default:
                result = false;
                break;
      }
      //console.log(result);
      if (typeof result == "object"){
          result['field_value'] = value_from_field;
      }
      return result;
    }

    #isValidFieldData(value_from_field,array_with_rules){
        //console.log(value_from_field);
        if (value_from_field.length > 0)
        {
            if (array_with_rules.length > 0)
            {            
                let data_type = array_with_rules[0].toString();
                    data_type = this.#filterDataType(data_type);
                    //console.log(data_type);
                if ( this.#IsValidDataType(data_type,value_from_field) )
                {

                    let array_with_string_rules = []; let counter = 0;

                    array_with_rules.forEach( rule => {
                        if (counter > 0)
                        {
                            array_with_string_rules[counter-1] =  rule;
                        }
                        counter++;
                    });

                    //console.log(array_with_rules);
                    if ( array_with_rules.length > 0)
                    {
                        let result = this.#validateDataRules(value_from_field,data_type,array_with_string_rules);
                        console.log(result);
                        return result;
                    }
                }

            }
        }
        else
        {
            let response = {
                field_name : "",
                field_value : value_from_field,
                status : "failed",
                result : false
            }
            return response;
        }

    }

    /**
     * Valida los campos segun las reglas enviadas como cadena
     * @param {array} array_form_pair 
     * @param {string} string_with_validate_rules 
     * @returns array con los nombre de los campos
     */
    #validateRuleStringFromField(array_form_pair,string_with_validate_rules){

        if (array_form_pair.length == 2 && string_with_validate_rules.toString().length > 0){
            //console.log(array_form_pair);
            let pair_name = array_form_pair[0];
            let pair_value = array_form_pair[1];

            let array_with_rules = string_with_validate_rules.split("|");

            let result = this.#isValidFieldData(pair_value,array_with_rules);

            if (typeof result == "object"){
                result['field_name'] = pair_name;
            }

            //console.log(result);
            return result;
        }
        return false;            
    }

    /**
     * matchea los campos a validar con los campos enviados
     * @param {object} formData 
     * @param {object} object_with_validate_rules 
     * @returns devuelve un array con los nombre de las reglas validadas y su estado true o false
     */
    #formMatchWithRules(formData,object_with_validate_rules)
    {
        // si es una instancia de new Form()
        if ( this.#isformDataInstance(formData) ){

            let form_fields_value_pairs = this.#getFieldNamesAndValuesFromFormDataInArray(formData);
            let fields_name_to_validate = this.#getFieldNamesFromObjectInArray(object_with_validate_rules);

            let result = {};

            //console.log(form_fields_value_pairs);
            //console.log(fields_name_to_validate);

            // recorremos el array con los los pares de valores
            for (let i = 0 ; i < form_fields_value_pairs.length; i++ ){
                
                // recorremos el array con los nombre de los campos
                fields_name_to_validate.forEach( field_name => {
                    
                    // si los nombres coinciden
                    if ( form_fields_value_pairs[i][0] == field_name )
                    {
                        result[i] = this.#validateRuleStringFromField(form_fields_value_pairs[i],object_with_validate_rules[field_name]);
                    }

                });
                
            }
            
            /* result = [
                [{ length : false, }],
                [{ max_length : false, }],
                [{ min_length : false, }]
            ]; */
            //-----------------------
            return result;

        }

        return false;

    }

    /**
     * 
     * @param {object} form - objeto de formulario new Form. <required>
     * @param {object} object_with_validate_rules - objeto con las entradas del formulario y las reglas de validacion. <required>
     * @param {array} array_with_custom_messages - array con los mensajes a mostrar si encuentra una excepcion. Un mensaje por cada entrada del objeto Form [opcional],
     * Si no se define un mensaje por cada entrada devolvera un mensaje por defecto.
     */
    validateFormFields(form,object_with_validate_rules, array_with_custom_messages){

        //console.log(form);
        //console.log(object_with_validate_rules);
        //console.log(array_with_custom_messages);
        
        let response = {
           result : this.#formMatchWithRules(form,object_with_validate_rules),
           status : "failed",
           field_name : ""
        };

        console.log(response);

        let catched = false;
        for (const index in response.result) {
            
            // Esta comprobacion es debido al tiempo de ejecucion.
            // para evitar asignacion de la siguiente iteracion.
            if ( catched == false )
            {
                response['message'] = array_with_custom_messages[index];
                response.field_name = response.result[index]['field_name'];
            }
            // si hubo error (false)
            if ( response.result[index]['result'] == false )
            {
                catched = true;                    
            }
            // si hubo un error (true)
            else if ( typeof response.result[index] == "object" )
            { 
                response.result[index]['result'].forEach( object => {
                                            
                    for (const key in object) {                            
                        
                        if ( object[key] == false && catched == false )
                        {
                            catched = true;
                        }
                    }
                });
            }                

        }
        // si hubo un error catched=true devuelve el objeto con la respuesta
        if ( catched != true ){
            response['status'] = "success";
            response['message'] = "Formulario Validado Correctamente";
        }
        //console.log(response);
        return response;

    }

}