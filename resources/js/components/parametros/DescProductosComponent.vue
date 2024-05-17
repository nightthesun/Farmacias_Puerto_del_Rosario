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
                    <i class="fa fa-align-justify"></i> Configuracion de Descuentos en Productos
                    <button type="button" v-if="puedeCrear==1" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarDescuentos(1)">
                                <button type="submit" class="btn btn-primary" @click="listarDescuentos(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-1">Opciones</th>
                                <th class="col-md-2">Nombre</th>
                                <th class="col-md-2">Monto Descuento</th>
                                <th class="col-md-2">Tipo Descuento</th>
                                <th class="col-md-2">Regla</th>
                                <th class="col-md-2">Aplica A</th>
                                <th class="col-md-1">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="descuento in arrayDescuentos" :key="descuento.id">
                                <td class="col-md-1">
                                    <div  class="d-flex justify-content-start">
                                        <div  v-if="puedeEditar==1">
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',descuento)" style="margin-right: 5px;">
                                             <i class="icon-pencil"></i>
                                             </button>
                                        </div>
                                        <div v-else>
                                            <button type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                             <i class="icon-pencil"></i>
                                             </button>
                                        </div>
                                        <div v-if="puedeActivar==1">
                                            <button v-if="descuento.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarDescuento(descuento.id)" style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarDescuento(descuento.id)" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                        <div v-else>
                                            <button v-if="descuento.activo==1" type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm"  style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                        </div>
                                    </div>                                 
                                    
                                </td>
                                <td v-text="descuento.nombre" class="col-md-2"></td>
                                <td v-text="descuento.monto_descuento" class="col-md-2"></td>
                                <td v-text="descuento.idtipodescuento" class="col-md-2"></td>
                                <td v-text="descuento.regla" class="col-md-2"></td>
                                <td v-text="descuento.aplica_a" class="col-md-2"></td>
                                <td class="col-md-1">
                                    <div v-if="descuento.activo==1">
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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Descuento: <span  v-if="nombre==''" class="error">(*)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Nombre Descuento" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="nombre==''" class="error">Debe Ingresar el Monto del descuento</span>
                                </div>
                            </div>
                            <div class="from-group row">
                                <div class="col-md-6">
                                    <strong>Tipo de Descuento:</strong>
                                    <select class="form-control" @change="listarSubcategorias()" v-model="idtipodescuentoselected">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option v-for="tipodescuento in arrayTipoDescuentos" :key="tipodescuento.cod" v-text="tipodescuento.aplica_a " :value="tipodescuento.cod"></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Sub Categoria:</strong>
                                    <select class="form-control" v-model="subcategoriaselected" @change="listarDetalle()">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option v-for="subcategoria in arraySubCategorias" :key="subcategoria" v-text="subcategoria" :value="subcategoria"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="from-group row col-md-12" v-if="valor==1 || valor==4 || valor==5 ">
                                    <div class="col-md-6" >
                                        <strong>Operador:</strong>
                                        <select class="form-control" v-model="detalleselected">
                                            <option disabled value="0">Seleccionar...</option>
                                            <option v-for="detalle in arrayDetalle" :key="detalle.id" v-text="detalle.valor" :value="detalle.valor"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" v-if="valor==4 || valor==5">
                                        <strong>Valor:</strong>
                                        <input type="number" class="form-control" placeholder="0" v-model="limite" v-on:focus="selectAll" style="text-align:right">
                                        <span  v-if="limite==0" class="error">Debe Ingresar el valor</span>
                                    </div>
                                </div>
                                <div v-else-if="valor==2" class="col-md-6">

                                </div>
                                <div v-else-if="valor==3" class="col-md-6">
                                    <strong>Categoria:</strong>
                                     <select class="form-control" v-model="idcategoriaselected">
                                            <option disabled value="0">Seleccionar...</option>
                                            <option v-for="categoria in categorias" :key="categoria.id" v-text="categoria.nombre" :value="categoria.id"></option>
                                        </select>

                                    <!--<Ajaxselect  v-if="clearSelected"
                                        ruta="/categoria/selectcategoria?buscar=" @found="categorias" @cleaning="cleancategorias"
                                        resp_ruta="categorias"
                                        labels="nombre"
                                        placeholder="Ingrese Texto..." 
                                        idtabla="id"
                                        :id="idcategoriaselected"
                                        :clearable='true'>
                                    </Ajaxselect>-->
                                </div>
                                <div v-else-if="valor==7" class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="form-control-label" for="date-input">Fecha Inicial:</label>
                                            <input class="form-control" 
                                                type="date" v-model="fechainicio"
                                                :min="fechahoy">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-control-label" for="date-input">Fecha Final:</label>
                                            <input class="form-control" 
                                                type="date" v-model="fechafin"
                                                :min="fechahoy">
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="valor==6" class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-5">
                                            <div  class="form-check" v-for="diasemana in arrayDetalle" v-bind:key="diasemana.id">
                                                <input class="form-check-input" type="checkbox" v-model="diaselected" :id="diasemana.valor" :value="diasemana.valor">
                                                <label class="form-check-label" :for="diasemana.valor">{{diasemana.valor}}</label>
                                            </div>
                                        </div>
                                        <div class="col.md-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" v-model="repetir" id="cadasemana" value="1">
                                                <label class="form-check-label" for="cadasemana">Repetir Cada Semana</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" v-model="repetir" id="unavez" value="2">
                                                <label class="form-check-label" for="unavez">Repetir Una Vez</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="valor==8" class="col-md-6">
                                    <strong>Detalle:</strong>
                                    <input class="form-control" 
                                        type="date" v-model="fechax"
                                        :min="fechahoy">
                                </div>
                               <!--  <div v-else-if="valor==6" class="col-md-6" >
                                    <div  class="form-check " v-for="diasemana in arrayDetalle" v-bind:key="diasemana.id">
                                        <input class="form-check-input" type="checkbox" v-model="diaselected" :id="diasemana.valor" :value="diasemana.valor">
                                        <label class="form-check-label" :for="diasemana.valor">{{diasemana.valor}}</label>
                                    </div>
                                </div> -->
                            </div>
                            <hr>
                            <div class="from-group row">
                                <div class="col-md-3">
                                    <strong>% de Descuento:</strong>
                                    <input type="number" class="form-control" placeholder="0" v-model="descuento" v-on:focus="selectAll" style="text-align:right">
                                    <span  v-if="descuento==0" class="error">Debe Ingresar el % del descuento</span>
                                </div>
                                <div class="col-md-6" v-if="idtipodescuentoselected!=1">
                                    <strong>Aplica A:</strong>
                                    <select class="form-control" v-model="aplicaselected">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option v-for="aplica in arrayAplica" :key="aplica.id" v-text="aplica.valor" :value="aplica.id"></option>
                                    </select>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDescuento()" :disabled="!sicompleto || !descuento>0">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDescuento()">Actualizar</button>
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
import { error401 } from '../../errores';
//Vue.use(VeeValidate);
    export default {
        //---permisos_R_W_S
        props: ['codventana'],
        //-------------------
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
                regla_descuento:'',
                codigo:'',
                correlativo:0,
                tituloModal:'',
                tipoAccion:1,
                iddescuento:'',
                buscar:'',
                idtipodescuento:true,
                regla:'',
                caracter:'%',

                arrayDias:[],
                arrayDescuentos:[],
                arrayTipoDescuentos:[],
                idtipodescuentoselected:0,
                aplica_a:'',
                arrayAplica:[{'id':1,'valor':'Todos los Productos'},
                                {'id':2,'valor':'Producto individual'},
                                {'id':3,'valor':'Categoria'},
                                {'id':4,'valor':'Metodo ABC'}],
                montodescuento:0,
                arraySubCategorias:[],
                subcategoriaselected:0,
                monto:'',
                nomdescuento:'',
                arrayOperadores:[{'id':1,'valor':'='},
                                    {'id':2,'valor':'>'},
                                    {'id':3,'valor':'<'}
                                    ],
                arrayABC:[{'id':1,'valor':'A'},
                            {'id':2,'valor':'B'},
                            {'id':3,'valor':'C'}],
                arrayDias:[{'id':1,'valor':'Lunes'},
                            {'id':2,'valor':'Martes'},
                            {'id':3,'valor':'Miercoles'},
                            {'id':4,'valor':'Jueves'},
                            {'id':5,'valor':'Viernes'},
                            {'id':6,'valor':'Sabado'},
                            {'id':7,'valor':'Domingo'}],
                arrayDetalle: [],
                detalleselected:0,
                valor:0,

                idcategoria:[],
                idcategoriaselected:0,
                categorias:[],
                clearSelected:1,

                fechainicio:'',
                fechafin:'',
                fechamin:'',
                fechahoy:'',
                diaselected:[],
                repetir:1,
                fechax:'',
                descuento:0,
                aplicaselected:0,
                limite:0,
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------

                
            }

        },
        computed:{
            sicompletoregla(){
                let me=this;
                if (me.regla!=0)
                    return true;
                else
                    return false;
            },
            sicompleto(){
                let me=this;
                if (me.nombre!='')
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
            //-----------------------------------permisos_R_W_S        
 listarPerimsoxyz() {
                //console.log(this.codventana);
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
//--------------------------------------------------------------  
            obtenerfecha(valor){
                let me = this;
                var url= '/obtenerfecha';
                axios.get(url).then(function (response) {
                    let respuesta= response.data; 
                    me.fechaactual=respuesta[0].fecha;
                    me.fechainicio=me.fechaactual;
                    me.fechafin=me.fechaactual;
                    me.fechahoy=me.fechaactual;
                    me.fechax=me.fechahoy;
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
            /*cleancategorias(){
                this.idcategoria=[];
                this.idcategoriaelected='';
            
            },
            categorias(categorias){
                this.idcategoria=[];
                for (const key in categorias) {
                    if (categorias.hasOwnProperty(key)) {
                        const element = categorias[key];
                        //console.log(element);
                        this.idcategoria.push(element);
                    }
                }
            },*/
            listarCategorias(){
                let me = this;
                var url= '/categoria/selectcategoria2';
                axios.get(url).then(function (response) {
                    let respuesta= response.data; 
                    me.categorias=respuesta;
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });

            },
            listarSubcategorias(){
                let me =this;
                me.subcategoriaselected=0;
                me.valor=0;
                let resp=me.arrayTipoDescuentos.find(element=>element.id==me.idtipodescuentoselected);
                let subcategoria=resp.subcategorias;
                
                me.arraySubCategorias=subcategoria.split('|');
            },
            selectTipoDescuentos(){
                let me=this;
                var url='/tipodescuento/selecttipodescuento';
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    //me.arrayDias=respuesta.arraydias;
                    me.arrayTipoDescuentos=respuesta.tipodescuentos;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            listarDescuentos(page){
                let me=this;
                var url='/proddescuento?page='+page+'&buscar='+me.buscar;
                axios.get(url)
                .then(function(response){
                    console.log(response);
                    var respuesta=response.data;
                    me.pagination=respuesta.pagination;
                    me.arrayDescuentos=respuesta.descuentos.data;
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarDescuentos(page);
            },
            registrarDescuento(){
                let me = this;
                //console.log(me.subcategoriaselected);
                //me.regla=me.subcategoriaselected+"|"+me.detalleselected+"|"+me.limite+"|"+me.idcategoriaselected+"|"+me.fechainicio+"|"+me.fechafin+"|"+me.diaselected+"|"+me.repetir+"|"+me.fechax;
                if (me.idtipodescuentoselected == 1) {
                    switch (me.subcategoriaselected) {
                        case me.arraySubCategorias[0]:
                            me.regla=me.subcategoriaselected+"|"+me.detalleselected;       
                            break;
                        case me.arraySubCategorias[1]:
                            me.regla=me.subcategoriaselected;       
                            break;
                        case me.arraySubCategorias[2]:
                            me.regla=me.subcategoriaselected+"|"+me.idcategoriaselected;
                            break;
                        default:
                            break;
                    }
                } else if (me.idtipodescuentoselected == 2) {
                    me.regla=me.subcategoriaselected+"|"+me.detalleselected+"|"+me.limite;                  
                } else if (me.idtipodescuentoselected == 3){
                    switch (me.subcategoriaselected) {
                        case me.arraySubCategorias[0]:
                            me.regla=me.subcategoriaselected+"|"+me.diaselected+"|"+me.repetir;       
                            break;
                        case me.arraySubCategorias[1]:
                            me.regla=me.subcategoriaselected+"|"+me.fechainicio+"|"+me.fechafin;       
                            break;
                        case me.arraySubCategorias[2]:
                            me.regla=me.subcategoriaselected+"|"+me.fechax;
                            break;
                        default:
                            break;
                    }
                }else if (me.idtipodescuentoselected == 4){
                    me.regla=me.subcategoriaselected;
                }else{
                    me.regla="";
                }

                axios.post('/proddescuento/registrar',{
                    'nombre':me.nombre,
                    'monto_descuento':me.descuento,
                    'idtipodescuento':me.idtipodescuentoselected,
                    'regla':me.regla,
                    'aplica_a':me.aplicaselected,
                    'activo':1,
                    'estado':0,                    
                }).then(function(response){
                    Swal.fire(
                    'Almacenado Correctamente!',
                    'Presione el boton ok para continuar!',
                    'success'
                    )
                    me.cerrarModal('registrar');
                    me.listarDescuentos(1);
                }).catch(function(error){
                    error401(error);
                    console.log(error);
                });

            },
            eliminarDescuento(iddescuento){
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
                     axios.put('/proddescuento/desactivar',{
                        'id': iddescuento
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarDescuentos();
                        
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
            activarDescuento(iddescuento){
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
                     axios.put('/proddescuento/activar',{
                        'id': iddescuento
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarDescuentos();
                        
                    }).catch(function (error) {
                        error401(error);
                        console.log(error.data);
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
            actualizarDescuento(){
               // const Swal = require('sweetalert2')
                let me =this;
                if (me.idtipodescuentoselected == 1) {
                    switch (me.subcategoriaselected) {
                        case me.arraySubCategorias[0]:
                            me.regla=me.subcategoriaselected+"|"+me.detalleselected;       
                            break;
                        case me.arraySubCategorias[1]:
                            me.regla=me.subcategoriaselected;       
                            break;
                        case me.arraySubCategorias[2]:
                            me.regla=me.subcategoriaselected+"|"+me.idcategoriaselected;
                            break;
                        default:
                            break;
                    }
                } else if (me.idtipodescuentoselected == 2) {
                    me.regla=me.subcategoriaselected+"|"+me.detalleselected+"|"+me.limite;                  
                } else if (me.idtipodescuentoselected == 3){
                    switch (me.subcategoriaselected) {
                        case me.arraySubCategorias[0]:
                            me.regla=me.subcategoriaselected+"|"+me.diaselected+"|"+me.repetir;       
                            break;
                        case me.arraySubCategorias[1]:
                            me.regla=me.subcategoriaselected+"|"+me.fechainicio+"|"+me.fechafin;       
                            break;
                        case me.arraySubCategorias[2]:
                            me.regla=me.subcategoriaselected+"|"+me.fechax;
                            break;
                        default:
                            break;
                    }
                }else if (me.idtipodescuentoselected == 4){
                    me.regla=me.subcategoriaselected;
                }else{
                    me.regla="";
                }
                axios.put('/proddescuento/actualizar',{
                    'id':me.iddescuento,
                    'nombre':me.nombre,
                    'monto_descuento':me.descuento,
                    //'idtipodescuento':me.idtipodescuento,
                    'idtipodescuento':me.idtipodescuentoselected,
                    'regla':me.regla,
                    'aplica_a':me.aplicaselected

                }).then(function (response) {
                    console.log(response);
                    // if(response.data.length){
                    // }
                    // // console.log(response)
                    // else{
                    //         Swal.fire('Actualizado Correctamente')

                    //     me.listarDescuentos();
                    // } 
                }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                me.cerrarModal('registrar');


            },
            listarDetalle(){
                let me=this;
                me.arrayDetalle=0;
                me.valor=0;
                switch (me.subcategoriaselected) {
                    case 'Metodo ABC':
                        {
                            me.valor=1;
                            me.arrayDetalle=me.arrayABC;
                            break;
                        }
                    case 'Producto Individual':
                    {
                        me.valor=2;
                        break;
                    }
                    case 'Categoria':
                    {
                        me.valor=3;
                        break;
                    }
                    case 'Monto mayor A':
                    {
                        me.valor=4;
                        me.arrayDetalle=me.arrayOperadores;
                        break;
                    }
                    case 'Cantidad de Compras':
                    {
                        me.valor=5;
                        me.arrayDetalle=me.arrayOperadores;
                        break;
                    }
                    case 'Semana':
                    {
                        me.valor=6;
                        me.arrayDetalle=me.arrayDias;
                        break;
                    }
                    case 'Rango de Fechas':
                    {
                        me.valor=7;
                        break;
                    }
                    case 'Fecha X':
                    {
                        me.valor=8;
                        break;
                    }
                    case 'Efectivo':
                    {
                        
                        me.valor=9;
                        break;
                    }
                    case 'Tarjeta':
                    {
                        me.valor=10;
                        break;
                    }
                    case 'Transferencia':
                    {
                        me.valor=11;
                        break;
                    }

                    default:
                        break;
                }

            },

            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        //me.listarDescuentos();
                       
                        me.tituloModal='Registar Descuento'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.regla_descuento='';
                        me.idtipodescuento=true;
                        me.regla='';
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.iddescuento=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Descuento';
                        me.nombre=data.nombre;
                        me.idtipodescuentoselected=data.idtipodescuento;
                        this.listarSubcategorias();
                        this.listarDetalle();
                        let auxArray=data.regla.split("|");
                        // console.log(auxArray[0]);
                        me.subcategoriaselected=auxArray[0];
                        this.listarDetalle();
                        me.detalleselected = auxArray[1]; 
                        me.limite=auxArray[2];
                        me.idcategoriaselected=auxArray[2];
                        me.fechainicio=auxArray[1];
                        me.fechafin=auxArray[2];
                        me.diaselected=auxArray[1];
                        me.repetir=auxArray[2];
                        me.fechax=auxArray[1];
                        me.descuento=data.monto_descuento;
                        me.aplicaselected=data.aplica_a;
                        // me.regla_descuento=data.regla_descuento;
                        // me.idtipodescuento=data.idtipodescuento;
                        // me.regla=data.regla;
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.regla_descuento='';
                me.idtipodescuento=true;
                me.regla=0;
                me.tipoAccion=1;
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
            //-------permiso E_W_S-----
            this.listarPerimsoxyz();
          //    this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
            this.obtenerfecha();
            this.selectTipoDescuentos();
            this.listarCategorias();
            this.listarDescuentos(1);
            this.classModal = new _pl.Modals();
            this.classModal.addModal('registrar');
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
