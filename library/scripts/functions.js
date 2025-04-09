
close_session = () =>{
    window.location.href = '/sign-out';
}
not_found = () =>{
    window.location.href = URL_BASE + '/not-found';
}
go_home = () =>{
    window.location.href = URL_BASE;
}

toggle_class_on_dom_element_by_id = (dom_element_id,class_to_toggle) =>{

    const dom_element = document.getElementById(dom_element_id);
          dom_element.classList.toggle(class_to_toggle);
}

/**
 * Lanza el spinner de bootstrap
 */
triggerSpinner = (target = "no-show", class_selector = "spinner_box") =>{

    const snipper = document.querySelector(`.${class_selector}`);            

    if(snipper)
    {
        if (target == "show")
        {
            snipper.setAttribute("class",`${class_selector}`);
        }
        else if( target == "no-show")
        {
            snipper.setAttribute("class",`${class_selector} no-show`);
        }
    }
    
}

loading = () =>
{
    //console.log("loading");
    if(this.state.data.param.loading == true)
    {
        this.state = {
            ...this.state,
            data : {
                ...this.state.data,
                param: {
                    ...this.state.data.param,
                    loading:false
                }
            }
        };
    }
    else
    {
        this.state = {
            ...this.state,
            data : {
                ...this.state.data,
                param: {
                    ...this.state.data.param,
                    loading:true
                }
            }
        };
    }

    setTimeout(() => {

        const load = document.querySelector(".loading");        

        if (load)
        {            
            load.classList.toggle("show");                                  
        }
        
    }, 300);

}

/**
 *  Devuelve la cadena capitalizada (primera letra en mayuscula)
 *  example: string.capitalize();
 * @returns string
 */
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

/**
 * Devuelve una cadena eliminando un caracter pasado como segundo parametro
 * en ambos lados, si lo encuentra 
 * @param {*} caracter 
 * @returns string
 */
String.prototype.removeCharBothSize = function (caracter) {
    let string = this;
    console.log('in:', string);

    // Eliminar al principio
    while (string.charAt(0) === caracter) {
        string = string.substring(1);
    }

    // Eliminar al final
    while (string.charAt(string.length - 1) === caracter) {
        string = string.substring(0, string.length - 1);
    }
    console.log('out:', string);
    return string;
}

/**
 * Devuelve el tamaño de un archivo en megabites, kilobites y bits, respectivamente.
 * @param {*} size
 */
function sizeInMB(size = '0')
{
    if ( isNaN(size) )
    {
        return 0;
    }

    if (size > 0)
    {
        let sizeInMB = size;

            if (sizeInMB >= 1024000)
            {
                size = `${(sizeInMB/ (1024*1024)).toFixed(2)} mb`;
            }
            else if (sizeInMB >= 1024)
            {
                size = `${(sizeInMB / 1024).toFixed(2)} kb`;
            }
            else
            {
                size = `${size} bits`;
            }
    }

    return size.toString();
}

/**
 * Esta funcion recibe una cadena y le agrega una mascara.
 * La mascara debe contener solo guiones y ceros. e.i. 000-0000000-0
 * @param str {*} cadena
 * @param mask numero (012...9) y guiones (-) solamente 
 * */ 
formatMask = (str = "", mask = "") =>
{   
    var charToInsert = "-";

    let first_regular_expresion = new RegExp("^[0-9,"+charToInsert+"]+$","g");  

    if ( first_regular_expresion.test(str) )                
    {
        
        str = str.replaceAll("-","");
        

            charToInsert = "-";
        var arrayMask = mask.split(charToInsert);

        var arrayStr = [];
        
        var count = 0; var poslen = 0;
        for (let i = 0; i < arrayMask.length; i++)
        {
            count = parseInt(count)+arrayMask[i].length;                
            arrayStr[i] = "";
            if (i == 0 && str.length > count )
            {
                arrayStr[i] = str.substr(0,arrayMask[i].length);
                poslen = parseInt(poslen)+arrayStr[i].length;
            }            
            else if ( i > 0 && (str.length >= count) )
            {
                arrayStr[i] = str.substr(poslen,arrayMask[i].length);
                poslen = parseInt(poslen)+arrayStr[i].length;
            }            
            else 
            {                
                arrayStr[i] = str.substr(poslen,arrayMask[i].length);
                poslen = parseInt(poslen)+arrayStr[i].length;
            }
        }                    

        var newStr = "";
        count = 0; poslen = 0;
        for (let i = 0; i < arrayStr.length; i++)
        {
            
            count = parseInt(count)+arrayMask[i].length;

            if  ( i == 0 && arrayStr[i].length <= count && "" !== arrayStr[i] )
            {
                newStr = arrayStr[i];
            }
            else if( i > 0 && arrayStr[i].length <= count && "" !== arrayStr[i] )
            {
                newStr = newStr+charToInsert+arrayStr[i];
            }
        }
        return newStr; 
    } 
    else
    {
        let str_len = str.length;
        return str.substr(0,str_len-1);
    }
    
}

/**
 * 
 * @param {*} file 
 * @returns false si no se obtiene la extension
 */
get_file_extension = (file = null) =>
{

    let array = file.split(".");

    for (let i = 0; i < array.length; i++) {
        
        if ( i == array.length-1 )
        {
            return array[i];
        }
        
    }
    return false;
    
}

// crea la cookie o la actualiza
setOrUpdateCookie = () =>
{
    // parametros para crear la cookie.
    const cookieName = 'sessionId';
    const cookieValue = localStorage.user_id ? localStorage.user_id : this.getCookieValue();
    const expireDate = this.getCookieTime('minutes',30);
    
    this.setCookie(cookieName,cookieValue,expireDate);
}

// asigna el tiempo de vigencia de la cookie.                                
getCookieTime = (where,time) => {
    var currentDate = new Date();                    
    if (where == 'year') {
        currentDate.setFullYear(currentDate.getFullYear()+time);
    } else if ( where == 'month') {
        currentDate.setMonth(currentDate.getMonth()+time);
    } else if (where == 'week') {
        currentDate.setDate(currentDate.getDate()+(time*7));
    } else if (where == 'day') {
        currentDate.setDate(currentDate.getDate()+time);
    } else if (where == 'hours') {
        currentDate.setHours(currentDate.getHours()+time);
    } else if (where == 'minutes') {
        currentDate.setMinutes(currentDate.getMinutes()+time);
    } else if (where == 'seconds') {
        currentDate.setSeconds(currentDate.getSeconds()+time);
    }
    var expireDate = "expires="+ currentDate.toUTCString();                
    return expireDate;
}

destroyCookie = () =>
{
    // parametros para crear la cookie.
    const cookieName = 'sessionId';
    const cookieValue = localStorage.session_token+localStorage.user_id;
    const expireDate = this.getCookieTime('seconds',1);
    
    this.setCookie(cookieName,cookieValue,expireDate);
}

// Crea una cookie por el tiempo de expiracion indicado.            
setCookie = (cookieName,cookieValue,expireDate) => {
    document.cookie = cookieName + "=" + cookieValue + ";" + expireDate + ";path=/";
}

// Obtiene la cookie por su nombre.
getCookieValue = (cookieName = "sessionId") => {
    var cookieToDecode = document.cookie;            
    var decodedCookie = decodeURIComponent(cookieToDecode);
    
    var arrayCookie = decodedCookie.split(';');
    
    for (let i = 0; i < arrayCookie.length; i++) {
        
        
        arrayCookie[i] = arrayCookie[i].trim();

        if ( (arrayCookie[i].search(cookieName) > -1) )
        {
            
            // crea una array
            var arrayId = arrayCookie[i].split("=");
            //console.log(arrayId);
            for (let i = 0; i < arrayId.length; i++) {
                
                // devuelve el ultimo valor del array    
                if (i+1 == arrayId.length)
                {
                    return arrayId[i];
                }
                
            }
        }
    }
}

/**
 * Devuelve un array con el par de rango de fecha
 * formateado para ser aceptado por mysql, ej. 01/31/2020 - 02/28/2021 ---> [0] => 2020-01-31, [1] => 2021-02-28
 * @param {string} string_with_date_range 
 */
 function fix_date_format(string_with_date_range)
 {
     let array = string_with_date_range.split(' - ');
 
     if ( array.length = 2 )
     {
         
         let array_date = [], c=0;
 
         array.forEach( (item, index) => {
 
             let new_item = item.replaceAll('/','-');
             let new_array = new_item.split('-');            
 
             if ( new_array.length == 3)
             {                 
                 array_date[c] = new_array[2]+'-'+new_array[1]+'-'+new_array[0];
                 c++;                
             };            
 
         });
         
         return array_date;
 
     }
 
     return string_with_date_range;
 
 }

 compareTwoDates = ( symbol = ">", first_date = new Date(), last_date = new Date() ) => {
    
    switch (symbol) {
        case ">":
            if (first_date > last_date){return true};
            break;    
        case "<":
            if (first_date < last_date){return true};
            break;    
        case "==":
            if (first_date == last_date){return true};
            break
        case "<=":
            if (first_date <= last_date){return true};
            break
        case ">=":
            if (first_date >= last_date){return true};
            break
        case "!=":
            if (first_date != last_date){return true};
            break
        default:
            break;
    }
    
    return false;
}
 
stringToDate = (string_date = "2022-01-31") => {

    var currentDate = new Date();

    string_date = string_date.toString();

    let arrayDate = string_date.split("-");

    if (arrayDate.length == 3)
    {
        currentDate.setFullYear(parseInt(arrayDate[0]));
        currentDate.setMonth(parseInt(arrayDate[1]-1));
        currentDate.setDate(parseInt(arrayDate[2]));
        
        currentDate.setHours(0);
        currentDate.setMinutes(0);
        currentDate.setSeconds(0);
        currentDate.setMilliseconds(0);

        //console.log(currentDate);    
        return currentDate;
    }
    
    return string_date;
    /*
        if (where == 'year') {
            currentDate.setFullYear(currentDate.getFullYear()+time);
        } else if ( where == 'month') {
            currentDate.setMonth(currentDate.getMonth()+time);
        } else if (where == 'week') {
            currentDate.setDate(currentDate.getDate()+(time*7));
        } else if (where == 'day') {
            currentDate.setDate(currentDate.getDate()+time);
        } else if (where == 'hours') {
            currentDate.setHours(currentDate.getHours()+time);
        } else if (where == 'minutes') {
            currentDate.setMinutes(currentDate.getMinutes()+time);
        } else if (where == 'seconds') {
            currentDate.setSeconds(currentDate.getSeconds()+time);
        }
        var expireDate = "expires="+ currentDate.toUTCString();
        return false;
    */
}

// aceptar solo letras de la a-z
eventKeydownOnlyLetter = (event) =>{

    // solo son letras
    if ( 
         event.key.match(/[a-zA-Z]/gi) != null &&
         event.key.match(/[a-zA-Z]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// aceptar solo letras de la a-z y espacios
eventKeydownOnlyLetterAndSpaces = (event) =>{

    // solo letras y espacios
    if ( 
         event.key.match(/[a-zA-Z\s]/gi) != null &&
         event.key.match(/[a-zA-Z\s]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// aceptar solo letras de la a-z, numeros enteros y espacios
eventKeydownOnlyLetterNumbersAndSpaces = (event) =>{

    // solo letras y espacios
    if ( 
         event.key.match(/[\da-zA-Z\s]/gi) != null &&
         event.key.match(/[\da-zA-Z\s]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// aceptar solo letras de la a-z, numeros enteros, quiones y espacios
eventKeydownOnlyLetterNumbersDashesAndSpaces = (event) =>{

    // solo letras, numeros enteros, quiones y espacios
    if ( 
         event.key.match(/[-\da-zA-Z\s]/gi) != null &&
         event.key.match(/[-\da-zA-Z\s]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        return true;      
    }    
    else
    {
        event.preventDefault();
        return false;
    }    

}

eventKeydownOnlyLetterNumbersCommasAndDashes = (event) =>{

    // solo letras, numeros enteros, quiones y espacios
    if ( 
         event.key.match(/[-\da-zA-Z,]/gi) != null &&
         event.key.match(/[-\da-zA-Z,]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// aceptar solo letras de la a-z, numeros enteros y quiones
eventKeydownOnlyLetterNumbersAndDashes = (event) =>{

    // solo letras, numeros enteros, quiones y espacios
    if ( 
         event.key.match(/[-\da-zA-Z]/gi) != null &&
         event.key.match(/[-\da-zA-Z]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// acepta solo numeros enteros
eventKeydownOnlyIntegerNumber = (event) =>{

    // solo son numeros
    if ( 
         event.key.match(/[\d]/gi) != null &&
         event.key.match(/[\d]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha y Delete
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 )
    )
    {        
        // no hacer nada        
    }    
    else
    {
        event.preventDefault()
    }    

}

// acepta solo numeros y un punto decimal
eventKeyDownOnlyFloatNumber = (event) =>{

    if ( 
         event.key.match(/[\d\.]/gi) != null &&
         event.key.match(/[\d\.]/gi).length >= 1 || 
        // Es igual a Backspace, flecha edicion izquierda, derecha, Delete y el punto (.) 
        ( event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 )
    )
    {
        // no hay match, hay un solo match y no es un punto
        if ( 
            event.target.value.match(/[\d]+\.{1}/gi) == null ||
            ( event.target.value.match(/[\d]+\.{1}/gi).length == 1 && 
            event.keyCode != 110 && event.keyCode != 190 )
            )
        {
            // no hacer nada
        }
        else
        {
            event.preventDefault()    
        }
    }    
    else
    {
        event.preventDefault()
    }    

}

elementDisabledById = (elementId) => {
    const ele = document.getElementById(elementId);
        ele.disabled = true;    
}

elementEnabledById = (elementId) => {
    const ele = document.getElementById(elementId);
        ele.disabled = false;
}

elementDisabledByClass = (elementClass) => {

    const ele = document.getElementsByClassName(elementClass);
    
    if (ele)
    {
        for (let i = 0; i < ele.length; i++) {            
            ele[i].disabled = true;            
        }
        return true;
    }
    return false;
}

elementEnabledByClass = (elementClass) => {

    const ele = document.getElementsByClassName(elementClass);
    
    if (ele.length > 0)
    {
        setTimeout(() => {
            for(let i=0; i < ele.length; i++){
                ele[i].disabled = false;
            }            
        }, 100);
      
        return true;
    }
    return false;    
}

toggleClassOnElementsByClass = (classToToggle = '', classTarget = "") => {
    
    if ( classToToggle === '' || classTarget == "" ){
        return false;        
    }   
        let elements = document.getElementsByClassName(classTarget);
        
        for (let i = 0; i < elements.length; i++) {
            
            if (elements[i]) {
                elements[i].classList.toggle(classToToggle);
            }
            else
            {
                return false;
            }            
        }        
    
    return true;    
}

toggleClassOnElementsByArrayWithIds = (classToToggle = '', arrayWithIds = []) => {
    
    if ( classToToggle === '' || arrayWithIds.length === 0 ){
        return false;        
    }
    for (let i = 0; i < arrayWithIds.length; i++) {
        
        let element = document.getElementById(arrayWithIds[i]);
        
        if (element) {
            element.classList.toggle(classToToggle);
        }
        else
        {
            return false;
        }        
    }
    return true;    
}

setClassOnElementsByArrayWithIds = (classToSet = '', arrayWithIds = []) => {
    
    if ( classToSet === '' || arrayWithIds.length === 0 ){
        return false;        
    }
    for (let i = 0; i < arrayWithIds.length; i++) {
        
        let element = document.getElementById(arrayWithIds[i]);        
        
        let allClassToSet = '';
        if (element) {

            if (element.classList.length > 0)
            {
                
                for(let i = 0; i < element.classList.length; i++) {
                    
                    if (i == 0) {
                        allClassToSet = element.classList[i]; 
                    }
                    else if (i+1 !== element.classList.length)
                    {
                        allClassToSet += " " + element.classList[i]; 
                    }

                    if (i+1 ===  element.classList.length)
                    {
                        allClassToSet += " " + classToSet; 
                    }
                    
                }
            }
            else
            {
                allClassToSet = classToSet; 
            }

            element.setAttribute('class',allClassToSet);

        }
        else
        {
            return false;
        }        
    }
    return true;    
}

removeClassOnElementsByArrayWithIds = (classToRemove = '', arrayWithIds = []) => {
    
    if ( classToRemove === '' || arrayWithIds.length === 0 ){
        return false;        
    }
    for (let i = 0; i < arrayWithIds.length; i++) {
        
        let element = document.getElementById(arrayWithIds[i]);        
        
        let allClassToSet = '';
        if (element) {

            if (element.classList.length > 0)
            {
                
                for(let i = 0; i < element.classList.length; i++) {                    

                    if ( classToRemove !== element.classList[i] && i == 0)
                    {
                        allClassToSet = element.classList[i]; 
                    }
                    else if ( classToRemove !== element.classList[i] && i > 0 )
                    {
                        allClassToSet += " " + element.classList[i];
                    }                   
                    
                }
            }
            else
            {
                return false;
            }

            element.setAttribute('class',allClassToSet);
        }
        else
        {
            return false;
        }        
    }
    return true;    
}

 /**
 * Remueve un elemento del DOM desvaneciendolo.
 * @param {int} element_id 
 */
function removeDOMElementWithStyleByElementId(element_id){

    let DOMElement = document.querySelector(`#${element_id}`);
    
    if (DOMElement)
    {
        // obtemos los estilos
        let styles = DOMElement.getAttribute("style");

        // desvanecer
        DOMElement.setAttribute('style',styles+"transition: 0.2s !important;opacity: 0 !important;");
        //DOMElement.classList.toggle("fade_out");
        setTimeout(() => {
            // no mostrar
            DOMElement.setAttribute('style',"display:none");
            //DOMElement.classList.toggle("no-show");
            setTimeout(() => {
                // eliminar
                DOMElement.remove();                
            }, 50);
        }, 200);

        return true;
    }
    
    return false;
}


 /**
 * Muestra un elemento del DOM desvaneciendolo.
 * @param {int} element_id 
 */
 function showROWElementWithStyleByElementId(element_id, expand_to = "down"){

    let DOMElement = document.querySelector(`#${element_id}`);
    
    if (DOMElement)
    {
        // quitamos la clase no-show
        DOMElement.classList.toggle("no-show");

        switch (expand_to) {
            case 'down':
                
                DOMElement.setAttribute('style','display:block;height:0px;min-height:0px;max-height:0;overflow:hidden;transition: 0.3s;position:relative;opacity:0;');       

                setTimeout(() => {
                    DOMElement.setAttribute('style','display:block;height:160px;min-height:120px;max-height:66px;overflow:hidden;transition: 0.3s;position:relative;opacity:0;');            
                    setTimeout(() => {
                        DOMElement.setAttribute('style','height:160px;min-height:160px;max-height:66px;overflow:hidden;transition: 0.3s;position:relative;opacity:1;');            
                    }, 300);
                }, 100);

                break;
        
            default:
                break;
        }

        return true;
    }
    
    return false;
}

/**
 * Envia una peticion a una url desde un formulario. Esto cambiara la ubiacion de la ventana
 * @param {string} path el path donde se enviara el post
 * @param {object} params lose parametros que se agregan a la url
 * @param {string} [method=post] el methodo de envio usado en el form
 */

post = (path, params, method='post') => {

  // The rest of this code assumes you are not using a library.
  // It can be made less verbose if you use one.
  const form = document.createElement('form');
  form.method = method;
  form.action = path;

  for (const key in params) {
    if (params.hasOwnProperty(key)) {
      const hiddenField = document.createElement('input');
      hiddenField.type = 'hidden';
      hiddenField.name = key;
      hiddenField.value = params[key];

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}