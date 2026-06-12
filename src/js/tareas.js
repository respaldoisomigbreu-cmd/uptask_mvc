(function(){

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //boton para mostrar el madal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function(){
        mostarFormulario();
    });

    //filtros de busquedad
    const filtros = document.querySelectorAll('#filtros input[type = "radio"');
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e){
        const filtro = e.target.value;

        if(filtro !== ''){
            filtradas = tareas.filter( tarea  => tarea.estado === filtro);
        }else{
            filtradas = [];
        }
        mostrarTareas();
    }

    //

    async function obtenerTareas(){
        try {
            const id = ObtenerProyecto();                   //obtener el id del proyecto actual
            const url = `/api/tareas?url=${id}`;            //construir la url para obtener las tareas del proyecto
            const respuesta = await fetch(url);             //obtener la respuesta del servidor
            const resultado = await respuesta.json();       //obtener el resultado en formato json
            tareas = resultado;                             //mostrar las tareas en el html
            mostrarTareas(tareas);                          //si no hay tareas mostrar un mensaje
        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas(){
        limpiarTareas();                            //limpiar las tareas anteriores para mostrar las nuevas tareas
        totalPendiente();
        totalcompletadas();

        const arrayTareas = filtradas.length ? filtradas : tareas;


        if(arrayTareas.length === 0){
            const contenedorTareas = document.querySelector('#listado-tareas');             //seleccionar el contenedor de las tareas

            const textoNoTareas = document.createElement('LI');                             //crear un elemento li para mostrar el mensaje de no hay tareas
            textoNoTareas.textContent = 'No hay tareas en este proyecto';                   //agregar una clase para el estilo del mensaje
            textoNoTareas.classList.add('no-tareas');                                       //agregar el mensaje al contenedor de las tareas

            contenedorTareas.appendChild(textoNoTareas);                                    //agrega el mensaje "No hay tareas" a la pantalla.
           // return;
        }

        const estado = {
            0: 'Pendiente',
            1: 'Completa'
        };

        arrayTareas.forEach(tarea => {

            const contenedorTareas = document.createElement('LI');          //crear un elemento li para cada tarea
            contenedorTareas.dataset.tareaId = tarea.id;                    //agregar un atributo data-tarea-id con el id de la tarea para poder identificarla posteriormente
            contenedorTareas.classList.add('tarea');                        //agregar una clase para el estilo de las tareas

            const nombreTareas = document.createElement('P');                      //crear un elemento p para mostrar el nombre de la tarea
            nombreTareas.textContent = tarea.nombre;                               //agregar el nombre de la tarea al contenedor de la tarea
            nombreTareas.ondblclick = function(){
                mostarFormulario(editar = true, {...tarea});
            }

            const opcionesDiv = document.createElement('DIV');                      //crear un div para las opciones de cada tarea
            opcionesDiv.classList.add('opciones');                                  //agregar una clase para el estilo de las opciones

            //botones para editar y eliminar cada tarea
            const btnEstadoTareas = document.createElement('BUTTON');                      //crear un boton para editar la tarea
            btnEstadoTareas.classList.add('estado-tarea');                                  //agregar una clase para el estilo del boton de editar
            btnEstadoTareas.classList.add(`${estado[tarea.estado].toLowerCase()}`);         //agregar una clase para el estilo del boton de editar dependiendo del estado de la tarea
            btnEstadoTareas.textContent = estado[tarea.estado];                             //agregar el texto al boton de editar
            btnEstadoTareas.dataset.estadoTarea = tarea.estado;                             //agregar un atributo data-estado-tarea con el estado de la tarea para poder identificarla posteriormente
            btnEstadoTareas.ondblclick = function(){                                         //agregar un evento click al boton de editar para cambiar el estado de la tarea
                cambiarEstadoTarea({...tarea});                                             //cambiar el estado de la tarea y pasar una copia de la tarea para evitar modificar el estado de la tarea original
            }


            const btnEliminarTarea = document.createElement('BUTTON');                      //crear un boton para eliminar la tarea
            btnEliminarTarea.classList.add('eliminar-tarea');                               //agregar una clase para el estilo del boton de eliminar
            btnEliminarTarea.dataset.idTarea = tarea.id;                                    //agregar un atributo data-id-tarea con el id de la tarea para poder identificarla posteriormente
            btnEliminarTarea.textContent = 'Eliminar';                                      //agregar el texto al boton de eliminar
            btnEliminarTarea.ondblclick = function(){                                                     //agregar un evento click al boton de editar para cambiar el estado de la tarea
                confirmarEliminarTarea({...tarea});
            }
            opcionesDiv.appendChild(btnEstadoTareas);                                  //agregar el boton de editar al div de opciones
            opcionesDiv.appendChild(btnEliminarTarea);                                  //agregar el boton de eliminar al div de opciones

            contenedorTareas.appendChild(nombreTareas);                                  //agregar el nombre de la tarea al contenedor de la tarea
            contenedorTareas.appendChild(opcionesDiv);                                  //agregar el div de opciones al contenedor de la tarea

            const listadoTareas = document.querySelector('#listado-tareas');             //seleccionar el contenedor de las tareas
            listadoTareas.appendChild(contenedorTareas);                                    //agregar el contenedor de la tarea al contenedor de las tareas
        });

    }

    function totalPendiente(){
        const totalPendiente = tareas.filter( tarea => tarea.estado === "0");
        const pendienteradio = document.querySelector('#pendientes');

        if(totalPendiente.length === 0){
            pendienteradio.disabled = true;
        }else{
            pendienteradio.disabled = false;
        }
    }

    function totalcompletadas(){
        const totalcompletadas = tareas.filter( tarea => tarea.estado === "1");
        const completadasradio = document.querySelector('#completadas');

        if(totalcompletadas.length === 0){
            completadasradio.disabled = true;
        }else{
            completadasradio.disabled = false;
        }

    }

    function mostarFormulario(editar = false, tarea = {}){


        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario nueva-tarea">
                <legend>${editar ? 'Editar Tarea' : 'Agregar Nueva Tarea'}</legend>
                <div class="campo">
                    <label>Tarea</label>
                    <input
                        type="text"
                        name="tarea"
                        placeholder="${tarea.nombre ? 'Editar La Tarea' : 'Nueva Tarea al Proyecto Actual'}"
                        id="tarea"
                        value="${tarea.nombre ? tarea.nombre : '' }"
                    />
                </div>
                <div class="opciones">
                    <input
                        type="submit"
                        class="submit-nueva-tarea"
                        value="${tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'} "
                    />
                    <button
                        type="button"
                        class="cerrar-modal"
                        >Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        },0);

        //boton para cerrar el modal
        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 1000);

            }
            if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreTarea = document.querySelector('#tarea').value.trim();

            if(nombreTarea === ''){

                mostrarAlerta('El Nombre de la Tarea es Obligatorio', 'error',
                document.querySelector('.formulario legend') );
                return;
            }

            if(editar){
                tarea.nombre = nombreTarea;
                actualizarTarea(tarea);
            }else{
                agregarTarea(nombreTarea);
            }

            }
        });

        //agregar el modal al html
        document.querySelector('body').appendChild(modal);
    }



        //Muestra un mensaje de alerta
        function mostrarAlerta(mensaje, tipo, referencia){
            //previen que se creen varias alertas
            const alertaPrevia = document.querySelector('.alerta');
            if(alertaPrevia){
                alertaPrevia.remove();
            }

            const alerta = document.createElement('DIV');
            alerta.classList.add('alerta', tipo);
            alerta.textContent = mensaje;

            //insetar la alerta despues del legend
            referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

            //eliminar la alerta despues de 3 segundos
            setTimeout(() => {
                alerta.remove();
            }, 5000);

        }

        //funcion para agregar la tarea al servidor
    function cambiarEstadoTarea(tarea){

        const nuevoEstado = tarea.estado === '0' ? '1' : '0';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }



        async function agregarTarea(tarea){
            //construir peticion
            const datos = new FormData();
            datos.append('nombre', tarea);
            datos.append('proyectoId', ObtenerProyecto());


            try {
                const url = '/api/tareas';
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datos
                });
                const resultado = await respuesta.json();

                mostrarAlerta(resultado.mensaje, resultado.tipo,
                document.querySelector('.formulario legend') );

                    //si la tarea se agrego correctamente
                if(resultado.tipo === 'exito'){
                    const modal = document.querySelector('.modal');
                    setTimeout(() => {
                        modal.remove();
                    }, 3000);

                    //agregar el objeto de tarea al arreglo de tareas
                    const tareaObj = {
                        id: String(resultado.id),
                        nombre: tarea,
                        estado: '0',
                        proyectoId: resultado.proyectoId
                    }
                    tareas = [...tareas, tareaObj];
                    mostrarTareas();
                    console.log(tareas);
                }

            } catch  {
                console.log(error);
            }

        }

    async function actualizarTarea(tarea){
        //construir peticion
        const{estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', ObtenerProyecto());

        try {
            const url = '/api/tareas/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.respuesta.tipo === 'exito'){
                swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );

                const modal = document.querySelector('.modal');
                if(modal){
                    modal.remove();
                }

                tareas = tareas.map(tareaMemoria =>{
                    if(tareaMemoria.id === id){
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }


    function confirmarEliminarTarea(tarea){
        Swal.fire({
            title: "Eliminar Tarea?",
            showCancelButton: true,
            confirmButtonText: "SI",
            cancelButtonText: "No"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        });
    }

        async function eliminarTarea(tarea){
            //construir peticion
            const{estado, id, nombre} = tarea;

            const datos = new FormData();
            datos.append('id', id);
            datos.append('nombre', nombre);
            datos.append('estado', estado);
            datos.append('proyectoId', ObtenerProyecto());

            try{
            const url = '/api/tareas/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if(resultado.resultado){
                // mostrarAlerta(
                //     resultado.mensaje,
                //     resultado.tipo,
                //     document.querySelector('.contenedor-nueva-tarea')
                // );
                Swal.fire('Eliminando!', resultado.mensaje, 'success');
                tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }

            } catch(error) {
                console.log(error);
            }
        }

    function ObtenerProyecto(){
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;
    }

    function limpiarTareas(){
        const listadoTareas = document.querySelector('#listado-tareas');            //seleccionar el contenedor de las tareas
        while(listadoTareas.firstChild){                                            //mientras el contenedor de las tareas tenga un hijo, eliminar el primer hijo del contenedor de las tareas
            listadoTareas.removeChild(listadoTareas.firstChild);                    //eliminar el primer hijo del contenedor de las tareas
        }
    }
})();