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
                    <i class="fa fa-align-justify"></i> Corregir caja               
                   
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="listarIndex(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_almacen!=null"
                                        v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' '+sucursal.razon_social">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Buscar por usuario, codigo de caja, codigo "
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"/>
                                <button type="submit" class="btn btn-primary"
                                @click="listarIndex(1)"  
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                        
            </div>             
    <div class="form-group row"  :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                      
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" :disabled="sucursalSeleccionada===0" @change="listarIndex(0)">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="sucursalSeleccionada===0" @change="listarIndex(0)">
        </div>        
    </div> 
      
  <br>
    <div class="alert alert-warning" role="alert" v-if="sucursalSeleccionada===0">
        <span>Debe elegir una opción</span>
    </div>
    <div v-else>
  <!---inserte tabla-->
  <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Codigo Nombre</th>
                        <th class="col-md-2">Nombre</th>
                        <th class="col-md-1">Nro apertura</th>
                        <th class="col-md-1">Nro cierre</th>
                        <th class="col-md-1">Nro arqueo</th>                  
                        <th class="col-md-1">Total apertura</th>
                        <th class="col-md-1">Total cierre</th>
                        <th class="col-md-1">Diferencia</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>                
                <tr v-for="i in arrayIndex" :key="i.id">  
                    <td>
                        <div  class="d-flex justify-content-start">                          
                            <button type="button" v-if="i.id_mod_v2===null" class="btn btn-warning btn-sm" @click="abrirModal('registrar',i )" style="margin-right: 5px;"><i class="icon-pencil"></i></button>
                            <button type="button" v-else class="btn btn-light btn-sm" style="margin-right: 5px;"><i class="icon-pencil"></i></button>
                             
                          
                            <button type="button"  class="btn btn-info btn-sm"  style="margin-right: 5px; color: white;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        </div>                       
                    </td>
                    <td class="col-md-1">{{i.codigo}}</td>
                    <td class="col-md-2">{{i.nombre_caja}}</td>
                    <td class="col-md-1">{{i.id_apertura}}</td>
                    <td class="col-md-1">{{i.id}}</td>
                    <td class="col-md-1">{{i.id_arqueo}}</td>
                    <td class="col-md-1">{{i.total_apertura+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.total_cierre+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.diferencia_caja+" "+i.moneda}}</td>
                    <td class="col-md-1">{{i.created_at}}</td>
                    <td class="col-md-2">{{i.name}}</td>
                    <td>{{ i.estado_caja }}</td>
                </tr>
            </table>    
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
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar" aria-hidden="true"
            data-backdrop="static" data-key="false" >
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
                                
                                <div class="form-group row">
                                    <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Nro cierre</th>                                                               
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Diferencia</th>                                    
                                    <th class="col-md-2" style="font-size: 11px; text-align: center">Estado</th>
                                    <th class="col-md-6" style="font-size: 11px; text-align: center">Motivo</th>                                   
                                 </tr>
                            </thead>
                            <tbody>
                                <td class="col-md-2" style="font-size: 11px; text-align: center">{{id_cierre_modal}}</td>
                                <td class="col-md-2" style="font-size: 11px; text-align: center">{{diferencia_modal}}</td>
                                <td class="col-md-2" style="font-size: 11px; text-align: center">{{estado_modal}}</td>                        
                                <td class="col-md-6" style="font-size: 11px; ">
                                <textarea v-model="textArea_modal" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
                                </td>
                            </tbody> 
                      
                        </table>
                                   
        
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
                            :disabled="textArea_modal===''"
                        >
                        Corregir
                        </button>
                        <button
                            type="button"
                            v-if="tipoAccion == 2"
                            class="btn btn-primary"
                          
                        >
                            Actualizar
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button  type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crear()">Corregir</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Corregir</button>
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
//Vue.use(VeeValidate);
export default {
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
            isSubmitting: false, // Controla el estado del botón de envío
            offset:3,
            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
         
            arrayIndex:[],
            id_sucursal:'',

            startDate: '',
            endDate: '',  
            id_cierre_modal:'',
            diferencia_modal:'',
            estado_modal:'',
            textArea_modal:'',
            
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
    watch: {
        sucursalSeleccionada: function (newValue) {
           
        let s = this.arraySucursal.find(
                    (element) => element.codigo === newValue);
            if (s) {               
                this.id_sucursal = s.id_sucursal;  
            }        
        },

    },
    methods: {

        crear(){
            let me = this;     
            if (me.textArea_modal==="" || me.textArea_modal===null) {
                Swal.fire("debe llenar el motivo","Haga click en Ok","error",); 
            } else {
                            // Si ya está enviando, no permitas otra solicitud
        if (me.isSubmitting) return;
        me.isSubmitting = true; // Deshabilita el botón    
                 
                axios.post("/caja_modificar/registrar", {             
                id_cierre_modal:me.id_cierre_modal,
                diferencia_modal:me.diferencia_modal,
                estado_modal:me.estado_modal,
                textArea_modal:me.textArea_modal, 
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

        listarIndex(page) {
            let me = this;  
            
            var url ="/caja_modificar/listarInicio?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_sucursal+"&ini="+me.startDate+"&fini="+me.endDate;
          
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;
            
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
                    me.isSubmitting=false;
                    me.tituloModal = "Registro de "+data.nombre_caja;
                    console.log(data);                      
                    me.id_cierre_modal=data.id;
                    me.diferencia_modal=data.diferencia_caja;
                    me.estado_modal=data.estado_caja;
                    me.textArea_modal="";
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
                me.isSubmitting=false;
                me.id_cierre_modal="";
                    me.diferencia_modal="";
                    me.estado_modal="";
                    me.textArea_modal="";
                
            }
        },

        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 20);
    // Formatear el año, mes y día para la fecha de inicio
    const startYear = startDate.getFullYear();
    const startMonth = String(startDate.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const startDay = String(startDate.getDate()).padStart(2, '0');
    // Formatear el año, mes y día para la fecha final (hoy)
    const endYear = today.getFullYear();
    const endMonth = String(today.getMonth() + 1).padStart(2, '0');
    const endDay = String(today.getDate()).padStart(2, '0');
    // Asignar las fechas a los campos correspondientes
    this.startDate = `${startYear}-${startMonth}-${startDay}`;  // Fecha de inicio (5 días antes)
    this.endDate = `${endYear}-${endMonth}-${endDay}`;  // Fecha final (hoy)
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
