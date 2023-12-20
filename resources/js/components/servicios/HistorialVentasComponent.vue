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
                    <i class="fa fa-align-justify"></i> Historial de Ventas
                    <!-- <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button> -->
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="form-control-label" for="date-input">Fecha Inicial:</label>
                            <input class="form-control" 
                                type="date" v-model="fechainicio"
                                :max="fechafin"
                                :min="fechamin">
                            <span class="text-error" v-if="!verificarfechainicio">La Fecha debe ser menor a la fecha actual o fecha final</span>
                        </div>
                        <div class="col-md-3">
                            <label class="form-control-label" for="date-input">Fecha Final:</label>
                            <input class="form-control" 
                                type="date" v-model="fechafin"
                                :max="fechahoy"
                                :min="fechainicio">
                            <span class="text-error" v-if="!verificarfechafin">La Fecha debe ser menor a la fecha actual</span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-control-label">Buscador:</label>
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="listarVentas(1)">
                                <button type="submit" class="btn btn-primary" @click="listarVentas(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                            
                            
                        </div>
                    </div>
                    
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Fecha Venta</th>
                                <th>Cliente</th>
                                <th>Precio Total</th>
                                <th>Efectivo</th>
                                <th>Cambio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVentas" :key="venta.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('detalle',venta)">
                                        <i class="icon-eye"></i>
                                    </button> &nbsp;
                                    <button v-if="venta.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarVenta(venta.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarVenta(venta.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td>Admin</td>
                                <td v-text="venta.created_at"></td>
                                <td v-text="venta.nombres"></td>
                                <td v-text="venta.total +' Bs.'" style="text-align:right"></td>
                                <td v-text="venta.efectivo +' Bs.'" style="text-align:right"></td>
                                <td v-text="venta.cambio +' Bs.'" style="text-align:right"></td>
                                <td>
                                    <div v-if="venta.activo==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning">Desactivado</span>
                                    </div>
                                    
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align:right">Suma Total:</th>
                                <th style="text-align:right">{{sumatotal}} &nbsp;Bs.</th>
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
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="detalle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close"  aria-label="Close" @click="cerrarModal('detalle')">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table class="table table-responsive">
                                    <tr style="background-color: antiquewhite;">
                                        <td ><b>Cliente:</b> <br />  {{ cliente }}</td>
                                        <td ><b>Fecha de Venta:</b> <br />  {{ fechaventa }}</td>
                                    </tr>
                                    </table>
                                    <table class="table table-bordered table-striped table-sm table-responsive">
                                    
                                    
                                        <tr style="background-color: bisque;">
                                            <th>#</th>
                                            <th>Codigo</th>
                                            <th>Prestacion</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Total Cancelado</th>
                                        </tr>
                                        <tr v-for="ventadetalle in arrayVentasDetalle" :key="ventadetalle.id">
                                            <td>1</td>
                                            <td v-text="ventadetalle.cod"></td>
                                            <td v-text="ventadetalle.nompres"></td>
                                            <td v-text="ventadetalle.precio + ' Bs.'" style="text-align:right"></td>
                                            <td v-text="ventadetalle.descuento"></td>
                                            <td v-text="ventadetalle.monto_cancelado + ' Bs.'" style="text-align:right"></td>
                                        </tr>
                                        <tr style="background-color: lightblue;">
                                            <td colspan="5" style="text-align:right"><b>Venta Total:</b> </td>
                                            <td style="text-align:right">{{ ventatotal + ' Bs.' }}</td>
                                        </tr>
                                        <tr style="background-color: lightgoldenrodyellow;">
                                            <td colspan="5" style="text-align:right"><b>Efectivo:</b> </td>
                                            <td style="text-align:right">{{ efectivo + ' Bs.' }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" style="text-align:right"><b>Cambio:</b> </td>
                                            <td style="text-align:right">{{ cambio + ' Bs.' }}</td>
                                        </tr>

                                        

                                    </table>

                                </div>
                                
                                
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('detalle')">Cerrar</button>
                        <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArea()" :disabled="!sicompleto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArea()">Actualizar</button> -->
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
                arrayVentas:[],
                tituloModal:'',
                tipoAccion:1,
                idarea:'',
                buscar:'',

                fechainicio:'',
                fechafin:'',
                fechamin:'',
                anio:'',
                mes:'',
                dia:'',
                fechahoy:'',
                sumatotal:0,
                fechaventa:'',
                cliente:'',
                ventatotal:0,
                efectivo:0,
                cambio:0,
                arrayVentasDetalle:[],
                contador:0,

                
            }

        },
        computed:{
            verificarfechainicio:function(){
                let me=this;
                var correcto=true;
                if(me.fechainicio>me.fechahoy || me.fechainicio>me.fechafin)
                    correcto=false;    
                return correcto

            },
            verificarfechafin:function(){
                let me=this;
                var correcto=true;
                if(me.fechafin>me.fechahoy || me.fechafin<me.fechainicio)
                    correcto=false;    
                return correcto

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
            obtenerfecha(valor){
                let me = this;
                var url= '/obtenerfecha';
                axios.get(url).then(function (response) {
                    var respuesta= response.data; 
                    me.fechaactual=respuesta[0].fecha;
                    
                    
                    var arrayfechas = me.fechaactual.split("-");
                    me.anio=arrayfechas[0];//año
                    me.mes=arrayfechas[1];//mes
                    me.dia=arrayfechas[2];//dia
                    me.fechainicio=me.fechaactual;
                    me.fechafin=me.fechaactual;
                    me.fechahoy=me.fechaactual;
                    if(valor==1)
                     me.listarVentas(1);
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                
                
                //me.fechafactura=me.fechaactual;
            },
            listarVentas(page){
                let me=this;
                var url='/ventasmaestro?page='+page+'&buscar='+me.buscar+'&fechainicio='+me.fechainicio+'&fechafin='+me.fechafin;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    //console.log(respuesta.areas);
                    me.pagination=respuesta.pagination;
                    //console.log(me.areas.data);
                    me.arrayVentas=respuesta.ventamaestro.data;
                    //porcenit=Number((me.invoice_subtotal * me.porcentajeit).toFixed(2));
                    me.sumatotal=Number((respuesta.sumatotal).toFixed(2));
                    //console.log(me.arrayVentas);
                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
            },
            cambiarPagina(page){
                let me =this;
                me.pagination.current_page = page;
                me.listarVentas(page);
            },
            
            eliminarVenta(idventamaestro){
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
                     axios.put('/ventasmaestro/desactivar',{
                        'id': idventamaestro
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarVentas();
                        
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
            activarVenta(idventamaestro){
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
                     axios.put('/ventasmaestro/activar',{
                        'id': idventamaestro
                    }).then(function (response) {
                        
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarVentas();
                        
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
            
            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'detalle':
                    {
                        let me=this;
                        var url='/ventas/detalle?id='+data.id;
                        axios.get(url).then(function(response){
                            var respuesta=response.data;
                            me.arrayVentasDetalle=respuesta;
                            me.tituloModal='Detalle Venta'
                            me.fechaventa=data.created_at;
                            me.cliente=data.nombres;
                            me.ventatotal=data.total;
                            me.efectivo=data.efectivo;
                            me.cambio=data.cambio;
                            me.contador=0;
                            me.classModal.openModal('detalle');

                        })
                        .catch(function(error){
                            error401(error);
                            console.log(error);
                        });
                        
                        
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.idarea=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Area'
                        me.nombre=data.nombre;
                        me.descripcion=data.descripcion;
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.fechaventa='';
                me.cliente='';
                me.ventatotal='';
                me.efectivo='';
                me.cambio='';
                me.contador=0;
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
            
            this.obtenerfecha(1);
            
            this.classModal = new _pl.Modals();
            this.classModal.addModal('detalle');
            //this.listarVentas(1);
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
