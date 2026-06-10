    <?php

    $nombre = $usuario->nombre  ?? '';
    $email = $usuario->email  ?? '';

        include_once __DIR__ . '/header-dashboar.php'; 
    ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ;?>
        <a href="/perfil" class="enlace">Volver a Perfil</a>


        <form class="formulario" method="POST" action="/cambiar-password">
            <div class="campo">
                <label for="nombre">Password Actual</label>
                <input 
                    type="password"
                    name="password_actual" 
                    placeholder="Tu Password Actual" 
                />
            </div>
            <div class="campo">
                <label for="nombre">Password Nuevo</label>
                <input 
                    type="password"
                    name="password_nuevo" 
                    placeholder="Tu Password Nuevo" 
                />
            </div>
            <input type="submit" class="boton" value="Cambiar Password" />
        </form>
    </div>

    <?php include_once __DIR__ . '/footer-dashboard.php'; ?>