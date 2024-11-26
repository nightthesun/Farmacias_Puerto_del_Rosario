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
                    <i class="fa fa-align-justify"></i> Crear caja               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="listarUsuario(); abrirModal('registrar');"
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
                <div class="col-md-2" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_almacen!=null"
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
             

  <br>
            <!---inserte tabla-->
            <div class="alert alert-warning" role="alert" v-if="sucursalSeleccionada===0">
               Debe seleccionar una sucursal.     
</div>
<div v-else>
    <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Codigo de caja</th>
                        <th class="col-md-3">Nombre de caja</th>
                        <th class="col-md-3">Usuarios</th>
                        <th class="col-md-1">Monto por sucursal</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Estado</th>
                       
                            
                    </tr>
                </thead>
            </table>   
</div>
            

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
                            <div class="row">
                                <div class="form-group col-sm-7">
                                                <strong>Nombre de caja:</strong>
                                                <input type="text"  class="form-control" placeholder="Debe ingresar numero de comprobante"   v-model="nombreCaja" >
                                                <span  v-if="nombreCaja==''" class="error">Debe ingresar un nombre</span> 
                                </div>
                                <div class="form-group col-sm-5">
                                                <strong>Monto de caja:</strong>
                                                <input type="number" @input="checkInput_decimal" class="form-control" placeholder="formato permitido enteros o con punto 0.00" style="text-align: right;"  v-model="monto_caja" >
                                          
                                </div>
                                    
                                </div>  
                            <!-- insertar datos -->
                            <div class="form-group row"  >
                                <div class="col-md-12 input-group ">
                                    <multiselect v-model="value"
             :options="options" 
             :multiple="true" 
             :close-on-select="false" 
             :clear-on-select="false"
            :preserve-search="true" 
            placeholder="Seleccione una opción" 
            label="nombre" 
            :custom-label="nameWithLang_2" 
            
            track-by="id" 
            :preselect-first="false"
            selectLabel="Añadir a seleccion"
            deselectLabel="Quitar seleccion"
            selectedLabel="Seleccionado">
          
                <template #selection="{ values, search, isOpen }">
                    <span class="multiselect__single" v-if="values.length>1" v-show="!isOpen">{{ values.length }} opciones seleccionadas</span>
                    <span class="multiselect__single" v-else v-show="!isOpen">{{ values.length }} opcion seleccionada</span>
                </template>
            </multiselect>
            <button v-if="value.length>0" type="button" class="btn btn-primary" @click="clearAll()"><i class="fa fa-times" aria-hidden="true"></i></button>
            <button v-else type="button" class="btn btn-light"><i class="fa fa-times" aria-hidden="true"></i></button>
               
                                </div>     
                                <span v-if="value.length===0" class="error">Debe seleccionar al menos un usuario</span>
                            </div>  
                            <table  class="table table-bordered table-striped table-sm table-responsive" :hidden="value.length===0">
                                    <thead>
                                        <tr>
                                            <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                            <th class="col-md-2" style="font-size: 11px; text-align: center">CI</th>
                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nom Usuario</th>  
                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Nombre</th>                                                         
                                        </tr>
                                    </thead>
                                    <tbody>                                       
                                        <tr v-for="o in value" :key="o.id">
                                            <td  class="col-md-1" style="font-size: 11px; text-align: center">{{o.id}}</td>
                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ o.ci }}</td>
                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ o.name }}</td>    
                                            <td  class="col-md-3" style="font-size: 11px; text-align: center">{{ o.nom_completo }}</td>    
                                                                                               
                                        </tr>                                                        
                                    </tbody>
                                </table>        
                                        
                        </form>
                    </div>
                  
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crear()"  :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
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
import { error401 } from "../../errores";
import Multiselect from 'vue-multiselect';
//Vue.use(VeeValidate);
export default {
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
        components: { Multiselect},
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
            isSubmitting: false, // Controla el estado del botón de envío
            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,

            arrayUser:[],
            id_sucursal:'',

            //--selector
             //----selector multiple   
        value: [],
        options: [],  
      //-------
      nombreCaja:'',   
      monto_caja:0,   
        };
    },

    

    computed: {
        sicompleto() {
            let me = this;
            if ( me.nombreCaja!=""&&me.value.length!=0   )
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

        
         crear(){
            let me = this;           
                 // Si ya está enviando, no permitas otra solicitud
        if (me.isSubmitting) return;
        me.isSubmitting = true; // Deshabilita el botón 
        console.log("----");
            let array_id=[];
      me.value.forEach(element => {
        array_id.push(element.id);       
      });
          
                axios.post("/caja_crear/registrar", {             
                nombre_caja:me.nombreCaja,
                monto_caja:me.monto_caja,
                id_sucursal:me.id_sucursal,
                array_id_v:array_id,   

                    })       
                    .then(function (response) {
                                            
                        let a=response.data;
                        me.cerrarModal("registrar");
                  
                           //me.listarInicio();  
                            if (a===null || a==="" ) {
                                Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                             
                            } else {
                                Swal.fire(""+a,"Haga click en Ok","error",); 
                            } 
                    })                
                  .catch(function (error) {                  
                    console.log(error.response.data);
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
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

        checkInput_decimal(event) {
      const value = event.target.value;
      // Allow numbers with more than one decimal point (for typing purposes)
      if (/^[\d.]*$/.test(value)) {
        this.numericValue = value;
      } else {
        event.target.value = this.numericValue; // Revert to last valid value
      }
    },

        listarUsuario() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listarUser";
           me.arrayUser=[];
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.options = respuesta;
                    console.log(me.options);
                 
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
        clearAll () {
      this.value = [];
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
                    me.isSubmitting=false;
                    me.tituloModal = "Creación de caja";
                    me.nombreCaja = "";
                    me.value = [];
                    me.options =[];
                    me.monto_caja=0;
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
                    me.nombreCaja ="";
                    me.isSubmitting=false;   
                    me.tituloModal = "";             
                    me.value = [];
                    me.options =[];
                    me.monto_caja=0;
            }
        },

        nameWithLang_2 ({name,nom_completo}) {
            
            return `${name} - ${nom_completo}`
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