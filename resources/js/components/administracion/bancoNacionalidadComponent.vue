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
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-banco-tab" data-toggle="pill" href="#pills-banco" role="tab" aria-controls="pills-banco" aria-selected="true" @click="listarIndexBanco(1)">Bancos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-nacioPais-tab" data-toggle="pill" href="#pills-nacioPais" role="tab" aria-controls="pills-nacioPais" aria-selected="false" @click="listarIndexNacionalidad(1)">Nacionalidades y paises</a>
  </li>
</ul>
                </div>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-banco" role="tabpanel" aria-labelledby="pills-banco-tab" v-show="show_1==1&&show_2==0">
    <div>
        <i class="fa fa-align-justify"></i> Banco               
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>   
    </div> 
<!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-3">Nombre</th> 
                        <th class="col-md-2">Sigla</th>
                        <th class="col-md-4">Datos asociados</th> 
                        <th class="col-md-1">Tiene Prioridad</th>
                        <th class="col-md-1">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayIndex" :key="index">
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="abrirModal('actualizar',i);">
                                <i class="icon-pencil"></i>
                                </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                                </div>
                                <div v-if="puedeActivar==1">
                                <button v-if="i.activo==1" type="button" class="btn btn-danger btn-sm"  @click="activar_desactivar(i.id,0)"  style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-info btn-sm"  @click="activar_desactivar(i.id,1)"  style="margin-right: 5px;">
                                <i class="icon-check"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="i.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            </div> 
                        </td>
                        <td class="col-md-3">{{i.nombre}}</td>
                        <td class="col-md-2">
                            <span v-if="i.sigla==null">
                                No tiene Sigla
                            </span>
                            <span v-else>                                
                                {{i.sigla}}
                            </span>
                        </td>
                        <td class="col-md-4">
                             <div v-if="i.data==null">
                            <span>  No tiene datos relacionados</span>
                            </div>
                            <div v-else>
                                <span>{{i.data}}</span>
                            </div>                            
                        </td>
                        <td class="col-md-1">
                            <div v-if="i.prioridad==0">
                            <span class="badge badge-pill badge-secondary" >Normal</span>
                            </div>
                            <div v-else>
                                 <span class="badge badge-pill badge-primary">Ajustado</span>   
                            </div>                     
                        </td>
                        <td class="col-md-1">
                             <div v-if="i.activo==1">
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

            <!-----fin de tabla------->
  </div>
<!----------------------------inicio de nacionalidad y paises-------------------------------------->
  <div class="tab-pane fade" id="pills-nacioPais" role="tabpanel" aria-labelledby="pills-nacioPais-tab" v-show="show_1==0&&show_2==1">
     <div>
        <i class="fa fa-align-justify"></i> Nacionalidad               
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrarNacionalidad');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>   
    </div> 
    <table class="table table-bordered table-striped table-sm table-responsive" style="margin-top: 15px;">
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-2">Nacionalidad</th> 
                        <th class="col-md-2">Pais</th>
                        <th class="col-md-1">Simbolo</th> 
                        <th class="col-md-1">Codigo</th>
                        <th class="col-md-2">Fecha creacion</th>
                        <th class="col-md-2">Usuario</th>
                        <th class="col-md-1">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayIndex" :key="index">
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px;" @click="abrirModal('actualizarNacionalidad',i);">
                                <i class="icon-pencil"></i>
                                </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-pencil"></i>
                                </button> 
                                </div>
                                <div v-if="puedeActivar==1">
                                <button v-if="i.activo==1" type="button" class="btn btn-danger btn-sm"  @click="activar_desactivarNacionalidad(i.id,0)"  style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-info btn-sm"  @click="activar_desactivarNacionalidad(i.id,1)"  style="margin-right: 5px;">
                                <i class="icon-check"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="i.activo==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            </div> 
                        </td>
                        <td class="col-md-2">{{i.nombre}}</td>
                        <td class="col-md-2">{{ i.pais }}</td>
                        <td class="col-md-1">{{ i.simbolo }}</td>
                        <td class="col-md-1">
                            {{i.codigo}}                 
                        </td>
                        <td class="col-md-2">
                            {{i.updated_at}}                 
                        </td>
                        <td class="col-md-2">
                            {{i.name}}                 
                        </td>
                        <td class="col-md-1">
                             <div v-if="i.activo==1">
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
                        <a class="page-link" href="#" @click.prevent="cambiarPaginaN(pagination.current_page - 1)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active':'']">
                        <a class="page-link" href="#" @click.prevent="cambiarPaginaN(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page< pagination.last_page">
                        <a class="page-link" href="#" @click.prevent="cambiarPaginaN(pagination.current_page + 1)">Sig</a>
                    </li>
                </ul>
            </nav>                        
  </div>
</div> 
            
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   

        <!--Inicio del modal NACIONALIDAD agregar/actualizar-->
        <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal_2 }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrarNacionalidad')">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                         <form action="" class="form-horizontal">
                            <div class="row">                              
                                <div class="form-group col-sm-4" >
                                    <label for="">Nacionalidad:</label>
                                        <input  type="text" class="form-control" placeholder="Ingresar el nombre de la nacionalidad"  v-model="nacionalidad">                                  
                                        <span  v-if="nacionalidad==''" class="error">Debe ingresar la nacionalidad</span>
                                </div>
                                <div class="form-group col-sm-4" >
                                    <label for="">Pais:</label>
                                        <input  type="text" class="form-control" placeholder="Ingresar el pais"  v-model="pais">                                  
                                        <span  v-if="pais==''" class="error">Debe ingresar la nacionalidad</span>
                                </div>
                                <div class="form-group col-sm-2" >
                                    <label for="">Simbolo:</label>
                                        <input  type="text" class="form-control" placeholder="En corta descripción"  v-model="simbolo">                                  
                                        <span  v-if="simbolo==''" class="error">Debe ingresar el simbolo</span>
                                </div>
                                <div class="form-group col-sm-2" >
                                    <label for="">Codigo:</label>
                                        <input  type="text" class="form-control" placeholder="En codigo de pais"  v-model="codigo">                                  
                                        <span  v-if="codigo==''" class="error">Debe ingresar el codigo</span>
                                </div>
                                    
                            </div>                            
                         </form>                        
                    </div>                   
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrarNacionalidad')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion_2 == 1" class="btn btn-primary" @click="crearNacionalidad()"  :disabled="nacionalidad=='' || pais=='' || nacionalidad=='' || codigo=='' ||simbolo==''">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion_2 == 2" class="btn btn-primary" @click="editarNacionalidad()" :disabled="nacionalidad=='' || pais=='' || nacionalidad=='' || codigo=='' ||simbolo==''">
                            Actualizar
                        </button>
                    </div>
                    </div>    
                </div>
            </div>      
    </transition>
        <!---------------------------------------------------->
           <!--Inicio del modal BANCOS agregar/actualizar-->
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
                         <form action="" class="form-horizontal">
                            <div class="row">
<div class="form-group col-sm-2" >
                                    Nombre banco:
                                    </div>  
                                    <div class="form-group col-sm-5" >
                                        <input  type="text" class="form-control" placeholder="Ingresar el nombre del banco "   v-model="nombre_banco" style="text-align: right;">                                  
                                        <span  v-if="nombre_banco==''" class="error">Debe ingresar la sigla del banco</span>
                                    </div>
                            <div class="form-group col-sm-1" >
                                        Sigla: 
                                    </div>  
                                    <div class="form-group col-sm-4" >
                                        <input  type="text" class="form-control" placeholder="Ingresar el nombre de la sigla ejemplo ABC "   v-model="sigla" style="text-align: right;">                                  
                                        <span  v-if="sigla==''" class="error">Debe ingresar la sigla del banco</span>
                                    </div>
                            </div>                            
                         </form>                        
                    </div>                   
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crearBanco()" :disabled="nombre_banco=='' || sigla=='' || nombre_banco==null || sigla==null">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="editar()"  :disabled="nombre_banco=='' || sigla==''||nombre_banco==null || sigla==null">
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
//Vue.use(VeeValidate);
export default {
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

            tituloModal:'',       
            buscar:'',
            tipoAccion:1,
            arrayindex:[],
            tamaño:0,
            nombre_banco:'',
            sigla:'',
             //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //----
        isSubmitting : false,
        arrayIndex:[],
        id_index:0,

        //------nacionalidad-----------
        tipoAccion_2:1,
        tituloModal_2 : '',
        showModal_2 : false,
        nacionalidad:'',
        pais:'',
        simbolo:'',
        codigo:'',
        id_index_2:'',
        isSubmitting_2:false,
         
        show_1:0,
        show_2:0,
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
        });
},


//------------------------NACIONALIDAD--------------------------------------

activar_desactivarNacionalidad(id,puntero){
                let me=this;
                 let title_="";
                 let text_="";
                 let Desactivado_="";
                 let pieMSN="";
                if (puntero===0) {
                    title_="Esta Seguro de Desactivar?";
                    text_="Es una eliminacion logica";   
                    Desactivado_="Desactivado!";
                    pieMSN="El registro a sido desactivado Correctamente";              
                } else {
                     title_= "Esta Seguro de Activar?";
                text_= "Es una Activacion logica";
                 Desactivado_="Activado!";
                    pieMSN="El registro a sido Activado Correctamente";   
                }

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: title_,
        text: text_,
        icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/nacion/ativar_desactivarNacionalidad',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarIndexNacionalidad(1);
                    }).catch(function (error) {
                        error401(error);
                    
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


cambiarPaginaN(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarIndexNacionalidad(page);
        },

crearNacionalidad(){    
            let me = this;  
            const data_1=me.nacionalidad;
            const data_2=me.pais;
            const data_3=me.simbolo;
            const data_4=me.codigo;
            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== '' || data_4==null || data_4=== ''
            ) {
            Swal.fire("Error", "Registro nullo en nacionalidad, pais, simbolo y codigo","error");
            } else {
                me.isSubmitting=true;
                me.nacionalidad=me.nacionalidad.trim();
                me.pais=me.pais.trim();
                me.simbolo=me.simbolo.trim();
                me.codigo=me.codigo.trim();
        
                 axios.post("/nacion/crearNacionalidad", {
                    nombre:me.nacionalidad,
                    pais:me.pais,                
                    simbolo:me.simbolo,
                    codigo:me.codigo,                             
                })
                .then(function (response) {
                  //  me.listarCredencial();
                    me.cerrarModal('registrarNacionalidad');  
                    let respuesta=response.data;
                    if (respuesta===0) {
                         Swal.fire("Registro creado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }
                      me.isSubmitting =false;    
                     me.listarIndexNacionalidad(0);                                                
                })
                .catch(function (error) {
                    error401(error);
                });   
            }                         
        },

        editarNacionalidad(){    
            let me = this;  
            const data_1=me.nacionalidad;
            const data_2=me.pais;
            const data_3=me.simbolo;
            const data_4=me.codigo;

            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== '' || data_4==null || data_4=== ''
            ) {
            Swal.fire("Error", "Registro nullo en nacionalidad, pais, simbolo y codigo","error");
            } else {
                me.isSubmitting=true;
                me.nacionalidad=me.nacionalidad.trim();
                me.pais=me.pais.trim();
                me.simbolo=me.simbolo.trim();
                me.codigo=me.codigo.trim();
             
                 axios.put("/nacion/editarNacionalidad", {
                    id:me.id_index_2,
                    nombre:me.nacionalidad,
                    pais:me.pais,                
                    simbolo:me.simbolo,
                    codigo:me.codigo,                             
                })
                .then(function (response) {
                  //  me.listarCredencial();
                    me.cerrarModal('registrarNacionalidad');  
                    let respuesta=response.data;
                    if (respuesta===0) {
                         Swal.fire("Edicion!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }
                      me.isSubmitting =false;    
                     me.listarIndexNacionalidad(0);                                                
                })
                .catch(function (error) {
                    error401(error);
                });   
            }                         
        },


listarIndexNacionalidad(page)
            {
                let me=this;   
                me.arrayIndex=[]; 
                me.show_1=0;
                me.show_2=1;           
                var url='/nacion/listarInicio?page='+page;                         
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.query.data;           
                    
                })
                .catch(function(error){
                    error401(error);
                }); 
            },


//------------------------BANCO---------------------------------------------

activar_desactivar(id,puntero){
                let me=this;
                 let title_="";
                 let text_="";
                 let Desactivado_="";
                 let pieMSN="";
                if (puntero===0) {
                    title_="Esta Seguro de Desactivar?";
                    text_="Es una eliminacion logica";   
                    Desactivado_="Desactivado!";
                    pieMSN="El registro a sido desactivado Correctamente";              
                } else {
                     title_= "Esta Seguro de Activar?";
                text_= "Es una Activacion logica";
                 Desactivado_="Activado!";
                    pieMSN="El registro a sido Activado Correctamente";   
                }

                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: title_,
        text: text_,
        icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                
                if (result.isConfirmed) {
                     axios.put('/banco/ativar_desactivar',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarIndexBanco(1);
                    }).catch(function (error) {
                        error401(error);                
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

editar(){
 let me = this;  
            const data_1=me.nombre_banco;
            const data_2=me.sigla;
            if (data_1==null || data_1=== '' || data_2==null || data_2=== '') {
            Swal.fire("Error", "Registro nullo en nombre de banco o sigla","error");
            } else {
                me.isSubmitting=true;
                me.nombre_banco=me.nombre_banco.trim();
                me.sigla=me.sigla.trim();
                 axios.post("/banco/editar", {
                    nombre_banco:me.nombre_banco,
                    sigla:me.sigla,    
                    id:me.id_index,            

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"edicion de registro en tabal banco: "+me.nombre_banco+" "+me.sigla,     
                          
                })
                .then(function (response) {
                  //  me.listarCredencial();
                    me.cerrarModal('registrar');  
                    let respuesta=response.data;
                    if (respuesta===0) {
                         Swal.fire("Registro editado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }
                      me.isSubmitting =false;    
                      me.listarIndexBanco(0);                                                
                })
                .catch(function (error) {
                    error401(error);
                });   
            } 
},

listarIndexBanco(page)
            {
                let me=this;   
                me.show_1=1;
                me.show_2=0;
                me.arrayIndex=[];            
                var url='banco/listarIndex?page='+page;                         
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.query.data;      
                    
                })
                .catch(function(error){
                    error401(error);
                }); 
            },

crearBanco(){    
            let me = this;  
            const data_1=me.nombre_banco.trim();
            const data_2=me.sigla.trim();
            if (data_1==null || data_1=== '' || data_2==null || data_2=== '') {
            Swal.fire("Error", "Registro nullo en nombre de banco o sigla","error");
            } else {
                me.isSubmitting=true;
                me.nombre_banco=me.nombre_banco.trim();
                me.sigla=me.sigla.trim();
                 axios.post("/banco/crearBanco", {
                    nombre_banco:me.nombre_banco,
                    sigla:me.sigla,                

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"creacion de registro en tabal banco: "+me.nombre_banco+" "+me.sigla,     
                          
                })
                .then(function (response) {
                  //  me.listarCredencial();
                    me.cerrarModal('registrar');  
                    let respuesta=response.data;
                    if (respuesta===0) {
                         Swal.fire("Registro creado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }
                      me.isSubmitting =false;    
                      me.listarIndexBanco(0);                                                
                })
                .catch(function (error) {
                    error401(error);
                });   
            }                         
        },

       
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarIndexBanco(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de identidad bancaria";
                    me.showModal = true;
                    me.nombre_banco="";
                    me.sigla="";
                    me.id_index=0;
                    me.isSubmitting=false;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   me.tituloModal = "Registro de identidad bancaria";
                   me.showModal = true;
                   me.nombre_banco=data.nombre;
                   me.sigla=data.sigla;
                   me.isSubmitting=false;
            me.id_index=data.id;
                    me.classModal.openModal("registrar");

                    break;
                }
                case "registrarNacionalidad": {
                    me.tipoAccion_2 = 1;
                    me.tituloModal_2 = "Registro de identidad bancaria";
                    me.showModal_2 = true;
                    me.nacionalidad="";
                    me.pais="";
                    me.simbolo="";
                    me.codigo="";
                    me.id_index_2=0;
                    me.isSubmitting_2=false;
                    me.classModal.openModal("registrarNacionalidad");
                    break;
                }
                case "actualizarNacionalidad": {
                    me.tipoAccion_2 = 2;
                    me.tituloModal_2 = "Registro de identidad bancaria";
                    me.showModal_2 = true;
                    me.nacionalidad=data.nombre;
                    me.pais=data.pais;
                    me.simbolo=data.simbolo;
                    me.codigo=data.codigo;
                    me.id_index_2=data.id;
                    me.isSubmitting_2=false;
                    me.classModal.openModal("registrarNacionalidad");
                    break;
                }
            
            }
        },

       
        cerrarModal(accion) {
            let me = this;
          if (accion=="registrar") {
            me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";
               me.nombre_banco="";
                    me.sigla="";
                    me.id_index=0;
                    me.isSubmitting=false;
          }
           if (accion=="registrarNacionalidad") {
                me.tipoAccion_2 = "";
                    me.tituloModal_2 = "";
                    me.showModal_2 = false;
                    me.nacionalidad="";
                    me.pais="";
                    me.simbolo="";
                    me.codigo="";
                    me.id_index_2=0;
                    me.isSubmitting_2=false;
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
        this.listarPerimsoxyz();
        this.listarIndexBanco(1);
        this.classModal.addModal("registrar");
        this.classModal.addModal("registrarNacionalidad");    
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
