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
                    <i class="fa fa-align-justify"></i> Descuentos
                    <button type="button" class="btn btn-secondary" @click="abrirModal('registrar')">
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
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Porcentaje</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="descuento in arrayDescuentos" :key="descuento.id">
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('actualizar',descuento)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <button v-if="descuento.activo==1" type="button" class="btn btn-danger btn-sm" @click="eliminarDescuento(descuento.id)" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm" @click="activarDescuento(descuento.id)" >
                                        <i class="icon-check"></i>
                                    </button>
                                </td>
                                <td v-text="descuento.nombre"></td>
                                <td v-text="descuento.descripcion"></td>
                                <td v-if="descuento.siporcentaje">Si</td>
                                <td v-else>No</td>
                                <td v-if="descuento.siporcentaje"> {{descuento.monto}} %</td>
                                <td v-else> {{ descuento.monto }} Bs.</td>
                                
                                <td>
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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre <span  v-if="!sicompleto" class="error">(*)</span></label>
                                <div class="col-md-9">
                                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del Descuento" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="!sicompleto" class="error">Debe Ingresar el Nombre del Descuento</span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Descripción:</label>
                                <div class="col-md-9">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese una Descripción" v-model="descripcion" v-on:focus="selectAll">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="chekbox" class="col-md-4">¿Descuento en Porcentaje?</label>
                                <div class="col-md-8">
                                    <label class="switch switch-pill switch-success">
                                        <input class="switch-input" 
                                                v-model="siporcentaje"
                                                type="checkbox" 
                                                checked="">
                                        <span class="switch-slider"></span>
                                    </label>    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Monto: <span  v-if="!sicompletomonto" class="error">(*)</span></label>
                                <div class="col-md-8">
                                    <input type="number" id="monto" name="monto" class="form-control" placeholder="0.0" v-model="monto" style="text-align:right" v-on:focus="selectAll" ><span v-if="siporcentaje">&nbsp;%</span><span v-else>&nbsp;Bs.</span>
                                    <span  v-if="!sicompletomonto" class="error">Debe Ingresar el Monto del descuento</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  @click="cerrarModal('registrar')">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDescuento()" :disabled="!sicompleto">Guardar</button>
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
import Swal from 'sweetalert2'
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
                arrayDescuentos:[],
                tituloModal:'',
                tipoAccion:1,
                iddescuento:'',
                buscar:'',
                siporcentaje:true,
                monto:'',
                caracter:'%'
                
            }

        },
        computed:{
            sicompletomonto(){
                let me=this;
                if (me.monto!=0)
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
            listarDescuentos(page){
                let me=this;
                var url='/descuento?page='+page+'&buscar='+me.buscar;
                axios.get(url).then(function(response){
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
                axios.post('/descuento/registrar',{
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'siporcentaje':me.siporcentaje,
                    'monto':me.monto
                }).then(function(response){
                    me.cerrarModal('registrar');
                    me.listarDescuentos();
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
                     axios.put('/descuento/desactivar',{
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
                     axios.put('/descuento/activar',{
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
            actualizarDescuento(){
               // const Swal = require('sweetalert2')
                let me =this;
                axios.put('/descuento/actualizar',{
                    'id':me.iddescuento,
                    'nombre':me.nombre,
                    'descripcion':me.descripcion,
                    'siporcentaje':me.siporcentaje,
                    'monto':me.monto
                    
                }).then(function (response) {
                    if(response.data.length){
                    }
                    // console.log(response)
                    else{
                            Swal.fire('Actualizado Correctamente')

                        me.listarDescuentos();
                    } 
                }).catch(function (error) {
                    error401(error);
                    console.log(error);
                });
                me.cerrarModal('registrar');


            },
            abrirModal(accion,data= []){
                let me=this;
                switch(accion){
                    case 'registrar':
                    {
                        me.tituloModal='Registar Descuento'
                        me.tipoAccion=1;
                        me.nombre='';
                        me.descripcion='';
                        me.siporcentaje=true;
                        me.monto='';
                        me.classModal.openModal('registrar');
                        break;
                    }
                    
                    case 'actualizar':
                    {
                        me.iddescuento=data.id;
                        me.tipoAccion=2;
                        me.tituloModal='Actualizar Descuento'
                        me.nombre=data.nombre;
                        me.descripcion=data.descripcion;
                        me.siporcentaje=data.siporcentaje;
                        me.monto=data.monto;
                        me.classModal.openModal('registrar');
                        break;
                    }

                }
                
            },
            cerrarModal(accion){
                let me = this;
                me.classModal.closeModal(accion);
                me.nombre='';
                me.descripcion='';
                me.siporcentaje=true;
                me.monto=0;
                me.tipoAccion=1;
                
            },
            selectAll: function (event) {
                setTimeout(function () {
                    event.target.select()
                }, 0)
            },  


        },
        mounted() {
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
