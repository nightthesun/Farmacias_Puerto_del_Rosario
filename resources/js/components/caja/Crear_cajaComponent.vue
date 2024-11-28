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
                        @click="listarUsuario(0); abrirModal('registrar');"
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
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex()">
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
                                    placeholder="Buscar por nombre de caja o  codigo de caja"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)" 
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
                        <th class="col-md-2">Codigo de caja</th>
                        <th class="col-md-2">Nombre de caja</th>
                        <th class="col-md-2">Usuarios</th>
                        <th class="col-md-2">Monto por sucursal</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Estado</th>   
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">     
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1 && i.estado==1">  
                                    <button type="button"  class="btn btn-warning btn-sm" @click="abrirModal('actualizar',i);" style="margin-right: 5px;"><i class="icon-pencil"></i></button> 
                                </div>
                                <div v-else>
                                    <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;"><i class="icon-pencil"></i></button>
                                </div>
                                <div v-if="puedeActivar==1">
                           
                            <button v-if="i.estado==1"  type="button" class="btn btn-danger btn-sm" @click="eliminar(i.id)" style="margin-right: 5px;"><i class="icon-trash"></i></button>
                            <button v-else type="button"   class="btn btn-info btn-sm" @click="activar(i.id)" style="margin-right: 5px;"><i class="icon-check"></i></button>                            
                      
                                </div>
                                <div v-else>   
                                    <button v-if="i.estado == 1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">11
                                    <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm"  tyle="margin-right: 5px;">
                                    <i class="icon-check"></i>
                                    </button>
                                </div>
                                <button type="button" class="btn btn-info btn-sm"  style="margin-right: 5px; color: white" @click="abrirModal('ver',i); listarUsuarioNormal(i.array_user);"><i class="fa fa-eye" aria-hidden="true"></i></button>                               
                            </div> 
                        </td>
                        <td class="col-md-2">{{i.codigo}}</td>
                        <td class="col-md-2">{{i.nombre_caja}}</td>
                        <td class="col-md-2">{{ i.name }}</td>
               
                        <td class="col-md-2">{{i.monto_caja+" "+i.moneda}}</td>
                        <td class="col-md-2">{{ i.fecha_mas_reciente }}</td>
                        <td class="col-md-1">
                            <span v-if="i.estado===1" class="badge badge-pill badge-success">Activado</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                        </td>
                    </tr>
                </tbody>
            </table>   
                 <!-----fin de tabla------->
                 <nav>
                    <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent=" cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
</div>
            

            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-key="false" >
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
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="editar()" :disabled="!sicompleto">Actualizar</button>
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

          <!--Inicio del modal ver-->
          <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="ver" aria-hidden="true" data-backdrop="static" data-key="false" >
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button"
                            class="close"
                            aria-label="Close"
                            @click="cerrarModal('ver')"
                        >
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped table-sm table-responsive" >
                            <thead>
                                <tr>
                                    <th class="col-md-7">Nombre</th>
                                    <th class="col-md-5">Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in arrayUser" :key="u.id">
                                    <td class="col-md-7">{{u.nom_completo}}</td>
                                    <td class="col-md-5">{{ u.name }}</td>
                                </tr>
                            </tbody>
                        </table>            
                    </div>
                  
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('ver')">Cerrar</button>
                 
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
import { toInteger } from "lodash";
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
            arrayIndex:[],
            id_index:'',
            //--selector
             //----selector multiple   
        value: [],
        options: [],  
      //-------
      nombreCaja:'',   
      monto_caja:0,  
      
      //---permisos_R_W_S
      puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
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
   //--------------------------------------------------------------  

        listarIndex(page) {
            let me = this;        
            var url ="/caja_crear/listarInicio?page="+page+"&buscar="+me.buscar+"&id_sucursal="+me.id_sucursal;         
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;  
                   // console.log(me.arrayIndex);                              
                })
                .catch(function (error) {
                    error401(error);
                 console.log(error);   
                });          
        },

         crear(){
            let me = this;     
            if (me.value.length<=0) {
                Swal.fire("lista de usuarios vacia","Haga click en Ok", "error",);    
            } else {
                      // Si ya está enviando, no permitas otra solicitud
        if (me.isSubmitting) return;
        me.isSubmitting = true; // Deshabilita el botón    
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
                           me.listarIndex();  
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
            }      
          
        },

        editar(){
            let me = this;
            if (me.value.length<=0) {
                Swal.fire("lista de usuarios vacia","Haga click en Ok", "error",);  
            } else {
                let array_id=[];
                me.value.forEach(element => {
        array_id.push(element.id);       
      }); 
                    axios.put("/caja_crear/editar", {
                id:me.id_index, 
                nombre_caja:me.nombreCaja,
                monto_caja:me.monto_caja,            
                array_id_v:array_id,   
                                                                       
                    })       
                    .then(function (response) {
                   
                        me.cerrarModal("registrar");
                        me.listarIndex();    
                        Swal.fire("Se registro exitosamente","Haga click en Ok", "success",);                                            
                    })                
                  .catch(function (error) { 
                    error401(error);
                    console.log(error);                         
            }); 
                
            
            }
     
        },

        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursal = respuesta;               
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
    
        listarUsuario(data) {
            let me = this;   
           var url = "/listarUser";        
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.options = respuesta;

                    if (me.tipoAccion===2) {
                        console.log("---entro---");
                        // Convertir la cadena en un array
                        let valores = data.split(",").map(Number); // ["2", "21", "20"]
                        console.log(valores);
                      
                        for (let i = 0; i < valores.length; i++) {
                            me.options.forEach(element => {
                                if ( valores[i] === element.id) {                                 
                                    me.value.push(element);
                                }                                
                            });
                        }
                     
                    } 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarUsuarioNormal(user) {
            let me = this;   
           var url = "/listarUserNomal?id_users="+user;
           me.arrayUser=[];
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUser = respuesta;                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
        },

        clearAll () {
      this.value = [];
    },

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
           me.arrayIndex(page);
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
                    console.log(data);
                    me.isSubmitting=false;
                    me.tituloModal = "Edición de caja";
                    me.nombreCaja = data.nombre_caja;
                   // {{value}}
                   console.log("***************");
                   me.listarUsuario(data.array_user);
                  //  me.value = [];
                  //  me.options =[];
                    me.monto_caja=data.monto_caja;
                    me.classModal.open
                    me.id_index=data.id;
            
                    me.classModal.openModal("registrar");

                    break;
                }

                case "ver":{
                    console.log(data);
                    me.tituloModal = "Nombre "+data.nombre_caja;
                    me.classModal.openModal("ver");
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
                    me.tipoAccion="";
            }
            if (accion == "ver") {
                me.classModal.closeModal(accion);
            }
        },
       
        eliminar(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/caja_crear/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIndex();  
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )                     
                        
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

            activar(id){
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
                     axios.put('/caja_crear/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIndex();  
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
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
   //-------permiso E_W_S-----
   this.listarPerimsoxyz();       
        //-----------------------
        this.classModal.addModal("registrar");
        this.classModal.addModal("ver");
    
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