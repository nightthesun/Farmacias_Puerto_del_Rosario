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
                    <i class="fa fa-align-justify"></i> Detalle de ventas               
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"    @change="listarVentas(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
                                        v-text="
                                            sucursal.codigoS +
                                            ' -> ' +
                                            sucursal.codigo+
                                            ' ' +
                                            sucursal.razon_social
                                        "
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
                                    placeholder="Texto a buscar por usuario, nombre a facturar, numero de documento"
                                    v-model="buscar"
                                    @keyup.enter="listarVentas(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarVentas(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input  @change="listarVentas(1)" id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input  @change="listarVentas(1)" id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
  
    <div v-if="sucursalSeleccionada ===0" class="alert alert-secondary" role="alert">
  Debe seleccionar una sucursal!
</div>
<div v-else>
    <!---inserte tabla-->
    <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Cliente</th>
                        <th class="col-md-1">Nro Documento</th>
                        <th class="col-md-1">Nro Transaccion</th>
                        <th class="col-md-1">Tipo Comprobante</th>
                        
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Efectivo</th>
                        <th class="col-md-1">Cambio</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Anulado</th>
                        <th>Estado</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="v in arrayVentas" :key="v.id">
                        <td class="col-md-1">
            <!-- Example single danger button -->
<div class="btn-group">
  <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#" ><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura ticket</a>
    <a class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura plana</a>
    <a class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver estado</a>
    <a class="dropdown-item" href="#"><i style="color: black;" class="fa fa-trash" aria-hidden="true"></i> Anular venta</a>
    
    
  </div>
</div>                       
                        </td>
                        <td class="col-md-1" v-text="v.nom_a_facturar"></td>
                        <td class="col-md-1" v-text="v.num_documento"></td>
                        <td class="col-md-1" v-text="v.nro_comprobante_venta"></td>
                        <td class="col-md-1" v-text="v.tipo_venta_reci_fac"></td>
                        <td class="col-md-1" v-text="v.total_venta"></td>
                        <td class="col-md-1" v-text="v.efectivo_venta"></td>
                        <td class="col-md-1" v-text="v.cambio_venta"></td>
                        <td class="col-md-2" v-text="v.created_at"></td>
                        <td class="col-md-1" v-text="v.name"></td>
                        <td>
                            <div v-if="v.anulado===0">
                                <span class="badge badge-pill badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-dange">Eliminado</span>
                            </div>
                        </td>
                        <td >
                            <div v-if="v.estado_venta==='ACEPTADO'">
                                <span class="badge badge-pill badge-success">Valida</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-warning">Revisar</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>    
            <!-----fin de tabla------->
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
        </div>
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
          

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
      id_seleccionada_sucursal:0,
      cod_seleccionada_sucursal:'',
      arrayVentas:[],
        };
    },

    watch: {
    sucursalSeleccionada(valor) {
      if (valor !== 0) {
        let sucursal = this.arraySucursal.find(element => element.codigo === valor);

        if (sucursal) {
          this.id_seleccionada_sucursal = sucursal.id_sucursal;
          this.cod_seleccionada_sucursal = sucursal.codigo;
        }

        console.log(this.id_seleccionada_sucursal);
        console.log(this.cod_seleccionada_sucursal);
        console.log(this.startDate);
        console.log(this.endDate);
      }
    }
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

    methods: {
  
    listarVentas(page){
        let me=this;       
        var url = "/detalle_venta_2/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_seleccionada_sucursal+"&codigo_tienda_almacen="+me.cod_seleccionada_sucursal+"&startDate="+me.startDate+"&endDate="+me.endDate;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayVentas = respuesta.ventas_show.data;
                    console.log(me.arrayVentas);
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
            me.listarVentas(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de traspaso origen ";
            
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
