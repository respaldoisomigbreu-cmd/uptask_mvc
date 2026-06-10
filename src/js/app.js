const mobilMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');

if(mobilMenuBtn){
    mobilMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('mostrar');
    });

}

if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');

        setTimeout(() => {
        sidebar.classList.remove('mostrar');
        sidebar.classList.remove('ocultar');

        }, 1000);
    });
}

//Eliminar la calse de mostrar, en una ancho de pantalla mayor a una tablet
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768){
        sidebar.classList.remove('mostrar');
    }
})