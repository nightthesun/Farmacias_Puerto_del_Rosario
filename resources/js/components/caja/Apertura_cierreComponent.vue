<template>
    
    <main class="main">
        <div  v-if="bloqueador>0">
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
                    <button type="button" class="btn btn-secondary" @click="cajaAnteriror();verificador_moneda_sistemas();"
                        :disabled="sucursalSeleccionada === 0 || selectApertura_Cierre === 1">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada === 0 ||  selectApertura_Cierre === 1" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar una sucursal.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-6">
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
             

            <div class="row justify-content-center" v-if="sucursalSeleccionada !== 0">
    <div class="col-md-8">
        <div class="col-md-2" style="text-align: center">
                     <label for="">Apertura/Cierre:</label>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <select class="form-control" v-model="selectApertura_Cierre" @change="listarIndex(0)">
                    <option value="1" disabled selected>Seleccionar...</option>
                    <option value="0" >Apertura</option> 
                    <option value="9" >Cierre</option>                 
                </select>
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
                        <th class="col-md-1">Fecha/Hora</th>
                        <th class="col-md-1">Tipo</th>
                        <th class="col-md-2">Turno</th>
                        <th class="col-md-1">Total Ingreso</th>
                        <th class="col-md-1">Total Egreso</th>
                        <th class="col-md-1">Total Caja</th>
                        <th class="col-md-1">Total Arqueo</th>
                        <th class="col-md-1">Diferencia</th>                        
                        <th class="col-md-1">Usuario</th>
                        <th class="col-md-2">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                    
                        <td>
                            <button type="button" class="btn btn-warning"  style="margin-right: 5px;">
                                <i class="fa fa-eye" aria-hidden="true"></i></button>
                        </td>
                        <td>{{i.created_at}}</td>
                        <td>
                            <span v-if="turno_caja===1">TURNO UNO</span>
                            <span v-if="turno_caja===2">TURNO DOS</span>
                            <span v-if="turno_caja===3">TURNO COMPLETO</span>
                        </td>
                        <td>
                            <span v-if="tipo_caja_c_a===0">Apertura</span>
                            <span v-if="tipo_caja_c_a===9">Cierre</span>
                        </td>
                        <td>{{i.ingresos}}</td>
                        <td>{{}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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

            
                    </div>
                    <div class="modal-body">                      
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-2">Tipo caja</th>
                                    <th class="col-md-2">Monto</th>
                                    <th class="col-md-2">Estado anterior</th>
                                    <th class="col-md-2">Estado</th>   
                                    <th class="col-md-4">Turno</th>                                
                                 </tr>
                            </thead>  
                            <tbody>  
                                <tr>                         
                                    <td class="col-md-2">{{turno_caja}}</td>
                                    <td class="col-md-2">{{total_caja}}</td>
                                    <td class="col-md-2">Anterior</td>
                                    <td class="col-md-2">{{estado_caja}}</td>                                    
                                    <td class="col-md-4">
                                        <select v-model="selectTurno" class="form-control">
                                            <option value="0" disabled>Seleccionar...</option>
                                            <option value="1">TURNO UNO</option> 
                                            <option value="2">TURNO DOS</option>
                                            <option value="3">TURNO COMPLETO</option>
                                        </select>
                                    </td>                                    
                                </tr>    
                            </tbody>          
                        </table>
                    </div> 
                    <div class="modal-body" >                      
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
                            @click="registrarArqueo()"
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
        </div>      
     
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
            valor_entero:0,
          
            bloqueador:0,
            arrayMoneda:[],
            input:{},
            arrayInput:[],
            tituloModal: "",
            sucursalSeleccionada:0,
            selectApertura_Cierre:1,
         
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
     
            totalMonedas:"0.00",
            SimboloM:'S/N',
            SimboloB:'S/N',            
            totalBilletas:"0.00",
            totalMonto:"0.00",
            cantidadMonedas:0,
            cantidadBilletes:0, 
            id_sucursal:0,
            selectTurno:0,

            turno_caja:'',
            tipo_caja_c_a:'',
            total_caja:'',
            estado_caja:'',

            arrayIndex:[],
        };
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

    computed: {
        sicompleto() {
            let me = this;
            console.log("+++++++");
            console.log(me.input);
            if (
                (me.input).length ==0
             
            )
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
        
        listarIndex(page) {
            let me = this;    
            console.log(me.selectApertura_Cierre+" "+me.id_sucursal);      
            var url ="/apertura_cierre/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_sucursal+"&a_e="+parseInt(me.selectApertura_Cierre);
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;
                    console.log(me.arrayIndex);
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        cajaAnteriror(){
            let me=this;
            var url = "/apertura_cierre/cajaAnteriror?id_sucursal="+me.id_sucursal ;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;          
                    if (respuesta===0 ) {
                        if (me.selectApertura_Cierre==="9") {
                            Swal.fire(
                    "Debe aperturar una caja",
                    "Haga click en Ok",
                    "warning",
                );
                        } else {
                            me.turno_caja="Inicio";
                            me.tipo_caja_c_a="Inicio";
                            me.total_caja=Number(0).toFixed(2);
                            me.estado_caja="Inicio";
                            me.abrirModal('registrar');                            
                        }
                        
                    } else {
                        if (respuesta.tipo_caja_c_a===Number(me.selectApertura_Cierre)) {
                            Swal.fire("Ya se hizo una apertura",
                                        "Haga click en Ok",
                                        "warning");
                        } else {
                                if (respuesta.turno_caja===1) {
                                me.turno_caja= "TURNO UNO";
                                }
                                if (respuesta.turno_caja===2) {
                                me.turno_caja= "TURNO DOS";
                                } 
                                if (respuesta.turno_caja===3) {
                                me.turno_caja= "TURNO COMPLETO ";
                                } 
                                  
                                if(respuesta.tipo_caja_c_a===0){
                                me.tipo_caja_c_a="Apertura";
                                }    

                                if(respuesta.tipo_caja_c_a===9){
                                me.tipo_caja_c_a="Cierre";
                                }                                  
                            
                            me.total_caja=respuesta.total_caja;
                            me.estado_caja=respuesta.estado_caja;
                            me.abrirModal('registrar');  
                        }
                    }
                    
                    console.log("*-*");
                    console.log(respuesta);
                   
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
            
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
        
        registrarArqueo(){
            let me = this;
            console.log("================");
            console.log(me.id_sucursal+" "+me.selectTurno+" "+me.selectApertura_Cierre+" "+me.total_caja+" "+me.cantidadMonedas+" "+me.totalMonedas+" "+me.cantidadBilletes+" "+me.totalBilletas+" "+me.totalMonto+" "+me.input+" "+me.arrayMoneda); 
            if (me.selectTurno === "0") {
                Swal.fire(
                    "Debe seleccionar su tuno",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                //------------------algoritmo-------------------------
                let a=me.totalMonto;
                let b=me.total_caja;
                let estado="";
                //c ---- diferencia
                  //  b=40.60;
                let cero=Number(0).toFixed(2);
                  console.log(a+"-"+b);  
                let c=a-b;   
                console.log(c);               
                if (c===0) {
                    if (a===cero && b===cero) {
                        estado="Acción nula";
                    } else {
                        if (a>0 && b>0) {
                            estado="OK";  
                        } else {
                            estado="Error de entrada"; 
                        }
                    }
                } else {
                    if (c>cero) {
                        if (a>cero && b>cero) {
                            estado="Sobrante";
                        } else {
                            if (a>cero && b===cero) {
                                estado="Saldo inicial";
                            } else {
                                if (a>cero && b<cero) {
                                    estado="Caja negativa";
                                } else {
                                    if (a===cero && b>cero) {
                                        estado="Perdida";
                                    } else {
                                        estado="Error";
                                    }
                                }
                            }
                        }
                    } else {
                        if (c<cero) {
                            if (a>cero && b>cero) {
                                estado="Faltante";
                            } else {
                                if (a===cero && b>cero) {
                                    estado="Perdida";
                                } else {
                                    estado==="Error";
                                }
                            }
                        } else {
                            estado="Error"; 
                        }
                    }
                }
               
                axios.post("/apertura_cierre/store", {
                    	id_sucursal:me.id_sucursal,
                        selectTurno:me.selectTurno,
                        tipo_caja_c_a:me.selectApertura_Cierre,
                        total_caja:me.total_caja,
                        total_arqueo_caja:me.totalMonto,
                        cantidadMonedas:me.cantidadMonedas,
                        totalMonedas:me.totalMonedas,
                        cantidadBilletes:me.cantidadBilletes,
                        totalBilletas:me.totalBilletas,
                        input:me.input,
                        arrayMoneda:me.arrayMoneda,
                        diferencia:c,
                        estado:estado
                    })
                    .then(function (response) {
                        console.log(response.data);
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );

                   //     me.listarAjusteNegativos();
                   //     me.sucursalFiltro();
                    })
                    
               
                  .catch(function (error) {                 
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

        verificador_moneda_sistemas(){
            let me=this;
            var url = "/apertura_cierre/verificador_moneda_sistemas";
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
                    "error",
                );
                  } else {
                    me.bloqueador=1;
                    me.arrayMoneda=respuesta_lista;
                    console.log(me.arrayMoneda);
                  }                                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        cambiarEstadoSucursal(){
            let me=this;
            me.selectApertura_Cierre=1;
        },

               
        sucursalFiltro() {
            let me = this;
           // var url = "/traspaso/listarSucursal";
           var url = "/listar_tienda_alamce_generico_lista_x_rol_usuario";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    console.log("************************");
                    console.log(respuesta);
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
         switch (accion) {
            
                case "registrar": {
                    me.tipoAccion = 1;
                    if (me.selectApertura_Cierre==="0") {
                        me.tituloModal = "Registro de apertura de caja";
                        me.selectTurno="0";
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};
                       
                    } else {
                        me.tituloModal = "Registro de cierre de caja"; 
                    }
                    
            
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
                
                me.selectTurno="0";
                        me.totalMonedas="0.00";
                        me.SimboloM="S/N";
                        me.SimboloB="S/N";            
                        me.totalBilletas="0.00";
                        me.totalMonto="0.00";
                        me.cantidadMonedas=0;
                        me.cantidadBilletes=0; 
                        me.input={};
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
        this.verificador_moneda_sistemas();
        this.classModal = new _pl.Modals();
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
