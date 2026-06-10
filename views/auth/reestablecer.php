<div class="contenedor reestablecer ">

<?php include_once __DIR__ .'/../templates/nombre-sitio.php';  ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Nuevo Password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if($mostar) { ?>

        <form class="formulario" method="POST" >


            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Tu Password"
                />
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input 
                    type="password" 
                    id="password2" 
                    name="password2" 
                    placeholder="Repite tu Password"
                />
            </div>
            <input type="submit" class="boton" value="Guardar Password">
        </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿No tienes una cuenta? Crear Una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>

    </div> <!--.contenedor-sm-->
</div>
