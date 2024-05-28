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
                    <i class="fa fa-align-justify"></i> Descuentos    

                    <button v-if="selectTabla==4" :disabled="selectTabla==0"
                        type="button"
                        class="btn btn-secondary"
                        
                        @click="abrirModal('registrar');listarProductoX();">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>   
                    <button v-else :disabled="selectTabla==0"
                    type="button"
                    class="btn btn-secondary"
                    
                    @click="abrirModal('registrar');">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Tipo de tabla:</label>
                </div>
                        <div class="col-md-6">
                            
                                        <select v-model="selectTabla" class="form-control">
                                            <option v-bind:value="0" disabled>
                                                Seleccionar...
                                            </option>
                                            <option v-for="t in arrayTabla" :key="t.id" :value="t.id" v-text="t.nombre"></option>
                                        </select>
                               
                             
                        </div>
                        <div class="col-md-4" :hidden="selectTabla==0">
                            
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"/>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
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
                               
                                <div v-if="selectTabla!=0">
                                    <div class="from-group row" >
                                        <div class="col-md-6">
                                            <strong>Nombre descuento:</strong>
                                            <input type="text" class="form-control" placeholder="Nombre Descuento" v-model="nombre_Des" v-on:focus="selectAll" >
                                        <span  v-if="nombre_Des==''" class="error">Debe Ingresar el nombre de descuento</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Descripcion:</strong>                                    
                                                <input type="text" class="form-control" placeholder="Describa como se realizara el descuento" v-model="descripcion_Des" v-on:focus="selectAll" >
                                            <span  v-if="descripcion_Des==''" class="error">Debe Ingresar la descripcion de descuento</span>
                                            
                                        </div>                                                             
                                    </div>
    <br>
                                    <div class="from-group row">
                                        <div class="col-md-4">
                                            <strong>Nombre descuento:</strong>
                                            <div class="col-auto my-1">
                                           
                                                <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="selectTipoNumPor">
                                                  <option selected :value="0">Seleccionar...</option>
                                                  <option value="1">Numeral #</option>
                                                  <option value="2">Procential %</option>
                                                  
                                                </select>
                                              </div> <span  v-if="selectTipoNumPor==0" class="error">Debe Ingresar el nombre de descuento</span>
                                        </div>
                                        <div class="col-md-8" v-if="selectTipoNumPor!=0">
                                            <strong>Monto: <span  v-if="selectTipoNumPor==1">en bolivianos</span><span  v-if="selectTipoNumPor==2">en porcentaje</span></strong>                                    
                                                <input type="number"  style="text-align: right;" class="form-control" placeholder="Ingresa valores numericos" v-model="monto" v-on:focus="selectAll" >
                                            <span  v-if="monto==''" class="error">Debe Ingresar un monto </span> 
                                            
                                        </div>                                                             
                                    </div> 
                                </div> 
                                <br>
                                <hr> 
                                <div v-if="selectTabla==2">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de cantidad o valor: <span  v-if="selectCantidad_precio==0" class="error">(*)</span></label>
                                         <div class="col-md-8">
                                            <select v-model="selectCantidad_precio" class="form-control">
                                                <option selected :value="0">Seleccionar...</option>
                                                <option value="1">Cantidad</option>
                                                <option value="2">Valor de compra</option>
                                            </select>
                                        <span  v-if="selectCantidad_precio==0" class="error">Debe eligir un tipo de descuento</span>
                                    </div>
                                    </div> 
                                    <br>
                                    <div class="from-group row" v-if="selectCantidad_precio!=0&&nombre_Des!=''&&monto!=''">
                                        <div class="col-md-4">
                                            <strong>Regla:</strong>
                                            <div class="col-auto my-1">                       
                                               
                                            <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" v-model="selectRegla">
                                                <option selected :value="0">Seleccionar...</option>
                                                <option value="<">Menor a</option>
                                                <option value=">">Mayor a</option>
                                                <option value="=">Igual a</option>
                                              </select>
                                            </div> <span  v-if="selectRegla==0" class="error">Debe Ingresar la regla.</span>
                                        </div>  
                                       
                                        <div class="col-md-8" v-if="selectRegla!=0">
                                            <strong> <span  v-if="selectCantidad_precio==1">Cantida en unidades:</span><span  v-if="selectCantidad_precio==2">Monto en bolivianos:</span></strong>                                    
                                                <input type="number"  style="text-align: right;" class="form-control" placeholder="Debe ingresar un monto o cantidad positiva" v-model="cantidad_monto_x" v-on:focus="selectAll" >
                                            <span  v-if="cantidad_monto_x==''" class="error">Debe Ingresar una cantidad o monto</span> 
                                            
                                        </div>                                                             
                                    </div> 

                                </div> 
                                <div v-if="selectTabla==3">
                                    <span>Cliente</span>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" id="texto"  name="texto" class="form-control" placeholder="Numero de documento"
                                                    v-model="buscarCliente"/>
                                                <button type="button" class="btn btn-primary" @click=" listarClienteX()">                                               
                                                    <i class="fa fa-search"></i> 
                                                </button>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" id="texto" disabled name="texto" class="form-control" placeholder="Sin datos"
                                                 :value="'cliente: ' + nombre_cliente + ' Numero de documento ' + num_cliente"/>
                                        </div>
                                    </div>
                                   
                                </div>                             
                                <div v-if="selectTabla==4">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                       
                                            <VueMultiselect
                                            v-model="selected"
                                            :options="arrayProductoX"
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
                                            </div>
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
                           @click="registrarDescuento()"
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
          
            offset:3,
            tituloModal: "",
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,

            //---------
            selectTabla:0,
            arrayTabla:[],
            nombre_Des:'',
            descripcion_Des:'',
            selectTipoNumPor:0,
            monto:0,
            //-----
            selectCantidad_precio:0,
            selectRegla:0,
            cantidad_monto_x:0,
            buscarCliente:'',
            buscarCliente_Nom:'',
            buscarResultado:'',
          
            id_cliente:'',
            nombre_cliente:'',
            num_cliente:'',
            arrayProductoX:[],
            selected:null,
        };
    },

   

    computed: {
        sicompleto() {
           let me = this;
            if (
          
                me.nombre_Des != "" &&
                me.descripcion_Des != "" &&
                me.selectTipoNumPor !=0 && me.monto !=""
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
    
        nameWithLang ({codigo_prod,leyenda, envase,tipo_lugar}) {
            
            return `${codigo_prod} ${leyenda} ${envase} ${tipo_lugar}`
          },

          registrarDescuento(){
                let me = this;
                let codificador=0;
                let valor = me.selectTabla;
            
    switch (valor) {
    case 1:
    if (me.nombre_Des==""|| me.descripcion_Des==""||me.selectTipoNumPor==0||me.monto=="") {
                        codificador=1;
                    }
        break;
    case 2:
        if (me.selectCantidad_precio==0|| me.selectRegla==0||me.cantidad_monto_x=="") {
                        codificador=1;
                    }
        break;
    case 3:
        if (me.id_cliente==0||me.id_cliente=="") {
            codificador=1;
        }
        break;
    case 4:
        if (me.selected==null||me.selected==""||me.selected==0) {
            codificador=1;
        }
        break;    
    default:
        console.log('noexiste datos');
        break;
}
                if(codificador==1){
                    Swal.fire(
                    "Error",
                    "Existen campos nulos debe revisar que campos estan vacios", 
                    "error"
                );
                }
                else{

                    let data = {};

if (valor == 1) {
    data = {
        'id_tipo_tabla': valor,
        'tipo_tabla': 0,
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto
    };
}
if (valor == 2) {
    data = {
        'id_tipo_tabla': valor,
        'tipo_tabla': 0,
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'es_cantidad_es_monto': me.selectCantidad_precio,
        'regla': me.selectRegla,
        'cantidad_valor': me.cantidad_monto_x,
    };
}
if (valor == 3) {
    data = {
        'id_tipo_tabla': valor,
        'tipo_tabla': 0,
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'monto_descuento': me.monto,
        'id_cliente_p': me.id_cliente,
        'num_documento': me.num_cliente,
     
      
    };
}
if (valor == 4) {
    data = {
        'id_tipo_tabla': valor,
        'tipo_tabla': 0,
        'nombre_descuento': me.nombre_Des,
        'descripcion': me.descripcion_Des,
        'desc_num': me.selectTipoNumPor,
        'id_prod': me.selected.id,
        'cod_prod': me.selected.cod_prod,
        'envase': me.selected.envase,
        'tipo_tda_alm': me.selected.tipo_lugar,
        'id_linea': me.selected.id_liena

    };
}
axios.post('/descuento2/registrarDescuento', data)
    .then(function(response) {
        Swal.fire('Registrado Correctamente');
        me.cerrarModal('registrar');
        //me.listarIndex(1);  

              
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
            });

                }
                
            },


        listarProductoX() {
            let me = this;
            var url = "/descuento2/listarProductoX";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                    me.arrayProductoX =respuesta1;                  
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarClienteX() {
            let me = this;
            var url = "/descuento2/listarClienteX?num="+me.buscarCliente;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                    if(respuesta1.nom_a_facturar==undefined&&respuesta1.num_cliente==undefined){
                        me.nombre_cliente="Sin datos";
                        me.num_cliente="No se encontro datos";
                        me.id_cliente=0;
                    }else{
                        me.id_cliente=respuesta1.id;
                    me.nombre_cliente=respuesta1.nom_a_facturar;
                    me.num_cliente=respuesta1.num_documento;
                    }
               
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
        

        listarTipoTabla() {
            let me = this;
            var url = "/descuento2/listarTipoTabla";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data;
                                
                    me.arrayTabla = respuesta1;
                    console.log(me.arrayTabla);
            
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
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
                    me.tituloModal = "Registro de descuentos";
                          
                    me.nombre_Des="";
                    me.descripcion_Des="";
                    me.selectTipoNumPor=0;
                    me.monto="",
            
                    me.selectCantidad_precio=0;
                    me.selectRegla=0;
                    me.cantidad_monto_x=0;
                    me.buscarCliente="";
                    me.buscarCliente_Nom="";
                    me.buscarResultado="";        
                    me.id_cliente="";
                    me.nombre_cliente="";
                    me.num_cliente="";
                    me.selected=null;

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
                
              
                         
                    me.nombre_Des="";
                    me.descripcion_Des="";
                    me.selectTipoNumPor=0;
                    me.monto="",
            
                    me.selectCantidad_precio=0;
                    me.selectRegla=0;
                    me.cantidad_monto_x=0;
                    me.buscarCliente="";
                    me.buscarCliente_Nom="";
                    me.buscarResultado="";        
                    me.id_cliente="";
                    me.nombre_cliente="";
                    me.num_cliente="";
                    me.selected=null;
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
        this.listarTipoTabla();
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
