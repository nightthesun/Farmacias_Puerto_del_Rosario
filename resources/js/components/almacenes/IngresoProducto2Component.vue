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
                    <button
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
                                    <qrcode-vue :value="codigoQr" :size="size" level="H" />
                             
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
import QrcodeVue from 'qrcode.vue';
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import { watch } from 'vue';
//Vue.use(VeeValidate);
export default {
    components: { VueMultiselect ,QrcodeVue},
   
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
        codigoQr:'',
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


        };
    },

    

    computed: {      
        codigoQr() {
      if (this.selected.codigo_prod) {
        // Verifica si el campo de entrada está lleno
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
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
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
                    me.tituloModal = "Ingreso de productos al alamcen";
                    me.selectEntrada=0;
                    me.lote='';
                    me.fecha_vencimiento='';
                    me.registrosanitario='';
                    me.selected=null;
                    me.cantidad=0;
                    me.id_almacen=me.selectAlmTienda;
                    me.idalmacen_v='';
                    me.id_prod_producto_v='';
                    me.id_tipoentrada_v='';    
                    me.envase_v='';                    
                    me.stock_ingreso_v='';    
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
                    me.selectEntrada=0;
                    me.lote='';
                    me.fecha_vencimiento='';
                    me.registrosanitario='';
                    me.selected=null;
                    me.cantidad=0;
                    me.id_almacen=me.selectAlmTienda;
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
                axios.post('/almacen/ingreso-producto/registrar',{
                    'id_prod_producto':me.idproductoRealSeleccionado,
                    'envase':me.envaseProductoSelecionadoIngresoAlmacen,
                    'idalmacen':me.almacenselected,
                    'cantidad':me.cantidad,
                    'id_tipo_entrada':me.tipo_entrada,
                    'fecha_vencimiento':me.fecha_vencimiento,
                    'lote':me.lote,
                    'registro_sanitario':me.registrosanitario,
                    //'codigo_internacional':me.codigointernacional, 
                }).then(function(response){
                    Swal.fire('Registrado Correctamente')
                    me.cerrarModal('registrar');
                    me.listarProductosAlmacen(1);
                })
                //.catch(function(error){
                //    error401(error);
                //    console.log(error);
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
        this.classModal = new _pl.Modals();
        this.listarAlmTienda();
        this.obtenerfecha(1);
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>