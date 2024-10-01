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
                    <i class="fa fa-align-justify"></i> Proveedor               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar',listarCliente())"
                        :disabled="selectTipo == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="selectTipo">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="t in arrayTipo" :key="t.id" :value="t.id" v-text="t.tipo"></option>
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
                              
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                               
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
          
            </div>
   
  <br>
            <!---inserte tabla-->
            <div v-if="selectTipo===0" class="alert alert-warning" role="alert">
  Debe seleccionar una opcion!
</div>
            <table v-else class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Nombre</th>
                        <th class="col-md-5">Tipo</th>
                        <th>Datos adiciones</th>
                        <th class="col-md-2">Fecha/Hora</th>
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
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('registrar')">
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
                                <strong  class="col-md-3 form-control-label" for="text-input">Producto: <span v-if="selected == null" class="error" >(*)</span></strong>
                                <div class="col-md-7 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selected"
                        :options="arrayCliente"
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
                            <div v-if="selected===null" class="alert alert-info" role="alert">
                                    Debe seleccionar.
                                            </div>
                                            <div v-else class="modal-body">                      
                                                <table class="table table-bordered table-striped table-sm table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nombre</th>
                                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Nom a Facturar</th>                                                         
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Pais</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Ciudad</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Num documento</th>
                                                        
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                       
                                                            <tr> 
                                                            <td  class="col-md-1" style="font-size: 11px; text-align: center">{{selected.id}}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.name_all }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px; text-align: center">{{ selected.nom_a_facturar }}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.pais }}</td>                                                        
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.ciudad }}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.num_documento }}</td>
                                                        </tr>                                                        
                                                    </tbody>
                                                </table>                                                  
                                                <div class="container">                                
                                <div class="form-group row"  >
                                    <strong  class="col-md-3 form-control-label" for="text-input">Datos adicionales: <span v-if="dato_1 == ''" class="error" >(*)</span></strong>
                                        <div class="col-md-7 input-group mb-3">
                                            <input type="text" class="form-control rounded" placeholder="Datos adicionales" v-model="dato_1">
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
    components: { VueMultiselect },
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
          
            selected:null,
            arrayTipo:[{id:1,tipo:'Persona'},
                            {id:2,tipo:'Empresa'}],
            selectTipo:0,  
            arrayCliente:[],

            tituloModal: "",
           dato_1:'',
           
            buscar:"",
            tipoAccion:1,
       
        };
    },

    

    computed: {
       sicompleto() {
           let me = this;
           if ( me.dato_1 != "" && me.selected != null)
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
        listarCliente() {
        let me = this;           
        var url = "/proveedor/get?tipo_per_emp="+me.selectTipo;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCliente = respuesta;
                    console.log(me.arrayCliente);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        crear(){
            let me = this;
            axios.post("/proveedor/registrar", {
                datos_adicionales: me.dato_1,              
                id_persona: me.selected.id,
                selectTipo:me.selectTipo
                               
                    })       
                    .then(function (response) {
                       // console.log(response.data);
                        me.cerrarModal("registrar");
                       // me.listarIndex();                  
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    })                
                  .catch(function (error) { 
                    console.log(error.response.data);          
                  //  this.errorMessage = error.response.data; // Aquí guardamos el error
                  //  Swal.fire("Error comunicarse con el administrador",""+errorMessage,"error");               
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
                    me.tituloModal = "Registrar proveedor";
                    me.selected =null;
                    me.dato_1="Sin datos adicionales...";
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
                me.selected =null;
                me.dato_1="";
                me.tituloModal = "";         
            }
        },

        nameWithLang ({name_all,nom_a_facturar, num_documento,}) {
            
            return `${name_all} - ${nom_a_facturar} - ${num_documento} `
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

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
   
       
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