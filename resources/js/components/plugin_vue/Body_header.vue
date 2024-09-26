<template>
<!-- <main>
    <div><h1>hola</h1></div>
</main>
 -->

   <main class="ml-auto"> 
  <ul class="nav navbar-nav ml-auto"> 
        <li>
             <div class="text-center" style="width: 300px;">
                    <span>Sucursal: </span><strong> {{ nomsucursal }} - {{ nomrol }}</strong>
                </div>
        </li>
        <li id="valueSession" class=" d-md-down-none" style="font-size: 13px; padding-right: 10px;">
            <a class="nav-link" href="#">
                
                <div class="text-center" style="width: 156px;">
                    <!-- <span>Tu sesión expira en :</span> <div id="timeout">{{tiempoSession}} seg.</div>  -->
                </div>
            </a> 
        </li>
        <!-- <li id="clockSession" class="nav-item dropdown navItem d-md-down-none" style="font-size: 26px;">
            <a class="nav-link spring" href="#"> <i class="icon-clock"></i> </a>
        </li> -->

       <li class="nav-item dropdown navItem">
           
            <a class="avatar nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
             aria-expanded="false"
             style="width: auto;    height: 100%; padding: 0px 14px 0px 17px; display: inline-flex;  vertical-align: middle;">
               <span class="icon-bell" style="  margin: auto !important;"></span>
               <span class="badge badge-pill badge-danger">5</span>
           </a>
           <div class="dropdown-menu dropdown-menu-right">
               <div class="dropdown-header text-center">
                   <strong>Notificaciones</strong>
               </div>
               <a class="dropdown-item" href="#">
                   <i class="fa fa-envelope-o"></i> Ventas Pendientes
                   <span class="badge badge-success">3</span>
               </a>
               <a class="dropdown-item" href="#">
                   <i class="fa fa-tasks"></i> Devoluciones
                   <span class="badge badge-danger">2</span>
               </a>
           </div>
       </li>
       <li class="nav-item dropdown navItem">
           <a class="avatar nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false"
               style="width: auto;    height: 100%; padding: 0px 14px 0px 17px; display: inline-flex;  vertical-align: middle;">
                 <span class="d-md-down-none"
                   style="align-content: center; width: inherit;display: grid;    padding: 0 10px 0 10px;">
                   <b>{{usuario}}</b> </span>
                   
               <img v-if="arrayEmpleado.foto" class="img-avatar" :src="'storage/'+ arrayEmpleado.foto.substring(10)" style="  margin: auto !important;"
                   alt="eddyclarosc@gmail.com">
                <img v-else src="img/avatars/persona.png"  class="img-avatar" style="  margin: auto !important;" >

           </a>
           <div class="dropdown-menu dropdown-menu-right">
               <div class="dropdown-header text-center">
                   <strong>Cuenta </strong> 
               </div>
               <div style="text-align:center">
                   <input type="button" value="Ver Perfil" data-toggle="modal" data-target="#exampleModal" style=" width:100% ;padding-top: 8px;padding-bottom: 8px; background-color: aliceblue;border:none">
                    <!-- <button type="button" class="" data-toggle="modal" data-target="#exampleModal">
                        Ver Perfil
                    </button> -->
               </div>
               <div style="text-align:center">
                <input type="button" @click="listarSaldo()" value="Ver Saldo" data-toggle="modal" data-target="#exampleModalCenter" style=" width:100% ;padding-top: 8px;padding-bottom: 8px; background-color: white;border:none">
              </div>

              <a class="dropdown-item" id="logoutUser" href="#" @click="logout"><i class="fa fa-lock"></i> Cerrar sesión</a>
             </div>
       </li>
        
    </ul>
     <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perfil de usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                         <div class="form-group col-sm-8" style="padding-right: 0px;">
                            <div class="form-group row rounded " style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                                <label for="" class="col-sm-6 col-form-label" style="text-align:right">Nombre: </label>
                                <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nomempleado }}</label>
                            </div>
                        
                            <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                                <label for="" class="col-sm-6 col-form-label" style="text-align:right">Primer Apellido: </label>
                                <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nombre }}</label>
                            </div>
                        
                            <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                                <label for="" class="col-sm-6 col-form-label" style="text-align:right">Segundo Apellido: </label>
                                <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.papellido }}</label>
                            </div>
                        
                            <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                                <label for="" class="col-sm-6 col-form-label" style="text-align:right">CI: </label>
                                <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.sapellido }}</label>
                            </div>
                        </div>
                        <div class="form-group col-sm-4" style="padding-left: 0px;">
                            <img :src="'storage/'+ arrayEmpleado.foto" alt="" class="img-thumbnail">
                        </div>

                    </div>
                   
                    <div class="form-group col">
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Telefonos: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.telefonos }}</label>
                        </div>
                    
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Celular: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.celular }}</label>
                        </div>
                    
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Grado Academico: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nomformacion }}</label>
                        </div>
                    
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Formacion: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nomprofecion }}</label>
                        </div>
                    
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Cargo: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nomcargo }}</label>
                        </div>
                    
                        <div class="form-group row rounded " style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Cuenta de Banco: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.nombanco + ' ' + arrayEmpleado.nrcuenta }}</label>
                        </div>
                    
                        <div class="form-group row rounded" style="background-color:gainsboro ;margin-left: 15px;margin-right: 15px; margin-bottom: 8px;">
                            <label for="" class="col-sm-6 col-form-label" style="text-align:right">Fecha de Ingreso: </label>
                            <label for="" class="col-sm-6 col-form-label">{{ arrayEmpleado.fechaingreso }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!------------------------modal saldo-------------------------->
  <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Saldo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <!---inserte tabla-->
       <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-3" style="text-align: center">Rol</th>                     
                        <th class="col-md-3" style="text-align: center">total</th>
                        <th class="col-md-3" style="text-align: center">Monto limite</th>
                        <th class="col-md-3" style="text-align: center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-md-3" style="text-align: center">{{ nomrol_S }}</td>
                        <td class="col-md-3" style="text-align: center">{{ suma_valor+" "+simbolo_S }}</td>
                        <td class="col-md-3" style="text-align: center">{{ montoLimite_S+" "+simbolo_S }}</td>
                        <td class="col-md-3" style="text-align: center">
                            <span v-if="estado_S===1" class="badge badge-pill badge-success">OK</span>
                            <span v-else class="badge badge-pill badge-danger">En Mora</span>
                            
                        </td>                       
                    </tr>
                </tbody>
        </table> 
        <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>                      
                        <th class="col-md-4" style="text-align: center">Fecha inicial</th>
                        <th class="col-md-4" style="text-align: center">Fecha limite</th>
                        <th class="col-md-2" style="text-align: center">Dia</th>
                        <th class="col-md-2" style="text-align: center">Hora/minutos</th>                   
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-md-4" style="text-align: center">{{ created_at_S }}</td>
                        <td class="col-md-4" style="text-align: center">{{ fecha_modificada_S }}</td>
                        <td class="col-md-2" style="text-align: center">{{ dias_faltantes_S }}</td>   
                        <td class="col-md-2" style="text-align: center">{{ horas_faltantes_S+" : "+ minutos_faltantes_S }}</td>                   
                    </tr>
                </tbody>
        </table>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
      </div>
    </div>
  </div>
</div>


  </main>
</template>

<script> 
import Swal from 'sweetalert2';
import {error401} from '../../errores';
    export default {
        props : ['user','nomsucursal','nomrol','tiempoSession'],

         
        data (){
            return {
                usuario:this.user,
                nomsucursal:this.nomsucursal,
                nomrol:this.nomrol,
                arrayEmpleado:[],
                tiempoSession:0,

               
                montoLimite_S:0,
                nomrol_S:'',
                created_at_S:'',
                dias_faltantes_S:0,
                fecha_modificada_S:'',
                horas_faltantes_S:0,
                minutos_faltantes_S:0,
                suma_valor:"",
       
                estado_S:0,
                simbolo_S:"",
            

            }
        },  
        methods : {
                        
            listarSaldo(){
                let me=this;
                var url='/empleado/getsaldo';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    var respuesta_2=response.data.registro;
            
                   me.montoLimite_S=respuesta.montoLimite;
                    me.nomrol_S=respuesta.nomrol;     
                   
                   if (respuesta_2===null) {
                    me.created_at_S="Sin fecha";
                    me.dias_faltantes_S=0;
                    me.fecha_modificada_S="Sin fecha";
                    me.horas_faltantes_S="00";
                    me.minutos_faltantes_S="00";
                    me.suma_valor="0.00";  
                 
                    me.estado_S=1;
                    me.simbolo_S = "";
                    
                   }else{
                    me.created_at_S=respuesta_2.created_at;
                    me.dias_faltantes_S=respuesta_2.dias_faltantes;
                    me.fecha_modificada_S=respuesta_2.fecha_modificada;
                    me.horas_faltantes_S=respuesta_2.horas_faltantes;
                    me.minutos_faltantes_S=respuesta_2.minutos_faltantes;
                    me.suma_valor= respuesta_2.suma_valor;                 
                    me.simbolo_S = respuesta.simbolo;

                    if ((parseFloat(me.suma_valor)>=parseFloat(me.montoLimite_S)) || (parseFloat(me.horas_faltantes_S) < 0 && parseFloat(me.dias_faltantes_S) < 0) ) {
                        me.estado_S=9;
                    } else {
                        me.estado_S=1;
                    }
                   }
                   
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });            
            },

            listarEmpleado(){
                let me=this;
                var url='/empleado/perfil';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayEmpleado=respuesta[0];               
                    
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

          

            logout(event){
         
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Desea Cerrar Sesion',
                
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Si, Cerrar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href='/logout';
                 } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                }
                })
            }
            
        },

        mounted(){
            this.listarEmpleado();
                       
           // this.classModal.addModal('saldo');
            //this.actualizarTiempoSessionUsuario();         
      
        }
       
    }
</script>
<style> 

</style>
