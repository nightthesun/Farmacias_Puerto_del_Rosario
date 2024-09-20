
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
                    <i class="fa fa-align-justify"></i> Transacciónes               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');getModal();listarUser()"
                        :disabled="selectPersona_banco===0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="cambioDeSucursal()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.tipoCodigo==='Almacen'"
                                        v-text="sucursal.codigoS +' -> '+sucursal.codigo+' '+sucursal.razon_social"></option>
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
            <div class="form-group row">
                <div class="col-md-2" style="text-align: right">
                     <label for="">Tipo de deposito:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="selectPersona_banco">
                                    <option value=0 disabled selected>Seleccionar...</option>
                                    <option value=1>Persona</option>
                                    <option value=2>Banco</option>
                                </select>
                            </div>
                        </div>                     
            </div>
     
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opcion</th>
                        <th class="col-md-2">Banco</th>
                        <th class="col-md-1">Nombre cuenta</th>
                        <th class="col-md-1">Comprobante</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-2">Nro de salida</th>
                        <th class="col-md-1">Monto total</th>
                        <th class="col-md-2">Observación</th>                                               
                        <th class="col-md-1">Usuario</th>
                        <th >Estado</th>       
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
                                <div class="form-group row">                                   
                                    <div class="row">
                              
                                    <div v-if="selectPersona_banco==='1'" class="form-group col-sm-7">
                                        <label>Persona:</label>
                                        <select class="form-control"  v-model="selectUser_2">
                                        <option value=0 disabled selected>Seleccionar...</option>
                                        <option v-for="b in arrayUser_2" :key="b.id" :value="b.id" :hidden="b.responsable===0">                                 
                                            {{ b.name +" ( "+b.nom_completo+" )"}}
                                        </option>
                                    </select>  
                                    </div>
                                    <div v-if="selectPersona_banco==='2'" class="form-group col-sm-7">
                                        <label>Banco:</label>
                                        <select class="form-control"  v-model="selectCuentasBancos">
                                        <option value=0 disabled selected>Seleccionar...</option>
                                        <option v-for="b in arrayCuentasBancos" :key="b.id" :value="b.id">
                                 
                                            {{ b.nom_banco +" - "+b.nom_cuenta+" - "+b.nro_cuenta}}
                                        </option>
                                    </select>  
                                    </div>
                                    
                                    <div class="form-group col-sm-5">
                                        <label>Comprobante:</label>
                                        <input type="text" class="form-control rounded" placeholder="Nro de comprobante" v-model="comprobante">
                                    </div>                                  
                                </div>
                              
                                            <div class="row">                                               
                                                <div class="form-group col-sm-9">                                                                         
                                                    <select class="form-control"  v-model="selectEntradasSalidas">
                                                        <option value=0 disabled selected>Cuenta...</option>
                                                        <option v-for="c in arrayCajaEntradasSalidas" :key="c.id" :value="c.id">                                 
                                                        {{ "Numero: "+c.id+" Responsable: "+c.mensaje }}
                                                        </option>
                                                    </select>                                                  
                                                </div>
                                                <div class="form-group col-sm-3">
                                                    <a  v-if="selectEntradasSalidas===0" href="#" class="btn btn-light">Añadir</a>
                                                    <a  v-else href="#" class="btn btn-primary" @click="añadir(selectEntradasSalidas)" >Añadir</a>
                                                </div>                                                
                                            </div>
                                            
                                 
                                </div>
                                <div v-if="arrayAñadir.length<=0" class="alert alert-info" role="alert">
                                                    Sin datos en la tabla de salida.
                                            </div>
                                            <div v-else class="modal-body">                      
                                                <table class="table table-bordered table-striped table-sm table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="col-md-1" style="font-size: 11px; text-align: center">Nro Salida</th>
                                                            <th class="col-md-1" style="font-size: 11px; text-align: center">Nro arqueo</th>
                                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Responsable</th>
                                                         
                                                            <th class="col-md-4" style="font-size: 11px; text-align: center">Observación</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                       
                                                            <tr v-for="a in arrayAñadir" :key="a.id">                                                            
                                                            <td  style="font-size: 11px; text-align: center">
                                                                <button @click="quitar(a.id)" type="button" class="btn btn-danger" style="font-size: 12px; padding: 2px 5px;">
                                                                    <i class="fa fa-minus" aria-hidden="true"></i></button>
                                                            </td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{a.id}}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{ a.id_arqueo }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px; ">{{ a.mensaje }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px;">{{ a.observacion }}</td>                                                        
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{ a.valor+" "+a.simbolo }}</td>
                                                        </tr>
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

            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
            startDate: '',
            endDate: '',

            arrayCuentasBancos:[],
            selectCuentasBancos:0,
            arrayCajaEntradasSalidas:[],
            selectEntradasSalidas:0,
            comprobante:'',
            arrayAñadir:[],
            valor_total:0,

            id_sucursal:0,

            selectPersona_banco:0,
            arrayUser_2:[],
            selectUser_2:0,

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

        cambioDeSucursal(){
            this.selectPersona_banco=0;
        },

        listarUser(){
            let me = this;        
            var url = "/listarUser";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUser_2=respuesta;
                    // me.arrayUser_2 = respuesta.filter(user => user.responsable === 0);
                   // me.arrayUserResponsable = respuesta.filter(user => user.responsable === 1);
                   console.log(me.arrayUser_2);          
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        añadir(newValue){
            let me=this;
            let busca=me.arrayCajaEntradasSalidas.find((element)=>element.id===newValue);
            if (busca) {
                me.arrayAñadir.push({id:busca.id,id_arqueo:busca.id_arqueo,mensaje:busca.mensaje,observacion:busca.observacion,valor:busca.valor,simbolo:busca.simbolo});
                me.valor_total=me.valor_total+busca.valor;
            }
        },
        quitar(newValue){
            let me=this;         
            me.arrayAñadir =me.arrayAñadir.filter(element => element.id !== newValue); 
          
        },

        getModal(){
              // var url = "/traspaso/listarSucursal";
              let me = this;
           var url = "/transaccion/cuenta_salida?id_sucursal="+me.id_sucursal;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCuentasBancos = respuesta.cuentasBancos;
                    me.arrayCajaEntradasSalidas = respuesta.cajaEntradasSalidas;                    
                    console.log(respuesta);
                 
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
                    me.tituloModal = "Registro de transacción";
                    me.comprobante="";
                    me.selectCuentasBancos=0;
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
