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
                    <i class="fa fa-align-justify"></i> Entrada de Productos a Almacenes
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')" :disabled="sucursalselected==0">
                        <i class="icon-plus"></i>&nbsp;Nuevo 
                    </button><span  v-if="sucursalselected==0" class="error"> &nbsp; &nbsp;Debe Seleccionar una Sucursal</span>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2" style="text-align:center">
                            <label for="" >Sucursal:</label>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" @change="listarProductosAlmacen(1,buscar)"
                                    v-model="sucursalselected">
                                    <option value="0" disabled>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursals" :key="sucursal.id" :value="sucursal.id" v-text="sucursal.cod + ' ' + sucursal.nombre"></option>
                                </select>                              
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarProductosAlmacen(1)">
                                <button type="submit" class="btn btn-primary" @click="listarProductosAlmacen(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Fecha Vencimiento</th>
                                <th>Estante</th>
                                <th>Lote</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="almacen in arrayAlmacen" :key="almacen.id">
                                <td>
                                    <!-- <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',almacen)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp; -->
                                    <button v-if="almacen.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarAlmacen(almacen.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarAlmacen(almacen.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td>Admin</td>
                                <td v-text="almacen.codprod"></td>
                                <td v-text="almacen.cantidad" style="text-align:right"></td>
                                <td v-text="almacen.fecha_vencimiento"></td>
                                <td v-text="almacen.ubicacion_estante"></td>
                                <td v-text="almacen.lote"></td>
                                <td>
                                    <div v-if="almacen.activo==1">
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
                        <form  enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <strong class="col-md-3 form-control-label" for="text-input">Producto: <span  v-if="idproductoselected==0" class="error">(*)</span></strong>
                                <div class="col-md-9">
                                    <select v-model="idproductoselected" class="form-control">
                                     <option v-for="producto in productos" :key="producto.id" :value="producto.id" v-text="producto.cod"></option>
                                    </select>
                                   <!-- <Ajaxselect  v-if="clearSelected"
                                    ruta="/producto/selectproducto?buscar=" @found="productos" @cleaning="cleanproductos"
                                    resp_ruta="productos"
                                    labels="cod"
                                    placeholder="Ingrese Texto..." 
                                    idtabla="id"
                                    :id="idproductoselected"
                                    :clearable='true'>
                                </Ajaxselect>-->
                                <span  v-if="idproductoselected==0" class="error">Debe Ingresar el Nombre del producto</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <strong>Cantidad: <span  v-if="cantidad==0" class="error">(*)</span></strong>
                                    <input type="number" class="form-control" v-model="cantidad" style="text-align:right" placeholder="0" v-on:focus="selectAll">
                                    <span  v-if="cantidad==0" class="error">Debe Ingresar la Cantidad </span>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong>Tipo Entrada:</strong>
                                    <select v-model="tipo_entrada" class="form-control">
                                    <option v-for="tipo in arraytipoentrada" :key="tipo" :value="tipo" v-text="tipo"></option>
                                </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong>Fecha de Vencimiento: <span  v-if="fecha_vencimiento==''" class="error">(*)</span></strong>
                                    <input type="date"
                                    :min="fechamin"
                                    class="form-control" 
                                    v-model="fecha_vencimiento" >
                                </div>
                                <span  v-if="fecha_vencimiento==''" class="error">Debe Ingresar la fecha de Vencimiento</span>
                            </div>
                            <div class="row">
                                <!-- <div class="form-group col-sm-4">
                                    <strong>Seleccionar Estante: <span  v-if="estanteselected==0" class="error">(*)</span></strong>
                                    <select v-model="estanteselected" class="form-control" @change="listarposicion(estanteselected)">
                                        <option value="0">Seleccionar...</option>
                                        <option v-for="estante in arrayEstantes" :key="estante.id" :value="estante.id" v-text="estante.codestante"></option>
                                    </select>
                                    <span  v-if="estanteselected==0" class="error">Debe seleccionar un Estante</span>
                                </div> -->
                                <!-- <div class="form-group col-sm-4">
                                    <strong>Seleccionar Ubicacion: <span  v-if="ubicacionSelected==0" class="error">(*)</span></strong>
                                    <select v-model="ubicacionSelected" class="form-control">
                                        <option value="0">Seleccionar...</option>
                                        <option v-for="ubicacion in arrayUbicacions" :key="ubicacion" :value="ubicacion" v-text="ubicacion"></option>
                                    </select>
                                    <span  v-if="ubicacionSelected==0" class="error">Debe seleccionar la ubicacion</span>
                                </div> -->
                                <div class="form-group col-sm-4">
                                    <strong>Lote: <span  v-if="lote==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Lote" v-model="lote" v-on:focus="selectAll">
                                    <span  v-if="lote==''" class="error">Debe Ingresar el lote</span>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong>Registro Sanitario:<span  v-if="registrosanitario==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Registro Sanitario" v-model="registrosanitario" v-on:focus="selectAll">
                                    <span  v-if="registrosanitario==''" class="error">Debe Ingresar el Registro Sanitario</span>
                                </div>
                                <div class="form-group col-sm-4">
                                    <strong class="col-md-3 form-control-label">Codigo Internacional:<span  v-if="codigointernacional==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Codigo Internaciona" v-model="codigointernacional" v-on:focus="selectAll">
                                    <span  v-if="codigointernacional==''" class="error">Debe Ingresar el Codigo Internacional</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 ">
                                    <strong>Codigo: </strong><br><br>
                                    <qrcode-vue :value="codigo" :size="size" level="H" />
                                    <!-- <input type="text" class="form-control" placeholder="Codigo" v-model="codigo" v-on:focus="selectAll"> -->
                                    <!--<span  v-if="codigo==''" class="error">Debe Ingresar el Codigo</span> -->
                                </div>
                                <!-- <div class="form-group col-sm-6 ">
                                    <strong>Registro Sanitario:<span  v-if="registrosanitario==''" class="error">(*)</span></strong>
                                    <input type="text" class="form-control" placeholder="Registro Sanitario" v-model="registrosanitario" v-on:focus="selectAll">
                                    <span  v-if="registrosanitario==''" class="error">Debe Ingresar el Registro Sanitario</span>
                                </div> -->
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAlmacen()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAlmacen()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        
        
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import QrcodeVue from 'qrcode.vue';
import { error401 } from '../../errores';
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
                idproducto:[],
                idproductoselected:'',
                productos:[],
                descripcion:'',
                codigo:'',
                correlativo:0,
                arraySucursals:[],
                tituloModal:'',
                tipoAccion:1,
                idalmacen:'',
                buscar:'',
                codigointernacional:'',
                arrayAlmacen:[],
                sucursalselected:0,
                precio:'',
                clearSelected:1,
                cantidad:0,
                tipo_entrada:'Compra',
                fechaactual:'',
                fechamin:'',
                fecha_vencimiento:'',
                altura:0,
                posicion:0,
                codestante:'',
                estanteselected:0,
                arrayEstantes:[],
                arrayUbicacions:[],
                ubicacionSelected:0,
                lote:'',
                registrosanitario:'',
                arraytipoentrada:['Bonificacion',
                                    'Compensacion',
                                    'Compra',
                                    'Devolucion',
                                    'Donacion',
                                    'Error de Registro',
                                    'Permuta',
                                    'Prestamo',
                                    'Recuperacion',
                                    'Reintegro',
                                    'Reposicion',
                                    'Sobrante',
                                    'Traspaso'],
                
                 //////qrcode
                value: 'https://example.com',
                size: 120,
                productos:[]
                
            }

        },
        components: {
            QrcodeVue,
        },
        computed:{
            generarqr(){
                let me=this;
                me.codigo='';
                if(me.idproductoselected!=0 && me.lote!='' && me.fecha_vencimiento!=me.fechaactual)
                    return me.codigo=me.idproductoselected+'|'+me.lote+'|'+ me.fecha_vencimiento+'|'+me.tipo_entrada;
                else
                    return me.codigo=me.idproductoselected+'|'+me.lote+'|'+ me.fecha_vencimiento+'|'+me.tipo_entrada;
            },
            sicompleto(){
                let me=this;
                me.codigo=JSON.stringify({
                        idproducto:me.idproductoselected,
                        cantidad: me.cantidad,
                        fechaVencimiento:me.fecha_vencimiento,
                        //estante:me.estanteselected,
                        //ubicacion:me.ubicacionSelected,
                        registroSanitario:me.registrosanitario
                    });
                    
                //old if (me.idproductoselected!=0 && me.cantidad!=0 && me.fecha_vencimiento!='' && me.estanteselected!=0 && me.ubicacionSelected!=0 && me.lote!='' && me.codigo!='' && me.registrosanitario!='')
                if (me.idproductoselected!=0 && me.cantidad!=0 && me.fecha_vencimiento!='' && me.estanteselected!=0 && me.lote!='' && me.codigo!='' && me.registrosanitario!='')
                {
                    return true;
                }
                else
                {
                    return false;
                }
                    
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
            listarEstantes(idsucursal){
                let me=this;
                var url='/estante/selectestante?idsucursal='+ idsucursal;
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.arrayEstantes=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });


            },
            listarposicion(idestante){
                let me=this;
                let respuesta=me.arrayEstantes.find(element=>element.id==idestante);
                //console.log(respuesta);
                var valor
                me.codestante=respuesta.codestante;
                me.ubicacionSelected=0;
                me.arrayUbicacions=[];
                for (let index = 1; index <= respuesta.numposicion; index++) {
                    for (let index2 = 1; index2 <= respuesta.numaltura; index2++) {
                        if(index<10)
                        {
                            if(index2<10)
                                valor='0'+index+'-0'+index2;
                            else
                                valor='0'+index+'-'+index2;
                            
                            me.arrayUbicacions.push(valor);
                        }
                        else
                        {
                            if(index2<10)
                                valor=index+'-0'+index2;
                            else
                                valor=index+'-'+index2;

                            me.arrayUbicacions.push(valor);
                        }
                    }
                }
            },
            obtenerfecha(){
                let me = this;
                var url= '/obtenerfecha';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fechaactual=respuesta[0].fecha;
                    me.fechamin=me.fechaactual
                    me.fecha_vencimiento=me.fechaactual;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                
                
                //me.fechafactura=me.fechaactual;
            },
            tiempo(){
            this.clearSelected=1;
            },
            listarProductos(){
                let me = this;
                var url= '/producto/selectproducto2';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.productos=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                
                


            },
            /*
            productos(productos){
                this.idproducto=[];
                for (const key in productos) {
                    if (productos.hasOwnProperty(key)) {
                        const element = productos[key];
                        //console.log(element);
                        this.idproducto.push(element);
                    }
                }
                //console.log(this.idprestaciones);
            },
            cleanproductos(){
                this.idproducto=[];
                this.idproductoselected='';
            
            },*/
            listarProductosAlmacen(page){
                let me=this;
                //me.listarEstantes(me.sucursalselected);
                var url='/almacen?page='+page+'&idsucursal='+me.sucursalselected+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayAlmacen=respuesta.productos.data;
                    me.pagination=respuesta.pagination;
                    me.listarEstantes(me.sucursalselected);
 
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            selectSucursals(){
                let me=this;
                var url='/sucursal/selectsucursal';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arraySucursals=respuesta;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarProductosAlmacen(page);
            },
            registrarAlmacen(){
                let me = this;
                axios.post('/almacen/registrar',{
                    'idsucursal':me.sucursalselected,
                    'idproducto':me.idproductoselected,
                    'idusuario':1,
                    'cantidad':me.cantidad,
                    'tipo_entrada':me.tipo_entrada,
                    'lote':me.lote,
                    'fecha_vencimiento':me.fecha_vencimiento,
                    'codigo':me.codigo,
                    'registro_sanitario':me.registrosanitario,
                    'ubicacion_estante':me.codestante+'-'+me.ubicacionSelected
                }).then(function(response){
                    Swal.fire('Registrado Correctamente')
                    me.cerrarModal('registrar');
                    me.listarProductosAlmacen(1);
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            eliminarAlmacen(idalmacen){
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
                title: 'Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/almacen/desactivar',{
                        'id': idalmacen
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarProductosAlmacen(1);
                        
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
            activarAlmacen(idalmacen){
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
                title: 'Esta Seguro de Activar?',
                text: "Es una Activacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Activar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/almacen/activar',{
                        'id': idalmacen
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarProductosAlmacen(1);
                        
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
                    'El Registro no fue Activado',
                    'error'
                    ) */
                }
                })
            },
            /* actualizarAlmacen(){
               // const Swal = require('sweetalert2')
                let me =this;
                axios.put('/almacen/actualizar',{
                    'id':me.idalmacen,
                    'nombre':me.nombre,
                    'precio':me.precio,
                    'descripcion':me.descripcion,
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarProductosAlmacen(1);
                    } 
                }).catch(function (error) {
                   
                });
                me.cerrarModal('registrar');


            }, */
            abrirModal(accion,data= []){
                let me=this;
                me.listarEstantes(me.sucursalselected);
                let respuesta=me.arraySucursals.find(element=>element.id==me.sucursalselected);
                switch(accion){
                    case 'registrar':
                    {
                        if(me.sucursalselected!=0)
                        {
                            
                            me.tituloModal='Registar Almacen para: '+ respuesta.sucursal;
                            me.tipoAccion=1;
                            me.nombre='';
                            me.precio='';
                            me.descripcion='';
                            me.classModal.openModal('registrar');
                        }
                        else
                        {
                            Swal.fire('Debe Seleccionar un Sucursal')
                        }
                        
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.idalmacen=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Almacen para: '+ respuesta.sucursal
                        me.nombre=data.nombre;
                        me.precio=data.precio;
                        me.descripcion=data.descripcion;
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                //me.sucursalselected=0;
                me.idproducto=[];
                me.clearSelected=0;
                setTimeout(me.tiempo, 200); 
                me.cantidad=0;
                me.tipo_entrada='';
                me.lote='';
                me.fecha_vencimiento=me.fechaactual;
                me.codigo='';
                me.registrosanitario='';
                me.ubicacionSelected=0;
                me.estanteselected=0;
                me.codestante='';
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  
            


        },
        mounted() {
            this.obtenerfecha(1);
            this.selectSucursals();
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
            this.listarProductos();
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
