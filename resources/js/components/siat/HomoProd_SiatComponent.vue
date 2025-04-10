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
                    <button v-if="puedeCrear===1" type="button" class="btn btn-secondary" @click="abrirModal('registrar');listarProducto_homo();">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>  
                      
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2">
                    <span>Datos a buscar</span>
                </div>    
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto"   @keyup.enter="listarIndex(1)"  class="form-control" placeholder="Texto a buscar por codigo, nombre prodcuto y linea" v-model="buscar"/>
                                <button type="submit" class="btn btn-primary" @click="listarIndex(1)">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                                <button type="button" class="btn btn-info" style="color: white;" @click="limpiarIndex();">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Limpiar
                    </button>      
                            </div>
                        </div>
                        
                       

            </div>
             

       
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Codigo</th>
                        <th class="col-md-2">Producto</th>
                        <th class="col-md-1">Linea</th>
                        <th class="col-md-2">Codigo actividad</th>  
                        <th class="col-md-2">Codigo producto</th>
                        <th class="col-md-3">Descripción</th>   
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1"> 
                            <div class="button-container">
                                <div  class="d-flex justify-content-start">
                                    <div  v-if="puedeEditar==1 && puedeActivar==1">
                                        <button @click="eliminar(i.id)" type="button" class="btn btn-danger" style="margin-right: 5px;">
                                            <i class="icon-trash"></i></button>                                          
                                    </div>
                                    <div v-else>
                                        <button type="button" class="btn btn-light" style="margin-right: 5px;">
                                            <i class="icon-trash"></i></button>  
                                    </div>  
                                  
                                </div>
                            </div>    
                        </td>  
                        <td class="col-md-1">{{i.codigo}}</td>
                        <td class="col-md-2">{{ i.nombre }}</td>
                        <td class="col-md-1">{{ i.linea }}</td>
                        <td class="col-md-2">{{ i.codigoActividad }}</td>
                        <td class="col-md-2">{{ i.codigoProducto}}</td>
                        <td class="col-md-3">{{i.descripcion}}</td>
                    </tr>
                </tbody>
            </table>    
 <!-----fin de tabla------->
 <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,)">Sig</a>
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

            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
              <span>&times;</span>
            </button>
                    </div>
                    <div class="modal-body">
                      
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
                                <div v-show="denotador_1===1" class="alert alert-danger" role="alert">
                                   <h3><strong>Debe quitar los elementos de la tabla antes de cambiar a modo automatico!!!!!!!</strong></h3> 
                                </div>
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
                                    <td class="col-md-1" style="font-size: 13px; text-align: center"><button type="button" @click="quitarDatos(u.id)" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></td>
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
                        <button type="button" class="btn btn-secondary" v-if="selected_modal===null || selectModo_vmodal==='0' || denotador_1===1">Guardar</button>
                        <button type="button" class="btn btn-primary" v-else @click="añadir_homo()">Guardar</button>
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
    props: ['codventana','idmodulo'],
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
             selected_modal:null,
             array_modal:[],
             array_prod:[],
             selectProd:"0",
             arrayFalso:[],
             selectModo_vmodal:"0",
             denotador_1:0,
             arrayIndex:[],

            tituloModal: "",
            buscar:"",
            tipoAccion:1,
          
            //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
     
        };
    },



    computed: {
     
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

        //-----------------------------------permisos_R_W_S        
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

        eliminar(id) {
            let me = this;           
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Desactivar?",
                    text: "Es una eliminacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let cadena="eliminacion ";
                        axios.post("/siat_homologacion/desactivar", {
                                id: id,
                                id_modulo: me.idmodulo,
                                id_sub_modulo:me.codventana, 
                                des:cadena                               
                            })
                            .then(function (response) {
                                let respuesta=response.data;
                                if (respuesta.length>0) {
                                    swalWithBootstrapButtons.fire(
                                    "Error!",
                                    "Los registros con error"+respuesta,
                                    "error",
                                );  
                                }else{
                                    me.listarIndex();
                                swalWithBootstrapButtons.fire(
                                    "Desactivado!",
                                    "El registro a sido desactivado Correctamente",
                                    "success",
                                );  
                                }                                                          
                            })                          
                           .catch(function (error) {           
                            console.log(error);

            });
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                    }
                });
        },

        limpiarIndex(){
            let me=this;
            me.buscar="";
            me.listarIndex();
        },

        listarIndex(page){
        let me=this;       
        var url = "/siat_homologacion/listarInicio?page="+page+"&buscar=" +me.buscar;
        axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                 
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
    },
   

        añadir_homo(){
            let me = this; 
            if (me.selected_modal===null || me.selectModo_vmodal==='0' || me.denotador_1===1) {
                me.cerrarModal('registrar');
                Swal.fire("Error!","Datos de insercción incorrecto","error",);
            } else {
                let array_enviar=[];
                let cadena="";
                if (me.selectModo_vmodal==="1") {
                    array_enviar=me.array_prod;
                    cadena="Tipo automatico";
                } else {
                    if (me.selectModo_vmodal==="2") {
                        array_enviar=me.arrayFalso; 
                        cadena="Tipo manual";
                    }                   
                }
                axios.post("/siat_homologacion/añadir", {
                     
                      codigoProducto:me.selected_modal.codigo,
                      codigoActividad:me.selected_modal.id_erp, 
                      array_enviar:array_enviar,

                      id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"homologacion "+cadena,  

                }).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.cerrarModal('registrar'); 
                    if (respuesta.length>0) {                                    
                    Swal.fire(
                        "Error!",
                        "De envio de datos."+respuesta,
                        "error",
                    );
                    } else {                      
                        me.listarIndex();                                      
                    Swal.fire(
                        "Accion!",
                        "Realizada correctamente",
                        "success",
                    ); 
                    }
                   
                })
                .catch(function (error) {                    
                    console.log(error401(error));
                }); 
            }                  
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
                    me.denotador_1=1;               
            }else{
                me.denotador_1=0; 
            }
        }else{
            me.denotador_1=0;
        }
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
                    me.tituloModal = "Homologacion de productos";
                    me.selected_modal=null;
                    me.selectProd="0";
                    me.arrayFalso=[];
                    me.showModal = true;
                    me.selectModo_vmodal="0";
                    me.denotador_1=0;
                   // me.classModal.openModal("registrar");
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
               // me.classModal.closeModal(accion);
               me.showModal = false;
               me.arrayFalso=[];
                    me.tituloModal = " ";
                    me.selected_modal=null;
                    me.selectProd="0";
                    me.selectModo_vmodal="0";
                    me.denotador_1=0;
          
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
        this.listarIndex();
        this.listarlistaActividad();
        this.classModal.addModal("registrar");
        this.listarPerimsoxyz();
    
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
