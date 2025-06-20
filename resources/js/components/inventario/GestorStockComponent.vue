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
                    <i class="fa fa-align-justify"></i> Gestor de sctock               
                    <button type="button" class="btn btn-secondary" style="color:white;" @click="abrirModal('registrar');iniciarOperacio();" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                        <i class="icon-plus"></i>&nbsp;Generar
                    </button>&nbsp;
                    <button type="button" class="btn btn-info"  style="color:white;" @click="abrirModal('registrar');" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary"  @click="abrirModal('registrar');" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                       <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
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
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row">
                <div class="col-md-2" >
                     <label for="">&nbsp;</label>
                </div>
    
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
     
         <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">Usar fecha:</label>
            <button type="button" class="btn btn-success mt-1" style="color: white;">
                <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>

        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">No usar fecha:</label>
            <button type="button" class="btn btn-warning mt-1" style="color: white;">
                <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>


  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th>Nro</th>
                        <th>Distribuidor</th>
                        <th>Fecha de pedigo</th>
                        <th>Total</th>
                        <th>Fecha de pago</th>
                        <th>Formato de pago</th>
                        <th>Turno de entrega</th>
                        <th>Plazo</th>
                        <th>Obs.</th>
                        <th>Tipo</th>
                        <th>Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>
            </table>    

            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
         <transition name="fade">
  <div v-if="showModal" class="modal d-block fullscreen-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ tituloModal }}</h4>
          <button type="button" class="close" @click="cerrarModal('registrar')">
            <span>Cerrar</span>
          </button>
        </div>
        <div class="modal-body">     
    <div class="card-header">
        <br>
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Opciones:            
                    
                    <button type="button" class="btn btn-info"  style="color:white;">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary"   >
                       <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
                    </button>
             
                </div>
                <br>
                 <div class="form-group row">
                  
                <div class="col-md-2" style="text-align: center">
                     <label for="">Distribuidor:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
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
                               
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             
          <!-- Formulario -->
          <form class="form-horizontal">
            <div class="container-fluid">
              <div class="form-group row">
                <table class="table table-bordered table-striped table-sm table-responsive" >
                    <thead>
                        <tr>
                            <th>Linea</th>
                            <th>Producto</th>
                            <th>Ciclo de Stock</th>
                            <th>Consumo promedio mensual</th>
                            <th>Plazo de entrega</th>
                            <th>Consumo promedio venta</th>
                            <th>Stock Maximo</th>
                            <th>Stock Medio</th>
                            <th>Stock Actual</th>
                            <th>Stock pedido</th>
                            <th>Indice de rotacion</th>
                            <th>Indice de cobertura</th>
                            <th>Rentabilidad</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="i in arrayModalOperacionGestor" :key="i.id_producto">
                            <td>{{i.linea}}</td>
                            <td>{{i.producto}}</td>
                            <td>{{i.consumo_mensual}}</td>
                            <td>{{i.plazo}}</td>
                            <td>{{i.consumo_dia}}</td>
                            <td>{{i.stmax}}</td>
                            <td>{{i.stmedio}}</td>
                            <td>{{i.stock_total}}</td>
                            <td>{{i.indicerot}}</td>
                            <td>{{i.indicecober}}</td>
                            <td>{{i.rentabilidad}}</td>
                           
                        </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </form>
        </div>

        <div class="card-header">
            <div class="container">                               
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input">
                     <strong>Distribuidor:</strong>                                
                    </label>
                        <div class="col-md-5 input-group mb-3">
                        <VueMultiselect
                        v-model="selected"
                        
                        :options="arrayDistribuidor"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado">
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                        </div>
                        <div class="col-md-5 input-group mb-3">
<button type="button" class="btn" style="background-color: orange; color: white;">Stock alerta</button>&nbsp;
<button type="button" class="btn" style="background-color: yellow; color: black;">Stock minimo</button>&nbsp;
<button type="button" class="btn" style="background-color: red; color: white;">Stock cero</button>&nbsp;
<button type="button" class="btn btn-secondary">Cerrar modal</button>
                        </div>
                </div>        
            </div>
        </div>    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
            Cerrar
          </button>
          <button type="button" v-if="tipoAccion == 1" class="btn btn-primary">
            Guardar
          </button>
          <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">
            Actualizar
          </button>
        </div>
      </div>
    </div>
  </div>
</transition>

        <!--fin del modal-->
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';

export default {
     components: { VueMultiselect},
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
     
            showModal: false,
            //offset:3,
  selected:null,
            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',

            arrayDistribuidor:[],

            arrayModalOperacionGestor:[],
        };
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

        iniciarOperacio(){
            let me=this;
            me.listarModalGestorOperacion();
            me.listarDistribuidor();
        },

        listarModalGestorOperacion(){
            
            let me=this;
            me.arrayModalOperacionGestor=[];
            var url = "/gestor-stock/listarGestorStockModal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayModalOperacionGestor = respuesta;
                    console.log("*******************");
                    console.log(respuesta);
                 console.log("*******************");
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });

        },

        sucursalFiltro() {
            let me = this;
      
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
       
        nameWithLang ({nom_a_facturar,nom_linea_array}) {            
            return `Dist: ${nom_a_facturar} Linea: ${nom_linea_array}`
          },

        listarDistribuidor() {
            let me = this;       
           var url = "/gestor-stock/listarDistribuidor";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayDistribuidor = respuesta;
                    console.log(me.arrayDistribuidor);
                 
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
              
                    me.tituloModal = "Ventana gestor de stock";
                    me.showModal = true;
                    me.selected0null;
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

        fecha_inicial(){
         
            // Obtener la fecha actual
    const today = new Date();
    // Obtener el año, mes y día actual
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const day = String(today.getDate()).padStart(2, '0');

    // Asignar la fecha del primer día del mes al input de fecha de inicio
    this.startDate = `${year}-${month}-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`;
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";            
               me.selected=null;
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
        this.sucursalFiltro();
        this.fecha_inicial();
        this.classModal.addModal("registrar");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
<style scoped>
.fullscreen-modal .modal-dialog {
  max-width: 100%;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.fullscreen-modal .modal-content {
  height: 100%;
  border-radius: 0;
}
</style>

<style scoped>
.modal {
  transition: opacity 0.5s ease;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active en versiones de Vue < 2.1.8 */ {
  opacity: 0;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>