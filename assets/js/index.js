function logear(){
    $.ajax({
      type:"POST",
      data:$('#frmLogin').serialize(),
      url:"procesos/index/login.php",
      success:function(respuesta){
        respuesta = respuesta.trim();
        if (respuesta == 1) {
          window.location = "vistas/inicio.php";
        }else{
          swal(" ","El nombre de usuario o la contraseña son incorrectos. Por favor, inténtalo de nuevo.","error");
        }
      }
    });
    return false;
  }