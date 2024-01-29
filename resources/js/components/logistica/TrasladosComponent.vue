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
                    <i class="fa fa-align-justify"></i> Traslados         
                    <button type="button" class="btn btn-secondary" 
                        @click="abrirModal('registrar');listarVehiculo(cod_alm_tienda);listarTraspaso();"
                        :disabled="selectAlmTienda == 0">
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
                                     Traspaso:
                                        <span v-if="selectTraspaso == '0'" class="error">(*)</span>
                                    </label>
                                    <div class="col-md-7 input-group mb-3">
                                        <select v-model="selectTraspaso"
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
                                        <button class="btn btn-primary" type="button" id="button-addon1"
                                        @click="abrirModal('bucarProductoIngreso');listarRetornoTraspaso();">                                           
                                            <i class="fa fa-search"></i>                                            
                                        </button>                                     
                                        
                                    </div>
                                        <!-- debe ingresar los datos de para asignar datos del array-->
                                </div>
                              
                                <div class="form-group row" v-if="selectTraspaso!=''"> 
                                    <label class="col-md-3 form-control-label" for="text-input">
                                    Persona a enviar:    
                                    <span v-if="selectUsuario == '0'" class="error">(*)</span>
                                    </label>
                                    <div class="col-md-7">
                                        <select
                                            name=""
                                            id=""
                                            v-model="selectUsuario"
                                            class="form-control"
                                        >
                                            <option value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                            v-for="usu in arrayUsuario"
                                        :key="usu.emple_id"
                                        :value="usu.emple_id"
                                        v-text="usu.nom_completo"></option>
                                        </select>
                                        <span
                                            v-if="selectUsuario == 0"
                                            class="error"
                                            >Debe seleccionar una opcion</span
                                        >
                              
                                    </div>
                                </div>

                                <div class="form-group row" v-if="selectTraspaso!=''"> 
                                    <label class="col-md-3 form-control-label" for="text-input">
                                    Vehiculo:    
                                    <span v-if="selectVehiculo == '0'" class="error">(*)</span>
                                    </label>
                                    <div class="col-md-7">
                                        <select
                                            name=""
                                            id=""
                                            v-model="selectVehiculo"
                                            class="form-control"
                                        >
                                            <option value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option
                                            v-for="ve in arrayVehiculo"
                                        :key="ve.id_vehiculo"
                                        :value="ve.id_vehiculo"
                                        v-text="'Matricula: '+ve.matricula+'| Tipo:'+ve.tipo_vehiculo"
                                        ></option>
                                        </select>
                                        <span
                                            v-if="arrayVehiculo == 0"
                                            class="error"
                                            >Debe seleccionar una opcion</span
                                        >
                              
                                    </div>
                                </div>
                                <div class="form-group row" v-if="selectTraspaso!=''">
                                      <label class="col-md-3 form-control-label" for="text-input">Tiempo estimado: 
                                       
                                      </label>
                                      <div class="col-md-7">
                                         <input type="time" id="" name="" v-model="estimacion" class="form-control" placeholder="Debe ingresarun tiempo estimado " >
                                    
                                        </div>
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
                            v-if="tipoAccion == 1"
                            class="btn btn-primary"
                            @click="registrar()"
                            
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
                            <label for="exampleInputEmail1" class="form-label">Introduzca el codigo Internacional: </label>
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
           
            selectTraspaso:0,
            arrayTraspaso:[],
            cod_alm_tienda:"",
            razon_socialAlmTienda:"",
            selectUsuario:0,
            arrayUsuario:[],
            selectVehiculo:0,
            arrayVehiculo:[],
            inputTextBuscar:"",
            arrayRetornarTraspaso:[],
            tipoEvento:1,
            estimacion:"",
            observacion:"",
          
                       
            
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
        } 
    },
   

    computed: {
        sicompleto() {
        let me = this;
        if (me.selectTraspaso != 0 && me.selectUsuario !=0 &&me.arrayVehiculo!=0)
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
                var url ="/traslado/listarRetornoTraspaso?codigo=" + me.cod_alm_tienda +
                    "&input=" + me.inputTextBuscar;
                    console.log(url);        
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
        listarVehiculo(cod) {
            if (cod!==undefined) {
                let me = this;
            
            var url = "/traslado/listarVehiculo?codigo="+cod;
            console.log(url);
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayVehiculo = respuesta;
             
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });   
            }
            
        },
        listarAlmTienda() {
            let me = this;
            var url = "/traslado/listarSucursal";
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
        listarTraspaso() {
            if (this.cod_alm_tienda!="") {
                let me = this;            
     
            var url = "/traslado/listarTraspaso?codigo="+me.cod_alm_tienda;
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
        listarUsuario() {
            let me = this;
            var url = "/traslado/listarUsuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta;
                 
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
            //console.log("id_tras:"+me.selectTraspaso+" id_empleado"+me.selectUsuario+" id_ve:"+me.selectVehiculo+" time:"+me.estimacion);    
            if (me.selectTraspaso==="" || me.selectUsuario === "" ||
            me.selectVehiculo ==="" || me.estimacion===""
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                axios
                    .post("/traslado/registrar", {
                        'id_traspaso': me.selectTraspaso,
                        'id_empelado': me.selectUsuario,
                        'id_vehiculo': me.selectVehiculo,
                       'time':me.estimacion, 
      
                     'lote':me.lote, 
                        'activo': 1,
                        'id_sucursal': me.id_sucursal,
                        'id_producto': me.id_producto,
                        'cod': me.sucursalSeleccionada,
                        'id_ingreso': me.id_ingreso,
                        'leyenda': me.leyenda,
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );

                        me.listarAjusteNegativos();
                        me.sucursalFiltro();
                    })
                    .catch(function (error) {
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
                    me.tituloModal = "Nombre de traspaso origen: "+me.razon_socialAlmTienda;
                   if(this.tipoEvento==1){
                    me.selectTraspaso=0;
                   }
                   if(this.tipoEvento==2){
                    me.selectTraspaso=data.id=== null ?0:data.id;
                   }
                    me.selectUsuario=0;
                    me.selectVehiculo=0;
                    me.estimacion="";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }
                case 'bucarProductoIngreso':
                    {     
                      me.tipoEvento=2;  
                        me.inputTextBuscar= "";
                      //  me.arrayRetornarTraspaso = [];
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
                    me.tituloModal = " ";
                    me.selectTraspaso=0;
                    me.selectUsuario=0;
                    me.selectVehiculo=0;
                    me.tipoAccion = 1;
                    me.estimacion="";
                    setTimeout(me.tiempo, 200); 
     
              
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
        this.listarTraspaso();
        this.listarUsuario();
        this.listarVehiculo();
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
