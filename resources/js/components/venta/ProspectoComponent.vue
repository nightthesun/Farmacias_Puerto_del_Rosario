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
                    <i class="fa fa-align-justify"></i> Prospecto               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');"
                        :disabled="sucursalSeleccionada == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar sucrsal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucrsal:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarVentas(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="(sucursal,index) in arraySucursal" :key="index"  :value="sucursal.id_sucursal" :hidden="sucursal.tipoCodigo==='Almacen'"
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
                                      @keyup.enter="listarVentas(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                       @click="listarVentas(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
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
          <input id="start-date" type="date" class="form-control" v-model="startDate"  @change="listarVentas(0)">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate"  @change="listarVentas(0)">
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
                        <th class="col-md-1">Linea</th>  
                        <th class="col-md-1">Cod Producto</th>                                         
                        <th class="col-md-2">Producto</th>
                        <th class="col-md-1">Envase</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-2">Descripción</th> 
                        <th class="col-md-2">Usuario</th>                      
                        <th>Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id"> 
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="listarProducto(i.envase);abrirModal('actualizar',i);">
                                <i class="icon-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                        <td class="col-md-1">{{i.nom_linea}}</td>
                        <td class="col-md-1">{{i.codigoProducto}}</td>
                        <td class="col-md-2">{{i.nom_prod}}</td>
                        <td class="col-md-1">
                            <span v-if="i.envase===1">Primario</span>
                            <span v-if="i.envase===2">Segundario</span>
                            <span v-if="i.envase===3">Terciario</span>    
                        </td>
                        <td class="col-md-2">{{i.updated_at}}</td>
                        <td class="col-md-2">{{i.descripcion}}</td>
                        <td class="col-md-2">{{i.nom_user}}</td>
                        <td>
                            <div v-if="i.activo==1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-warning">Desactivado</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>  
                        <!-----fin de tabla------->
                         <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active':'']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page< pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
           <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-show="tipoAccion===1">
                      <button 
    type="button" 
    @click="seleccionBotonEnvase(1)" 
    :class="tipoEnvase === 1 ? 'btn btn-info text-white mb-2' : 'btn btn-secondary text-white mb-2'">
    Envase Primario
</button>
<button 
    type="button" 
    @click="seleccionBotonEnvase(2)" 
    :class="tipoEnvase === 2 ? 'btn btn-info text-white mb-2' : 'btn btn-secondary text-white mb-2'">
    Envase Secundario
</button>
   <button 
    type="button" 
    @click="seleccionBotonEnvase(3)" 
    :class="tipoEnvase === 3 ? 'btn btn-info text-white mb-2' : 'btn btn-secondary text-white mb-2'">
    Envase Terciario
</button>
                       <form action="" class="form-horizontal"> 
                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row">                                  
                                    <label class="col-md-2 form-control-label" for="text-input">
                     <strong>Producto: </strong>                                
                    </label> 
                      <div v-show="tipoAccion===1" class="col-md-10 input-group mb-3">
                        <VueMultiselect
                        v-model="selectProducto"
                        
                        :options="arrayProducto"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id_producto" 
                        :custom-label="nameWithLang"                     
                        track-by="id_producto"
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
                                <div class="form-group row" v-show="tipoEnvase!=0">                                  
                                    <label class="col-md-2 form-control-label" for="text-input">
                     <strong>Descripción:</strong>                                
                    </label> 
                      <div class="col-md-10 input-group mb-3">
                           
                             <textarea class="form-control" aria-label="With textarea" v-model="descripcion"></textarea>
                      </div>
          
                                </div>  
                            </div>
                        </form>
                    </div>
                    <div class="modal-body" v-show="tipoAccion===2">
                        <br><h4>
<strong   >Producto: {{cadenaTexto}}</strong>
                        </h4>
                        
                        <br>
                        <div class="form-group row ">                                  
                                    <label class="col-md-2 form-control-label" for="text-input">
                     <strong>Descripción:</strong>                                
                    </label> 
                      <div class="col-md-10 input-group mb-3">                           
                             <textarea class="form-control" aria-label="With textarea" v-model="descripcion"></textarea>
                      </div>
                </div>  
                        
                    </div>
   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" :disabled="isSubmitting === true" @click="crear()">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" :disabled="isSubmitting === true" @click="modificar()" class="btn btn-primary">
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
import * as XLSX from 'xlsx';

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
            offset:3,

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',

            arrayIndex:[],
            selectProducto:null,
            arrayProducto:[],
            tipoEnvase:0,
            descripcion:'',
            isSubmitting:null,

            id_producto:'',
            cadenaTexto:'',
            id_index:'',
          
            
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

          listarVentas(page){
        let me=this;       
        var url = "/prospeto/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.sucursalSeleccionada+"&startDate="+me.startDate+"&endDate="+me.endDate;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                   
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.prospectos.data;  
                    console.log(me.arrayIndex);  
                              
                })
                .catch(function (error) {
                    error401(error);
               
                });
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
                    console.log(error);
                });
        },

        seleccionBotonEnvase(data){
            let me=this;
            me.listarProducto(data);
            me.tipoEnvase=data;
            me.descripcion="";
            me.selectProducto=null;

        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        crear(){
            let me=this;
             if (me.selectProducto === null || me.tipoEnvase <= 0 || me.descripcion === "") {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                 // Si ya está enviando, no permitas otra solicitud
            me.isSubmitting=true;
                axios.post("/prospeto/crear", {
                        'id_sucursal': me.sucursalSeleccionada,
                        'id_producto': me.selectProducto.id_producto,
                        'envase': me.tipoEnvase,
                        'descripcion':me.descripcion                                 
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                          me.isSubmitting=false;
                          var respuesta = response.data;
                          if (respuesta.length>0) {                             
                            Swal.fire(""+respuesta,"Haga click en Ok","error",);                                
                          }else{
                            Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                          }
                    })
                   .catch(function (error) {                
                                  
            });
            }    
        },

        modificar(){
            let me=this;
             if (me.tipoEnvase <= 0 || me.descripcion === "") {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                 // Si ya está enviando, no permitas otra solicitud
            me.isSubmitting=true;
                axios.put("/prospeto/modificar", {
                        'id':id,
                        'id_producto': me.id_producto,                        
                        'descripcion':me.descripcion                                 
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                          me.isSubmitting=false;
                          var respuesta = response.data;
                          if (respuesta.length>0) {                             
                            Swal.fire(""+respuesta,"Haga click en Ok","error",);                                
                          }else{
                            Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                          }
                    })
                   .catch(function (error) {                
                                  
            });
            }    
        },

        listarProducto(data){
            let me = this;
            var url = "/listarProducto_x_envase?envase="+data;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta;
                    console.log(me.arrayProducto);
                 
                })
                .catch(function (error) {
                    error401(error);
                });
        },

//$reg->linea.": ".$reg->nombre_producto." - ".$reg->dispenser." X ".$reg->cantidad_dispenser_producto." ".$reg->forma_farmaceutica."
         nameWithLang ({linea,cod_prod,nom_prod,nom_dispenser,cantidad_dispenser,nombre}) {            
            return `${linea}: ${cod_prod} ${nom_prod} - ${nom_dispenser} X ${cantidad_dispenser} ${nombre}`
          },


        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page; 
            me.listarVentas(page); 
        },

        abrirModal(accion, data = []) {
            let me = this;      
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Añadir producto consultado";
                    me.showModal = true;
                    me.selectProducto=null;           
                    me.tipoEnvase=0;                    
                    me.descripcion="";
                    me.isSubmitting=null;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    console.log(data);
                    me.tituloModal = "Actualizar producto consultado";
                    me.showModal = true;
                    me.isSubmitting=null;              
                    me.descripcion=data.descripcion;
                    me.id_index=data.id;
                    
                    me.id_producto=data.id_producto;                      
                    if (data.envase===1) {
                        me.cadenaTexto =  "Linea: "+data.nom_linea+" "+data.codigoProducto+" - "+data.nom_prod+" Envase: Primario";
                    }
                    if (data.envase===2) {
                        me.cadenaTexto =  "Linea: "+data.nom_linea+" "+data.codigoProducto+" - "+data.nom_prod+" Envase: Segundario";
                    }
                    if (data.envase===3) {
                        me.cadenaTexto =  "Linea: "+data.nom_linea+" "+data.codigoProducto+" - "+data.nom_prod+" Envase: Terciario";
                    }                   
                    me.tipoEnvase=data.envase;   
                
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
                me.selectProducto=null;           
                me.tipoEnvase=0;
                me.descripcion="";
                me.isSubmitting=null;
                me.id_producto="";
                me.cadenaTexto="";
                me. me.id_index="";                  
                    
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
