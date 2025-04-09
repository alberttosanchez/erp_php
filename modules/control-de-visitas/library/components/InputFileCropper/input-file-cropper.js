
const file = document.querySelector('#input_file_cropper');

const image = document.getElementById("img-original");

const modal = document.getElementById("div-modal");

// temp_photo es el elemento IMG que contiene la foto en el DOM
const temp_photo = document.querySelector('.photo_container > img');

if ( typeof localStorage.temporalImageKey === 'undefined' )
{
    // guardamos un numero unico para la imagen temporal
    localStorage.temporalImageKey = Date.now().toString();
}

//si deseamos interactuar con el modal usando los metodos nativos de bootstrap5
//debemos construirlo pasando el elemento. En nuestro caso .show() y .hide()
const objmodal = new bootstrap.Modal(modal, {
    //que el modal no interactue con el teclado
    keyboard: false
});

file.addEventListener("change", function (ev) {

    const load_image = (url) => {
        image.src = url;
        //temp_photo.src = url;
        objmodal.show();
    };

    const files = ev.target.files;

    // si hay un archivo, entonces ...
    if ( files && files.length > 0 ) {

        const objfile = files[0];
        //el objeto file tiene las propiedades: name, size, type, lastmodified, lastmodifiedate
        
        //para poder visualizar el archivo de imagen lo debemos pasar a una url 
        //el objeto URL está en fase experimental así que si no existe usaria FileReader
        if ( URL ){

            //crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
            const url = URL.createObjectURL(objfile);
            load_image(url);

        }
        else if (FileReader) {

            const reader = new FileReader();

            reader.onload = (ev) => {
                load_image(reader.result);
            };

            reader.readAsDataURL(objfile);

        }
    }

});

//el objeto cropper que habrá que crearlo y destruirlo. 
//Crearlo al mostrar el modal y destruirlo al cerrarlo
let cropper = null;
modal.addEventListener("shown.bs.modal", function (){
    console.log("modal.on-show")
    //crea el marco de selección sobre el objeto $image
    cropper = new Cropper( image, {
        //donde se mostrará la parte seleccionada
        preview: document.getElementById("div-preview"),
        //3: indica que no se podrá seleccionar fuera de los límites
        viewMode: 3,
        //NaN libre elección, 1 cuadrado, proporción del lado horizontal con respecto al vertical
        aspectRatio: CROOPER_PROFILE_PIC_ASPECT_RATIO, // ex. 114 / 132
    });
});

modal.addEventListener("hidden.bs.modal", function (){
    file.value = "";
    console.log("modal.on-hide");
    cropper.destroy();
    cropper = null;
}); 


const erase_temp_photo_btn = document.querySelector('#erase_temp_photo_btn');
erase_temp_photo_btn.addEventListener('click', () => { 
    temp_photo.src = ""; 
    show_temp_photo(false);
});

const reduceImageSize = (file, maxWidth, maxHeight) => {
    return new Promise((resolve, reject) => {
        const img = new Image();
        const reader = new FileReader();

        reader.onload = event => {
            img.src = event.target.result;
        };

        img.onload = () => {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round((height *= maxWidth / width));
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round((width *= maxHeight / height));
                    height = maxHeight;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(blob => {
                resolve(blob);
            }, file.type);
        };

        reader.readAsDataURL(file);
    });
}

const btncrop = document.getElementById("btn-crop");
//configuramos el click del boton crop
btncrop.addEventListener("click", function (){

    MAX_IMAGE_SIZE_MB = '2';
    MAX_WIDTH = '600';
    MAX_HEIGHT = '800';

    //render_style_on_temp_photo();
    
    //obtenemos la zona seleccionada
    const canvas = cropper.getCroppedCanvas();

    canvas.toBlob(function (blob){
        //el objeto blob (binary larege object) tiene las propiedades: size y type
        const reader = new FileReader();
        //se pasa el binario base64
        reader.readAsDataURL(blob);

        reader.onloadend = async function (){
            const base64data = reader.result;
            
            const sizeInMB = checkBase64Size(base64data);

            let finalBase64data = base64data;

            if (sizeInMB > MAX_IMAGE_SIZE_MB){
                const reducedBlog = await reduceImageSize(blob, MAX_WIDTH, MAX_HEIGHT);
                finalBase64data = await getBase64(reducedBlog);
            }

            //base64data es un string del tipo: data:image/png;base64,iVBORw0KGgoAAAA....
            //console.log("base64data", base64data);
            localStorage.base64data = finalBase64data;

            const contentType = 'image/png';

            let blob_image = create_blob_img(base64data, contentType);

            render_blob_image_on_temp_new_student_photo(blob_image);

            let temporalImageKey = localStorage.temporalImageKey;

            //remove_temporal_image(temporalImageKey);
        
        };
    });
});

const show_temp_photo = (bool = true) =>{

    
    if (bool)
    {        
        removeClassOnElementsByArrayWithIds('no-show', ['photo_img_picture']);        
        setClassOnElementsByArrayWithIds('no-show', ['image_icon']);
    }
    else
    {
        removeClassOnElementsByArrayWithIds('no-show', ['image_icon']);        
        setClassOnElementsByArrayWithIds('no-show', ['photo_img_picture']);
    }
}

const render_blob_image_on_temp_new_student_photo = (blob_image) =>{
    
    const blobUrl = URL.createObjectURL(blob_image);          
    
    temp_photo.src = blobUrl;

    show_temp_photo(true);
};

const create_blob_img = (b64Data, contentType='', sliceSize=512) => {

    let arrayB64Data = b64Data.split(',');
    const byteCharacters = atob(arrayB64Data[1]);
    const byteArrays = [];
    
    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        const slice = byteCharacters.slice(offset, offset + sliceSize);
    
        const byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
        byteNumbers[i] = slice.charCodeAt(i);
        }
    
        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }
    
    const blob = new Blob(byteArrays, {type: contentType});
    return blob;
}


const checkBase64Size = (base64String) => {
    const stringLength = base64String.length;
    const sizeInBytes = 4 * Math.ceil(stringLength / 3) * 0.5624896334383812;
    const sizeInMB = sizeInBytes / (1024 * 1024);
    return sizeInMB;
}

const getBase64 = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}