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
                    <i class="fa fa-align-justify"></i>  Entrada de Productos a Almacenes               
                    <button v-if="puedeCrear==1"
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');listarProductos_almacen();listar_entradasXe();"
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
                                <select class="form-control" v-model="selectAlmTienda"   @change="listarIndex(1)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option  v-for="sucursal in arrayAlmTienda"
                                        :key="sucursal.id_almacen" :value="sucursal.id_almacen" v-text="sucursal.codigoS+' -> '+sucursal.codigo+' ' +sucursal.razon_social"></option>
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
                                    @keyup.enter="listarIndex(1)"
                                    v-model="buscar"
                               
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"
                                    :hidden="selectAlmTienda == 0"
                                    :disabled="selectAlmTienda == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

            </div>

            <!---tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Codigo</th>
                        <th class="col-md-1">Linea o Marca</th>
                        <th class="col-md-2">Producto</th>
                        <th class="col-md-1">Cantidad</th>
                        <th class="col-md-1">Lote</th>
                        <th class="col-md-1">Vencimiento</th>
                        <th class="col-md-1">R.S. SENASAG</th>
                        <th class="col-md-2">Fecha y Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th class="col-md-1">Traspaso</th>
                        <th class="col-md-1">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ingresoProducto in arrayIndex" :key="ingresoProducto.id">
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" @click="tiene_movimiento(ingresoProducto.idalmacen,ingresoProducto.id,ingresoProducto)"  style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                            </div>
                            <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                            </div>
                            <div v-if="puedeActivar==1">
                                <button v-if="ingresoProducto.activo==1" type="button" class="btn btn-danger btn-sm"  @click="eliminarProductoAlmacen(ingresoProducto.id)"  style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-info btn-sm" @click="activarProductoAlmacen(ingresoProducto.id)" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="ingresoProducto.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            </div>
                           
                        </td>
                        <td class="col-md-1" v-text="ingresoProducto.codigo_prod"></td>
                        <td class="col-md-1" v-text="ingresoProducto.nombre_linea"></td>
                        <td class="col-md-2" v-text="ingresoProducto.leyenda "></td>
                        <td class="col-md-1" v-text="ingresoProducto.cantidad" style="text-align:right"></td>
                        <td v-text="ingresoProducto.lote" class="col-md-1"></td>
                        <td  v-text="ingresoProducto.fecha_vencimiento" class="col-md-1"></td>
                        <td  v-text="ingresoProducto.registro_sanitario" class="col-md-1"></td>
                        <td class="col-md-1"  v-text="ingresoProducto.fecha"></td>
                        <td class="col-md-1">
                            <div v-if="ingresoProducto.user_id_M==null">
                                {{ ingresoProducto.user_name }}
                            </div>
                            <div v-else>
                                {{ ingresoProducto.user_name_M }}
                            </div>
                        </td>
                        <td class="col-md-1">
                            <div v-if="ingresoProducto.num_traspaso==null">
                                Sin datos
                            </div>
                            <div v-else>
                                {{ ingresoProducto.num_traspaso }}
                            </div>
                        </td>
                        <td class="col-md-1">
                            <div v-if="ingresoProducto.activo==1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-warning">Desactivado</span>
                            </div>
                            
                        </td>
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
                           
                                <span v-if="selected==null" class="error">Debe Ingresar el Nombre del producto</span>
                            </div>
                              
                            
                               
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Cantidad: <span  v-if="cantidad==0" class="error">(*)</span></strong>
                                    <input type="number" min="0" class="form-control" v-model="cantidad" style="text-align:right" placeholder="0" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                    <span  v-if="cantidad==0" class="error">Debe Ingresar la Cantidad </span>
                                </div>
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Tipo Entrada:</strong>
                                    <select v-model="selectEntrada" class="form-control">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="tipo in arrayTipoEntrada" :key="tipo.id" :value="tipo.id" v-text="tipo.nombre"></option>
                                    </select>
                                    <span  v-if="selectEntrada==0" class="error">Debe seleccionar un tipo de entrada</span>
                                </div>
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Lote: <span  v-if="lote==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Lote"  v-model="lote" v-on:focus="selectAll">
                                    <span  v-if="lote==''" class="error">Debe Ingresar el lote</span>
                                </div>
                            </div>
                            <div class="row">
                               
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Fecha de Vencimiento: <span  v-if="fecha_vencimiento==''" class="error">(*)</span></strong>
                                    <input type="date" :min="fechamin" class="form-control" v-model="fecha_vencimiento">
                                    <span  v-if="fecha_vencimiento==''" class="error">Debe Ingresar la fecha de Vencimiento</span>
                                </div>
                                
                                <div class="form-group col-sm-4" v-if="selected != null">
                                    <strong>Registro Sanitario:<span  v-if="registrosanitario==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Registro Sanitario" v-model="registrosanitario" v-on:focus="selectAll" onkeypress="return (event.charCode !=8 && event.charCode == 0 || (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 45 || event.charCode == 13 || event.charCode == 32 )">
                                    <span  v-if="registrosanitario==''" class="error">Debe Ingresar el Registro Sanitario</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 " v-if="selected != null">
                                    <strong>Codigo QR: </strong><br><br>
                                    <QrcodeVue :value="codigoQr" :size="size" level="H" />
                             
                                </div>
                              
                            </div>
                        </form>
                    </div>
                
                 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrarProductoEnAlmacen()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizarProductoEnAlmacen()" :disabled="!sicompleto">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
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
import QrcodeVue from 'qrcode.vue';
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import { watch } from 'vue';
//Vue.use(VeeValidate);
export default {
    components: { VueMultiselect ,QrcodeVue},
     //---permisos_R_W_S
     props: ['codventana'],
        //-------------------
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
            offset: 0,
            isSubmitting: false, // Controla el estado del botón de envío
            tituloModal: "",
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,
            
            arrayProductos:[],
            cantidad:0,
            arrayTipoEntrada:[],
            selectEntrada:0,
            lote:'',
            fecha_vencimiento:'',
            registrosanitario:'',
            //-------multiselector
            selected: null,
        options: ['list', 'of', 'options'],
       
        value: 'https://example.com',
        size: 120,

        idalmacen_v:'',
        id_prod_producto_v:'',
        id_tipoentrada_v:'',    
        envase_v:'',
        cantidad_v:'',
        stock_ingreso_v:'',    

        fechamin:'',
        fechaactual:'',
       arrayIndex:[],
//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
            id_index:'',
          
            codigo_alm:'',

        };
    },

    

    computed: {      
        codigoQr() {
      if (this.selected.codigo_prod) {
        return this.selected.codigo_prod + ' ' + this.selected.leyenda + ' tipo de envase: ' + this.selected.envase + ' lote: ' + this.lote + ' ' + this.fecha_vencimiento + ' tipo de entrada: ' + this.selectEntrada;
      } else {
        return 'codigo error';
      }
    
    },
       sicompleto() {
            let me = this;
            if (me.selected != null && me.cantidad != 0 && me.selectEntrada!=0 && me.lote!="" && me.fecha_vencimiento!=""&&me.registrosanitario!="")
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

          //---------------------------------permiso de ver lista de sucursales tiendas almacenes
    
 listarPerimsoxyz() {
           
    let me = this;        
    var url = '/gestion_permiso_editar_eliminar?win='+me.codventana;  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;     
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


tiene_movimiento(id_almacen,id_index,ingresoProducto){
    let me = this; 
    // para tienda es 1 y para almacen es 2   
    var alm_o_tda=2;    
    var url = '/tiene_movimiento?id_T_A='+id_almacen+'&id_ingreso='+id_index+'&almXtda='+alm_o_tda;  
    axios.get(url)
        .then(function(response) {
            var respuesta = response.data;     
             if (respuesta.length==0) {             
                me.listar_entradasXe();
                me.listarProductos_almacen();
                me.abrirModal('actualizar',ingresoProducto);
             } else{
            
                Swal.fire('Producto con movimiento');
             }
         
        })
        .catch(function(error) {
            error401(error);
            console.log(error);
        });
},


        nameWithLang ({codigo_prod,leyenda, envase,}) {
            
            return `${codigo_prod} ${leyenda} (${envase}) `
          },

        toggle () {
      this.$refs.multiselect.$el.focus()

      setTimeout(() => {
        this.$refs.multiselect.$refs.search.blur()
      }, 1000)
    },
    open () {
      this.$refs.multiselect.activate()
    },
    close () {
      this.$refs.multiselect.deactivate()
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

        listarProductos_almacen() {
            let me = this;
            var url = "/almacen/listarProductos_almacen";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductos = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
       

        listarAlmTienda() {
            let me = this;
            var url = "/almacen/listaAlmacen2";
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

        listarIndex(page)
            {
                let me=this;
                if (me.selectAlmTienda!=0) {
                    var url='/almacen/index?page='+page+'&buscar='+me.buscar+'&id_almacen='+me.selectAlmTienda;               
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.almacen.data;
                   
                })
                .catch(function(error){
                    error401(error);
                }); 
                } 
               
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
                    me.isSubmitting=false;
                    me.tituloModal = "Ingreso de productos a almacen";
                    me.selectEntrada=0;
                    me.lote='';
                    me.fecha_vencimiento='';
                    me.registrosanitario='';
                    me.selected=null;
                    me.cantidad=0;
                    me.id_almacen='';
                    me.idalmacen_v='';
                    me.id_prod_producto_v='';
                    me.id_tipoentrada_v='';    
                    me.envase_v='';                    
                    me.stock_ingreso_v='';    
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.isSubmitting=false;
                    me.tipoAccion = 2;            
                    me.tituloModal = "Ingreso de productos a almacen";
                   // Find the product with the matching id_prod_producto in arrayProductos
            let producto = me.arrayProductos.find(producto => producto.id === data.id_prod_producto);
            if (producto) {
                me.selected = producto;
            } else {
                me.selected = null;
            }
            me.id_index=data.id;
                   me.selectEntrada= data.id_tipoentrada === null ? 0 : data.id_tipoentrada;
                    me.lote=data.lote;
                    me.fecha_vencimiento=data.fecha_vencimiento;
                    me.registrosanitario=data.registro_sanitario;
                   
                    me.cantidad=data.cantidad;
                    me.id_almacen=data.idalmacen;
                    me.id_prod_producto_v=me.selected.id;
                    me.id_tipoentrada_v='';    
                    me.envase_v=data.envase;                    
                    me.stock_ingreso_v=data.stock_ingreso;    
                    me.codigo_alm=data.codigo_alm;
                    me.classModal.openModal("registrar");

                    break;
                }
            
            }
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;
                me.classModal.closeModal(accion);
               
                    me.tituloModal = " ";
                    me.selectEntrada=0;
                    me.lote='';
                    me.fecha_vencimiento='';
                    me.registrosanitario='';
                    me.selected=null;
                    me.cantidad=0;
                    me.id_almacen='';
                    me.idalmacen_v='';
                    me.id_prod_producto_v='';
                    me.id_tipoentrada_v='';    
                    me.envase_v='';                    
                    me.stock_ingreso_v='';  
                    setTimeout(me.tiempo, 200); 
                    //me.ProductoLineaIngresoSeleccionado = 0;
                    me.inputTextBuscarProductoIngreso = "";
                        me.arrayRetornarProductosIngreso = "";
              
            } else {
                me.classModal.closeModal(accion);
              
                me.classModal.openModal("registrar");
            }
        },

        registrarProductoEnAlmacen(){
                let me = this;
          // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;
      me.isSubmitting = true; // Deshabilita el botón
                axios.post('/almacen/ingreso-producto2/registrar',{
                    'id_prod_producto':me.selected.id,
                    'envase':me.selected.envase,
                    'idalmacen':me.selectAlmTienda,
                    'cantidad':me.cantidad,
                    'id_tipo_entrada':me.selectEntrada,
                    'fecha_vencimiento':me.fecha_vencimiento,
                    'lote':me.lote,
                    'registro_sanitario':me.registrosanitario,
              
                }).then(function(response){
                    Swal.fire('Registrado Correctamente');
                    me.cerrarModal('registrar');
                    me.listarIndex(1);
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
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
            },

            actualizarProductoEnAlmacen(){
                let me =this;
                axios.put('/almacen/ingreso-producto2/update',{
                    'id':me.id_index,
                    'id_prod_producto':me.selected.id,
                    'envase':me.selected.envase,
                    'idalmacen':me.selectAlmTienda,
                    'cantidad':me.cantidad,
                    'id_tipo_entrada':me.selectEntrada,
                    'fecha_vencimiento':me.fecha_vencimiento,
                    'lote':me.lote,
                    'registro_sanitario':me.registrosanitario,
                }).then(function (response) {
                    Swal.fire('Actualizado Correctamente')
                    me.listarIndex(1); 
                }).catch(function (error) {
                   console.log(error);
                });
                me.cerrarModal('registrar');
            },

            eliminarProductoAlmacen(idproductoalmacen){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/almacen/ingreso-producto2/desactivar',{
                        'id': idproductoalmacen
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarIndex(1);
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                        
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue desactivado',
                    'error'
                    ) */
                }
                })
            },
            activarProductoAlmacen(idproductoalmacen){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/almacen/ingreso-producto2/activar',{
                        'id': idproductoalmacen
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarIndex(1);
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });
                    
                    
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    /* swalWithBootstrapButtons.fire(
                    'Cancelado!',
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
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
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.obtenerfecha(1);
        this.classModal.addModal("registrar");
        this.listarIndex(1);
        this. listarProductos_almacen();
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>