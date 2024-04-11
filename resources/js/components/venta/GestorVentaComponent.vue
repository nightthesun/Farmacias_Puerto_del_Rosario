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
                <div class="card-header d-flex">
                    <span>Venta de productos </span>  
                 
                    &nbsp;
      <select class="form-control form-control-sm" v-model="selectSucursalGet" style="width: 200px;">
        <option value="0" selected disabled>Seleccionar sucursal...</option>
        <option  v-for="get in arraySucursalGet"  :key="get.id" 
        v-text="get.razon_social"></option>      
     
      </select>


     
    <select class="form-control form-control-sm" v-model="selectAlmTienda" style="width: 200px;">
        <option value="0" selected disabled>Seleccionar tienda o almacen...</option>
        <option v-for="sucursal in arrayAlmTienda" :key="sucursal.codigo" :value="sucursal.codigo"
            v-text="sucursal.codigoS + ' -> ' + sucursal.codigo+' '+sucursal.razon_social"></option>
    </select>                          
                    
                    
                </div>
        <div class="card-body" style="padding-top: 1px;">
            <div class="form-group row">

                <div class="card w-100">
                  
                    <div class="card-body">
                      <strong >Detalle de cliente</strong> 


                      <hr> <!-- Línea horizontal -->
                      
                      <div class="row">
                    
                        <div class="form-group col-sm-4" style="display: flex; align-items: center;">
                            <input type="text" class="form-control"  placeholder="Número de Cliente" v-model="buscarCliente">
                            <button class="btn btn-primary" type="button" 
                                    @click="listarUsuario()" :disabled="buscarCliente==''">
                                Buscar
                            </button>
                          
                        </div>
                        <div class="form-group col-sm-5" style="display: flex; align-items: center;">
                            <input type="text" class="form-control"  v-model="datos_cliete" disabled placeholder="Número Documento/Número de Cliente">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-info" type="button" style="color: white;">
                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                   
                       
                      
                        
                    </div> 
                    </div>
                  </div>













                <table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">Producto</th>
      <th scope="col">P/U</th>
      <th scope="col">Stock</th>
      <th scope="col">F. Ven</th>
      <th scope="col">Descuento</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Accion</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>3</td>
    </tr>
   
    
  </tbody>
</table>
                       

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
            //selecto get sucursal
            selectSucursalGet:0,
            arraySucursalGet:[],    
            id_sucu_get:'',
            razon_social_sucu_get:'',
            cod_sucu_get:'',            
            //sucusales_tienda_almacen   
            selectAlmTienda:0,
            arrayAlmTienda:[],
            //clientes
            buscarCliente:'',
            cliente_id:'',
            datos_cliete:'',
            nom_a_facturar:'',

            
            
            buscar:"",
            tipoAccion:1,
            
        };
    },

   

    computed: {
        sicompleto() {
           let me = this;
     //       if (
          
       //         me.glosa != "" &&
       //         me.cantidadS != "" &&
     //           me.ProductoLineaIngresoSeleccionado
     //       )
       //         return true;
      //      else return false;
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
       
        listarUsuario() {
            let me = this;
            var url = "/gestor_ventas/listarUsuario?num_doc="+me.buscarCliente;
            if (me.buscarCliente==99001) {
                
            }
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.cliente_id=response.data.id;
                    if (me.cliente_id==undefined) {
                        me.datos_cliete="No se encontro cliente..."
                    } else {
                        
                        me.nom_a_facturar=response.data.nom_a_facturar;
                        me.datos_cliete=response.data.nom_a_facturar+"/"+response.data.num_documento+"/"+response.data.tipo_doc_nombre;  
                    }
                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarSucursalGet() {
            let me = this;
            var url = "/listarSucursalGet";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arraySucursalGet = respuesta;
                    
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarAlmTienda() {
            let me = this;
            var url = "listarSucursal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmTienda = respuesta;
                 
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
        this.listarAlmTienda();
        this.listarSucursalGet();
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
