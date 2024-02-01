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
                               Traspaso:
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
                                 >                                           
                                      <i class="fa fa-search"></i>                                            
                                  </button>  
                                
                                
                               </div>
                                  <!-- debe ingresar los datos de para asignar datos del array-->
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
       
    },

    computed: {
      //  sicompleto() {
      //      let me = this;
       //     if (
          
     //           me.glosa != "" &&
     //           me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
      //  },
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

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso: "+me.razon_socialAlmTienda;
                    
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
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.classModal.addModal("registrar");
        this. listarTraspaso();
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
