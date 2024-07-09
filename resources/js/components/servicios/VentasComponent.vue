<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Venta de Servicios
                    
                </div>
                <div class="card-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width:450px">Prestacion</th>
                                <th>Precio</th>
                                <th>Descuento</th>
                                <th>Precio Final</th>
                                <th>Agregar</th>
                           </tr>
                        </thead>
                        <!--<tr>
                            <td>
                                <form action="form-inline ml-3">
                                    <div class="input-group input-group-sm">
                                        <input  class="form-control form-control navbar" type="search" placeholder="Buscar" 
                                        aria-label="Buscar"
                                        name="prestacionbus"
                                        v-model="prestacionBuscar"
                                        v-on:keyup="selectPrestaciones()"
                                        >
                                    </div>
                                </form>
                                <div class="panel-footer" v-if="arrayPresAutocomplete.length>0">
                                    <ul class="list-group">
                                        <li class="list-group-item" v-for="presbus in arrayPresAutocomplete" :key="presbus.id">
                                           <a href="#" class="dropdown-item" v-on:click.prevent="presbus.nombre">
                                                <span>{{ presbus.nombre}}</span>   
                                            </a> 
                                        </li>
                                        
                                    </ul>

                                </div>
                            </td>
                        </tr>-->
                        <tr>
                            <td>
                                <select name="" id="" v-model="prestacionselected" class="form-control" @change="selectPres()">
                                    <option value="0" disabled>Seleccionar...</option>
                                    <option v-for="prestacion in arrayPrestaciones" :key="prestacion.id" :value="prestacion.id" v-text="prestacion.cod"></option>
                                </select>
                                <!-- <Ajaxselect  v-if="clearSelected"
                                    ruta="/prestacion/selectprestaciones?buscar=" @found="prestaciones" @cleaning="cleanprestaciones"
                                    resp_ruta="prestaciones"
                                    labels="cod"
                                    placeholder="Ingrese Texto..." 
                                    idtabla="id"
                                    :id="idprestacionselected"
                                    :clearable='true'>
                                </Ajaxselect> -->
                            </td>
                            
                            <td v-if="siprestacion" @change="presfinal">{{ idprestaciones.precio }} Bs.</td>
                            <td v-else>-</td>
                            <td><select v-model="descuentoSelected"  class="form-control" @change="presfinal">
                                        <option value="0">Seleccionar...</option>
                                        <option v-for="descuento in arrayDescuentos" v-bind:key="descuento.id" :value="descuento.id" v-text="descuento.nombre"></option>
                                    </select></td>
                            <td  style="text-align:right">{{preciofinal}} &nbsp;Bs.</td>
                            <td><button type="button" class="btn btn-info btn-sm" @click="agregarVenta()" :disabled="!siprestacion" >
                                        <i class="icon-check"> Add Venta</i>
                                </button>
                            </td>
                        </tr>

                    </table>
                   
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Prestacion</th>
                                <th>Precio</th>
                                <th>Descuento</th>
                                <th style="width:150px">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVentas" :key="venta.id">
                                <td>
                                    <button v-if="venta.estado==0" type="button" class="btn btn-danger btn-sm" @click="eliminarVenta(venta.id)" >
                                        <i class="icon-trash">11</i>
                                    </button>
                                </td>
                                <td v-text="venta.cod"></td>
                                <td v-text="venta.nompres"></td>
                                <td v-text="venta.precio + ' Bs.'" style="text-align:right"></td>
                                <td v-text="venta.descuento"></td>
                                <td v-text="venta.monto_a_cancelar + ' Bs.'" style="text-align:right"></td>
                            </tr>
                            <tr>
                                <th colspan="5" style="text-align:right">Suma Total:</th>
                                <th v-text="sumatotal + ' Bs.'" style="text-align:right"></th>
                            </tr>
                            <tr>
                                <th colspan="5" style="text-align:right">Efectivo:</th>
                                <th><input type="number" v-model="efectivo" v-on:focus="selectAll" @keyup="restartotal()" style="text-align:right"></th>
                            </tr>
                            <tr>
                                <th colspan="5" style="text-align:right">Cambio:</th>
                                <th v-text="cambio + ' Bs.'" style="text-align:right"></th>
                            </tr>
                            <tr>
                                <th colspan="5" >Cliente:
                                    <select name="" id="" v-model="clienteselected" class="form-control" @change="selectCli()">
                                        <option value="0" disabled>Seleccionar...</option>
                                        <option v-for="client in arrayClientes" :key="client.id" :value="client.id" v-text="'Nombre: '+client.nom +' CI: '+client.ci+' Nit: '+client.nit"></option>
                                    </select>
                                    
                                   <!--  <Ajaxselect  v-if="clearSelected1"
                                        ruta="/clientes/selectclientes?buscar=" @found="clientes" @cleaning="cleanclientes"
                                        resp_ruta="clientes"
                                        labels="nom"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="id"
                                        :id="clienteselected"
                                        :clearable='true'>
                                    </Ajaxselect> -->

                                    <div class="col-md-1" style="padding-left: 0px;">
                                        <button type="button" class="btn btn-primary" @click="abrirModalClientes()" >
                                            <i class="icon-plus"> Add Cliente</i>
                                        </button>
                                    </div></th>
                                <th style="text-align:center">
                                    <button type="submit" class="btn btn-success" @click="registrarVenta()" :disabled="arrayVentas.length==0 || !sicliente || !sicancelado">
                                        <i class="icon-check" ></i> Registrar Venta
                                    </button>
                                </th>
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
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('registrar')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre <span  v-if="!sicompleto" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre de la Prestacion" v-model="nombre" v-on:focus="selectAll">
                                    <span  v-if="!sicompleto" class="error">Debe Ingresar el Nombre de la Prestacion</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio <span  v-if="!sicompletoprecio" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="number" id="precio" name="precio" class="form-control" placeholder="0.0" v-model="precio" style="text-align:right" v-on:focus="selectAll" >
                                    <span  v-if="!sicompletoprecio" class="error">Debe Ingresar el Precio de la prestacion</span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese una Descripción" v-model="descripcion" v-on:focus="selectAll">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPrestacion()" :disabled="!sicompleto || !sicompletoprecio">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPrestacion()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <!-- MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE MODAL CLIENTE  -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="cliente"  data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary">
                <div class="modal-content animated fadeIn">
                    <div class="modal-header">
                        <h4 class="modal-title">Registro de cliente</h4>
                        <button class="close" @click="cerrarModalCliente()">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <strong>Nombre cliente:</strong>
                                <input type="text" class="form-control" v-model="nombre">
                                <span class="error" v-if="nombre==''">Nombre de cliente no debe estar Vacio</span>
                            </div>
                            <div class="form-group col-sm-6">
                                <strong>Primer Apellido:</strong>
                                <input type="text" class="form-control" v-model="papellido">
                                <span class="error" v-if="papellido=='' && sapellido == '' ">Apellido no debe estar vacio</span>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <strong>Segundo Apellido:</strong>
                                <input type="text" class="form-control" v-model="sapellido">
                                <span class="error" v-if="papellido=='' && sapellido == '' ">Apellido no debe estar vacio</span>
                            </div>
                            <div class="form-group col-sm-6">
                                <strong>CI:</strong>
                                <input type="text" class="form-control" v-model="ci">
                                <span class="error" v-if="ci==''">CI no debe estar vacio</span>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <strong>NIT:</strong>
                                <input type="text" class="form-control" v-model="nit">
                            </div>
                            <div class="form-group col-sm-6">
                                <strong>Teléfono:</strong>
                                <input type="text" class="form-control" v-model="telefono">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModalCliente()">Cerrar</button>
                        <button :disabled="!iscompletecliente" class="btn btn-primary" @click="registrarCliente()">Guardar</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal add cliente -->
        
        
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import { error401 } from '../../errores';

//import vSelect from 'vue-select';
//Vue.component('v-select',vSelect);
//Vue.use(VeeValidate);
    export default {
        data(){
            return{
                pagination:{
                    'total' :0,
                    'current_page':0,
                    'per_page':0,
                    'last_page':0,
                    'from':0,
                    'to':0
                },
                offset:3,
                nombre:'',
                descripcion:'',
                codigo:'',
                correlativo:0,
                arrayAreas:[],
                tituloModal:'',
                tipoAccion:1,
                idprestacion:'',
                buscar:'',

                arrayPrestacion:[],
                areaselected:0,
                precio:'',
                idprestacionselected:0,
                idprestaciones:[],
                clearSelected:1,
                clearSelected1:1,
                descuentoSelected:0,
                arrayDescuentos:[],
                preciofinal:0,
                arrayVentas:[],
                sumatotal:0,
                efectivo:0,
                cambio:0,

                idclientes:[],
                //clienteselected:'',
                nombre:'',
                papellido:'',
                sapellido:'',
                ci:'',
                telefono:'',
                nit:'',
                arrayPrestaciones:[],
                prestacionselected:0,
                siprestacion:0,
                arrayClientes:[],
                clienteselected:0,
                sicliente:0,
                arrayPresAutocomplete:[],
                prestacionBuscar:'',
                
                
            }

        },
        computed:{
            iscompletecliente(){
                let me=this;
                if (me.nombre!='' && (me.papellido!='' || me.sapellido!='') && me.ci!='')
                    return true;
                else
                    return false;

            },
            sicancelado(){
                let me=this;
                me.sumatotal=Number(me.sumatotal);
                me.efectivo=Number(me.efectivo);
                if(me.efectivo<me.sumatotal)
                    return false
                else
                    return true

            },
            sicliente(){
                let me=this;
                if(me.sicliente)
                    return true                            
                else
                    return false;
            },
            
            
            siarealesected(){
                let me=this;
                if (me.areaselected!=0)
                    return true;
                else
                    return false;
            },
            sicompletoprecio(){
                let me=this;
                if (me.preciofinal!=0)
                    return true;
                else
                    return false;
            },
            sicompleto(){
                let me=this;
                if (me.nombre!='')
                    return true;
                else
                    return false;
            },
            isActived:function(){
                return this.pagination.current_page;
            },
            pagesNumber:function(){
                if(!this.pagination.to){
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from<1){
                    from=1;
                }
                var to = from +(this.offset * 2);
                if(to>= this.pagination.last_page){
                    to=this.pagination.last_page;
                }
                var pagesArray =[];
                while(from<=to){
                    pagesArray.push(from);
                    from++
                }
                return pagesArray;
            },


        },
        methods :{
            selectPres(){
                let me =this;
                me.idprestaciones=[];
                me.idprestaciones=me.arrayPrestaciones.find(element=>element.id==me.prestacionselected);
                me.siprestacion=1;
                //console.log(me.idprestaciones);

            },
            selectCli(){
                let me =this;
                me.idclientes=[];
                me.idclientes=me.arrayClientes.find(element=>element.id==me.clienteselected);
                me.sicliente=1;
                //console.log(me.idprestaciones);

            },
            abrirModalClientes(){
                let me=this;
                me.tituloModal='Registro de Clientes';
                me.nombre='';
                me.papellido='';
                me.sapellido='';
                me.nit='';
                me.ci='';
                me.telefono='';
                me.classModal.openModal('cliente');
            },
            cerrarModalCliente(){
                let me = this;
                me.classModal.closeModal('cliente');
                me.nombre='';
                me.papellido='';
                me.sapellido='';
                me.nit='';
                me.ci='';
                me.telefono='';
                
            },
            presfinal(){
                let me=this;
                if(me.siprestacion)
                {
                    if(me.descuentoSelected!=0)
                    {
                        let respuesta=me.arrayDescuentos.find(element=>element.id==me.descuentoSelected);
                        //console.log(respuesta)
                        let descuento = respuesta.monto;
                        let siporcentaje=respuesta.siporcentaje;
                        let precio=Number(me.idprestaciones.precio);

                        //console.log(precio,descuento);
                        if(siporcentaje)
                            me.preciofinal=Number(precio-(precio*(descuento/100)).toFixed(2));
                        else
                            me.preciofinal= precio-descuento;
                    }
                    else
                        me.preciofinal=me.idprestaciones.precio;
                }
                else
                    me.preciofinal=0;

            },
            restartotal(){
                let me=this;
                if(me.efectivo!=0)
                    me.cambio=Number(me.efectivo-me.sumatotal);
                else
                    me.cambio=0;
            },
            
            cambiaprestacion(){
                let me=this;
                me.clearSelected=0;
                setTimeout(me.tiempo, 200); 
                //me.directivo=valor;
                me.ideprestacion=[];
                
               
            },
            cambiacliente(){
                let me=this;
                me.clearSelected1=0;
                setTimeout(me.tiempo1, 200); 
                //me.directivo=valor;
                me.ideprestacion=[];
                
               
            },
            tiempo(){
            this.clearSelected=1;
            }, 
            tiempo1(){
            this.clearSelected1=1;
            }, 
            prestaciones(prestaciones){
                let me=this;
                this.idprestaciones=[];
                for (const key in prestaciones) {
                    if (prestaciones.hasOwnProperty(key)) {
                        const element = prestaciones[key];
                        //console.log(element);
                        this.idprestaciones.push(element);
                    }
                }
                me.preciofinal=this.idprestaciones.precio;
                //console.log(this.idprestaciones);
            },
            clientes(clientes){
                this.idcientes=[];
                for (const key in clientes) {
                    if (clientes.hasOwnProperty(key)) {
                        const element = clientes[key];
                        //console.log(element);
                        this.idclientes.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleanprestaciones(){
                this.idprestaciones=[];
                this.descuentoSelected=0;
                //this.idempleadorespuesta=0;
            //console.log('clean')
            
            },
            cleanclientes(){
                this.idclientes=[];
                this.clienteselected='';
            
            },
            listarVenta(){
                let me=this;
                var url='/ventas/listar';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    //console.log(respuesta);
                    me.arrayVentas=respuesta.ventas;
                    me.sumatotal=respuesta.sumatotal;
                    me.restartotal();
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },

            selectPrest(){
                let me=this;
                var url='/prestacion/selectprest';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayPrestaciones=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },

            selectPrestaciones(){
                let me=this;
                if(me.prestacionBuscar.length>2)
                {
                    var url='/prestacion/selectprestaciones?buscar='+me.prestacionBuscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayPresAutocomplete=respuesta;
                    console.log(me.arrayPresAutocomplete);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
                }
                
            },

            selectClientes(){
                let me=this;
                var url='/clientes/selectcli';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayClientes=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            selectDescuentos(){
                let me=this;
                var url='/descuento/selectdescuento';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayDescuentos=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarPrestaciones(page);
            },
            agregarVenta(){
                let me = this;
                
                axios.post('/ventas/registrar',{
                    'idprestacion':me.idprestaciones.id,
                    'iddescuento':me.descuentoSelected,
                    'monto_a_cancelar':me.preciofinal,
                    'idsucursal':1
                    
                }).then(function(response){
                    //me.cerrarModal('registrar');
                    //me.cambiaprestacion();
                    me.idprestaciones=[];
                    me.prestacionselected=0;
                    me.descuentoSelected=0;
                    me.preciofinal=0;
                    me.siprestacion=0;
                    me.listarVenta();
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            registrarVenta(){
                let me=this;
                let idclient;
                /* if(me.idclientes.length>0)
                    idclient=me.idclientes.id;
                else
                    idclient=me.clienteselected; */


                axios.post('/ventamaestro/registrarventamaestro',{
                    'idcliente':me.clienteselected,
                    'total':me.sumatotal,
                    'efectivo':me.efectivo,
                    'cambio':me.cambio

                        
                    }).then(function (response) {
                        if(response.data=='correcto')
                        {
                            console.log("///////////////");
                            console.log(response);

                            Swal.fire('Registrado Correctamente');
                            me.listarVenta();
                            me.arrayVentas=[];
                            me.clearSelected1=0;
                            setTimeout(me.tiempo1, 100);
                            me.clienteselected='';
                            me.cambio=0;
                            me.efectivo=0;
                            me.preciofinal=0;
                            me.clearSelected=0;
                            me.clienteselected=0;
                            me.sicliente = 0;
                            me.prestacionselected=0;
                            setTimeout(me.tiempo,100);
                         }
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });

            },
            registrarCliente(){
                let me=this;
                axios.post('/clientes/registrar',{
                    'nombre':me.nombre,
                    'papellido':me.papellido,
                    'sapellido':me.sapellido,
                    'ci':me.ci,
                    'nit':me.nit,
                    'telefono':me.telefono,

                    }).then(function (response) {
                        //console.log(response);
                        if(response.data)
                        {
                            me.clearSelected1=0;
                            setTimeout(me.tiempo1, 100);
                            Swal.fire('Registrado Correctamente');
                            me.cerrarModalCliente();
                            me.selectClientes();
                            me.sicliente = 1;
                            me.clienteselected=response.data;
                         }
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error);
                    });

            },
            eliminarVenta(idventa){
                let me=this;
                //console.log("prueba");
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta Seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/ventas/desactivar',{
                        'id': idventa
                    }).then(function (response) {
                        
                        /* swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        ) */
                        me.listarVenta();
                        
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
            
            
            
          
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  
        },
        mounted() {
            this.selectPrest();
            //this.selectPrestaciones();
            this.selectClientes();
            this.selectDescuentos();
            this.listarVenta();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.classModal.addModal('cliente');
            //console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.error{
    color: red;
    font-size: 10px;
    
}
</style>
