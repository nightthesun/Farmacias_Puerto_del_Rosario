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
                    <i class="fa fa-align-justify"></i> Recepcion               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');listarTraspaso();"
                        :disabled="selectAlmTienda == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="selectAlmTienda == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                <div class="col-md-6">
                            <div class="input-group">
                                <select
                                    class="form-control"
                                  v-model="selectAlmTienda"
                                >
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option
                                        v-for="sucursal in arrayAlmTienda"
                                        :key="sucursal.id"
                                        :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
                                            +'...('+sucursal.veces_repetido+')'
                                        "
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

            </div>
        </div>
            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            arial-labelledby="myModalLabel"
            id="registrar"
            aria-hidden="true"
            data-backdrop="static"
            data-key="false" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button
                            type="button"
                            class="close"
                            aria-label="Close"
                            @click="cerrarModal('registrar')"
                        >
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">
                              
                              <label class="col-md-3 form-control-label" for="text-input">
                              <strong>Traspaso:</strong> 
                                  <span v-if="selectTraspaso == '0'" class="error">(*)</span>
                              </label>
                              <div class="col-md-7 input-group mb-3">
                                  <select v-model="selectTraspaso" v-if="tipoAccion==1"
                                      class="form-control">
                                      <option v-bind:value="0" disabled>
                                          Seleccionar...
                                      </option>
                                      <option
                                          v-for="traspasoI in arrayTraspaso"
                                          :key="traspasoI.id"
                                          v-bind:value="traspasoI.id"
                                          v-text="'Nro:'+traspasoI.numero_traspaso +
                                              ' | Des: ' +traspasoI.name_des +
                                              ' | ' +traspasoI.leyenda +
                                              ' | C: ' + traspasoI.cantidad  "
                                      ></option>
                                  </select>
                                  <button class="btn btn-primary" 
                                  v-if="tipoAccion == 1"
                                  type="button" id="button-addon1"
                                  @click="abrirModal('bucarProductoIngreso');listarRetornoTraspaso();">                                           
                                                                                
                                      <i class="fa fa-search"></i>                                            
                                  </button>  
                                
                                
                               </div>
                                  <!-- debe ingresar los datos de para asignar datos del array-->
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Origen: <span>{{ origen }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Numero de traspaso: <span>{{ numero_traspaso }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Producto: <span>{{ leyenda }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Cantidad: <span>{{ cantidad }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Operario de logística: <span>{{ nom_completo }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Vehiculo: <span>{{ name_vehiculo }}</span></strong>   
                                  </div>
                                  <div class="form-group col-sm-4" v-if="selectTraspaso!=''">
                                    <strong>Estado: 
                                        <span v-if="estado==1" style="color: green;">Activo</span>
                                        <span v-else style="color: red;">Sin Accion</span>
                                    </strong>   
                                  </div>
          
                            
                                </div>
                                <div class="form-group row" v-if="selectTraspaso!='' &&estado==1 ">
                                      <label class="col-md-3 form-control-label" for="text-input"><strong>Observación: </strong> 
                                        <span v-if="observacion == ''" class="error">(*)</span>
                                      </label>
                                      <div class="col-md-7">
                                        <textarea id="" name="" v-model="observacion" class="form-control" placeholder="Debe ingresar una observación"></textarea>
                                        </div>
                                  </div>  
                     
                                  <div class="row justify-content-center" v-if="selectTraspaso!='' &&estado==1 ">
                                    <input type="checkbox" id="checkbox" v-model="checked" hidden/>
                                    <label for="checkbox" v-if="checked === false" style="background-color: rgb(51, 118, 145); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Aceptar</strong>
                                     </label>
                                     <label for="checkbox" v-else style="background-color: rgb(122, 30, 45); color: white; border: 1px solid #ccc; padding: 8px; border-radius: 4px;">
                                     <strong>Deshacer</strong>
                                     </label>
                                  </div>  
                              </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarModal('registrar')"
                        >
                            Cerrar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 1 && estado==1"
                            class="btn btn-primary"
                           
                            :disabled="!sicompleto"
                        >
                            Guardar
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            class="btn btn-primary"
                          
                        >
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--fin del modal-->
         <!-- Modal para la busqueda de producto por lote -->
 <div class="modal fade" id="staticBackdrop" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-primary">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Busqueda de Traspasos</h5>
                    <button type="button" @click="cerrarModal('staticBackdrop')" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Introdusca una loque quiere buscar: </label>
                            <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" 
                            v-model="inputTextBuscar"
                               @input="listarRetornoTraspaso()">
                            <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                        </div>
                        <div>
                            <table class="table table-hover" id="tablaProductosIngreso"  style='height:350px;display:block;overflow:scroll'>
                                    <thead>
                                        <tr>
                                            <th scope="col">Nro Traspaso.</th>
                                            <th scope="col">Descripcion Prod.</th>
                                            <th scope="col">Cantidad.</th>
                                       </tr>
                                    </thead>
                                    <tbody>  
                                        <tr v-for="RetornarProductosIngreso  in arrayRetornarTraspaso" :key="RetornarProductosIngreso.id" @click="abrirModal('registrar',RetornarProductosIngreso);listarRetornoTraspaso();" >
                                        <td v-text="RetornarProductosIngreso.numero_traspaso"></td>
                                        <td v-text="RetornarProductosIngreso.leyenda"></td>
                                        <td v-text="RetornarProductosIngreso.cantidad"></td>
                                      </tr>
                                    </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    
                 
                    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" data-bs-dismiss="modal" @click="cerrarModal('staticBackdrop');abrirModal('registrar');" >Cerrar</button>
     
                    
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
                </div>
            </div>
     </div>
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
//Vue.use(VeeValidate);
export default {
    data() {
        return {
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
          

            tituloModal: "",
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,
            cod_alm_tienda:"",
            razon_socialAlmTienda:"",
            selectTraspaso:0,
            arrayTraspaso:[],
            inputTextBuscar:"",
            arrayRetornarTraspaso:[],
            tipoEvento:1,
            ////////////
            id_traspaso:"",
            numero_traspaso:"",
            leyenda:"",
            origen:"",
            cantidad:"",
            id_traslado:"",
            nom_completo:"",
            name_vehiculo:"",
            estado:"",
            observacion:"",
            checked:"",
            id_ingreso:"",
            cod_1:"",
            cod_2:"",

        };
    },

    watch:{
        selectAlmTienda: function(cod){
           
            let tiendaOalmacenSeleccionado = this.arrayAlmTienda.find(
                    (element) => element.codigo === cod);
                    if (tiendaOalmacenSeleccionado) {                        
                        this.cod_alm_tienda=tiendaOalmacenSeleccionado.codigo;
                        this.razon_socialAlmTienda=tiendaOalmacenSeleccionado.razon_social;
                       
                    }
        },
        selectTraspaso: function(newValue){
            let traspasO = this.arrayTraspaso.find(
                    (element) => element.id === newValue,
                );
              if(traspasO){              
                this.id_traspaso=traspasO.id;
                this.numero_traspaso=traspasO.numero_traspaso;            
                this.leyenda=traspasO.leyenda;
                this.origen=traspasO.name_ori; 
                this.cantidad=traspasO.cantidad;  
                this.id_traslado=traspasO.id_traslado;  
                this.nom_completo=traspasO.nom_completo;    
                this.name_vehiculo=traspasO.name_vehiculo;   
                this.estado=traspasO.estado;   
                this.observacion="";
                this.checked=false;                   
                this.id_ingreso=traspasO.id_ingreso;            
                this.cod_1=traspasO.cod_1;
                this.cod_2=traspasO.cod_2;
                     
              }  
        }   
    },

    computed: {
      sicompleto() {
      let me = this;
           if (
          
               me.selectTraspaso != "" &&
               me.observacion != "" &&
               me.checked!=false
           )
             return true;
           else return false;
       },
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
        listarRetornoTraspaso() {
            let me = this;
                var url ="/recepcion/listarRetornoTraspaso?codigo=" + me.cod_alm_tienda +
                    "&input=" + me.inputTextBuscar;
                         
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayRetornarTraspaso = respuesta;   })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        listarTraspaso() {
            if (this.cod_alm_tienda!="") {
                let me = this;            
     
            var url = "/recepcion/listarTraspaso?codigo="+me.cod_alm_tienda;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTraspaso = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });  
            } 
        },
        listarAlmTienda() {
            let me = this;
            var url = "/recepcion/listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmTienda = respuesta;
                 
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
        registrar(){
            let me =this;
         //   console.log("id_tras:"+me.selectTraspaso+" id_empleado"+me.selectUsuario+" id_ve:"+me.selectVehiculo+" time:"+me.estimacion+" obs:"+me.observacion);    
            if (me.selectTraspaso===0 || me.observacion === "" || me.checked===false
          
            ) {
                Swal.fire(
                    "No puede ingresar valor vacios o verifique si hizo click en acepto?",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                axios
                    .get("/recepcion/registrar", {
                        'id_tralado': me.id_traslado,
                        'id_ingreso':me.id_ingreso,
                        'cod_1':me.cod_1,
                        'cod_2':me.cod_2,                
                       'observacion':me.observacion,   
                             
                        'activo': 1,
                    
                     
                    }).then(function(response){
                        me.cerrarModal('registrar');
                        me.listarAlmTienda();
                        const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                        confirmButton: "btn btn-info",
                        cancelButton: "btn btn-light",
                    },
                    buttonsStyling: false,
                });

                swalWithBootstrapButtons
                .fire({
                    title: "Se registro con exito",
                    text: "¿Desea añadir mas items?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Si, Añadir",
                    cancelButtonText: "No, Gracias",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {                       
                        me.listarTraslado();
                        me.abrirModal('registrar');
                        me.listarVehiculo(me.cod_alm_tienda);
                     
                        me.listarTraspaso();                        
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        me.listarTraslado();               
                    }
                });
                   
                  //  me.listarAlmTienda();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

                

            }
        }, 
        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso: "+me.razon_socialAlmTienda;
                    if(this.tipoEvento==1){
                    me.selectTraspaso=0;
                   }
                   if(this.tipoEvento==2){
                    me.selectTraspaso=data.id=== null ?0:data.id;
                   }
                   //
                   me.id_traspaso="";
                   me.numero_traspaso="";
                   me.leyenda="";
                   me.origen="";
                   me.cantidad="";
                   me.id_traslado="";
                   me.nom_completo="";
                   me.name_vehiculo="";
                   me.estado="";
                   me.observacion="";
                   me.checked=false;

                   me.id_ingreso="";            
                   me.cod_1="";
                   me.cod_2="";

                   me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }
                case 'bucarProductoIngreso':{
                    me.tipoEvento=2;  
                    me.inputTextBuscar= "";
                    //me.arrayRetornarTraspaso = [];
                    me.classModal.openModal('staticBackdrop');
                    break;
                }
            
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.tipoEvento=1;
                me.tipoAccion = 1;
                me.selectTraspaso=0;
                    me.tituloModal = " ";
                    me.id_traspaso="";
                   me.numero_traspaso="",
                   me.leyenda="",
                   me.origen="",
                   me.cantidad="",
                   me.id_traslado="",
                   me.nom_completo="",
                   me.name_vehiculo="",
                   me.estado="",
                   me.observacion="",
                   me.checked="",
                   me.id_ingreso="";            
                   me.cod_1="";
                   me.cod_2="";
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
              
            } else {
                me.classModal.closeModal(accion);
                me.tipoEvento=1;
                me.selectTraspaso=0;
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
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.classModal.addModal("registrar");
        this. listarTraspaso();
        this.classModal.addModal("staticBackdrop");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
