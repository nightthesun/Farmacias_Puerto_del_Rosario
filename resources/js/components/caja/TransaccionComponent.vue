
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
                    <button v-if="puedeCrear==1" 
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');getModal();listarUser()"
                        :disabled="selectPersona_banco===0">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: right">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="cambioDeSucursal()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo" :hidden="sucursal.tipoCodigo==='Almacen'"
                                        v-text="sucursal.codigoS +' -> '+sucursal.codigo+' '+sucursal.razon_social"></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Comprobante a buscar..." v-model="buscar" @keyup.enter="listarIndex(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"/>
                                <button type="submit" class="btn btn-primary" @click="listarIndex(1)" :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
            </div>
            <div class="form-group row"  :hidden="sucursalSeleccionada == 0" :disabled="sucursalSeleccionada == 0">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                    
                        <label for="Apectura / Cierre:">Tipo de deposito:</label>
                        <select class="form-control" v-model="selectPersona_banco" @change="listarIndex(1)">
                                <option value=0 disabled selected>Seleccionar...</option>
                                    <option value=1>Persona</option>
                                    <option value=2>Banco</option>
                        </select>                  
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" :disabled="selectPersona_banco===0" @change="listarIndex(0)">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="selectPersona_banco===0" @change="listarIndex(0)">
        </div>
        
            </div>   
         
     
  <br>
  <div class="alert alert-warning" v-if="selectPersona_banco===0" role="alert">
  Debe seleccionar una opcion de tipo de deposito
</div>
<div v-else>
 <!---inserte tabla-->
 <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opcion</th>
                        <th class="col-md-3">
                            <span v-if="selectPersona_banco==='1'">Responsable</span>
                            <span v-else-if="selectPersona_banco==='2'">Banco</span>                            
                        </th>
                        
                        <th class="col-md-2">Comprobante</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        
                        <th class="col-md-1">Monto total</th>
                        <th class="col-md-2">Observación</th>                                               
                        <th class="col-md-1">Usuario</th>
                        <th >Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1">
                            <div class="button-container">
                                <div  class="d-flex justify-content-start">
                                    <div  v-if="puedeEditar==1">
                                        <button type="button"  v-if="i.horas_restantes>0"  @click="abrirModal('actualizar',i);getModal();listarUser()" class="btn btn-warning btn-sm"  style="margin-right: 5px;"><i class="icon-pencil"></i></button>
                                        <button type="button"  v-else  class="btn btn-light btn-sm"  style="margin-right: 5px;"><i class="icon-pencil"></i></button>    
                                    </div>   
                                    <div v-else>
                                        <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;"><i class="icon-pencil"></i></button> 
                                    </div>
                                    <div  v-if="puedeHacerOpciones_especiales==1">
                                        <button type="button" @click="abrirModal('show',i)" class="btn btn-warning btn-sm" style="margin-right: 5px; color: whitesmoke;" >
                                            <i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                    <div v-else>
                                        <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px; color: whitesmoke;"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </div>
                           
                                </div>
                            </div>        
                          
                        </td>
                        <td class="col-md-3">{{i.name_all}}</td>
                        <td class="col-md-2">{{i.comprobante}}</td>
                        <td class="col-md-2">{{i.created_at}}</td>
                        <td class="col-md-1">{{i.monto_total}}</td>
                        <td class="col-md-2">{{i.observacion}}</td>
                        <td class="col-md-1">{{i.name}}</td>
                        <td>
                            <span v-if="i.horas_restantes>0"  class="badge badge-pill badge-success">En rango</span>
                            <span  v-else class="badge badge-pill badge-secondary">Fuera de rango</span>
                        </td>
                    </tr>
                </tbody>
            </table>    
            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,)">Ant</a>
                    </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                    </li>
                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,)">Sig</a>
                    </li>
                </ul>
            </nav>
            <!-----fin de tabla------->
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
                                                <div class="form-group col-sm-4">
                                                    <input type="text" class="form-control rounded" placeholder="Escriba la observación." v-model="observacion">                                                  
                                              
                                                </div>                                               
                                                <div class="form-group col-sm-7">                                                                         
                                                    <select class="form-control"  v-model="selectEntradasSalidas">
                                                        <option value=0 disabled selected>Cuenta...</option>
                                                        <option v-for="c in arrayCajaEntradasSalidas" :key="c.id" :value="c.id">                                 
                                                        {{ "Numero: "+c.id+" Responsable: "+c.mensaje }}
                                                        </option>
                                                    </select>                                                  
                                                </div>
                                                <div class="form-group col-sm-1">
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
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nro Salida</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nro arqueo</th>
                                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Responsable</th>
                                                         
                                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Observación</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Monto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                       
                                                            <tr v-for="a in arrayAñadir" :key="a.id">                                                            
                                                            <td  style="font-size: 11px; text-align: center">
                                                                <button @click="quitar(a.id,a.valor)" type="button" class="btn btn-danger" style="font-size: 12px; padding: 2px 5px;">
                                                                    <i class="fa fa-minus" aria-hidden="true"></i></button>
                                                            </td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{a.id}}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{ a.id_arqueo }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px; ">{{ a.mensaje }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px;">{{ a.observacion }}</td>                                                        
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: right">{{ a.valor+" "+a.simbolo }}</td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <th class="col-md-10" style="font-size: 11px; text-align: right;" colspan="5">Total:</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: right;">{{ valor_total+" "+simbolo_s }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>                                                  
                                                    
                              </div>    
                            
                               
                            </div>
                            <div class="modal-body d-flex justify-content-center align-items-center" style="height: 100%;" v-if="tipoAccion===2">
    <div class="row">
        <table class="table table-bordered table-striped table-sm table-responsive" style="width: 300px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; text-align: center">Numero de salida</th>
                    <th style="font-size: 11px; text-align: center">Total</th>
                    <th style="font-size: 11px; text-align: center">Sub total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 11px; text-align: center">{{ id_salida_SS }}</td>
                    <td style="font-size: 11px; text-align: center">{{ monto_total_SS }}</td>
                    <td style="font-size: 11px; text-align: center">{{ sub_total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
  
                        </form>
                    </div>
                  
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>                        
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrar_2()" :disabled="arrayAñadir.length<=0">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="editar_2()">Actualizar</button>
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
</transition>
        
        <!--fin del modal-->

         <!--Inicio del modal show-->

         <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('show')">
                            <span>&times;</span>
                        </button>
                        </div>

  <div class="modal-body">                   
                        
                        <form action="" class="form-horizontal">                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                                            
                                    <div class="row">
                                        <table class="table table-bordered table-striped table-sm table-responsive">
                                         <thead>
                                            <tr>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Comprobante</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Tipo cuenta</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Descripción</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Observación</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Fecha/Hora</th>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Total</th>                      
                                            </tr>
                                        </thead>
                                    <tbody>
                <tr>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ e_id }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ e_comprobante }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">
                        <span v-if="e_tipo_deposito===1">Persona</span>
                        <span v-else-if="e_tipo_deposito===2">Bancario</span>
                        <span v-else>Error</span>                        
                    </td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ e_name_all }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ e_observacion }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ e_created_at }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ e_monto_total }}</td>
                </tr>
            </tbody>
        </table>   
                                    </div>
                                    <div class="row">
                                        <table class="table table-bordered table-striped table-sm table-responsive">
                                         <thead>
                                            <tr>        
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Nro arqueo</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Observación</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Responsable</th>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Cant billete</th>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Total billete</th>                                                
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Cant moneda</th>
                                                <th class="col-md-1" style="font-size: 11px; text-align: center">Total moneda</th>
                                                <th class="col-md-2" style="font-size: 11px; text-align: center">Tipo de corte</th>        
                                            </tr>
                                        </thead>
                                    <tbody>
                <tr v-for="a in e2_array_show" :key="a.id"> 
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.id }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.id_arqueo }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ a.observacion }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ a.mensaje }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.cantidad_billete }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.total_billete }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.cantidad_moneda }}</td>
                    <td class="col-md-1" style="font-size: 11px; text-align: center">{{ a.total_moneda }}</td>
                    <td class="col-md-2" style="font-size: 11px; text-align: center">{{ a.tipo_corte }}</td>
                </tr>
            </tbody>
        </table>   
                                    </div>
                            </div>                    
                        </form>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('show')">Cerrar</button>
                   
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
            offset: 3,
            isSubmitting: false, // Controla el estado del botón de envío
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
            
            id_salida_SS:"",
            monto_total_SS:"",
            sub_total:"",

            id_sucursal:0,

            selectPersona_banco:0,
            arrayUser_2:[],
            selectUser_2:0,
            array_salida:[],

            observacion:"",
            simbolo_s:"",
            showModal: false,
            showModal_2: false,
            arrayIndex:[],
            id_transaccion:"",

            e_id:"",       
            e_comprobante:"",  
            e_tipo_deposito:"",  
            e_name_all:"",
            e_observacion:"",   
            e_created_at:"", 
            e_monto_total:"", 
            e2_array_show:[],

            //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------

           
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
//--------------------------------------

        cambioDeSucursal(){
            this.selectPersona_banco=0;
        },

        listarIndex(page){
         //   /transaccion/listar_   
                let me=this;
                var url='/transaccion/listar_?page='+page+'&buscar='+me.buscar+'&id_sucursal='+me.id_sucursal+'&tipo_deposito='+me.selectPersona_banco+"&ini="+me.startDate+"&fini="+me.endDate;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.resultado.data;               

                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
        },

        verArray(bloque){
            let me=this;
            var url='/transaccion/show?bloque='+bloque;
            axios.get(url).then(function(response){
                    var respuesta=response.data;   
                    me.e2_array_show=respuesta;              
                            

                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
        },

        registrar_2() {
            let me = this;
        
            let activador_S=0;  
            let id_cuenta_S=0;       
            if (me.selectPersona_banco==="1") {
                if (me.selectUser_2===0 || me.comprobante==="" || me.observacion==="" || me.arrayAñadir.length<=0) {
                    Swal.fire("Datos nulos","Se encotro datos vacios","error");  
                } else {
                    activador_S=1;
                    id_cuenta_S=me.selectUser_2;
                }
            } else {
                if (me.selectPersona_banco==="2") {
                    if (me.selectCuentasBancos===0 || me.comprobante==="" || me.observacion==="" || me.arrayAñadir.length<=0) {
                        Swal.fire("Datos nulos","Se encotro datos vacios","error");   
                    } else {
                        activador_S=1; 
                        id_cuenta_S=me.selectCuentasBancos;
                    }
                } else {
                    Swal.fire("Selección","No valida","error");  
                }
            }

            if (activador_S===1) {
                let cadena=""; 
        
                me.arrayAñadir.forEach((item, index) => {
    // Agrega una coma antes de cada ID excepto el primero
    cadena += (index === 0 ? '' : ',') + item.id;
});
              
      // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                axios.post("/transaccion/registrar", {
                    id_sucursal: me.id_sucursal,              
                    id_cuenta: id_cuenta_S,
                    comprobante: me.comprobante,
                    id_salida:cadena,
                    monto_total:me.valor_total,
                    observacion:me.observacion,
                    tipo_deposito:me.selectPersona_banco,
                    array:me.arrayAñadir                     
                    })       
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        me.listarIndex();                  
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    })                
                  .catch(function (error) {           
                    error401(error);
                    console.log(error);        
            }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
       
            } else {
                Swal.fire("Error","Acción sospechosa","error");
            }

        },

        editar_2(){

            let me = this;
            let activador_S=0;  
            let id_cuenta_S=0;  
            let tipo_S="";   
       

            if (parseInt(me.selectPersona_banco)===1) {
                id_cuenta_S=me.selectUser_2;
                tipo_S="persona";
            } else {
                if (parseInt(me.selectPersona_banco)===2) {
                    id_cuenta_S=me.selectCuentasBancos;
                    tipo_S="banco";
                } else {
                    Swal.fire("Error"," de entrada.","error");   
                }
            }

            if (id_cuenta_S===0 || me.comprobante==="" || me.observacion==="") {
                 Swal.fire("Datos nulos","Se encotro datos vacios","error");  
            } else {
                activador_S=1;  
            }

            if (activador_S===1) {
                let cadena=""; 
        
                me.arrayAñadir.forEach((item, index) => {
    // Agrega una coma antes de cada ID excepto el primero
    cadena += (index === 0 ? '' : ',') + item.id;
});
let sumador="";
if (me.arrayAñadir.length>0) {
    me.id_salida_SS=me.id_salida_SS+","+cadena;
    sumador=me.sub_total;
    }else{
    sumador=me.monto_total_SS;    
    }


     let permiso_e ="id datos";
                axios.post("/transaccion/editar", {
                    id_transaccion:me.id_transaccion,
                    id_sucursal: me.id_sucursal,              
                    id_cuenta: id_cuenta_S,
                    comprobante: me.comprobante,
                    id_salida:me.id_salida_SS,
                    monto_total:sumador,
                    observacion:me.observacion,
                    tipo_deposito:me.selectPersona_banco,
                    array:me.arrayAñadir,
                    
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:permiso_e,  
                    })       
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        me.listarIndex(); 
                        let va=response.data;
                              
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    })                
                  .catch(function (error) {           
                  //  this.errorMessage = error.response.data; // Aquí guardamos el error
                  //  Swal.fire("Error comunicarse con el administrador",""+errorMessage,"error");               
            });
       
            } else {
                Swal.fire("Error","Acción sospechosa","error");
            }

        },

        listarUser(){
            let me = this;        
            var url = "/listarUser";
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUser_2=respuesta;
                    // me.arrayUser_2 = respuesta.filter(user => user.responsable === 0);
                   // me.arrayUserResponsable = respuesta.filter(user => user.responsable === 1);
       
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        añadir(newValue) {
    let me = this; // Contexto de `this` para acceder a las propiedades del componente
    // Busca en el array `arrayCajaEntradasSalidas` si hay un elemento con el mismo `id`
    let busca = me.arrayCajaEntradasSalidas.find((element) => element.id === newValue);
    
    if (busca) {
        let busca_2 = me.arrayAñadir.find((element) => element.id === newValue);
        if (busca_2) {
            Swal.fire(
                            "El registro ya existe en la lista.",
                            "Haga click en Ok",
                            "error",
                        );  
        } else {
            // Si no existe, lo añade al array
            me.arrayAñadir.push({
                id: busca.id,
                id_arqueo: busca.id_arqueo,
                mensaje: busca.mensaje,
                observacion: busca.observacion,
                valor: busca.valor,
                simbolo: busca.simbolo
            });
            me.simbolo_s=busca.simbolo;
            let var_1 = parseFloat(me.valor_total); // Convertir a número
            let var_2 = parseFloat(busca.valor);     // Convertir a número
            me.valor_total = (var_1 + var_2).toFixed(2); // Sumar y formatear a dos decimales
         
            if (me.tipoAccion===2) {
                let var_11 = parseFloat(me.monto_total_SS); // Convertir a número
                let var_22 =  parseFloat(me.valor_total);     // Convertir a número
            me.sub_total = (var_11 + var_22).toFixed(2); // Sumar y formatear a dos decimales
            } else {
                me.sub_total = (0).toFixed(2);
            }

            me.selectEntradasSalidas = 0; // Resetea el selector
         
        }
    } else {
        console.log(`No se encontró el ID ${newValue} en arrayCajaEntradasSalidas.`);
    }
},

        quitar(newValue,valor){
            let me=this;         
            me.arrayAñadir =me.arrayAñadir.filter(element => element.id !== newValue); 
            let var_1 = parseFloat(me.valor_total); // Convertir a número
            let var_2 = parseFloat(valor);     // Convertir a número
            me.valor_total = (var_1 - var_2).toFixed(2); // Sumar y formatear a dos decimales
            if (me.tipoAccion===2) {
                let var_11 = parseFloat(me.monto_total_SS); // Convertir a número
                let var_22 =  parseFloat(me.valor_total);     // Convertir a número
            me.sub_total = (var_11 + var_22).toFixed(2); // Sumar y formatear a dos decimales
            } else {
                me.sub_total = (0).toFixed(2);
            }
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
            me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                    me.tituloModal = "Registro de transacción";
                    me.observacion="Sin Observación";
                    me.comprobante="";
                    me.selectCuentasBancos=0;
                    me.selectEntradasSalidas=0;
                    me.selectUser_2=0;
                    me.arrayAñadir=[];
                    me.valor_total=0;
                    me.id_salida_SS="";
                    me.monto_total_SS="";
                       me.showModal = true;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                
                    me.tipoAccion = 2;
                    me.isSubmitting=false;
                    if(data.tipo_deposito===1){
                        me.selectUser_2=data.id_cuenta;
                        me.selectCuentasBancos=0;
                    }else{
                        if (data.tipo_deposito===2) {
                            me.selectUser_2=0;
                            me.selectCuentasBancos=data.id_cuenta;
                        } else {
                            me.selectCuentasBancos=0;
                            me.selectUser_2=0; 
                        }
                       
                    }  
                      me.showModal = true;
                    me.id_salida_SS=data.id_salida;
                    me.monto_total_SS=data.monto_total;  
                    me.tituloModal = "Registro de transacción";
                    me.observacion=data.observacion;
                    me.comprobante=data.comprobante;                   
                    me.selectEntradasSalidas=0;                  
                    me.arrayAñadir=[];
                    me.valor_total=0;
                    me.sub_total="0.00";
                    me.id_transaccion=data.id;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "show":{
                 me.showModal_2 = true;
                    me.tituloModal = "Vista";
                    me.e_id=data.id;   
                                      me.verArray(data.id_salida);    
                    me.e_comprobante=data.comprobante;  
                    me.e_tipo_deposito=data.tipo_deposito;  
                    me.e_name_all=data.name_all;
                    me.e_observacion=data.observacion;   
                    me.e_created_at=data.created_at; 
                    me.e_monto_total=data.monto_total;
                    me.classModal.openModal("show");
  
                    break;
                }    
            
            }
        },

       


        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;     
                
                me.selectUser_2=0;        
                me.comprobante="";
                me.selectEntradasSalidas="";
                me.arrayAñadir=[];
                me.valor_total=0;
                me.observacion="";
                me.id_salida_SS="";
                me.monto_total_SS="";
                me.sub_total="";
                 me.showModal = false;
                  me.classModal.closeModal(accion);
            }
            if (accion === "show") {
             me.showModal_2 = false;
                
                me.e_id="";       
                me.e_comprobante="";  
                me.e_tipo_deposito="";  
                me.e_name_all="";
                me.e_observacion="";   
                me.e_created_at=""; 
                me.e_monto_total="";
                me.e2_array_show=[];
              me.classModal.closeModal(accion);   
            }
        },

        
        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 5);
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
         //-------permiso E_W_S-----
         this.listarPerimsoxyz();      
        //-----------------------
        this.classModal = new _pl.Modals();
        this.sucursalFiltro();
        this.fecha_inicial();
        this.classModal.addModal("registrar");
        this.classModal.addModal("show");           
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