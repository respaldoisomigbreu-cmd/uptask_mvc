<div class="contenedor login">

<?php include_once __DIR__ .'/../templates/nombre-sitio.php';  ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Inicia Sesión en tu Cuenta</p>
<?php include_once __DIR__ .'/../templates/alertas.php';  ?>

        <form class="formulario" method="POST" action="/" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Tu Email"
                />
            </div>

            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Tu Password"
                />
            </div>
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/crear">¿No tienes una cuenta? Crear Una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>

    </div> <!--.contenedor-sm-->
</div>
