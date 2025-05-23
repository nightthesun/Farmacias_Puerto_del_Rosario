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
                    <i class="fa fa-align-justify"></i> Tiendas Disponibles
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar" v-model="buscar"  @keyup.enter="buscarTienda(1)">
                                <button type="submit" class="btn btn-primary" @click="buscarTienda(1)"><i class="fa fa-search" ></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Codigo</th>
                                <th>Direccion</th>
                                <th>Nit</th>
                                <th>Telefonos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tienda in arrayTiendas" :key="tienda.id">
                                <td>
                                    <!-- <button type="button" class="btn btn-warning btn-sm"
                                        @click="abrirModal('actualizar', tienda)">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp; -->
                                    <div v-if="puedeActivar==1">
                                        <button v-if="tienda.activo_tienda == 1" type="button"
                                        class="btn btn-danger btn-sm" @click="eliminarTienda(tienda.id_tienda)">
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-info btn-sm"
                                        @click="activarTienda(tienda.id_tienda)">
                                        <i class="icon-check"></i>
                                    </button>
                                    </div>
                                    <div v-else>
                                        <button v-if="tienda.activo_tienda == 1" type="button"
                                        class="btn btn-light btn-sm" >
                                        <i class="icon-trash"></i>
                                    </button>
                                    <button v-else type="button" class="btn btn-light btn-sm"
                                       >
                                        <i class="icon-check"></i>
                                    </button>
                                    </div>
                                   
                                </td>
                                <td>{{ tienda.codigo_tienda }}</td>
                                <td>{{ tienda.direccion }} </td>
                                <td>{{ tienda.nit }} </td>
                                <td>{{ tienda.telefonos }}</td>
                                <td>
                                    <div v-if="tienda.activo_tienda == 1">
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
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#"
                                    @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    
        <!-- Modal para la busqueda de producto por lote -->
        <div class="modal fade" id="staticBackdrop" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Busqueda de Productos</h5>
                        <button type="button" @click="cerrarModal('staticBackdrop')" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            @click="cerrarModal('staticBackdrop')">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Fun Modal para la busqueda de producto por lote -->

        

    </main>
</template>

<script>
import Swal from 'sweetalert2';
import QrcodeVue from 'qrcode.vue';
import { error401 } from '../../errores';

//Vue.use(VeeValidate);
export default {
     //---permisos_R_W_S
     props: ['codventana'],
        //-------------------
    data() {
        return {
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0
            },
            offset: 3,
            arrayTiendas: [],
            arrayTiendaCopy:[],
            buscar:'',
          
              //---permisos_R_W_S
              puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
        }

    },
    components: {
        QrcodeVue,
    },
    computed: {

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
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++
            }
            return pagesArray;
        },

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
        });
},
//--------------------------------------------------------------   

        listarTiendas(page) {
            let me = this;
            var url = '/tienda?page=' + page + '&buscar=' + me.buscar;
            axios.get(url).then(function (response) {
                me.pagination = response.data.pagination;
                me.arrayTiendas = response.data.tiendas.data;
       
                // La copia se hace para facilitar la busqueda
                me.arrayTiendaCopy = me.arrayTiendas;  
            })
                .catch(function (error) {
                    error401(error);
                });
        },

        buscarTienda() {
            let me = this;
            me.listarTiendas(1);
            let arrayAuxiliar =[];
            if (me.buscar.trim().length == 0) {
                me.arrayTiendas = me.arrayTiendaCopy;
            } else {
                let evaluacion = false;
                let texto = me.buscar.trim().toLowerCase()
                me.arrayTiendas.forEach(tienda => {
                    evaluacion = (tienda.codigo_tienda.toLowerCase().includes(texto) ||
                        tienda.direccion.toLowerCase().includes(texto) ||
                        tienda.nit.toLowerCase().includes(texto) ||
                        tienda.telefonos.toLowerCase().includes(texto));

                    if (evaluacion) {
                        arrayAuxiliar.push(tienda);
                    }
                });
                me.arrayTiendas = arrayAuxiliar;
            }
        },

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarTiendas(page);
        },

        eliminarTienda(idtienda) {
            let me = this;
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
                    axios.put('/tienda/desactivar', {
                        'id': idtienda
                    }).then(function (response) {
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )
                        me.listarTiendas(1);
                    }).catch(function (error) {
                        error401(error);
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

        activarTienda(idtienda) {
            let me = this;
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
                    axios.put('/tienda/activar', {
                        'id': idtienda
                    }).then(function (response) {

                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
                        me.listarTiendas(1);
                    }).catch(function (error) {
                        error401(error);
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

      

       

        

        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.idproducto = [];
                me.clearSelected = 0;
                me.idproductoselected = 0;
                setTimeout(me.tiempo, 200);
                me.cantidad = 0;
                me.tipo_entrada = '';
                me.lote = '';
                me.fecha_vencimiento = '';//me.fechaactual;
                me.codigo = '';
                me.registrosanitario = '';
                me.ubicacionSelected = 0;
                me.estanteselected = 0;
                me.codestante = '';
                me.productoperecedero = 0;
            } else {
                me.classModal.closeModal(accion);
                //me.idproductoselected = me.idproductoselected; 
                me.classModal.openModal('registrar');
            }

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
            //-----------------------
        this.listarTiendas(1);
        this.classModal = new _pl.Modals();
        this.classModal.addModal('registrar');
        this.classModal.addModal('staticBackdrop');
    },
}

</script>

<style scoped>.error {
    color: red;
    font-size: 10px;
}</style>
