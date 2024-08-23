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
                    <i class="fa fa-align-justify"></i> Caducidad               
                    
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"  @change="listarIndex(0)">
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
             

       
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th  style="text-align: center">Opciones</th>
                        <th class="col-md-1" style="text-align: center">Codigo</th>
                        <th class="col-md-1" style="text-align: center">Linea</th>
                        <th class="col-md-4" style="text-align: center">Producto</th>
                        <th class="col-md-1" style="text-align: center">Stock actual</th>
                        <th class="col-md-1" style="text-align: center">Caducidad</th>
                        <th class="col-md-1" style="text-align: center">Siglo</th>  
                        <th class="col-md-1" style="text-align: center">Dias</th>                        
                        <th class="col-md-1" style="text-align: center">Prioridad</th>    
                        <th class="col-md-1" style="text-align: center">Estado</th>      
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="p in arrayPro" :key="p.id">
                        <td >
                            <div class="btn-group" >
  <button type="button" style="color: white;" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">  
        <a @click.prevent="cambiarPrioridad(p.cod_tda_alm, p.id_ingreso, p.id_tda_alm,0);" class="dropdown-item" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Ninguna</a>
        <a @click.prevent="cambiarPrioridad(p.cod_tda_alm, p.id_ingreso, p.id_tda_alm,1);" class="dropdown-item" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Bajo</a>
        <a @click.prevent="cambiarPrioridad(p.cod_tda_alm, p.id_ingreso, p.id_tda_alm,2);" class="dropdown-item" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Medio</a>
        <a @click.prevent="cambiarPrioridad(p.cod_tda_alm, p.id_ingreso, p.id_tda_alm,3);"  class="dropdown-item" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i> Alto</a>
   </div>
</div>
                 
                            <button  type="button" class="btn btn-danger btn-sm" style="margin-right: 5px;">
                                <i class="icon-trash"></i></button>
                        </td>
                        <td class="col-md-1" style="text-align: center" v-text="p.codigo"></td>
                        <td class="col-md-1" style="text-align: center" v-text="p.nombre"></td>
                        <td class="col-md-4"  v-text="p.leyenda"></td>
                        <td class="col-md-1" style="text-align: center" v-text="p.stock_ingreso"></td>
                        <td class="col-md-1" style="text-align: center" v-text="p.fecha_vencimiento"></td>
                        <td style="text-align: center" >
                            <span v-if="p.siglo==='1'">Un mes</span>
                            <span v-if="p.siglo==='3'">Tres meses</span>
                            <span v-if="p.siglo==='6'">Seis meses</span>
                            <span v-if="p.siglo==='12'">Doce meses</span>                            
                        </td>
                        <td class="col-md-1" style="text-align: center">                        
                            <span v-if="p.diferencia_dias<0">0</span>
                            <span v-else>{{p.diferencia_dias}}</span> 
                        </td>
                        <td class="col-md-1" style="text-align: center">
                            <span class="badge badge-pill badge-light" v-if="p.prioridad_caducidad===0">Ninguna</span>
                            <span class="badge badge-pill badge-secondary" v-if="p.prioridad_caducidad===1">Bajo</span>
                            <span class="badge badge-pill badge-warning" v-if="p.prioridad_caducidad===2">Medio</span>
                            <span class="badge badge-pill badge-danger" v-if="p.prioridad_caducidad===3">Alta</span>
                        </td>
                        <td class="col-md-2" style="text-align: center">  
                            <span class="badge badge-pill badge-danger" v-if="p.diferencia_dias<=0">Caducado</span>
                            <span class="badge badge-pill badge-warning" v-if="p.diferencia_dias>0&&p.diferencia_dias<=31">Por caducar</span>
                            <span class="badge badge-pill badge-info" v-if="p.diferencia_dias>31&&p.diferencia_dias<=60">Intervalo de cuidado</span>
                            <span class="badge badge-pill badge-success" v-if="p.diferencia_dias>61">En rago de movimiento</span>
                        </td>
                    </tr>
                </tbody>
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
            <div class="modal-dialog modal-primary modal-sm" role="document">
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
                        <span style="color:darkgray">Tipo de prioridad: {{prioridad}}</span>
              
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
            arrayPro:[],
            buscar:"",
            sucursalSeleccionada:0,
            arraySucursal:[],
            id_seleccionada_sucursal:'',
            
            prioridad:'',

            tipoAccion:1,
            startDate: '',
            endDate: '',
        };
    },

    watch: {
    sucursalSeleccionada(valor) {
      if (valor !== 0) {
        let sucursal = this.arraySucursal.find(element => element.codigo === valor);

        if (sucursal) {
          this.id_seleccionada_sucursal = sucursal.id_sucursal;
        
        }

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

        listarIndex(page){
        let me=this;       
        var url = "/caducida/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_seleccionada_sucursal+"&codigo_tienda_almacen="+me.sucursalSeleccionada;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayPro = respuesta.resultado.data;
                    console.log(me.arrayPro);
                 
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

        cambiarPrioridad(cod_tda_alm,id_ingreso,id_tda_alm,prioridad){
            let me = this;
            console.log(cod_tda_alm+" "+id_ingreso+" "+id_tda_alm+" "+prioridad);
            axios
                            .put("/caducida/prioridad", {
                              cod_tda_alm:cod_tda_alm, 
                              id_ingreso:id_ingreso, 
                              id_tda_alm:id_tda_alm, 
                              prioridad:prioridad, 
                            })
                            .then(function (response) {                        
                               me.listarIndex();
                               Swal.fire(
                        "Cambio de prioridad!",
                        "Correctamente...",
                        "success",
                    );                    
                            })
                    
                       .catch(function (error) {                
              
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                              
            });           
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    console.log(data);
                    me.tipoAccion = 1;
                    me.tituloModal = "Prioridad"; 

                    if (data.prioridad_caducidad===0) {
                        me.prioridad="Ninguna"
                    } else {
                        if (data.prioridad_caducidad===1) {
                            me.prioridad="Bajo"
                        } else {
                            if (data.prioridad_caducidad===2) {
                                me.prioridad="Medio" 
                            } else {
                                if (data.prioridad_caducidad===3) {
                                    me.prioridad="Alta"
                                }
                            }
                        }
                    }
                        
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
