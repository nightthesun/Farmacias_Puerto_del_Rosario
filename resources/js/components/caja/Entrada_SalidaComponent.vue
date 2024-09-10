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
                    <i class="fa fa-align-justify"></i> Apertura / Cierre de cajas               
                    <button type="button" class="btn btn-secondary" @click="verificadorAperturaCierre()"
                        :disabled="sucursalSeleccionada === 0 || selectEntradaSalida === 0 || bloqueador ===0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada === 0 ||  selectEntradaSalida === 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar una sucursal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3" style="text-align: center">
                     <label for="">Almacen / Tienda:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"  @change="cambiarEstadoSucursal()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.id_tienda===null"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' '+sucursal.razon_social"
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
            <div class="form-group row" v-if="sucursalSeleccionada !== 0">
                <div class="col-md-3" style="text-align: center">
                    <label for="">Entrada / Salida  :</label>
                </div>
                <div class="col-md-5">
            <div class="input-group">
                <select class="form-control" v-model="selectEntradaSalida" @change="listarIndex(0)">
                    <option value="0" disabled selected >Seleccionar...</option>
                    <option value="1" >Entrada</option> 
                    <option value="2" >Salida</option>                 
                </select>
            </div>
        </div>
            </div>    
        
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Nro.</th>
                        <th class="col-md-2">Fecha / Hora</th>
                        <th class="col-md-1">Valor total</th>
                        <th class="col-md-3">Receptor / Emisor</th>
                        <th class="col-md-3">Observación</th>                        
                        <th class="col-md-1">Usuario</th>
                        
                    </tr>
                </thead>
            </table>    
            <tr v-for="i in arrayIndex" :key="i.id">                    
                <td>
                    <button type="button" class="btn btn-info" style="margin-right: 5px;">
                        <i class="fa fa-book" aria-hidden="true"></i></button>
                    
                    <button type="button" class="btn btn-warning" style="margin-right: 5px;">
                        <i class="fa fa-eye" aria-hidden="true"></i></button>
                </td>
                <td>{{ i.id }}</td>
                <td>{{ i.id }}</td> 
            </tr>            

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
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Monto</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Tipo</th>
                                    <th class="col-md-1">Valor</th>
                                    <th class="col-md-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="a in arrayMoneda" :key="a.id">
                                    <td  class="col-md-1" style="text-align: right;">{{a.unidad_entera}}</td>
                                    <td class="col-md-1">{{a.unidad}}</td>
                                    <td  class="col-md-2">{{a.tipo_corte}}</td>
                                    <td  class="col-md-1" style="text-align: right;">{{a.valor_default}}</td>
                                    <td  class="col-md-3"> <input type="text" style="text-align: right;" class="form-control" placeholder="Solo valores enteros" v-model="input[a.id]"  @input="validateIntegerInput(a.id,a)" />
                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Cant. Moneda</th>
                                    <th class="col-md-2">Monto Moneda</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Cant. Billete</th>
                                    <th class="col-md-2">Monto Billete</th>
                                    <th class="col-md-1">Simbolo</th>
                                    <th class="col-md-2">Monto Total</th>
                                    <th class="col-md-1">Simbolo</th>
                                 </tr>
                            </thead>  
                            <tbody>
                                <tr>
                                    <td class="col-md-2" style="text-align: right;">{{cantidadMonedas}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{totalMonedas}}</td>
                                    <td class="col-md-1">{{SimboloM}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{cantidadBilletes}}</td>
                                    <td class="col-md-2" style="text-align: right;">{{ totalBilletas }}</td>
                                    <td class="col-md-1">{{SimboloB}}</td>
                                    <td class="col-md-2" style="text-align: right; background-color:darkred; color: azure;">{{totalMonto}}</td>
                                    <td class="col-md-1">{{SimboloB}}</td>
                                    
                                </tr>    
                            </tbody>          
                        </table>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-4">{{ titulo_ }}</th>
                                    <th class="col-md-4">Observación</th> 
                                    <th class="col-md-4">Código</th>                               
                                 </tr>
                            </thead>  
                            <tbody>
                                <tr>
                                    <td class="col-md-4" style="text-align: right;">
                                        <input type="text" class="form-control" v-model="emisor"/>    
                                    </td>
                                    <td class="col-md-4" style="text-align: right;">
                                        <input type="text" class="form-control" v-model="Obs"/>   
                                    </td> 
                                    <td class="col-md-4">
                                        <div class="d-flex align-items-center" v-if="selectEntradaSalida==='1'">
                                            <button type="button" class="btn btn-light me-2" disabled>
                                                <i class="fa fa-repeat" aria-hidden="true"></i>
                                            </button>
                                            <input type="text" class="form-control"  disabled/>
                                        </div>
                                      
                                        <div v-else class="d-flex align-items-center">
                                            <button type="button" class="btn btn-primary me-2">
                                                <i class="fa fa-repeat" aria-hidden="true"></i>
                                            </button>
                                            <input type="text" class="form-control" v-model="codigo"/>
                                       
                                        </div>                                        
                                    </td> 
                                </tr>    
                            </tbody>          
                        </table>
            
                    </div>
                
                             
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registro();">
                            Guardar
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
            sucursalSeleccionada:0,
            arraySucursal:[],
            arrayMoneda:[],
            buscar:"",
            tipoAccion:1,
            id_sucursal:0,
            bloqueador:0,
            moneda_s1:'',


      selectEntradaSalida:0,

      totalMonedas:"0.00",
            SimboloM:'S/N',
            SimboloB:'S/N',            
            totalBilletas:"0.00",
            totalMonto:"0.00",
            cantidadMonedas:0,
            cantidadBilletes:0,           
            selectTurno:0,
            input:{},

            emisor:'',
            Obs:'',
            codigo:'',

            titulo_:'',

            id_apertura_cierre:'',
            arrayIndex:[],

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
        }
    },
    methods: {

        listarIndex(page) {
            let me = this;    
            console.log("------------***");
            var url ="/entrada_salida/index?page="+page+"&buscar="+me.buscar+"&id_sucursal="+me.id_sucursal+"&entrada_salida="+parseInt(me.selectEntradaSalida);
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.respuesta.data;
                 
                    console.log(me.arrayIndex);
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        registro(){
            let me = this;
            let bandera=0;
            console.log(me.totalMonto);
            if ((me.SimboloM==="S/N"&&me.SimboloB==="S/N")||me.totalMonto==="0.00") {
                Swal.fire(
                    "Sector de monedas sin dados",
                    "Haga click en Ok",
                    "warning",
                    );
                me.cerrarModal('registrar');    
            } else {
                if (me.selectEntradaSalida==="1") {
                    if (me.emisor===""||me.Obs==="") {
                        Swal.fire(
                    "Emisor o la observacion esta nula",
                    "Haga click en Ok",
                    "warning",
                    );
                    } else {
                        //// datos de registro
                        bandera=1;
                    }   
                } else {
                    if (me.selectEntradaSalida==="2") {
                        if (me.emisor===""||me.Obs===""||me.codigo==="") {
                        Swal.fire(
                    "Emisor o la observacion esta nula, puede revisar el codigo tambien si es el correcto",
                    "Haga click en Ok",
                    "warning",
                    );
                    } else {
                    //// datos de registro
                    bandera=1;
                    }
                            } else {
                              Swal.fire(
                                 "error de entrada o salida",
                                      "Haga click en Ok",
                                    "warning",
                                         );
                                    me.cerrarModal('registrar');  
                                     }
                }
            }
            //---------------REGISTRO
            if (bandera===1) {
                axios.post("/entrada_salida/store", {
                  
                       total_arqueo_caja:me.totalMonto,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        moneda_s1:me.moneda_s1,

                                                
                    	id_sucursal:me.id_sucursal,
                        obs:me.Obs,
                        mensaje:me.emisor,
                        entrada_salida:me.selectEntradaSalida,      
                        
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,  
                        
                        id_apertura_cierre:me.id_apertura_cierre
                       
                    })
                    .then(function (response) {
                       // me.listarIndex();
                       console.log(response.data);
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    }).catch(function (error) {                 
                if (error.response) {               
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }              
            });
            } 
        },

        validateIntegerInput(id,index) {
            let me = this;
            me.cantidadMonedas=0;
           me.cantidadBilletes=0;
           me.totalMonedas=0;
           me.totalBilletas=0;
           me.totalMonto=0;
    me.input[id] = this.input[id].replace(/[^0-9]/g, '');
    if ( me.input[id]===""|| me.input[id]===null) {
        me.input[id]=0; 
        let aa=me.arrayMoneda[id-1];
        aa.valor_default="0.00";       
        aa.input=  me.input[id];
         
       
    }else{     
    
        let aa=me.arrayMoneda[id-1]; 
        aa.valor_default=Number(me.input[id] * aa.valor).toFixed(2);
        aa.input=me.input[id];
      
    }
    me.arrayMoneda.forEach(element => {
            if (element.tipo_corte==="Moneda") {
                me.cantidadMonedas=me.cantidadMonedas+  parseInt(element.input, 10);                
                me.totalMonedas = Number(me.totalMonedas) + Number(element.valor_default);
                // Si deseas que el resultado final esté formateado a dos decimales
                me.totalMonedas = Number(me.totalMonedas).toFixed(2);
                me.SimboloM=element.unidad;
      
            }
            if (element.tipo_corte==="Billete") {
                me.cantidadBilletes=me.cantidadBilletes+  parseInt(element.input, 10);             
                me.totalBilletas = Number(me.totalBilletas) + Number(element.valor_default);
                // Si deseas que el resultado final esté formateado a dos decimales
                me.totalBilletas = Number(me.totalBilletas).toFixed(2);
                me.SimboloB=element.unidad;
    
            }
            me.totalMonto=Number(me.totalMonto)+ Number(element.valor_default);   
            me.totalMonto=Number(me.totalMonto).toFixed(2);
        });  
       
    console.log("-*----");
    console.log(me.arrayMoneda);
  },

        verificador_moneda_sistemas(){
            let me=this;
            var url = "/verificador_moneda_sistemas";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_lista = response.data.listaMoneda;
                    var respuesta_moneda = response.data.moneda;

                    console.log("---*-*-*-*-*-*------");
                    console.log(respuesta_lista);
                    console.log(respuesta_moneda);
                  if (respuesta_moneda===0) {
                    me.bloqueador=0;
                    Swal.fire(
                    "No se activo el tipo de moneda necesita activar algun tipo de moneda.",
                    "Para activar necesita ir a configuracion y ver la pestaña de tipo de moneda.",
                    "warning",
                );
                  } else {
                    me.bloqueador=1;
                    me.arrayMoneda=respuesta_lista; 
                    me.moneda_s1=respuesta_moneda;
                    console.log(me.arrayMoneda);
                  }                                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        verificadorAperturaCierre(){
            let me=this;
            var url = "/verificacionAperturaCierre?id_sucursal="+me.id_sucursal;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;   
                    console.log(respuesta);       
                    if (respuesta===0||respuesta.tipo_caja_c_a===9||respuesta.id_apertura_cierre!=0) {
                        Swal.fire(
                    "Debe aperturar una caja",
                    "Haga click en Ok",
                    "warning",
                    );    
                    } else {
                        me.abrirModal('registrar'); 
                        console.log("*-------------------------------*");
                        console.log(respuesta);
                        me.id_apertura_cierre=respuesta.id;
                        console.log("***"+me.id_apertura_cierre);
                    } 
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
                    if (me.selectEntradaSalida==="1") {
                            me.titulo_ ="Emisor";
                            me.tituloModal = "Registro de entradas";
                    } else {
                        if (me.selectEntradaSalida==="2") {
                            me.titulo_ ="Receptor";
                            me.tituloModal = "Registro de salidas";
                        } else { Swal.fire("ERROR...","Haga click en Ok","warning",);  
                        }
                    }
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};  
                        me.emisor=""; 
                        me.Obs="Sin Observación";
                        me.codigo="";
                                     
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
        cambiarEstadoSucursal(){
            let me=this;
            me.selectEntradaSalida=0;
        },
    
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.totalMonedas="0.00";
            me.SimboloM='S/N';
            me.SimboloB='S/N';            
            me.totalBilletas="0.00";
            me.totalMonto="0.00";
            me.cantidadMonedas=0;
            me.cantidadBilletes=0;           
            me.selectTurno=0;
            me.input={};

            me.emisor="";
            me.Obs="";
            me.codigo="";
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
        this.verificador_moneda_sistemas();
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
