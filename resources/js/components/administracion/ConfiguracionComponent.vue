<template>
    <main class="main">
   <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <!-- inicio de index -->
        <div class="container-fluid">
    <div class="card">
        <div class="card-header">
           
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-credencial" aria-selected="true">Configuración de credenciales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Datos de empresa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-fa-tab" data-toggle="pill" href="#pills-fa" role="tab" aria-controls="pills-fa" aria-selected="false">Tipo de venta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-moneda-tab" data-toggle="pill" href="#pills-moneda" role="tab" aria-controls="pills-moneda" @click="listarTipomoneda()" aria-selected="false">Tipo de moneda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-cuenta-tab" data-toggle="pill" href="#pills-cuenta" role="tab" aria-controls="pills-cuenta" @click="listarBanco()" aria-selected="false">Tipo de cuenta</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
           
             <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-credencial-tab">
                        <div class="card">
                            <div class="card-header">
                                Credencial para servidor de correos
                            </div>
                            <div class="card-body">    
                                <div class="form-group row">

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Host:</strong> 
                                    <span v-if="host == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="host" class="form-control" placeholder="mail.empresa.com" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir el host</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Correo:</strong> 
                                    <span v-if="correo == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="correo" class="form-control" placeholder="correo@correo.es" v-on:focus="selectAll"/>
                                        <span v-if="correo == ''" class="error">Debe escribir un correo</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Puerto:</strong> 
                                    <span v-if="puerto == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="number" min="0" v-model="puerto" class="form-control" placeholder="" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir un puerto</span>
                                    </div>
                                </div>
                                <div class="form-group row">

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Usuario:</strong> 
<span v-if="usuario == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="text" v-model="usuario" class="form-control" placeholder="puede ser un correo" v-on:focus="selectAll"/>
    <span v-if="host == ''" class="error">Debe escribir el usuario</span>
</div>

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Contraseña:</strong> 
<span v-if="contraseña == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="password" v-model="contraseña" class="form-control"  v-on:focus="selectAll"/>
    <span v-if="contraseña == ''" class="error">Debe escribir una contraseña</span>
</div>

<label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>SSL:</strong> 
</label>
<div class="col-md-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="autoSizingCheck2"  :true-value="1" 
    :false-value="0" v-model="ssl">      
      </div>
</div>
</div>
                            </div>
                            <div class="form-group row justify-content-center">
    <div class="col-md-3 d-flex justify-content-center">
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="update_credecial_correo();">Actualizar configuracion correro</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar configuracion correro</button>
   
    </div>
</div>

                          
                        </div>
                        
                    </div>


                    <!------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card">
                            <div class="card-header">
                              Datos de la empresa
                            </div>
                            <div class="card-body">
                                <div class="form-group row">

                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>NIT:</strong> 
                                    <span v-if="nit == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nit" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="nit == ''" class="error">Debe escribir el nit</span>
                                    </div>
                                      
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Empresa:</strong> 
                                    <span v-if="nombre_empresa == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="nombre_empresa" class="form-control" placeholder="nombre de negocio" v-on:focus="selectAll"/>
                                        <span v-if="nombre_empresa == ''" class="error">Debe escribir el un nombre de negocio</span>
                                    </div>                   
                               
                                </div>  
                                <div class="form-group row">
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Nro CEL/TEL:</strong> 
                                    <span v-if="celular == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="celular" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="celular == ''" class="error">Debe escribir telefono o celular</span>
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Actividad economica:</strong> 
                                    <span v-if="celular == ''" class="error">(*)</span></label>
                                    <div class="col-md-4">
                                        <input type="text" v-model="actividad_eco" class="form-control" placeholder="actividad economica" v-on:focus="selectAll"/>
                                        <span v-if="actividad_eco == ''" class="error">Debe escribir actividad economica</span>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row justify-content-center">
    <div class="col-md-3 d-flex justify-content-center">
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="update_datos_empresa();">Actualizar datos de empresa</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar datos de empresa</button>
   
    </div>
</div>
                        </div>
                    </div>
                <!------------------------------------------------------------------------------------------>
                <div class="tab-pane fade" id="pills-fa" role="tabpanel" aria-labelledby="pills-fa-tab">
                    
                        <div class="card">
                            <div class="card-header">
                             Tipo de venta
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                    </label>
                                    <div class="col-md-3">
                                        <select class="form-control"  v-model="selectTipoFac" @change="cambioEstado(selectTipoFac)">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option v-for="t in arrayTipofac" :key="t.id" :value="t.id">
                                                {{ t.tipo }}
                                            </option>
                                        </select>
                                     </div>
                                   
                                    <div class="col-md-3">
                                        <div v-if="validador_variables===0" class="alert alert-danger" role="alert">
                                           SIN FACTURACION
                                        </div>
                                        <div v-if="validador_variables===1" class="alert alert-primary" role="alert">
                                            FACTURA
                                        </div>
                                        <div v-if="validador_variables===2" class="alert alert-success" role="alert">
                                            DOSIFICACION
                                        </div>
                                     </div>
              
                                </div>
                            </div>
                        </div>
                    </div>
                <!-------------------------------------------------------------------------------------------------------------------->
                <div class="tab-pane fade" id="pills-moneda" role="tabpanel" aria-labelledby="pills-moneda-tab">                    
                    <div class="card">
                        <div class="card-header">Tipo de moneda</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Tipo:</strong> 
                                </label>
                                <div class="col-md-3">
                                    <select class="form-control"  v-model="selectMoneda" @change="cambioDeMoneda(selectMoneda)">
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option v-for="t in arrayMoneda" :key="t.id_nacionalidad_pais" :value="t.id_nacionalidad_pais">
                                            {{ t.pais+" "+t.simbolo }}
                                        </option>
                                    </select>
                                 </div>
                                 <div class="col-md-3">
                                        <div v-if="nombre_pais==='0'" class="alert alert-danger" role="alert">
                                           SIN TIPO DE MONEDA
                                        </div>
                                        <div v-else class="alert alert-primary" role="alert">
                                            {{nombre_pais}}
                                        </div>
                                       
                                     </div>
                               
          
                            </div>
                        </div>
                    </div>
                </div>
                
            <!-------------------------------------------------------------------------------------------------------------------------->
            <div class="tab-pane fade" id="pills-cuenta" role="tabpanel" aria-labelledby="pills-cuenta-tab">
                <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Cuenta bancaria
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         Bancos
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <div class="form-group row">
            <label class="col-md-1 form-control-label" for="text-input" style="font-size: 12px;"><strong>Nombre del banco:</strong></label>
                <div class="col-md-4">
                    <input type="text" v-model="banco_nombre" class="form-control" placeholder="Escriba el nombre del banco"/>                  
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-secondary" @click="crearBanco()">Añadir</button>
                </div>                         
                               
          </div> 
          <div class="form-group row">
            <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Opciones</th>
                                    <th class="col-md-8" style="font-size: 13px; text-align: center">Nombre</th>
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Estado</th>
                                                       
                                </tr>
                            </thead>
                            <tbody>                                
                                <tr v-for="b in arrayBanco" :key="b.id">                                 
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">
                                        <button type="button" class="btn btn-warning" style="margin-right: 5px;" @click="abrirModal('regbanco',b)">
                                            <i class="icon-pencil"></i></button>
                                    <button v-if="b.activo == 1" type="button" class="btn btn-danger" style="margin-right: 5px;">
                                             <i class="icon-trash"></i> </button>
                                    <button v-else type="button" class="btn btn-info" style="margin-right: 5px;">
                                            <i class="icon-check"></i>
                                    </button>    
                                    </td>
                                    <td class="col-md-8" style="font-size: 13px; text-align: center">{{(b.nombre).toUpperCase()}}</td>
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">
                                        <span v-if="b.activo===1" class="badge badge-pill badge-success">Activo</span>
                                        <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                                    </td>
                       
                                </tr>
                            </tbody>   
                        </table> 
          </div>

        </div>
    </div>
  </div>

</div>







                    </div>
            <!-------------------------------------------------------------------------------------------------------------------------->
                </div>
            
        </div>
    </div>
</div>
  <!-- modal registro bancos -->
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="regbanco" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Registrar Banco</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('regbanco')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: whitesmoke">
                        <div class="form-group">
                            <label>Nombre Banco:</label>
                            <input type="text" id="nombrebanco" name="nombrebanco" class="form-control rounded" placeholder="Nombre Banco" v-model="nombrebanco_0">
                 
                        </div>
                    </div>    
                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('regbanco')">Cerrar</button>
                        <button type="button" class="btn btn-primary rounded" @click="registrarBanco()" :disabled="nombrebanco==''">Guardar</button>
                        
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
         
        <!--fin del modal-->
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
//Vue.use(VeeValidate);
export default {
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
        //-------------------
    data() {
        return {
            //---correo  
            id_credencial:'',      
            host:'',
            correo:'',
            puerto:'',
            usuario:'',
            contraseña:'',
            ssl:0, 
            credenciales_correo:[],
            nit:'',
            nombre_empresa:'',
            celular:'',
            selectTipoFac:0,  
            actividad_eco:'',
            arrayTipofac: [{ id: 1, tipo: "Facturacion en linea" },{ id: 2, tipo: "Dosificacion" }],
//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
                validador_variables:0,

                arrayMoneda:[],
                selectMoneda:0,
                pais:'',
                estado_cambio_moneda:0,
                nombre_pais:'',

                arrayBanco:[],
                banco_nombre:'',
                nombrebanco_0:'',
                id_banco:'',
        };
    },

   

    computed: {
      
        isActived: function () {
            return this.pagination.current_page;
        },

        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },


    
    watch: {
        selectMoneda: function (newValue) {
            
           
                let p = this.arrayMoneda.find(
                    (element) => element.id_nacionalidad_pais === newValue,
                );

                if (p) {
                    this.pais =p.pais;
                    
                } 
            
        },
    },
    methods: {
          //-----------------------------------permisos_R_W_S        
    listarPerimsoxyz() {
                //console.log(this.codventana);
    let me = this;
   
        
    var url = '/gestion_permiso_editar_eliminar?win='+me.codventana;
  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;
            console.log(respuesta);
            if(respuesta=="root"){
            me.puedeEditar=1;
            me.puedeActivar=1;
            me.puedeHacerOpciones_especiales=1;
            me.puedeCrear=1; 
            }else{
            me.puedeEditar=respuesta.edit;
            me.puedeActivar=respuesta.activar;
            me.puedeHacerOpciones_especiales=respuesta.especial;
            me.puedeCrear=respuesta.crear;        
            }
           
        })
        .catch(function(error) {
            error401(error);
            console.log(error);
        });
},
//-------------------------------------------------------------- 



        update_tipo_venta(data){
            let me= this;
            console.log(me.id_credencial+"  "+data);
            axios
                .put("/credenciales_correo/tipo_venta_update", {
                    id: me.id_credencial,                   
                    validador_variables:data,    
                    
                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"cambio de tipo de venta",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                    me.selectTipoFac=0;
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
                .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
        },

    update_datos_empresa(){
            let me = this; 
            if (me.nit===""||me.nombre_empresa===""||me.celular===""||me.actividad_eco==="") {
                Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "datos nulos",

});
            }else{
                axios
                .post("/credenciales_correo/update_datos_empresa", {
                    id: me.id_credencial,                   
                    nit:me.nit,
                    nombre_empresa:me.nombre_empresa,
                    celular:me.celular,
                    actividad_eco:me.actividad_eco,                  

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"actualziacion datos de empresa",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
                .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }

        },
        
        listarTipomoneda(){
            let me = this;        
            var url = "/credenciales_correo/tipo_moneda";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayMoneda = respuesta;
                    console.log( me.arrayMoneda);
                    let p = me.arrayMoneda.find(
                    (element) => element.id_nacionalidad_pais === me.estado_cambio_moneda);
                if (p) {
                    me.nombre_pais =p.pais;                    
                } else {
                    me.nombre_pais ="0";  
                }
                    console.log(me.nombre_pais);           
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        update_credecial_correo() {
            let me = this;
            
            if (me.host==""||me.correo==""||me.puerto==""||me.usuario==""||me.contraseña=="") {
                
                    Swal.fire({
  icon: "error",
  title: "Oops...",
  text: "datos nulos",

});
            }else{
               
            axios
                .post("/credenciales_correo/update", {
                    id: me.id_credencial,                   
                    host:me.host,
                    correo:me.correo,
                    puerto:me.puerto,
                    usuario:me.usuario,
                    contraseña:me.contraseña,
                    ssl:me.ssl,

                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"actualziacion de credencial correo",  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
               
                .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }
            

            
        
        },
   
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        cambioDeMoneda(id){
            let me=this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });         
                
            swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de moneda?",
                    text: "Tendra un cambio en tipo de moneda.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("/credenciales_correo/tipomonedaUpdate", {
                    id: me.id_credencial,                   
                    id_moneda:id,    
                    
                    id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"cambio de tipo de venta id moneda"+id,  
                  
                })
                .then(function (response) {
                    me.listarCredencial();
                    me.nombre_pais=me.pais;
                    me.selectMoneda=0;
                  //  me.estado_cambio_moneda=0;
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                //.catch(function (error) {
                //    error401(error);
                //});
                .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
                      
                    } else if (
                        /* Read more about handling dismissals below */
                       
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
            
        },

        cambioEstado(id){
            let me=this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            if (id===1) {
                
            swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de facturación?",
                    text: "Se cambiara a facturacion en liena",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        me.validador_variables=1;
                        me.update_tipo_venta(1);
                    } else if (
                        /* Read more about handling dismissals below */
                       
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
            } else {
                if (id===2) {
                    swalWithBootstrapButtons
                .fire({
                    title: "¿Esta Seguro de cambiar el tipo de facturación?",
                    text: "Se cambiara a Dofisificacion",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        me.validador_variables=2;
                        me.update_tipo_venta(2);
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        me.selectTipoFac=0;
                        /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                    }
                });
                } else {
                    me.selectTipoFac=0,
                    swalWithBootstrapButtons.fire(
                                    "Error!",
                                    "Esta haciendo procesos o una mala manipulación del sistema",
                                    "error",
                                );
                }
            }
        },

         listarBanco(){
            let me = this;
            var url = "/getBancos";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayBanco=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });  
         },   

         crearBanco(){
            let me = this;
            if (me.banco_nombre===""||me.banco_nombre===null) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                axios.post("/credenciales_correo/crear_banco", {
                    nombre: me.banco_nombre,                 
                })
                .then(function (response) {
                    me.listarBanco(); 
                    me.banco_nombre=""; 
                    Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );              
                })               
                .catch(function (error) {                
                    Swal.fire(
                    "Error",
                    ""+error.response.data, // Muestra el mensaje de error en el alert
                    "error" );                
            });
            }
         },

         editarBanco(){
            let me = this;
            if (me.banco_nombre===""||me.banco_nombre===null) {
                Swal.fire(
                    "Error",
                    "Campo nulo o vacío", 
                    "error"
                );
            } else {
                axios.put("/credenciales_correo/editar_banco", {
                    id:me.id_banco,
                    nombre: me.nombrebanco_0,                 
                })
                .then(function (response) {
                    me.listarBanco(); 
                    me.banco_nombre=""; 
                    Swal.fire(
                        "Registro editado!",
                        "Correctamente",
                        "success",
                    );
                    me.cerrarModal("regbanco");             
                })               
                .catch(function (error) {                
                    Swal.fire(
                    "Error",
                    ""+error.response.data, // Muestra el mensaje de error en el alert
                    "error" );                
            });
            }
         },

 listarCredencial() {
            let me = this;
            var url = "/credenciales_correo";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                  
                    me.id_credencial=response.data[0].id;
                    me.host=response.data[0].host;                   
                   me.correo=response.data[0].correo;
                    me.puerto=response.data[0].puerto;
                    me.usuario=response.data[0].usuario;
                    me.contraseña=response.data[0].contraseña;
                    me.ssl=response.data[0].ssl;               
                    me.nit=response.data[0].nit;
                    me.nombre_empresa=response.data[0].nom_empresa;
                    me.celular=response.data[0].nro_celular;
                    me.validador_variables=response.data[0].factura_dosificacion === null ? 0:response.data[0].factura_dosificacion;
                    me.actividad_eco=response.data[0].actividad_economica;
                    me.estado_cambio_moneda=response.data[0].moneda;
             
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
        //    me.listarAjusteNegativos(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "regbanco": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Edicion de nombre de banco";
                    me.nombrebanco_0= (data.nombre).toUpperCase();
                    me.id_banco=data.id;
                    me.classModal.openModal("regbanco");
                    break;
                }           
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "regbanco") {
                me.tituloModal = "";
                me.nombrebanco_0="";
                me.id_banco="";
                me.classModal.closeModal(accion);
               
           
            }
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
            //-----------------------
        this.listarCredencial();
        this.classModal.addModal("regbanco");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
