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
                    <i class="fa fa-align-justify"></i> Homologación               
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');listarProducto_homo();">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>          
                </div>
        <div class="card-body">
            <div class="form-group row">
               
                        <div class="col-md-4">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Cliente</th>
                        <th class="col-md-5">Nro docuemnto</th>
                        <th>Tipo de comprobante</th>
                        <th>Numero de comprobante</th>
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Destino</th>
                        <th>Vehiculo</th>
                        <th class="col-md-3">Observación</th>
                        <th class="col-md-2">Per. Enviada</th>
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
                                    <div class="col-md-2 input-group mb-3">
                                        Actividad:
                                    </div>
                                <div class="col-md-10 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selected_modal"
                        :options="array_modal"
                        :max-height="300"                                    
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="descripcion"                                            
                        track-by="id"
                        class="w-1000"                        
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>           
                     </div>
                                   
        
                                </div>
                                <div class="form-group row" v-show="selected_modal!=null">
                                    <div class="col-md-2 input-group mb-3">
                                        <span>codigoProducto:</span>
                                    </div>
                                    <div class="col-md-4 input-group mb-3">
                                        <strong>{{selected_modal?.codigo}}</strong>
                                    </div>
                                    <div class="col-md-2 input-group mb-3">
                                        <span>codigoActividad:</span>
                                    </div>
                                    <div class="col-md-4 input-group mb-3">
                                        <strong>{{selected_modal?.id_erp}}</strong>
                                    </div>
                                </div> 
                                <hr>
                                <div class="form-group row"  v-show="selected_modal!=null">
                                    <div class="col-md-2 input-group mb-3">
                                        <span>Modo:</span>
                                    </div>
                                   
                                    <div class="col-md-10 input-group mb-3" >
                                        <select class="form-control"  v-model="selectModo_vmodal" @change="cambioManuAuto(selectModo_vmodal)">
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option value="1">Automatico</option>
                                        <option value="2">Manual</option>
                                       
                                     
                                    </select>   
                                    </div>
                                  

                                </div> 
                                <hr>
                                <div class="alert alert-warning" role="alert" v-show="selectModo_vmodal==='1'" >
  Los registros se cargaran todos sin tener que seleccionar ninguno!!!
</div>  
                                <div class="form-group row"  v-show="selected_modal!=null && selectModo_vmodal==='2'">
                                    <div class="col-md-2 input-group mb-3">
                                        <span>Producto:</span>
                                    </div>
                                   
                                    <div class="col-md-10 input-group mb-3" >
                                        <select class="form-control"  v-model="selectProd"  size="5" @click="añadirArray(selectProd)">
                                        <option value="0" disabled selected>Seleccionar...</option>
                                        <option  v-for="t in array_prod" :key="t.id" :value="t.id">{{t.codigo+" "+t.nombre}}</option>
                                     
                                    </select>   
                                    </div>
                                  

                                </div> 
                              
                                <table class="table table-bordered table-striped table-sm table-responsive" v-show="arrayFalso.length>0">
                            <thead>
                                 <tr>
                                    <th class="col-md-1" style="font-size: 13px; text-align: center">Opción</th>                                  
                                    <th class="col-md-3" style="font-size: 13px; text-align: center">Codigo</th>
                                    <th class="col-md-8" style="font-size: 13px; text-align: center">Nombre</th>                                                    
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="u in arrayFalso" :key="u.id">                                   
                                    <td class="col-md-1" style="font-size: 13px; text-align: center"><button type="button" @click="quitarDatos(u.id)" class="btn btn-danger btn-sm"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
                                    <td class="col-md-3" style="font-size: 13px; text-align: center">{{ u.codigo }}</td>
                                    <td class="col-md-8" style="font-size: 13px; text-align: center">{{ u.nombre }}</td>
                                </tr>
                            </tbody>   
                        </table> 
                            </div>
                         
                        </form>
                     
                        
                   
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-secondary " v-if="selected_modal===null || arrayFalso.length<=0">Guardar</button>
                        <button type="button" class="btn btn-primary" v-else>Guardar</button>
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
import VueMultiselect from 'vue-multiselect';
//Vue.use(VeeValidate);
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

             selected_modal:null,
             array_modal:[],
             array_prod:[],
             selectProd:"0",
             arrayFalso:[],
             selectModo_vmodal:"0",
          

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
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
        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
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

        añadirArray(data){
        let me=this;
        const index = me.array_prod.findIndex(p => p.id === data);
        const producto = me.array_prod[index]
        if (index !== -1) {
      const producto = me.array_prod[index];
      // Lo añades al nuevo array
      me.arrayFalso.push(producto);
      // Lo quitas del array original
      me.array_prod.splice(index, 1);
      // Limpias el select
      me.selectProd = 0;
    }
        },
        
        quitarDatos(data){
            let me=this;
            const index = me.arrayFalso.findIndex(p => p.id === data);
        const producto = me.arrayFalso[index]
        if (index !== -1) {
      const producto = me.arrayFalso[index];
      // Lo añades al nuevo array
      me.array_prod.push(producto);
      // Lo quitas del array original
      me.arrayFalso.splice(index, 1);
      // Limpias el select
      me.selectProd = 0;
      me.array_prod.sort((a, b) => a.id - b.id);
    }
        },

        listarlistaActividad() {
            let me = this;
           var url = "/siat_homologacion/listarLista";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.array_modal=respuesta;              
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarProducto_homo() {
            let me = this;
           var url = "/siat_homologacion/listarProdH";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.array_prod=respuesta;              
                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        
        cambioManuAuto(data){
            let me = this;
            if (data==="1") {
                if (me.arrayFalso.length>0) {
                    me.selectModo_vmodal="2";
                    Swal.fire({
                  title: "Error de array",
                  text: "Debe quitar todo los elementos de la tabla para cambiar modo automatico",
                  icon: "error",
              }); 
                
            }
        }
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
                    me.tituloModal = "Registrar producto a homologar";
                    me.selected_modal=null;
                    me.selectProd="0";
                    me.arrayFalso=[];
                    me.classModal.openModal("registrar");
                    me.selectModo_vmodal="0";
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
                me.arrayFalso=[];
                    me.tituloModal = " ";
                    me.selected_modal=null;
                    me.selectProd="0";
                    me.selectModo_vmodal="0";
              
          
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
        this.listarlistaActividad();
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
