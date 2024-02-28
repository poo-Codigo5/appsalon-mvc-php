<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__ ."/../templates/alertas.php";
?>
<?php if($mensaje) { ?>
        <p class="alerta exito"> <?php echo $mensaje; ?></p>;
<?php } ?>
<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tu Nombre"
            value="<?php echo s($usuario->nombre); ?>"
        
        />
    </div>    

    <div class="campo">
        <label for="apellidos">Apellidos</label>
        <input 
            type="text"
            id="apellidos"
            name="apellidos"
            placeholder="Tus Apellidos"
            value="<?php echo s($usuario->apellidos); ?>"
        />
    </div> 

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tu Teléfono"
            value="<?php echo s($usuario->telefono); ?>"
        />
    </div> 

    <div class="campo">
        <label for="email">E-mail</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tu E-mail"
            value="<?php echo s($usuario->email); ?>"
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

    <input type="submit" value="Crear Cuenta" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta?, Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>

</div>