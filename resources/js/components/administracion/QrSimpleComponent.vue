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
    <a class="nav-link " id="pills-credencial-tab" data-toggle="pill" href="#pills-credencial" role="tab" aria-controls="pills-credencial" aria-selected="false" @click="cambioPestañaInf(1,0,0);limpiar(0);">Credenciales QR</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-lista-tab" data-toggle="pill" href="#pills-lista" role="tab" aria-controls="pills-lista" aria-selected="false" @click="cambioPestañaInf(0,1,0);listarQR();">Lista de QR</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false" @click="cambioPestañaInf(0,0,1);">Contact</a>
  </li>
</ul>
                </div>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
  <!------------------------------credencial------------------------------------------------------->
  <div class="tab-pane fade" id="pills-credencial" role="tabpanel" aria-labelledby="pills-credencial-tab" v-show="show_1==1&&show_2==0&&show_3==0">

    <div class="row">                              
        <div class="form-group col-sm-4" >
            <label for="">Nombre servicio:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el nombre del servicio ejemplo QR en banco patito"  v-model="nomServicio">                                  
            <span  v-if="nomServicio==''" class="error">Debe ingresar la nombre servicio</span>
        </div>
        <div class="form-group col-sm-4" >           
           <label for="">Tipo QR</label>
          <select class="form-control" v-model="selectBanco" @change="seleccionBanco_data(selectBanco)">
              <option value="0" disabled selected>Seleccionar...</option>
                <option v-for="(i, index) in arrayBanco" :key="index" :value="i.id"
               v-text="i.nombre + ' -> ' + i.sigla">                                        
                </option>                                   
            </select>
             <span  v-if="selectBanco=='0'" class="error">Debe ingresar la selección</span>
        </div>
        <div class="form-group col-sm-4" >
            <label for="">Url:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el url de la identidad que presta el servicio"  v-model="url_">                                  
            <span  v-if="url_==''" class="error">Debe ingresar la URL</span>
        </div>                            
    </div> 
    <div class="row">
       <div class="form-group col-sm-4" >
            <label for="">Usuario:</label>
            <input  type="text" class="form-control" placeholder="Ingresar usuario dada por la identidad"  v-model="usuario_">                                  
            <span  v-if="usuario_==''" class="error">Debe ingresar el usuario</span>
        </div>  
        <div class="form-group col-sm-4" >
            <label for="">Contraseña:</label>
            <input  type="text" class="form-control" placeholder="Ingresar contraseña dada por la identidad"  v-model="contraseña_">                                  
            <span  v-if="contraseña_==''" class="error">Debe ingresar la contraseña</span>
        </div>  
        <div class="form-group col-sm-4" style="margin-top: 15px;" >
            <button type="button" class="btn btn-warning btn-lg btn-block" @click="crearServicioQr()" style="color: white;" :disabled="isSubmitting==true">Añadir</button>         
        </div>        
    </div>

  </div>
  <!------------------------------lista------------------------------------------------------->
  <div class="tab-pane fade" id="pills-lista" role="tabpanel" aria-labelledby="pills-lista-tab" v-show="show_1==0&&show_2==1&&show_3==0">
     <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>       
                        <th class="col-md-1">Opcion</th>                
                        <th>Nombre</th>
                        <th>Banco</th>                       
                        <th>Token</th>
                        <th>URL</th>                        
                        <th>Fecha creacion</th>                        
                        <th>Usuario</th> 
                        <th class="col-md-1">En uso</th>
                        <th class="col-md-1">Estado</th>                         
                    </tr>
                </thead>
          <tbody>
  <template v-for="(i, index) in arrayQR" :key="i.id">
                    <tr> 
                         <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px; color: white;" data-toggle="collapse" :data-target="'#collapseExample' + index" :aria-expanded="false" aria-controls="collapseExample" @click="actualizarPorId(i,$event)"> 
                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                                </button> 
                                </div>
                                <div v-else>
                                <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="fa fa-angle-double-down" aria-hidden="true"></i>
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
                            <div v-if="puedeHacerOpciones_especiales==1">
                                <button v-if="i.prioridad==1" type="button" class="btn btn-success btn-sm"  @click="activar_desactivarPri(i.id,0)"  style="margin-right: 5px;">
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </button>
                            <button v-else type="button" class="btn btn-primary btn-sm"  @click="activar_desactivarPri(i.id,1)"  style="margin-right: 5px;">
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                            </button>
                            </div>
                            <div v-else>
                                <button v-if="i.prioridad==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i>
                                </button>
                                <button v-else type="button" class="btn btn-light btn-sm" style="margin-right: 5px;">
                                <i class="icon-check"></i>
                                </button>
                            </div>
                            </div> 
                        </td>
                        <td >{{i.nom_servicio_qr}}</td>
                        <td >{{i.nombre}}</td>
                        <td>{{i.token_qr}}</td>
                        <td>{{i.url_banco_servicio}}</td>
                        <td>{{i.updated_at}}</td>
                        <td>{{i.name}}</td>
                        <td class="col-md-1">
                            <div v-if="i.prioridad==0">
                                <span class="badge badge-primary">NO</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-success">SI</span>
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
                      <!-- Fila colapsable -->
    <tr>
      <td colspan="9">
        <div class="collapse" :id="'collapseExample' + index">
          <div class="card card-body">
            <div class="row">                              
        <div class="form-group col-sm-4" >
            <label for="">Nombre servicio:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el nombre del servicio ejemplo QR en banco patito"  v-model="nomServicio">                                  
            <span  v-if="nomServicio==''" class="error">Debe ingresar la nombre servicio</span>
        </div>
        <div class="form-group col-sm-4" >           
           <label for="">Tipo QR</label>
          <select class="form-control" v-model="selectBanco" @change="seleccionBanco_data(selectBanco)">
              <option value="0" disabled selected>Seleccionar...</option>
                <option v-for="(i, index) in arrayBanco" :key="index" :value="i.id"
               v-text="i.nombre + ' -> ' + i.sigla">                                        
                </option>                                   
            </select>
             <span  v-if="selectBanco=='0'" class="error">Debe ingresar la selección</span>
        </div>
        <div class="form-group col-sm-4" >
            <label for="">Url:</label>
            <input  type="text" class="form-control" placeholder="Ingresar el url de la identidad que presta el servicio"  v-model="url_">                                  
            <span  v-if="url_==''" class="error">Debe ingresar la URL</span>
        </div>                            
    </div> 
    <div class="row">
       <div class="form-group col-sm-4" >
            <label for="">Usuario:</label>
            <input  type="text" class="form-control" placeholder="Ingresar usuario dada por la identidad"  v-model="usuario_">                                  
            <span  v-if="usuario_==''" class="error">Debe ingresar el usuario</span>
        </div>  
        <div class="form-group col-sm-4" >
            <label for="">Contraseña:</label>
            <input  type="text" class="form-control" placeholder="Ingresar contraseña dada por la identidad"  v-model="contraseña_">                                  
            <span  v-if="contraseña_==''" class="error">Debe ingresar la contraseña</span>
        </div>  
        <div class="form-group col-sm-4" style="margin-top: 15px;" >
            <button type="button" class="btn btn-warning btn-lg btn-block" @click="crearServicioQr()" style="color: white;" :disabled="isSubmitting==true">Añadir</button>         
        </div>        
    </div>
            Contenido del detalle para {{ i.nom_servicio_qr }}
          </div>
        </div>
      </td>
    </tr>

  </template>
          
                </tbody>
    </table>    
  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" v-show="show_1==0&&show_2==0&&show_3==1">...</div>
</div>
            
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
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">
                                
                                <div class="form-group row">
                                   
                                   
        
                                </div>
                              
                            
                               
                            </div>
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" >
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">
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
            //offset:3,

            show_1:0,
            show_2:0,
            show_3:0,

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',


            nomServicio:'',
            url_:'',
            usuario_:'',
            contraseña_:'',          
            isSubmitting : false, 
            selectBanco:'0',

             arrayBanco:[],
            arrayQR:[],
 //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //----

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

//-----------------pestaña listar,editar,activar,desactivar---------------------

limpiar(){
    let me=this;    
    me.nomServicio="";
    me.url_="";
    me.usuario_="";
    me.contraseña_="";         
    me.isSubmitting = false; 
    me.selectBanco="0";     
},

actualizarPorId(data,data2){
    let me=this;    
    
  const expanded = event.currentTarget.getAttribute('aria-expanded');

    // expanded es string: "true" o "false"
    if (expanded=='false') {
        console.log(data);
         me.nomServicio="";
    me.url_="";
    me.usuario_="";
    me.contraseña_="";         
    me.isSubmitting = false; 
    me.selectBanco="0";  
    }
    console.log( expanded);
      
},

activar_desactivarPri(id,puntero){
                let me=this;
                 let title_="";
                 let text_="";
                 let Desactivado_="";
                 let pieMSN="";
                if (puntero===0) {
                    title_="Esta Seguro de Desactivar ya que todo no seleccionados se desactivaran?";
                    text_="Es una eliminacion logica";   
                    Desactivado_="Desactivado!";
                    pieMSN="El registro a sido desactivado Correctamente";              
                } else {
                     title_= "Esta Seguro de Activar solo puede estar activo uno?";
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
                     axios.put('/qr_simple/activar_desactivarPri',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarQR(1);
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
                     axios.put('/qr_simple/activar_desactivar',{
                        'id': id,
                        'puntero':puntero
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            Desactivado_,
                            pieMSN,
                            'success'
                        )
                        me.listarQR(1);
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

        
        listarQR() {
            let me = this;
           var url = "/qr_simple/listarQR";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayQR = respuesta;
                    console.log(me.arrayQR);
                 
                })
                .catch(function (error) {
                    error401(error);                
                });
        },

        crearServicioQr(){    
            let me = this;  
            const data_1=me.nomServicio;
            const data_2=me.usuario_;
            const data_3=me.contraseña_;
            const data_4=me.selectBanco;
            if (data_1==null || data_1=== '' || data_2==null || data_2=== ''||
                data_3==null || data_3=== '' || data_4=='0' || data_4==0
            ) {
            Swal.fire("Error", "Registro nullo o vacio","error");
            } else {
                me.isSubmitting=true;       
                 axios.post("/qr_simple/crearBanco", {
                    nombre:data_1,
                    usuario:data_2,                
                    contraseña:data_3,
                    banco:data_4,   
                    url:me.url_                          
                })
                .then(function (response) {                  
                    let respuesta=response.data;
                    console.log(respuesta);
                    me.nomServicio="";
                    me.usuario_="";
                    me.contraseña_="";
                    me.selectBanco="0";
                    me.isSubmitting = false;
                    me.url_="";
                    if (respuesta===0) {
                         Swal.fire("Registro creado!","Correctamente","success");
                    } else {
                         Swal.fire("Error:",respuesta,"error");
                    }                                                                   
                })                
                .catch(function (error) {
                    error401(error);
                    me.isSubmitting = false;
                });   
            }                         
        },

        seleccionBanco_data(id){
            let me=this;
            const banco = me.arrayBanco.find(e => e.id === id);
            if (banco) {
            console.log("existe");
            console.log("nombre:", banco.nombre);        
            } else {
            console.log("no existe");
            }
         },

        cambioPestañaInf(data_1,data_2,data_3){
            let me=this;
            me.show_1=data_1;
            me.show_2=data_2;
            me.show_3=data_3;
        },

        listarbanco() {
            let me = this;
           var url = "/qr_simple/listarbanco";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayBanco = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);                
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
                    me.tituloModal = "Ejemplo titulo";
                    me.showModal = true;
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

        fecha_inicial(){
            // Obtener la fecha actual
    const today = new Date();
    // Obtener el año, mes y día actual
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const day = String(today.getDate()).padStart(2, '0');

    // Asignar la fecha del primer día del mes al input de fecha de inicio
    this.startDate = `${year}-${month}-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`;
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
                me.tituloModal = " ";
             
             
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
        this.fecha_inicial();
        this.listarPerimsoxyz();
        this.listarbanco();
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
