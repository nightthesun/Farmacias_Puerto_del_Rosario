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
                    <i class="fa fa-align-justify"></i> Sucursal Siat               
                    <button type="button" v-if="puedeCrear==1" class="btn btn-secondary" @click="listarSucursal(); listarDepartamento();abrirModal('registrar');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>                  
                </div>
        <div class="card-body">
                
            <div class="form-group row">
             
                       
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    @keyup.enter="listarIndex(1)"
                                    v-model="buscar"
                               
                                    :hidden="arrayIndex.length ===0"
                                    :disabled="arrayIndex.length ===0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"
                                    :hidden="arrayIndex.length ===0"
                                    :disabled="arrayIndex.length ===0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
            </div>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-2">Opciones</th>
                        <th class="col-md-1">Sucursal</th>
                        <th class="col-md-1">Ciudad</th>
                        <th class="col-md-1">Codigo siat</th>
                        <th class="col-md-2">Codgios asociados</th>    
                        <th class="col-md-2">Fecha / Hora</th>                      
                        <th class="col-md-2">Usuario</th>
                        <th class="col-md-1">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-2">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                    <button type="button" class="btn btn-warning btn-sm"  @click="abrirModal('actualizar',i);listarSucursal(); listarDepartamento();" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                            </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                            </div>
                            <div v-if="puedeActivar==1">
                                <button v-if="i.estado==1" type="button" @click="desactivar(i.id)" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" @click="activar(i.id)" class="btn btn-info btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="ingresoProducto.estado==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            <button type="button" class="btn btn-info btn-sm"  style="margin-right: 5px; color: white;" @click="listarSucursalVer(i.id_sucursal);abrirModal('ver',i);">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            </div>    
                         
                            

                           
                        </td>
                        <td class="col-md-1">{{ i.nombre_suc_siat }}</td>
                        <td class="col-md-1">{{ i.departamento }}</td>
                        <td class="col-md-1">{{ i.codigo_siat }}</td>
                        <td class="col-md-2">{{ i.id_sucursal }}</td>
                        <td class="col-md-2">{{ i.updated_at }}</td>
                        <td class="col-md-2">{{ i.name }}</td>
                        <td class="col-md-1">
                            <span v-if="i.estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>
                        
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
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <div class="alert alert-info" role="alert">
                            Codigo Siat = 0, para casa matriz
                        </div>
                        <div class="alert alert-info" role="alert">
                            Solo puede seleccionar una sucursal.
                        </div>

                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row" >                                   
                                    <div class="col-md-4">
                                        <label>Nombre sucursal siat: <span v-show="nomsucursalSiat===''"></span></label>
                                        <input  type="text" class="form-control" v-model="nomsucursalSiat">
                                        <span  v-if="nomsucursalSiat==''" class="error">Debe llenar el dato</span> 
                                    </div>
                                    <div class="col-md-4">
                                    <label for="end-date">Codigo siat: <span v-show="codSiat===''"></span></label>
                                        <input type="text" class="form-control" v-model="codSiat">
                                        <span  v-if="codSiat==''" class="error">Debe llenar el dato</span> 
                                    </div>   
                                    <div class="col-md-4">
                                    <label >Departamento: <span v-show="selectDepartamento==='0'"></span></label>
                                        <select class="form-control" v-model="selectDepartamento">
                                            <option value="0" disabled selected>Seleccionar...</option>     
                                            <option v-for="depa in arrayDepartamento" :key="depa.id"  :value="depa.id">
                                                   {{ depa.nombre }} 
                                            </option>                                                               
                                        </select>
                                        <span  v-if="selectDepartamento==='0'" class="error">Debe seleccionar</span> 
                                    </div>     
                                </div>  
                                <div class="form-group row" v-show="tipoAccion===1">     
                                    <div class="col-md-2">
                                        <label >Sucursal: </label>
                                    </div>                              
                                    <div class="col-md-7">                                 
                                        <select class="form-control" v-model="selectSucursal">
                                            <option value="0" disabled selected>Seleccionar...</option>     
                                            <option v-for="s in arraySucusal_x" :key="s.id"  :value="s.id" :hidden="s.seleccionado!=0">
                                                   {{ s.cod+" - "+s.razon_social }} 
                                            </option>                                                               
                                        </select>
                                   
                                    </div> 
                                    <div class="col-md-2" >                                                                      
                                        <button  v-if="selectSucursal==='0' || controlador_v1===1" type="button" class="btn btn-light">Añadir</button>      
                                        <button  v-else type="button" class="btn btn-primary" @click="añadirDato(selectSucursal,1)">Añadir</button>          
                                    </div> 
                                </div> 
                                <table class="table table-bordered table-striped table-sm table-responsive" v-show="tipoAccion===1">
                              
                            <thead>
                                 <tr>  
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Opción</th>                                
                                    <th class="col-md-2" style="font-size: 13px; text-align: center">Codigo</th>
                                    <th class="col-md-8" style="font-size: 13px; text-align: center">Nombre</th>                                                    
                                </tr>
                            </thead>
                            <tbody>                                  
                                <tr v-for="s in arraySucusal_x" :key="s.id" v-show="s.seleccionado===1">                                   
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">  <button type="button" @click="añadirDato(s.id,0)" class="btn btn-danger btn-sm"><i class="fa fa-minus" aria-hidden="true"></i></button></td>
                                    <td class="col-md-2" style="font-size: 13px; text-align: center">{{ s.cod }}</td>
                                    <td class="col-md-8" style="font-size: 13px; text-align: center">{{ s.razon_social }}</td>
                                </tr>
                            </tbody>   
                        </table> 
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button v-if="tipoAccion===1" @click="crear()" type="button" class="btn btn-primary rounded" :disabled="!sicompleto">Guardar</button>
                                <button v-else type="button" @click="editar()" class="btn btn-primary rounded" :disabled="!sicompleto">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-else class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
                                             
                    </div>

                    </div>
                </div>
            </div>
        </transition>                

   
        <!--fin del modal-->

           <!--Inicio del modal VER-->
           <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
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
                                <table class="table table-bordered table-striped table-sm table-responsive">
    <thead>
      <tr>
        <th style="font-size: 11px;">Tipo</th>
        <th style="font-size: 11px;">Codigo</th>
        <th style="font-size: 11px;">Razón social</th>
        <th style="font-size: 11px;">Nombre comercial</th>
        <th style="font-size: 11px;">Dirección</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="v in arrayVer" :key="v.id">
        <td style="font-size: 11px;">{{ v.tipo }}</td>
        <td style="font-size: 11px;">{{ v.cod }}</td>
        <td style="font-size: 11px;">{{ v.razon_social }}</td>
        <td style="font-size: 11px;">{{ v.nombre_comercial }}</td>
        <td style="font-size: 11px;">{{ v.direccion }}</td>
      </tr>
    </tbody> 
  </table>  
                            
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded"  @click="cerrarModal('ver')">Cerrar</button>                          
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
//Vue.use(VeeValidate);
export default {
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
          offset:3,
            isSubmitting: false, // Controla el estado del botón de envío
            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            controlador_v1:0,

      nomsucursalSiat:'',
      codSiat:'',
      selectDepartamento:'0',
      selectSucursal:'0',
      id_sucursalSiat:'',
      arrayDepartamento:[],
      arraySucusal_x:[],
      arrayIndex:[],
      arrayVer:[],
      
      arrayFalasoSucursal:[],   
//---permisos_R_W_S
puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
    showModal: false,
        showModal_2: false,


        };
    },

    

    computed: {
        sicompleto() {
          let me = this;
            if ( me.nomsucursalSiat != "" && me.codSiat != "" &&  me.selectDepartamento!='0' )
            return true;
         else return false;
      },
      isActived: function () {
            return this.pagination.current_page;
        },

       function () {
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

        listarIndex(page)
            {
                let me=this;  
                 var url='/siat_su/listarInicio?page='+page+'&buscar='+me.buscar;     
                        
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.index.data;                   
                })
                .catch(function(error){
                    error401(error);
                }); 
             
               
            },

        añadirDato(data,numero){
            let me=this;                        
        let index = me.arraySucusal_x.findIndex(elemento => elemento.id === data);
        if (numero===1) {
            me.controlador_v1=1;  
        } else {
            me.controlador_v1=0;  
        }
        if (index !== -1) {        
            me.arraySucusal_x[index].seleccionado = numero;  // Modificar el campo si el id existe    
                          
        }
        me.selectSucursal='0';
        },

       crear(){
        let me = this;
        let activador=0; 
        let cadena = "";         
        me.arraySucusal_x.forEach(dato => {
           if (dato.seleccionado===1) {
            activador=1;
            if (cadena !== "") {
            cadena += ",";
        }
        cadena += String(dato.id);
           }         
        });

        if (activador===0) {
            Swal.fire(  "Error","Debe seleccionar almenos una sucursal","error" ); 
        } else {

            if (me.isSubmitting) return;
                me.isSubmitting = true; // Deshabilita el botón
                axios.post("/siat_su/crear", {
             
                nombre:me.nomsucursalSiat,
                codigo:me.codSiat,
                departamento:me.selectDepartamento,
                id_sucursal:cadena,             
                })
                .then(function (response) {
                    me.listarIndex(); 
                    let respuesta=response.data;                                   
                  
                    if (respuesta.length>0) {
                        Swal.fire(
                        "Error!",
                        ""+respuesta,
                        "error",
                    );    
                    } else {

                        Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );  
                    }
                    me.cerrarModal('registrar');
                                
                })               
                .catch(function (error) {                
                  console.log(error);                
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
            
        } 
       },

       editar(){
        let me = this; 
                axios.put("/siat_su/actualizar", { 
                id:me.id_sucursalSiat,                
                nombre:me.nomsucursalSiat,
                codigo:me.codSiat,
                departamento:me.selectDepartamento,                      
                })
                .then(function (response) {                   
                    let respuesta=response.data;                   
                    if (respuesta.length>0) {
                        Swal.fire(
                        "Error!",
                        ""+respuesta,
                        "error",
                    );    
                    } else {

                        Swal.fire(
                        "Registro creado!",
                        "Correctamente",
                        "success",
                    );  
                    }         
                    me.listarIndex();                
                    me.cerrarModal('registrar');        
                                             
                })               
                .catch(function (error) {                
                  console.log(error);                
            });
       },
       
        listarDepartamento() {
            let me = this;    
           var url = "/siat_su/departamento_siat";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                 
                    me.arrayDepartamento = respuesta;                              
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSucursalVer(data) {
            let me = this;    
            me.arrayVer=[];
           var url = "/siat_su/sucursal_siat_ver?id_sucursal="+data;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;                 
                    me.arrayVer = respuesta;              
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSucursal() {
            let me = this;       
           var url = "/siat_su/sucursal_siat";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;                  
                    me.arraySucusal_x = respuesta;              
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

        desactivar(id){
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
                     axios.put('/siat_su/desactivar',{
                        'id': id
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
                     axios.put('/siat_su/activar',{
                        'id': id
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
                    me.tituloModal = "Registro de sucursal a siat";
                    me.isSubmitting=false;
                    me.nomsucursalSiat="";
                    me.codSiat="";
                    me.selectDepartamento="0";
                    me.selectSucursal="0";
                    me.showModal = true;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "ver": {
             
                    me.tipoAccion = 1;
                    me.tituloModal = "Nombre siat: "+data.nombre_suc_siat+" Codigo siat: "+data.codigo_siat;
                    me.showModal_2 = true;
                    me.classModal.openModal("ver");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                 me.showModal = true;
                    me.isSubmitting=false;
                    me.tituloModal = "Actualización de sucursal a siat";     
                    me.nomsucursalSiat=data.nombre_suc_siat;
                    me.codSiat=data.codigo_siat;
                    me.selectDepartamento=data.id_departamento;
                    me.selectSucursal="0";   
                    me.id_sucursalSiat=data.id;    
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
                    me.isSubmitting=false;
                    me.nomsucursalSiat="";
                    me.codSiat="";
                    me.selectDepartamento="0";
                    me.selectSucursal="0";
                    me.id_sucursalSiat="";
                    me.controlador_v1=0;
                           me.showModal = false;
            }
            if (accion== "ver") {
                me.arrayVer=[];
                       me.showModal_2 = false;
                me.classModal.closeModal(accion); 
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
            //-------permiso E_W_S-----
               this.listarPerimsoxyz();        
            //-----------------------
        this.listarIndex();
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