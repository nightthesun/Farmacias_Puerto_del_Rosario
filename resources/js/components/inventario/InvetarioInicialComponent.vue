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
                    <button :disabled="sucursalSeleccionada===-1" type="button" class="btn btn-secondary" @click="abrirModal('registrar');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>                    
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="seleccionSucursal_data(sucursalSeleccionada)">
                                    <option value="-1" disabled selected>Seleccionar...</option>
                                    <option v-for="(sucursal, index) in arraySucursal" :key="index" :value="index"
                                    v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' ' +sucursal.razon_social">                                        
                                    </option>
                                   
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
                               
                                    :hidden="sucursalSeleccionada == -1"
                                    :disabled="sucursalSeleccionada == -1"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                               @click="buscarProductos()"
                                    :hidden="sucursalSeleccionada == -1"
                                    :disabled="sucursalSeleccionada == -1"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             <div class="form-group row"  :hidden="sucursalSeleccionada == -1" :disabled="sucursalSeleccionada == -1">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                    
                        <label for=""></label>                                      
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" :disabled="sucursalSeleccionada===-1" @change="listarIndex(0)">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="sucursalSeleccionada===-1" @change="listarIndex(0)">
        </div>        
            </div> 
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>                       
                        <th>Linea</th>
                        <th class="col-md-3">Producto</th>
                        <th>Cantidad de ingreso</th>
                        <th>Stock</th>
                        <th>Lote</th>
                        <th>Fecha ingreso</th>
                        <th>Fecha Vencimiento</th>                        
                        <th>Usuario</th>                          
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayIndex" :key="index">
                        <td>{{i.nombre_linea}}</td>
                        <td class="col-md-3">{{i.leyenda}}</td>
                        <td>{{i.cantidad}}</td>
                        <td>{{i.stock_ingreso}}</td>
                        <td>{{i.lote}}</td>
                        <td>{{i.fecha}}</td>
                        <td>{{i.fecha_vencimiento}}</td>
                        <td>{{i.user_name}}</td>
                    </tr>
                </tbody>
            </table>    
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
                     
                          <form  enctype="multipart/form-data" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row"  >
                                <strong  class="col-md-3 form-control-label" for="text-input">Producto: <span v-if="selected == null" class="error" >(*)</span></strong>
                                <div class="col-md-7 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selected"
                        :options="arrayProductos"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="leyenda" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>           

                                    <!-- <option value="0" disabled>Seleccionar...</option>
                                    <option v-if="producto.almacenprimario == 1" :key="producto.idproduc" :value="producto.idproduc" v-text="producto.cod"></option> -->
                                </div>                        
                            </div>  
                        </div>

                            <div class="row">
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Cantidad: </strong>
                                    <input type="number" min="0" class="form-control" v-model="cantidad" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                  
                                </div>
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Tipo Entrada:</strong>
                                    <select v-model="selectEntrada" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="tipo in arrayTipoEntrada" :key="tipo.id" :value="tipo.id" v-text="tipo.nombre"></option>
                                    </select>
                                   
                                </div>
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Lote: </strong>
                                    <input type="text" class="form-control" placeholder="Lote"  v-model="lote" v-on:focus="selectAll">                                 
                                </div>
                            </div>
                            <div class="row">
                               
                                <div class="form-group col-sm-5" v-if="selected != null">
                                    <strong>Fecha de Vencimiento: </strong>
                                    <input type="date" :min="fechamin" class="form-control" v-model="fecha_vencimiento">           
                                </div>
                                
                                <div class="form-group col-sm-5" v-if="selected != null">
                                    <strong>Registro Sanitario:</strong>
                                    <input type="text" class="form-control" placeholder="Registro Sanitario" v-model="registrosanitario" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 13 || event.charCode == 32 )">
                                    
                                </div>

                                <div class="form-group col-sm-2" v-if="selected != null">
                                    <button type="button" class="btn btn-primary" style="margin-top: 10px;"
                                     @click="añadirArrayF();" :disabled="cantidad==''||selectEntrada=='0'||lote==''||fecha_vencimiento==''||registrosanitario==''">Añadir</button>
                                </div>
                            </div>   
                             <table class="table table-bordered table-striped table-sm" :hidden="arrayAñadir.length<=0">
    <thead>
        <tr>
            <th class="col-md-1">Opción</th>
            <th class="col-md-5">Producto</th>
            <th class="col-md-1">Cantidad</th>
            <th class="col-md-1">Lote</th>
            <th class="col-md-2">Registro sanitario</th>
            <th class="col-md-2">Fecha vencimiento</th>
        </tr>
    </thead>

    <tbody>
        <tr v-for="(i, index) in arrayAñadir" :key="index">
            <td class="col-md-1">
                <button type="button" class="btn btn-danger" @click="eliminarPorIdYEnvase(i.id_producto,i.envase);">
                    <i class="fa fa-minus"></i>
                </button>
            </td>
            <td class="col-md-5">{{ i.leyenda }}</td>
            <td class="col-md-1">{{ i.cantidad }}</td>
            <td class="col-md-1">{{ i.lote }}</td>
            <td class="col-md-2">{{i.registrosanitario}}</td>
            <td class="col-md-2">{{ i.fechaFormateada }}</td>
        </tr>
    </tbody>
</table>                        
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" :disabled="isSubmitting==true" @click="crearIngreso()">
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
            offset:3,

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
            arrayAñadir:[],

            arrayTipoEntrada:[], 
            arrayProductos:[],
            selected:null,             
            selectEntrada:'0', 
            fecha_vencimiento:'',  
            registrosanitario:'',
            fechamin:'',   
            fechaactual:'',   
            isSubmitting : false,     

            arrayIndex:[],
            arrayModalFalso:[],

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

         buscarProductos() {
        let me=this;       
        const texto = me.buscar.toLowerCase(); 
        const existe = me.arrayModalFalso.some(item =>
        (item.nombre_linea && item.nombre_linea.toLowerCase().includes(texto)) ||
        (item.leyenda && item.leyenda.toLowerCase().includes(texto))
        ); 
        if (existe) {
            // Filtra por coincidencias en línea o producto
    me.arrayIndex = me.arrayModalFalso.filter(item =>
      (item.nombre_linea && item.nombre_linea.toLowerCase().includes(texto)) ||
      (item.leyenda && item.leyenda.toLowerCase().includes(texto))
    );
        } else {
            me.arrayIndex=me.arrayModalFalso; 
        }
     
    },

         listarIndex(page)
            {
                let me=this;
                
                if (me.selectAlmTienda!=0) {
                    var url='/inventario-inial/index?page='+page+'&id_sucursal='+me.id_sucursal+'&id_tienda='+me.id_tienda+'&id_almacen='+me.id_almacen+"&ini="+me.startDate+"&fini="+me.endDate;     
                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.query.data;
                    me.arrayModalFalso = respuesta.query.data;                       
                })
                .catch(function(error){
                    error401(error);
                }); 
                } 
               
            },

         crearIngreso(){
            let me = this;       
            
            if (me.arrayAñadir.length>0) {
                // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post('/inventario-inial/registrar',{
                    'id_sucursal':me.id_sucursal,
                    'id_tienda':me.id_tienda,
                    'id_almacen':me.id_almacen,
                    'arrayAñadir':me.arrayAñadir,                    
              
                }).then(function(response){                   
                    let respuesta=response.data;
                    if (respuesta==1) {
                         Swal.fire('Registrado Correctamente');
                    } else {
                         Swal.fire('Error: '+respuesta);
                    }                    
                    me.cerrarModal('registrar');
                    me.listarIndex(1);
                })
              
                .catch(function (error) {  
                console.log(error.data);             
            });

             me.isSubmitting = false;
            me.cerrarModal('registrar');
            } else {
               Swal.fire('Debe haber al menos un registro a ingresar.');  
            }  
        },

       eliminarPorIdYEnvase(id, envase) {
    const index = this.arrayAñadir.findIndex(
        item => item.id_producto === id && item.envase === envase
    );

    if (index !== -1) {
        this.arrayAñadir.splice(index, 1);
    }
},

        añadirArrayF(){
            let me = this;         
            let leyenda=me.selected.codigo_prod+" "+me.selected.leyenda+" "+me.selected.envase;
           
            me.arrayAñadir.push(
               { id_producto: me.selected.id, id_linea: me.selected.id_liena,leyenda:leyenda,fechaFormateada:me.fecha_vencimiento,
                 lote:me.lote,cantidad:me.cantidad,envase:me.selected.envase,registrosanitario:me.registrosanitario,
                 selectEntrada:me.selectEntrada } 
            );
            me.selected=null;
            me.fecha_vencimiento="";
            me.lote="";
            me.cantidad=0;
            me.registrosanitario="";
            me.selectEntrada="0";
         },

        cambioEstado(){
            let me = this;
            me.selectArrayAnio='0';           
            me.selectArrayMes='0';            
            me.lote='';
            me.cantidad=0;            
          
        },

        obtenerfecha(){
                let me = this;
                var url= '/obtenerfecha';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fechaactual=respuesta[0].fecha;
                    me.fechamin=me.fechaactual
                    me.fecha_vencimiento=me.fechaactual;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                
                
                //me.fechafactura=me.fechaactual;
            },

         listar_entradasXe() {
            let me = this;
            var url = "/listar_entradasXe";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipoEntrada = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
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

        listarProductos_almacen() {
            let me = this;
            var url = "/tienda/listarProductos_tienda";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductos = respuesta;          
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSelectProducto() {
        let me = this;
        var url = "/inventario-inial/listarSelectProducto";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProducto = respuesta;                
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
            me.listarIndex(0);
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
          me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Ingresar productos";
                    me.showModal = true;
                    me.lote="";
                    me.cantidad=0;
                    me.selectArrayProducto=null;
                    me.selectArrayMes="0";
                    me.selectArrayAnio="0";       
                     me.isSubmitting=false;

                    me.selected=null;             
                    me.selectEntrada="0"; 
                    me.fecha_vencimiento="";  
                    me.registrosanitario="";  
                    me.arrayAñadir=[];                        
                    
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
 me.isSubmitting=false;
                me.selected=null;             
                    me.selectEntrada="0"; 
                    me.fecha_vencimiento="";  
                    me.registrosanitario="";
                    me.arrayAñadir=[];

                me.tituloModal = " ";
             
             
            }
        },

         nameWithLang_2 ({name_prod,name_dis,cantidad_dis,name_forma,name_linea,tipo}) {            
            return `${name_prod} - ${name_dis} X ${cantidad_dis} ${name_forma} :${name_linea} Envase:${tipo}` 
          },  

          nameWithLang ({codigo_prod,leyenda, envase,}) {
            
            return `${codigo_prod} ${leyenda} (${envase}) `
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
        this.listar_entradasXe();
        this.obtenerfecha(1);
        this.listarProductos_almacen();
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
