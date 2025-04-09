# ERP V2.2.2
### (ERP V2.2)

El presente proyecto es un sistema creado para administrar modulos para ser usados en este sistema de gestión.

Los modulos se deben instalar por un usuario administrador, este es quien puede eliminarlos de ser necesarios.


## PHP Extensiones

La úlima actulización esta adaptadar para utilizar PHP Version 8.0.28.

⚠️ Asegurarse que la extensión ***zip*** para descomprimir archivos .zip esta instalada y poder utilizar la clase **NewArchive.** Referencia: [PERL-zip](https://pecl.php.net/package/zip)

En Windows, en el archivo php.ini descomente la linea. **;extension=zip**.

### Introducción

Los modulos son programas (plugins, herramientas, etc.) que agregan funcionalidades al programa principal es decir al IJOVEN. Estos programas deben ser desarrollados siguiendo la estructura que se describe más adelante este apartado.

Los modulos se sirven del sistema de base de datos principal para validar la seguridad del sistema y permitir los accesos. Pero estos manejan sus propias tablas para la integridad de los datos que deben ser almacenados.

## Funcionamiento General

### Base de Datos



### Usuario Principal

### API

Para hacer peticiones a la API del IJOVEN se debe enviar un objeto JSON que incluya lo siguientes parametros:

    session_token   : localStorage.session_token,
    user_id         : this.state.data.fetched.user_id

El parametro **session_token** recibe del localStorage el token que identifica la session actual activa y el parametro **user_id** recibe el id del usuario para que el sistema verifique si tiene los permisos suficientes para realizar la accion.

## Clases

### Conn

### Modules

⚠️ Asegurarse que los script de creación de tablas sean homogeneos, es decir, que cada sintáxis sea identica. Por ejemplo si utiliza **CREATE OR REPLACE VIEW**, para crear una vista utilice la misma forma de sintásis para las demás.

### Session

### Files

### Email

### Cat (Categorías)

## Modulos


### Estructura de un modulo

Los modulos deben desarrollarse bajo la siguiente arquitectura para garantizar la estandarización de los mismo y buena practica.

#### **/modulo-directory**  
_El modulo debe estar contenido en un directorio. el nombre no debe contener espacios, ejemplo: clock-timer._  

#### **/modulo-install-file-name**  
_El nombre del archivo que contendrá la información sobre la instalación, y demás créditos debe tener el mismo nombre que el directorio que contiene el modulo y debe ser extensión ***.php***. Ejemplo: install.php_

_El archivo de instalación debe contener las siguientes cadenas comentadas para que el programa pueda instalar y configurar correctamente el modulo._

    /*
    Module Name     : Clock Timer  
    Version         : 1.0.0  
    Description     : Reloj digital clasico.  
    Author          : Jhon Alam  
    Web             : https://midomain.com  
    Licence         : GNU 3.0
    */

_Este archivo sera renombrado como ***functions.php*** por lo que se recomienda utilizarlos para tales fines._
    

_Debe crearse una estructura de tablas con un prefijo para evitar sobreescribir datos y tablas existentes en la base de datos. Ejemplo: ctm_nombre_tabla._

#### **/modulo-directory/library**  
_El modulo debe ser desarrollado bajo siguiendo la estructura establecido dentro del directorio ***modules***  y las paginas, partes y contenidos dentro del sub-directorio ***library/templates/...***._ 

#### **/modulo-directory/assets**  
_Debe crearse el directorio **assets** el cual contendrá como mínimo los sub-directorios: **js**, **css** e **images**._

#### **/modulo-directory/sql**  
_Este directorio contiene los script sql para crear las tablas, vistas, trigger, procedimientos almacenados,etc., que se necesiten para el correctafuncionamiento del modulo._

_Se recomienda enumerar los archivos para evitar una lectura incorrecta en la secuencia de ejecucion. O preferiblemente crear un solo script de instalacion._ _***Las tablas se instalarán en la base de datos principal.***_

*Ejemplo:*

    01_tablas_principales.sql
    02_tablas_secundarias.sql
    03_disparadores_triggers.sql
    04_procediminetos_almacenados.sql

⚠️ Elimine los comentarios de los archivos *.sql en produccion. Los comentarios de lineas seran omitidos que empience con **--** y **#**, pero no los de multilinea **/* ... */**.

### **Ejemplo de una extructura modular:**

    └───┬─ \clock-timer [directory]  
        ├───── index.php [entrada al modulo]  
        ├───── functions.php [contiene las instrucciones de instalacion y la funciones]  
        ├───── sidebar.php [contiene las instrucciones para el menu lateral]  
        ├───┬─ \library 
        |   ├───┬─ \class
        |   |   └───── \users.class.php
        |   └───┬─ \templates
        |       └───┬─ \pages
        |           ├─── main-page.php
        |           ├─ \parts
        |           ├─── main-part.php
        |           ├─ \contents
        |           └─── main-content.php        
        └───┬─ \src
            └───┬─ \assets
                ├───┬─ \css
                |   ├─── module-style.css
                |   └─── bootstrap.min.css
                ├───┬─ \js
                |   ├─── settings.js
                |   ├─── functions.js
                |   └─── poppers.min.js
                └───┬─ \images
                    ├─── default-user.png
                    ├─── clock-desktop-bg.png
                    └─── proyect-logo.jpg

### Modulos Disponibles

- [Control del Visitas](https://softwarepublico.gob.do/alberto.sanchez/control-de-visitas)
- [SysBecas](https://softwarepublico.gob.do/alberto.sanchez/sysbecas)

### Roles

Después de iniciar sesión se tiene acceso a la variable global de sesiones de PHP: **$_SESSION**, contiene informacion del usuario actual para verificar su rol y asi permitir u ocultar contenido segun sea el caso.

Pude consultar en la base de datos, en la tabla ***ijoven_cat_roles*** los roles disponibles y su descripción.

## Log de Versiones

### Version 2.2.2 (actual)

-   Soporte para cropper.js agregado

### Version 2.2.1 

-   Actualización a PHP Version 8.0.28.
-   Verificación por PHP de rol de usuario para mostrar u ocultar funciones del IJOVEN y/o de los modulos.
-   Nueva función "remove_get_attributes_from_url" para remover los atributos de la url al momento de comprobar el path.
-   Soporte para momentjs para fechas mas humanas.
-   Nueva funcion de JS "removeDOMElementWithStyleByElementId" para remover elemento del DOM estilo.
-   Nueva funcion de JS "sizeInMB" para devolver una cadena que indique el tamaño de un archivo en mb, kb y bits.
-   Correciones menores en la clase ManageDB.
-   Nuevo metodo "removeFiles" en la clase Files.
-   Nuevo metodo "combine_names_with_files_size" en la clase Files.
-   Correciones menores en la clase Email.

### Version 2.1

-	Corrección de tiempo de ejecución que impedía identificación validación de token de sesión.
-	Refactorización de funciones para aumentar su alcance.
-	Se agregaron funciones para simplificar el lanzamiento de spinner y validación de formularios.
-	Recodificación de scripts que crean tablas, vistas, disparadores (triggers), procedimientos almacenados (stored procedure) en la base de datos, así como su posterior desinstalación.
-	Se agregaron nuevos métodos a la clase de conexión de base de datos que permiten ejecutar multiqueries.
-	Se añadió mensaje de aviso de sesión próxima a expirar si no ejecuta una acción la cual comprueba la validez de esta.

### Version 1.0

-   Iniciar sesión.
-   CRUD de usuarios.
-   Gestionar perfil de usuario.
-   Asignar roles.
-   Validación de usuario mediante correo electrónico.
-   Recuperación de contraseña mediante correo electrónico.
-   Gestionar módulos (instalar, activar, desactivar, desinstalar).


### Licencia

Este software se distribuye bajo la lencia GNU GPL 3.0, la cual permite su copia, distribución y modificación, permaneciendo los derechos de autor y sin ningun tipo de garantia. Para más información consulte [gnu.org](https://www.gnu.org/licenses/).