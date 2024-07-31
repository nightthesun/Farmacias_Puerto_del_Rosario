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
                    <i class="fa fa-align-justify"></i> Traslados               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="sucursalGet_data(); abrirModal('registrar');"
                        :disabled="selectAlmTienda == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="selectAlmTienda == 0" class="error"
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
                                <select class="form-control" v-model="sucursalSeleccionada">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
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
                        <div class="col-md-4">
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
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th class="col-md-1">Cliente</th>
                        <th class="col-md-5">Nro docuemnto</th>
                        <th>Tipo de comprobante</th>
                        <th>Numero de comprobante</th>
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Destino</th>
                        <th>Vehiculo</th>
                        <th class="col-md-3">Observación</th>
                        <th class="col-md-2">Per. Enviada</th>
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
                                <div class="card">
  <div class="card-body">
   <span><strong>NIT: {{nit}}</strong></span> 
   <span style="padding-left: 20px;"><strong>Nombre: {{nom_empresa}}</strong></span>
  </div>
</div>
                                <div class="form-group row">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Sucursales: <span  v-if="sucursalSeleccionada===0" class="error">(*)</span></strong>
                                              
                                                <select class="form-control"  v-model="sucursalSeleccionada">
                                                    <option value="0" disabled selected>Seleccionar...</option>
                                                    <option v-for="sucursal in arraySucursal_2" :key="sucursal.id"  :value="sucursal.id"
                                                    v-text="sucursal.cod +' -> ' +sucursal.razon_social+' NIT:' +sucursal.nit">
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Numero de autorización: <span  v-if="autorizacion==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" placeholder="Escribir el numero de autorización"  v-model="autorizacion">                                              
                                                <span  v-if="autorizacion==''" class="error">Debe ingresar numero de autorización</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Número identificación:</strong>
                                                <input type="text" class="form-control" placeholder="Escriba el numero de indentificación" v-model="identificacion" v-on:focus="selectAll" >
                                               
                                            </div>
                                        </div> 
                                            <div class="row">
                                                <div class="form-group col-sm-4" >
                                                    <strong>Llave de dosificación: <span  v-if="fecha_a==''" class="error">(*)</span></strong>
                                                    <input  type="text" class="form-control" placeholder="Ingrese llave doficicacion"   v-model="dosificacion">                                  
                                                    <span  v-if="dosificacion==''" class="error">Debe ingresar la fecha de emision</span>
                                                </div>

                                                <div class="form-group col-sm-4">
                                                    <strong>Fecha activación: <span  v-if="fecha_a==''" class="error">(*)</span></strong>
                                                    <input  type="date" class="form-control" placeholder="Ingrese la fecha de emision"  v-model="fecha_a" v-on:focus="selectAll" required>
                                                    <span  v-if="fecha_a==''" class="error">Debe ingresar la fecha de emision</span>   
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <strong>Fecha limite de emision: <span  v-if="fecha_e==''" class="error">(*)</span></strong>
                                                    <input type="date"  class="form-control" placeholder="Ingrese el limite de emision"  v-model="fecha_e" v-on:focus="selectAll">
                                                    <span  v-if="fecha_e==''" class="error">Debe ingresar la fecha de emision</span>
                                                </div>                                             
                                            </div>    
                                            <div class="row">
                                                <div class="form-group col-sm-4" >
                                                    <strong>Nro. Inicio facturación: <span  v-if="n_ini_facturacion==''" class="error">(*)</span></strong>
                                                    <input  type="number" class="form-control" placeholder="Ingrese llave doficicacion"   v-model="n_ini_facturacion" style="text-align: right;">                                  
                                                    <span  v-if="n_ini_facturacion==''" class="error">Debe ingresar la fecha de emision</span>
                                                </div>

                                                <div class="form-group col-sm-4">
                                                    <strong>Nro. Final de facturación: <span  v-if="n_fin_facturacion==''" class="error">(*)</span></strong>
                                                    <input  type="number" class="form-control" placeholder="Ingrese la fecha de emision"  v-model="n_fin_facturacion" v-on:focus="selectAll" style="text-align: right;" required>
                                                    <span  v-if="n_fin_facturacion==''" class="error">Debe ingresar la fecha de emision</span>   
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <strong>Nro. actual de facturación: <span  v-if="n_act_facturacion==''" class="error">(*)</span></strong>
                                                    <input type="number"  class="form-control" placeholder="Ingrese el limite de emision"  v-model="n_act_facturacion" v-on:focus="selectAll" style="text-align: right;">
                                                    <span  v-if="n_act_facturacion==''" class="error">Debe ingresar la fecha de emision</span>
                                                </div>                                             
                                            </div>                                    
                                    </div>
                                    <hr>
                                    <div class="row" >
                 <table class="table table-bordered table-sm table-responsive table-primary ">
                     <thead>
                         <tr>
                             <th class="col-1">Opciones</th>
                             <th class="col-2">Sucursal</th>
                             <th class="col-5">Descripción</th>
                                                       
                         </tr>
                     </thead>
                     <tbody>
                        <tr v-for="venta in arrayVentas" :key="venta.id">
                            <td></td>
                        </tr>   
                    </tbody>       
                    </table>
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
          

            tituloModal: "",
    
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
            endDate: '',

            //------------------
            sucursalSeleccionada:0,
            autorizacion:'',
            identificacion:'',
            dosificacion:'',
            fecha_a:'',
            fecha_e:'',
            n_ini_facturacion:'',
            n_fin_facturacion:'',
            n_act_facturacion:'',

            arraySucursal_2:[],
            nom_empresa:'',
            nit:'',
            arrayCargar_dosificacion:[],   
            contador:0,
            nom_sucursal:'',
            cod_sucursal:'',

        };
    },

    watch: {
        sucursalSeleccionada: function (newValue) {
             let newTipo = this.arraySucursal_2.find(
                    (element) => element.id === newValue,
                );

                if (newTipo) {
                    this.nom_sucursal = newTipo.razon_social;
                    this.cod_sucursal =newTipo.cod;
                }         

        }
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

        cargarDosificacion(){
            let me = this;
            if (me.sucursalSeleccionada===0||me.autorizacion===""||me.identificacion===""||me.dosificacion===""||me.fecha_a===""||me.fecha_e===""||me.n_ini_facturacion===""||me.n_fin_facturacion===""||me.n_act_facturacion==="") {
                Swal.fire(
                    "Error",
                    "Existe datos nulos debe revisar que datos son nulos",
                    "error"
                );
            }else{
                me.contador++;
                me.arrayCargar_dosificacion.push({id_contador:me.contador,id_sucursal:me.sucursalSeleccionada,cod:me.cod_sucursal,nom_sucursal:me.nom_sucursal});
                me.sucursalSeleccionada=0;
                me.autorizacion="";
                me.identificacion="";
                me.dosificacion="";
                me.fecha_a="";
                me.fecha_e="";
                me.n_ini_facturacion="";
                me.n_fin_facturacion="";
                me.n_act_facturacion="";
            }
         },

        sucursalGet_data(){
            
            let me = this;
     
           var url = "/dosificacion/getDataSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_s = response.data.sucursal;
                    var respuesta_c = response.data.credecion;
                   // me.arraySucursal = respuesta;
                    console.log(respuesta_s);
                    console.log(respuesta_c);
                    me.arraySucursal_2 = respuesta_s;
                    me.nit=respuesta_c.nit;
                    me.nom_empresa=respuesta_c.nom_empresa;
                 
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
                    
                    me.tituloModal = "Registro de Dosificación";
                    me.sucursalSeleccionada=0;
                    me.autorizacion="";
                    me.identificacion="";
                    me.dosificacion="";
                    me.fecha_a="";
                    me.fecha_e="";
                    me.n_ini_facturacion="";
                    me.n_fin_facturacion="";
                    me.n_act_facturacion="";
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
               
                    me.tituloModal = " ";
             
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
