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
              
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Sucursal:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.id"
                                        v-text="sucursal.cod + ' -> ' + sucursal.razon_social"
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                           <button type="button"  v-if="sucursalSeleccionada != '0' " class="btn btn-primary" @click="listarInicio()">
                            General ABC
                        </button>
                        <button type="button"  style="margin-right: 10px; margin-left: 10px;" v-if="sucursalSeleccionada != '0' " class="btn btn-primary" >
                            Ver tabla
                        </button>
                         <button type="button" v-if="sucursalSeleccionada != '0' " class="btn btn-primary" >
                            Ver pareto
                        </button>
                        </div>
                       
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-3" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-3" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Linea</th>
                        <th>Envase</th>
                        <th>Demanda</th>
                        <th>Precio unitario</th>
                        <th>Inversion</th>
                        <th>I. Acumulada</th>
                        <th>% I. Acumulada</th>
                        <th>Zona</th>
                        <th>%</th>      
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayInicio" :key="index">
                        <td>{{i.codigo}}</td>
                        <td>{{i.leyenda}}</td>
                        <td>{{i.nombre_linea}}</td>
                        <td>{{i.envase}}</td>
                        <td>{{i.total_cantidad}}</td>
                        <td>{{i.precio_venta}}</td>
                        <td>{{i.inversion}}</td>                         
                        <td>{{i.acumulada1}}</td>
                        <td>{{i.acumulada2}}</td>
                        <td>{{i.zona}}</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>    

            <!-----fin de tabla------->
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
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" :disabled="!sicompleto">
                            Guardar
                        </button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary">
                            Actualizar
                        </button>
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
            showModal: false,
            //offset:3,

            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',

      arrayInicio:[],
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

    methods: {


        listarInicio() {
            let me = this;   
            me.arrayInicio=[];       
           var url = "/inventario-metodo-abc/listarInicio?id_sucursal="+me.sucursalSeleccionada+"&startDate="+me.startDate+"&endDate="+me.endDate;
            axios
                .get(url)
                .then(function (response) {
                    let respuesta = response.data;  
                    let cantidad = 0;
                    let precio = 0;
                    let inversion= 0;
                    let total_1=0; 
                    let total_2=0;
                    respuesta.forEach(e => {
                         cantidad = parseFloat(e.total_cantidad);
                         precio = parseFloat(e.precio_venta);
                         inversion= cantidad * precio;
                         total_1=total_1+e.cantidad;
                         total_2=total_2+inversion;
                        me.arrayInicio.push({
                            id_ingreso:e.id_ingreso,
                            codigo:e.codigo,
                            leyenda:e.leyenda,
                            envase:e.envase,
                            nombre_linea:e.nombre_linea,
                            precio_venta: e.precio_venta,
                            total_cantidad:e.total_cantidad,                            
                            inversion:inversion,
                            acumulada1:0,
                            acumulada2:0,
                            zona:"",
                            porcentaje:0,

                        });
                    });   
                    // ORDENAR DE MAYOR A MENOR
                    me.arrayInicio=me.arrayInicio.sort((a, b) => b.inversion - a.inversion);  
                    
                    // ACUMULADA
                    let contador=0;
                    let acu_1=0;
                    let acu_12=0;
                    let a=0;
                    let b=0;
                    let c=0;
                    me.arrayInicio.forEach(e => {
                        if (contador==0) {
                            acu_1=e.inversion;  
                            acu_12=e.inversion;                         
                        }else{
                            acu_12=e.inversion+acu_1;
                        }
                        contador++;
                        e.acumulada1=acu_12;
                        e.acumulada2=(acu_12/total_2).toFixed(2);
                        if (e.acumulada2<=0.80) {
                            e.zona="A";
                            a=e.acumulada2;
                        }else{
                            if (e.acumulada2<=0.95) {
                               e.zona="B"; 
                                b=e.acumulada2-a;
                            }else{
                                e.zona="C";
                                c=e.acumulada2-b;
                            }
                        }
                    });
                    let suma_abc=a+b+c;

                    console.log(respuesta);  
                    console.log(me.arrayInicio);               
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        sucursalFiltro() {
            let me = this;
        
           var url = "/listarSucursalNormal";
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

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Ejemplo titulo";
                    me.showModal = true;
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
    this.startDate = `${year}-01-01`;
    // Asignar la fecha actual al input de fecha final
    this.endDate = `${year}-${month}-${day}`; 
        },
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.showModal = false;
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
