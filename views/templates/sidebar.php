<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2> UpTask</h2>
        <div class="cerrar-menu">
            <img id="cerrar-menu" src="/build/img/cerrar.svg" alt="imagen cerrar menu">
        </div>
    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo (isset($titulo) && $titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo (isset($titulo) && $titulo === 'Crear Proyecto') ? 'activo' : ''; ?>" href="/crear-proyecto">Crear Proyectos</a>
        <a class="<?php echo (isset($titulo) && $titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Crerrar Sesión</a>

    </div>
</aside>