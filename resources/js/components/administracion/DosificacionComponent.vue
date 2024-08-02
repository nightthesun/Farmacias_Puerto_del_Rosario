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
                    <i class="fa fa-align-justify"></i> Dosificación               
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar');">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>                   
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursales:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada_intro"  @change="listarIndex(1)">
                                   
                                                    <option value="0" disabled selected>Seleccionar...</option>
                                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.id"
                                                    v-text="sucursal.cod +' -> ' +sucursal.razon_social">
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
                               
                                    :hidden="sucursalSeleccionada_intro == 0"
                                    :disabled="sucursalSeleccionada_intro == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                    :hidden="sucursalSeleccionada_intro == 0"
                                    :disabled="sucursalSeleccionada_intro == 0"
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
                        <th>Opciones</th>
                        <th class="col-md-1">Nro. Autorización</th>
                        <th class="col-md-1">Nro. Identificación</th>
                        <th class="col-md-3">Dosificación</th>
                        <th class="col-md-1">Fecha activación</th>
                        <th class="col-md-1">Fecha emision</th>                        
                        <th class="col-md-1">Nro. inicial de factura</th>
                        <th class="col-md-1">Nro. final de factura</th>
                        <th class="col-md-1">Nro. actual de factura</th>                    
                        <th class="col-md-1">Usuario</th>
                        <th class="col-md-1">Estado</th>    
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="d in arrayDosificacion" :key="d.id">
                        <td>
                            <button v-if="d.diferencia_dias>=0" type="button"  @click="abrirModal('actualizar',d);" class="btn btn-warning btn-sm"  style="margin-right: 5px;">
                            <i class="icon-pencil"></i></button>
                            <button  v-else type="button"   class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                <i class="icon-pencil"></i></button>
                        </td>
                        <td class="col-md-1" v-text="d.nro_autorizacion"></td>
                        <td class="col-md-1" v-text="d.identificacion"></td>
                        <td class="col-md-3" v-text="d.dosificacion"></td>
                        <td class="col-md-1" v-text="d.fecha_a"></td>
                        <td class="col-md-1" v-text="d.fecha_e"></td>                        
                        <td class="col-md-1" style="text-align: right" v-text="d.n_ini_facturacion"></td>
                        <td class="col-md-1" style="text-align: right" v-text="d.n_fin_facturacion"></td>
                        <td class="col-md-1" style="text-align: right" v-text="d.n_act_facturacion"></td>
                        <td class="col-md-1" v-text="d.name"></td>
                        <td class="col-md-1">
                            <span v-if="d.diferencia_dias>=0" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Expirado</span>
                          
                        </td>   
                    </tr>
                </tbody>
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
                                                    <span  v-if="dosificacion==''" class="error">Debe ingresar la dosificacion</span>
                                                </div>

                                                <div class="form-group col-sm-4">
                                                    <strong>Fecha activación: <span  v-if="fecha_a==''" class="error">(*)</span></strong>
                                                    <input  type="date" class="form-control" placeholder="Ingrese la fecha de emision"  v-model="fecha_a" v-on:focus="selectAll" required>
                                                    <span  v-if="fecha_a==''" class="error">Debe ingresar la fecha de activación</span>   
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
                                                    <span  v-if="n_ini_facturacion==''" class="error">Debe ingresar el numero de incio</span>
                                                </div>

                                                <div class="form-group col-sm-4">
                                                    <strong>Nro. Final de facturación: <span  v-if="n_fin_facturacion==''" class="error">(*)</span></strong>
                                                    <input  type="number" class="form-control" placeholder="Ingrese la fecha de emision"  v-model="n_fin_facturacion" v-on:focus="selectAll" style="text-align: right;" required>
                                                    <span  v-if="n_fin_facturacion==''" class="error">Debe ingresar el numero final</span>   
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <strong>Nro. actual de facturación: <span  v-if="n_act_facturacion==''" class="error">(*)</span></strong>
                                                    <input type="number"  class="form-control" placeholder="Ingrese el limite de emision"  v-model="n_act_facturacion" v-on:focus="selectAll" style="text-align: right;">
                                                    <span  v-if="n_act_facturacion==''" class="error">Debe ingresar numero actual</span>
                                                </div>                                             
                                            </div> 
                                            <div class="modal-body">
                                                <div class="d-flex justify-content-end">
        <button style="color: white;" @click="cargarDosificacion()" type="button" v-if="tipoAccion === 1" class="btn btn-secondary">
            <i class="fa fa-plus-square" aria-hidden="true"></i> Añadir
        </button>
    </div>
</div>

                                            
                                            <div class="row" v-if="arrayCargar_dosificacion.length>0 && tipoAccion === 1">
                                                <table class="table table-bordered table-sm table-responsive table-sm">
                     <thead>
                         <tr>
                             <th style="font-size: 12px;" class="col-1">Opciones</th>
                             <th style="font-size: 12px;" class="col-3">Sucursal</th>
                             <th style="font-size: 12px;" class="col-6">Descripción</th>
                                                       
                         </tr>
                     </thead>
                     <tbody>
                        <tr v-for="d in arrayCargar_dosificacion" :key="d.id_sucursal">
                            <td class="col-1">
                                <button @click="quitarDosificacion(d.id_contador)" style="font-size: 12px;" type="button" class="btn btn-danger btn-sm">
                                    <i class="fa fa-minus-square" aria-hidden="true"></i> Quitar</button>                               
                            </td>
                            <td style="font-size: 12px;" class="col-3">{{d.cod+"->"+d.nom_sucursal}}</td>
                            <td style="font-size: 12px;" class="col-6">{{d.dosificacion+"-"+d.identificacion+"-"+d.autorizacion}}</td>
                        </tr>   
                    </tbody>       
                    </table>    
                             
                                            </div>                                   
                                    </div>  
        
                                </div>     
                                
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" @click="registrar()" style="color: white;" v-if="tipoAccion === 1" :class="arrayCargar_dosificacion <= 0 ? 'btn btn-secondary' : 'btn btn-primary'" :disabled="arrayCargar_dosificacion<=0">
                            Guardar
                        </button>
                        <button type="button" @click="actualizar()" style="color: white;" class="btn btn-primary" v-if="tipoAccion === 2" >
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
          

            tituloModal: "",
    
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
            endDate: '',

            //------------------
            sucursalSeleccionada:0,
            sucursalSeleccionada_intro:0,
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
            nit_x_sucursal:'',
            arrayDosificacion:[],
            id_dosificacion:'',

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
                    this.nit_x_sucursal=newTipo.nit;
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
        quitarDosificacion(id){
            let me=this;
            me.arrayCargar_dosificacion = me.arrayCargar_dosificacion.filter(ve => ve.id_contador !== id);
            if (me.arrayCargar_dosificacion.length===0) {
                me.arrayCargar_dosificacion=[];
            }
        },

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
                me.arrayCargar_dosificacion.push({id_contador:me.contador,id_sucursal:me.sucursalSeleccionada,autorizacion:me.autorizacion,identificacion:me.identificacion,dosificacion:me.dosificacion,fecha_a:me.fecha_a,fecha_e:me.fecha_e,n_ini_facturacion:me.n_ini_facturacion,n_fin_facturacion:me.n_fin_facturacion,n_act_facturacion:me.n_act_facturacion,cod:me.cod_sucursal,nom_sucursal:me.nom_sucursal,nit_x_sucursal:me.nit_x_sucursal});
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

         listarIndex(page){
                let me=this;
                console.log(me.sucursalSeleccionada_intro);
                if (me.sucursalSeleccionada_intro!=0) {
                    var url='/dosificacion/index_dosificacion?page='+page+'&buscar='+me.buscar+'&id_sucursal='+me.sucursalSeleccionada_intro;
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayDosificacion = respuesta.dosificacion.data;
                    console.log(me.arrayDosificacion);
                })
                .catch(function(error){
                    error401(error);
                });     
            }              
        }, 

        actualizar() {
            let me = this;    
            if (me.sucursalSeleccionada===0||me.autorizacion===""||me.identificacion===""||me.dosificacion===""||me.fecha_a===""||me.fecha_e===""||me.n_ini_facturacion===""||me.n_fin_facturacion===""||me.n_act_facturacion==="") {
                Swal.fire(
                    "Error",
                    "Existe datos nulos debe revisar que datos son nulos",
                    "error"
                );
            }else{
                axios
                .post("/dosificacion/update_dosificacion", {
                    id: me.id_dosificacion,
                    autorizacion: me.autorizacion,
                    identificacion: me.identificacion,
                    dosificacion: me.dosificacion,
                    fecha_a: me.fecha_a,
                    fecha_e: me.fecha_e,
                    n_ini_facturacion: me.n_ini_facturacion,
                    n_fin_facturacion: me.n_fin_facturacion,
                    n_act_facturacion: me.n_act_facturacion, 
                    id_sucursal: me.sucursalSeleccionada,

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:"creacion de registro",  
                })
                .then(function (response) {
                    me.cerrarModal("registrar");
                    me.listarIndex();
                    Swal.fire(
                        "Actualizado Correctamente!",
                        "El registro a sido actualizado Correctamente",
                        "success",
                    );
                })
                .catch(function (error) {                  
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }
            });
            me.cerrarModal("registrar");
            }               
        },

         registrar(){
            let me = this;   
            console.log(me.arrayCargar_dosificacion);
            const data = {
                nit_empresa:me.nit,          
                nom_empresa:me.nom_empresa,            
                arrayCargar_dosificacion:me.arrayCargar_dosificacion,

                id_modulo: me.idmodulo,
                id_sub_modulo:me.codventana, 
                des:"creacion de registro",  
            }     
            // Realizar la solicitud POST con Axios
            axios.post("/dosificacion/store_dosificacion", data)
           
            .then(response => {
                me.cerrarModal("registrar");
                me.listarIndex();
                Swal.fire(
                        "Registro Correctamente!",
                        "El registro a fue creado correctamente",
                        "success",
                    );
             })       
          .catch(function (error) {                  
          if (error.response.status === 500) {
              me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
          Swal.fire(
              "Error",
              "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
              "error"
          );
          }else{
              Swal.fire(
              "Error",
              ""+error, // Muestra el mensaje de error en el alert
              "error"
          );  
          }              
      });
         },

        sucursalGet_data(){            
            let me = this;     
            var url = "/dosificacion/getDataSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_s = response.data.sucursal;
                    var respuesta_c = response.data.credecion;
                    me.arraySucursal_2 = respuesta_s;
                    me.nit=respuesta_c.nit;
                    me.nom_empresa=respuesta_c.nom_empresa;
                    me.arraySucursal=respuesta_s;
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
                    me.tituloModal = "Registro de Dosificación";
                    me.sucursalSeleccionada=data.id_sucursal === null ? 0 : data.id_sucursal;
                    me.autorizacion=data.nro_autorizacion;
                    me.identificacion=data.identificacion;
                    me.dosificacion=data.dosificacion;
                    me.fecha_a=data.fecha_a;
                    me.fecha_e=data.fecha_e;
                    me.n_ini_facturacion=data.n_ini_facturacion;
                    me.n_fin_facturacion=data.n_fin_facturacion;
                    me.n_act_facturacion=data.n_act_facturacion;
                    me.id_dosificacion=data.id;
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
                me.classModal.closeModal(accion);               
                me.tituloModal = " ";             
                me.contador=0;
                me.sucursalSeleccionada=0;
                me.autorizacion="";
                me.identificacion="";
                me.dosificacion="";
                me.fecha_a="";
                me.fecha_e="";
                me.n_ini_facturacion="";
                me.n_fin_facturacion="";
                me.n_act_facturacion="";
                me.arrayCargar_dosificacion=[];   
                me.id_dosificacion="";           
        },

     

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
        this.sucursalGet_data();
        this.listarIndex();
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
