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
                    <i class="fa fa-align-justify"></i> Inversiones               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');"
                        :disabled="sucursalSeleccionada == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
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
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="changeRango();listarInicio(1)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_tienda === null"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' ' +sucursal.razon_social">
                                    </option>
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
                                 @keyup.enter="listarInicio(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              @click="listarInicio(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    
            </div>
         
  <br>
  <div class="form-group row">
                        <div class="col-md-2" style="text-align: center">
                            <label for="" :hidden="sucursalSeleccionada == 0">Rango :</label>
                         </div>
                         <div class="col-md-6">
                            <div class="input-group">
                          
                    
                    <select class="form-control"   v-model="limite_X" @change="listarInicio(1)" :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                        <option value="0" disabled selected>Seleccionar...</option>
                        <option v-for="l in arrayLimite" :key="l.id" :value="l.limite">
                            <span v-if="l.limite === 0">Todos</span>
                            <span v-else>{{ l.limite }}</span>
                        </option>
                    </select>
              
         
                             </div>
                        </div>        
                    </div>  
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-2">Distribuidor</th>
                        <th class="col-md-2">Razon social</th>
                        <th class="col-md-1">Nro documento</th>                                                
                        <th class="col-md-1">Nro comprobante</th>
                        <th class="col-md-1">Descripción</th>
                        <th class="col-md-1">Tipo</th>
                        <th class="col-md-1">Fecha/Hora</th>
                        <th class="col-md-1">Total</th>                        
                        <th class="col-md-1">Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1">
                            <button type="button"  @click="abrirModal('actualizar',i);listarDistribuidor()" class="btn btn-warning btn-sm" style="margin-right: 5px;"><i class="icon-pencil"></i></button> 
                            <button v-if="i.estado==1" type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;"><i class="icon-trash"></i></button>
                            <button v-else type="button"  class="btn btn-info btn-sm" style="margin-right: 5px;"><i class="icon-check"></i></button>                            
                        </td>
                        <td class="col-md-2">{{ i.nombre_1 }}</td> 
                        <td class="col-md-2">{{ i.nom_a_facturar }}</td> 
                        <td class="col-md-1">{{ i.num_documento }}</td> 
                        <td class="col-md-1">{{ i.nro_comprobante }}</td> 
                        <td class="col-md-1">{{ i.descripcion }}</td> 
                        <td class="col-md-1">{{ i.tipo_comprabante }}</td> 
                        <td class="col-md-1">{{ i.fecha_mas_reciente }}</td>
                        <td class="col-md-1">{{ i.total +" "+i.simbolo }}</td>
                        <td class="col-md-1">{{ i.name}}</td>                     
                        <td>
                            <span v-if="i.estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>                            
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
                                <div class="form-group row"  >
                                <strong  class="col-md-3 form-control-label" for="text-input">Distribuidor: <span v-if="selected == null" class="error" >(*)</span></strong>
                                <div class="col-md-7 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selected"
                        :options="arrayDistribuidor"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nom_a_facturar" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-350"
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
                        <div v-if="selected===null" class="alert alert-info" role="alert">Debe seleccionar.</div>
                        <div v-else class="modal-body">                      
                            <table class="table table-bordered table-striped table-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                        <th class="col-md-2" style="font-size: 11px; text-align: center">Nombre</th>
                                        <th class="col-md-2" style="font-size: 11px; text-align: center">Razon social</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Nro documento</th>
                                        <th class="col-md-2" style="font-size: 11px; text-align: center">Contacto</th>
                                        <th class="col-md-3" style="font-size: 11px; text-align: center">Linea</th>
                                        <th class="col-md-1" style="font-size: 11px; text-align: center">Tipo</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{selected.id}}</td>
                                        <td class="col-md-2" style="font-size: 11px; text-align: center">{{selected.nombre_1}}</td>
                                        <td class="col-md-2" style="font-size: 11px; text-align: center">{{selected.nom_a_facturar}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{selected.num_documento}}</td>
                                        <td class="col-md-2" style="font-size: 11px; text-align: center">{{selected.contacto}}</td>
                                        <td class="col-md-3" style="font-size: 11px; text-align: center">{{selected.nom_linea_array}}</td>
                                        <td class="col-md-1" style="font-size: 11px; text-align: center">{{selected.tipo}}</td>                                    
                                    </tr>
                                </tbody>    
                            </table>
                            <div class="row">
                                            <div class="form-group col-sm-3">
                                                <strong>Tipo de comprobante: <span  v-if="selectTipoDoc==0" class="error">(*)</span></strong>
                                                    <select name="" id=""  class="form-control" v-model="selectTipoDoc">
                                                        <option value="0" selected disabled>-Seleccione un comprobante</option>
                                                        <option value="1">Recibo</option>
                                                        <option value="2">Factura</option>                     
                                                    </select>
                                           
                                                <span  v-if="selectTipoDoc==0" class="error">Debe ingresar los datos</span> 
                                            </div>  
                                            <div class="form-group col-sm-5">
                                                <strong>Nro comprobante:</strong>
                                                <input type="text" class="form-control" placeholder="Debe ingresar numero de comprobante"  style="text-align: right;" v-model="comprobante" v-on:focus="selectAll">
                                                <span  v-if="comprobante==''" class="error">Debe ingresar los datos</span> 
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Total:</strong>
                                                <input type="number" class="form-control" placeholder="Debe ingresar el total" style="text-align: right;"  v-model="total" v-on:focus="selectAll">
                                                <span  v-if="total==''" class="error">Debe ingresar los datos</span> 
                                            </div>
                                        </div> 
                                        <div class="container">                                
                                            <div class="form-group row"  >
                                            <strong  class="col-md-3 form-control-label" for="text-input">Descripción: <span v-if="descripcion == ''" class="error" >(*)</span></strong>
                                                <div class="col-md-7 input-group mb-3">
                                                    <textarea class="form-control" aria-label="With textarea" v-model="descripcion" placeholder="Describa la inversion"></textarea>
                                                    <span  v-if="descripcion==''" class="error">Debe descripción</span> 
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
                            @click="crear()"
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
          
            arrayLimite:[{id:1,limite:10},
                {id:2,limite:20},
                {id:3,limite:50},
                {id:4,limite:100},
                {id:5,limite:200},
                {id:6,limite:0},
                ],
            limite_X:10,

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            id_sucursal:0,

            arrayIndex:[],

            offset:3,
            selected:null,
            arrayDistribuidor:[],
            simbolo:"",
            total:0.00,
            selectTipoDoc:0,
            comprobante:'',    
            descripcion:'',
            
        };
    },

    

    computed: {
        sicompleto() {
            let me = this;
            if (me.comprobante!="" && me.selected !=null &&  me.total!="" &&me.selectTipoDoc!=0 && me.comprobante)
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
    watch: {
        sucursalSeleccionada: function (newValue) {           
        let s = this.arraySucursal.find(
                    (element) => element.codigo === newValue);
            if (s) {               
                this.id_sucursal = s.id_sucursal;  
            }        
        }
    },
    methods: {

        changeRango(){
            this.limite_X=10;
         },   

         listarInicio(page){           
              let me=this;             
                var url='/inversion/listarInicio?page='+page+'&buscar='+me.buscar+'&id_sucursal='+me.id_sucursal+'&limite='+me.limite_X;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;           
                    console.log(me.arrayIndex);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
        }, 

         crear(){
            let me = this;     
                axios.post("/inversion/crear", {
                id_dis: (me.selected).id, 
                tipo_persona_empresa: (me.selected).tipo_persona_empresa,         
                tipo_comprabante: me.selectTipoDoc,        
                nro_comprobante: me.comprobante,    
                total: me.total,    
                simbolo: me.simbolo,
                id_sucursal:me.id_sucursal,
                descripcion:me.descripcion,

                    })       
                    .then(function (response) {
                                            
                        let a=response.data;
                        me.cerrarModal("registrar");
                         //  me.listarDistribuidor();  
                            if (a===null || a==="" ) {
                                Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                             
                            } else {
                                Swal.fire(""+a,"Haga click en Ok","error",); 
                            }                
                        
                    })                
                  .catch(function (error) { 
                 
                    console.log(error.response.data);          
                  //  this.errorMessage = error.response.data; // Aquí guardamos el error
                  //  Swal.fire("Error comunicarse con el administrador",""+errorMessage,"error");               
            });
        },

         listarDistribuidor(){   
            let me = this;       
            var url = "/inversion/getCliente";
            axios.get(url)
                .then(function (response) {
                    var respuesta = (response.data).distribuidor;              
                    var respuesta_moneda = (response.data).moneda; 
                     me.arrayDistribuidor =respuesta;
                    me.simbolo = respuesta_moneda[0].simbolo;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
         },

         listarGetcliente(){   
            let me = this;       
           var url = "/inversion/getCliente";
            axios.get(url)
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
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarInicio(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de inversion";
                    me.selected=null;
                    me.descripcion="";
                    me.total=0.00;
                    me.selectTipoDoc=0;
                    me.comprobante="";  
            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    console.log(data);
                    let distribuidor = me.arrayDistribuidor.find(c => c.id_distribuidor === data.id_distribuidor);
            if (distribuidor) {
                me.selected = distribuidor;
            } else {
                me.selected = null;
            }
            
                    me.classModal.openModal("registrar");

                    break;
                }
            
            }
        },

      
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.selected=null;                    
                me.total=0.00;
                me.descripcion="";
                me.selectTipoDoc=0;
                me.comprobante="";
                me.simbolo="";  
                me.tituloModal = "";
            
            }
        },

        //--------------
        nameWithLang ({nombre_1,nom_a_facturar, num_documento,tipo}) {
            
            return `${nombre_1} - ${nom_a_facturar} - ${num_documento} - ${tipo}`
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
        //--------------
     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.sucursalFiltro();
        this.listarDistribuidor();
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
