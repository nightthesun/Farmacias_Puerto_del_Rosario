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
                    <i class="fa fa-align-justify"></i> Inventario inicial               
                    <button :disabled="sucursalSeleccionada==='-1'" type="button" class="btn btn-secondary" @click="abrirModal('registrar');listarSelectProducto();">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>                    
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="seleccionSucursal_data(sucursalSeleccionada)">
                                    <option value="-1" disabled selected>Seleccionar...</option>
                                    <option v-for="(sucursal, index) in arraySucursal" :key="index" :value="index"
                                    v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' ' +sucursal.razon_social">                                        
                                    </option>
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                    :hidden="sucursalSeleccionada == -1"
                                    :disabled="sucursalSeleccionada == -1"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="sucursalSeleccionada == -1"
                                    :disabled="sucursalSeleccionada == -1"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== -1">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== -1">
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
                        <th>Linea</th>
                        <th class="col-md-4">Producto</th>
                        <th >Cantidad de ingreso</th>
                        <th >Stock</th>
                        <th >Lote</th>
                        <th >Fecha ingreso</th>
                        <th >Fecha Vencimiento</th>                        
                        <th >Usuario</th>                          
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
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 75vh; overflow-y: auto;"> 
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">                                
                               <div class="form-group row">                                
                                    <div class="col-md-12">
                                        <label for="">Producto:</label>
                                        <VueMultiselect  
                        v-model="selectArrayProducto"                      
                        :options="arrayProducto"
                        :max-height="200"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id" 
                        :custom-label="nameWithLang_2"                     
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
                                </div> 
                                <div class="form-group row">                                
                                    <div class="col-md-3">
                                        <label for="">Lote:</label>
                                        <input type="text" class="form-control" v-model="lote" placeholder="Lote"/>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">V. Mes:</label>
                                    <select class="form-control" v-model="selectArrayMes">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="(i, index) in arrayMes" :key="index" :value="i.id"
                                    v-text="i.tipo">                                        
                                    </option>                                   
                                </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">V. año:</label>
                                        <select class="form-control" v-model="selectArrayAnio">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="(i, index) in arrayAnio" :key="index" :value="i"
                                    v-text="i">                                        
                                    </option>                                   
                                </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Cantidad:</label>
                                     <div class="input-group">
  <input type="number" class="form-control" v-model="cantidad" placeholder="Lote">
  <button type="button" class="btn btn-primary">
    <i class="fa fa-check" aria-hidden="true"></i>
  </button>
</div>
                                    </div>   
                                </div> 
                                 <table class="table table-bordered table-striped table-sm table-responsive" >
                                    <thead>
                                    <tr>                       
                                        <th>Opción</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Lote</th>
                                        <th>Fecha vencimiento</th>
                                    </tr>
                                    </thead>
                                    <body>
                                        <tr v-for="(item, index) in arrayAñadir" :key="index">
                                            <td></td>
                                        </tr>
                                    </body>
                                </table>               
                            </div>
                        </form>
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
            showModal: false,
            //offset:3,

            id_sucursal:0,
            id_tienda:0,
            id_almacen:0,

            tituloModal: '',
            sucursalSeleccionada:-1,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',

            selectArrayAnio:'0',
            arrayProducto:[],
            selectArrayMes:'0',
            arrayMes:[{ id: 1, tipo: "ENERO" },{ id: 2, tipo: "FEBRERO" },{ id: 3, tipo: "MARZO" },{ id: 4, tipo: "ABRIL" },{ id: 5, tipo: "MAYO" },{ id: 6, tipo: "JUNIO" },{ id: 7, tipo: "JULIO" },{ id: 8, tipo: "AGOSTO" },{ id: 9, tipo: "SEPTIEMBRE" },{ id: 10, tipo: "OCTUBRE" },{ id: 11, tipo: "NOVIEMBRE" },{ id: 12, tipo: "DICIEMBRE" }],
            arrayAnio:[],
            lote:'',
            cantidad:0,
            selectArrayProducto:null,
            arrayAñadir:{},

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

        añadirArrayF(){
            let me = this;
            me.arrayAñadir.push(
               { id_producto: me.selectArrayProducto.id_prod, id_linea: me.selectArrayProducto.id_linea,envase:me.selectArrayProducto.tipo,anio:me.selectArrayAnio,mes:me.selectArrayMes, lote:me.lote,cantidad:me.cantidad } 
            );
        },
        anio(){
            let me=this;
            const anioActual = new Date().getFullYear();
            let anioAtras=anioActual-5;
            let anioAdelante=anioActual+5;
            let n=1;
            let n1=1;
            let n2=5;
           while (n<=11) {
                if (n<=5) {
                   anioAtras=anioActual-n2 ; 
                    me.arrayAnio.push(anioAtras); 
                    n2--; 
                }else{
                    if (n==6) {
                      me.arrayAnio.push(anioActual);  
                    } else {
                        
                    }
                    anioAdelante=anioActual+n1;
                    me.arrayAnio.push(anioAdelante); 
                    n1++;
                }                
                n++;             
           }
         
        }, 

        listarSelectProducto() {
        let me = this;
        var url = "/inventario-inial/listarSelectProducto";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta;
                    console.log(me.arrayProducto);                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        seleccionSucursal_data(data){
            let me=this;
            const array = me.arraySucursal[data];
            me.id_sucursal=array.id_sucursal;
            me.id_tienda=array.id_tienda;
            me.id_almacen=array.id_almacen;          
        },

        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;
                    console.log(me.arraySucursal);                 
                })
                .catch(function (error) {
                    error401(error);
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
                    me.tituloModal = "Ejemplo titulo";
                    me.showModal = true;
                    me.lote="";
                    me.cantidad=0;
                    me.selectArrayProducto=null;
                    me.selectArrayMes="0";
                    me.selectArrayAnio="0";                   
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
                me.lote="";
                    me.cantidad=0;
                    me.selectArrayProducto=null;
                    me.selectArrayMes="0";
                    me.selectArrayAnio="0";
                me.tituloModal = " ";
             
             
            }
        },

         nameWithLang_2 ({name_prod,name_dis,cantidad_dis,name_forma,name_linea,tipo}) {            
            return `${name_prod} - ${name_dis} X ${cantidad_dis} X ${name_forma} :${name_linea} Envase:${tipo}` 
          },  

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.anio();
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
