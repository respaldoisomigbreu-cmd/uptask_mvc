    <?php

    $nombre = $usuario->nombre  ?? '';
    $email = $usuario->email  ?? '';

        include_once __DIR__ . '/header-dashboar.php'; 
    ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ;?>

        <a href="/cambiar-password" class="enlace">Cambiar Password</a>

        <form class="formulario" method="POST" action="/perfil">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Tu Nombre" 
                    value="<?php echo $nombre; ?>"
                />
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Tu Email"
                    value="<?php echo $email; ?>"                     
                />
            </div>
            <input type="submit" class="boton" value="Actualizar Perfil" />
        </form>
    </div>

    <?php include_once __DIR__ . '/footer-dashboard.php'; ?>