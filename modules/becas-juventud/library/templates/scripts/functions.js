
elementDisabledById = (elementId) => {
    const ele = document.getElementById(elementId);
        ele.disabled = true;    
}

elementEnabledById = (elementId) => {
    const ele = document.getElementById(elementId);
        ele.disabled = false;
}

elementClassDisabledById = (elementId) => {

    const ele = document.getElementById(elementId);

    let objClassList = ele.classList;

    let counter = 0;
    for (const key in objClassList ) {
               
        let className = objClassList[key];

        if( className === 'disabled' )
        {
            return true;
        }
        else if ( objClassList['length'] === 0 || className !== 'disabled' && objClassList['length'] === counter+1 )
        {
            ele.classList.toggle('disabled');
            return true;
        }
        counter++;
    }
    return false;
}

elementClassEnabledById = (elementId) => {

    const ele = document.getElementById(elementId);

    let objClassList = ele.classList;
        
    for (const key in objClassList ) {       
        
        let className = objClassList[key];

        if( objClassList['length'] > 0)
        {
            if( className === 'disabled' )
            {
                ele.classList.toggle('disabled');
                return true;
            }
        }
    }
    return false;
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