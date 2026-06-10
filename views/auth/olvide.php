<div class="contenedor olvide ">

<?php include_once __DIR__ .'/../templates/nombre-sitio.php';  ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar tu Password</p>

        <?php include_once __DIR__ .'/../templates/alertas.php';  ?>

        <form class="formulario" method="POST" action="/olvide">

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Tu Email"
                />
            </div>

            <input type="submit" class="boton" value="Recuperar Password">
        </form>
        <div class="acciones">
            <a href="/crear">¿No tienes una cuenta? Crear Una</a>
            <a href="/">Ya tienes una cuenta? Iniciar Sesión</a>
        </div>

    </div> <!--.contenedor-sm-->
</div>
