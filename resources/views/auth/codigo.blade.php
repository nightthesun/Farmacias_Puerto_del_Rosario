@extends('layouts.app')

@section('codigo')
<script src="js/jquery-3.2.1.min.js"></script>
<style>
    .background-radial-gradient {
      background-color: hsl(218, 41%, 15%);
      background-image: radial-gradient(650px circle at 0% 0%,
          hsl(218, 41%, 35%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%),
        radial-gradient(1250px circle at 100% 100%,
          hsl(218, 41%, 45%) 15%,
          hsl(218, 41%, 30%) 35%,
          hsl(218, 41%, 20%) 75%,
          hsl(218, 41%, 19%) 80%,
          transparent 100%);
    }
    body{
        background-color: #E3F2FD;
      }
      .error{
        color:white;
        padding-left: 12px;
        padding-right: 12px;
        padding-bottom: 15px;
        padding-top: 15px;
        margin-top: 30px;

        background-color: crimson;
      }
      .obligatorio{
        color:white;
        padding-left: 12px;
        padding-right: 12px;
        
        background-color: crimson;
      }

      

</style>
<section class="background-radial-gradient overflow-hidden" style="background-color: #20B2AA;">
    <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <div class="card shadow-2-strong" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">
              <form action="" class="mt-4" method="POST" id="formu">
                @csrf
                <h4 class="mb-3">Intruzca su codigo:</h4>
                @if(isset($incorrecto))
                  @if($incorrecto=='Incorrecto')
                  <h4 style="color: crimson">El Codigo no Coincide <br> <small>Intentelo otra vez</small></h4>
                  @endif
                @endif
                <div class="form-group" >
                  <input type="text" class="form-control" name="codigo" id="codigo" placeholder="00000" required autocomplete="new-password" style="text-align: center; font-size:xx-large " size="5" maxlength="5">
                </div>

                <div class="form-group">
                    <h4 class="mb-3">Nueva contraseña:</h4>
                    <input type="password" class="form-control" name="newpass" id="newpass" required required autocomplete="new-password">
                </div>

                <div class="form-group">
                    <h4 class="mb-3">Repita Contraseña:</h4>
                    <input type="password" class="form-control" name="confpass" id="confpass" required required autocomplete="new-password">
                </div>
                <div class="error">
                  <span >Las Contraseñas no coinciden</span>  
                </div>
                <div class="obligatorio">
                  <span >Debe Ingresar Todos los Campos</span>  
                </div>
                
                <br>
                <div class="form-check" style="text-align: left">
                  <label class="form-check-label" for="mostrar_contrasena">Mostrar Contraseña</label>
                  <input type="checkbox" class="form-check-input" id="mostrar_contrasena" title="clic para mostrar contraseña" />
                </div>
            <br>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" id="actualizar">Actualizar Password</button>
                </div>
                <br>
                <div class="form-group other_auth_links">
                    <a class="" href="{{ route('login.index') }}"><-Volver al login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script >
    $(document).ready(function () {
      
      $('.error').hide();
      $('.obligatorio').hide();
      $('#mostrar_contrasena').click(function () {
        if ($('#mostrar_contrasena').is(':checked')) {
          $('#newpass').attr('type', 'text');
          $('#confpass').attr('type','text');
        } else {
          $('#newpass').attr('type', 'password');
          $('#confpass').attr('type','password');
        }
      });
      $('#confpass').keyup(function(){
        let newpass=$('#newpass').val();
        let confpass=$('#confpass').val();
        
        if(newpass!=confpass){
          console.log(newpass,confpass);
          $('.error').show();
          return;
        }
        else
        {
          $('.error').hide();
          
          return
        }
      });

      $('#formu').submit(function(evento){
        
        evento.preventDefault();
        $('.error').hide();
        $('.obligatorio').hide();
        let sw=0;
        let newpass=$('#newpass').val();
        let confpass=$('#confpass').val();
        let codigo=$('#codigo').val();
        if(!newpass.length){
          $('obligatorio').show();
          return;
        }
        if(!confpass.length){
          $('obligatorio').show();
          return;
        }

        if(!confpass.length){
          $('obligatorio').show();
          //return;
        }
        
        if(newpass!==confpass){
          $('.error').show();
          sw=0;
          //return;
        }
        else
        {
          $('.error').hide();
          sw=1;
        }
        if(newpass.length>0 && confpass.length>0 && codigo.length>0 && sw==1)
        {
          console.log('entra');
          this.submit();
        }
        else
        console.log('noentra');
          
        
       

      });
    
  
    
  });
  
  
  </script>

@endsection