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

                                    <label class="col-md-1 form-control-label" for="text-input"><strong>Host:</strong> 
                                    <span v-if="host == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="host" class="form-control" placeholder="mail.empresa.com" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir el host</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input"><strong>Correo:</strong> 
                                    <span v-if="correo == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="correo" class="form-control" placeholder="correo@correo.es" v-on:focus="selectAll"/>
                                        <span v-if="correo == ''" class="error">Debe escribir un correo</span>
                                    </div>

                                    <label class="col-md-1 form-control-label" for="text-input"><strong>Puerto:</strong> 
                                    <span v-if="puerto == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="number" min="0" v-model="puerto" class="form-control" placeholder="" v-on:focus="selectAll"/>
                                        <span v-if="host == ''" class="error">Debe escribir un puerto</span>
                                    </div>
                                </div>
                                <div class="form-group row">

<label class="col-md-1 form-control-label" for="text-input"><strong>Usuario:</strong> 
<span v-if="usuario == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="text" v-model="usuario" class="form-control" placeholder="puede ser un correo" v-on:focus="selectAll"/>
    <span v-if="host == ''" class="error">Debe escribir el usuario</span>
</div>

<label class="col-md-1 form-control-label" for="text-input"><strong>Contraseña:</strong> 
<span v-if="contraseña == ''" class="error">(*)</span></label>
<div class="col-md-3">
    <input type="password" v-model="contraseña" class="form-control"  v-on:focus="selectAll"/>
    <span v-if="contraseña == ''" class="error">Debe escribir una contraseña</span>
</div>

<label class="col-md-1 form-control-label" for="text-input"><strong>SSL:</strong> 
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
       
        <button v-if="puedeEditar==1" type="button" class="btn btn-warning" style="color: white;"  @click="update_credecial_correo();">Actualizar</button>
        <button v-else type="button" class="btn btn-light"  >Actualizar</button>
   
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

                                    <label class="col-md-1 form-control-label" for="text-input"><strong>NIT:</strong> 
                                    <span v-if="nit == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="nit" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="nit == ''" class="error">Debe escribir el nit</span>
                                    </div>
                                      
                                    <label class="col-md-1 form-control-label" for="text-input"><strong>NOMBRE DE EMPRESA:</strong> 
                                    <span v-if="nombre_empresa == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="nombre_empresa" class="form-control" placeholder="nombre de negocio" v-on:focus="selectAll"/>
                                        <span v-if="nombre_empresa == ''" class="error">Debe escribir el un nombre de negocio</span>
                                    </div>
                                    <label class="col-md-1 form-control-label" for="text-input"><strong>TELEFONO/CELULAR:</strong> 
                                    <span v-if="celular == ''" class="error">(*)</span></label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="celular" class="form-control" placeholder="nit de la empresa" v-on:focus="selectAll"/>
                                        <span v-if="celular == ''" class="error">Debe escribir telefono o celular</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
    </div>
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
     props: ['codventana'],
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

//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            
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
                .put("/credenciales_correo/update", {
                    id: me.id_credencial,                   
                    host:me.host,
                    correo:me.correo,
                    puerto:me.puerto,
                    usuario:me.usuario,
                    contraseña:me.contraseña,
                    ssl:me.ssl
                  
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
   
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

 listarCredencial() {
            let me = this;
            var url = "/credenciales_correo";
            axios
                .get(url)
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
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso origen ";
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }
            
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
               
                    me.tituloModal = " ";
             
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
              
            } else {
                me.classModal.closeModal(accion);
              
                me.classModal.openModal("registrar");
            }
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();
            //-----------------------
        this.listarCredencial();
        
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
