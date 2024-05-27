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
                    <i class="fa fa-align-justify"></i> Descuentos               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');listarTipoDescuentos();"
                       
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        
                        <div class="col-md-8">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                               
                                 
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                              
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>

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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Descuento: <span  v-if="nombre==''" class="error">(*)</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="Nombre Descuento" v-model="nombre" v-on:focus="selectAll" >
                                    <span  v-if="nombre==''" class="error">Debe Ingresar el Monto del descuento</span>
                                </div>
                            </div>
                            <div class="from-group row">
                                <div class="col-md-6">
                                    <strong>Tipo de Descuento:</strong>
                                    <select class="form-control"  v-model="selectTipoDescuento" @change="resetSelect()">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option v-for="tipodescuento in arrayTipoDescuento" :key="tipodescuento.id" v-text="tipodescuento.aplica_a " :value="tipodescuento.id"></option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Sub Categoria:</strong>
                                    <select class="form-control" v-model="selectSubCategoria" @change="cambioSelect()">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option v-for="sub1 in arraySubCategoria" :key="sub1.id" v-text="sub1.metodo " :value="sub1.metodo" v-show="selectTipoDescuento==sub1.id"></option>
                                   
                                    </select>
                                </div>                                                             
                            </div>
                            <hr>  
                            <div class="from-group row" v-if="selectTipoDescuento==1&&selectSubCategoria=='Metodo ABC'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>
                                    <select class="form-control" v-model="selectDatoEsp">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="Alta demanda">Alta demanda</option>
                                        <option value="Liquidacion">Liquidacion</option>
                                        <option value="Por rotacion">Por rotacion</option>
                                    </select>
                                </div>                                                             
                            </div>
                            <div class="from-group row" v-if="selectTipoDescuento==1&&selectSubCategoria=='Producto Individual'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="Lugar">Lugar</option>
                                        <option value="Cantidad">Cantidad</option>
                                        <option value="Poca cantidad">Poca cantidad</option>
                                        <option value="Cantidad normal">Cantidad normal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="text" class="form-control" placeholder="Descripción" v-model="selectDatoEsp">
                                 </div>                                                             
                            </div>
                            <div class="from-group row" v-if="selectTipoDescuento==1&&selectSubCategoria=='Lote'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="Solo este producto">Solo este producto</option>
                                        <option value="A todos con el ID de ingreso">A todos con el ID de ingreso</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="text" class="form-control" placeholder="Codigo de producto" v-model="selectDatoEsp">
                                    <div class="alert alert-danger" role="alert">
                                 debe ver en el codigo de producto ejemplo L001001
                                      </div>
                                 </div>                                                             
                            </div>
                            <div class="from-group row" v-if="selectTipoDescuento==1&&selectSubCategoria=='Cantidad de Compras'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="=">Igual que</option>
                                        <option value="<">Menor que</option>
                                        <option value=">">Mayor que</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="number" class="form-control" placeholder="debe ingresar un numero positivo" v-model="selectDatoEsp">
                                  
                                 </div>                                                             
                            </div>

                            <div class="from-group row" v-if="selectTipoDescuento==2&&selectSubCategoria=='Monto mayor A'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="=">Igual que</option>
                                        <option value="<">Menor que</option>
                                        <option value=">">Mayor que</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="number" class="form-control" placeholder="debe ingresar un precio" v-model="selectDatoEsp">
                                    <div class="alert alert-danger" role="alert">
                                        debe ver ingresar un monto ejemplo 100 o 2
                                    </div>
                                 </div>                                                             
                            </div>

                            <div class="from-group row" v-if="selectTipoDescuento==2&&selectSubCategoria=='Cliente en especifico'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>
                                        <option value="Cliente normal">Cliente normal</option>
                                        <option value="Cliente especifico">Cliente especifico</option>
                         
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="number" class="form-control" placeholder="debe ingresar el numero de documento del cliente" v-model="selectDatoEsp">
                                    <div class="alert alert-danger" role="alert">
                                        debe ver ingresar el numero de documento del cliente buscar en el numero de directorio
                                    </div>
                                 </div>                                                             
                            </div>

                            <div class="from-group row" v-if="selectTipoDescuento==2&&selectSubCategoria=='Evento'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>                                       
                                        <option value="Enveto especifico">Envento especifico</option>
                                        <option value="Enveto varios">Envento vario</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="text" class="form-control" placeholder="Descripcion" v-model="selectDatoEsp">
                                    <div class="alert alert-danger" role="alert">
                                        Describa en evento.
                                    </div>
                                 </div>                                                             
                            </div>

                            <div class="from-group row" v-if="selectTipoDescuento==3&&selectSubCategoria=='Semana'">
                                <div class="col-md-6">
                                    <strong>Accion:</strong>
                                    <select class="form-control" v-model="selectAccion">
                                        <option disabled value="0">Seleccionar...</option>                                       
                                        <option value="Solo una vez">Solo una vez</option>
                                        <option value="Repetir">Repetir</option>
                                       
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <strong>Datos Especificos:</strong>                                    
                                    <input type="number" class="form-control" placeholder="Cantidad de dias" v-model="selectDatoEsp">
                                    <div class="alert alert-danger" role="alert">
                                        Debe ingresar el numero de dias, ejemplo 2 seran dos dias de rango.
                                    </div>
                                 </div>                                                             
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
            selectAlmTienda:0,
            arrayAlmTienda:[],
            buscar:"",
            tipoAccion:1,


            selectTipoDescuento:0,
            arrayTipoDescuento:[],
            arraySubCategoria:[],
            nombre:'',
            selectSubCategoria:0,

            selectAccion:0,
            selectDatoEsp:0,

            
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

        resetSelect(){
            if (this.selectTipoDescuento!=0) {
                this.selectSubCategoria=0;
                this.selectDatoEsp=0;
                this.selectAccion=0;
            } 
            
        },
        cambioSelect(){
            if (this.selectSubCategoria=='Metodo ABC') {
                this.selectDatoEsp=0;
                this.selectAccion=0;
            } 
            if (this.selectSubCategoria=='Producto Individual'||this.selectSubCategoria=='Lote'||this.selectSubCategoria=='Monto mayor A'
                ||this.selectSubCategoria=='Cliente en especifico'||this.selectSubCategoria=='Evento') {
                this.selectDatoEsp="";
                this.selectAccion=0;
            } 
            if (this.selectSubCategoria=='Cantidad de Compras'||this.selectSubCategoria=='Semana') {
                this.selectDatoEsp=0;
                this.selectAccion=0;
            }
            
          
        },
     

        listarTipoDescuentos() {
            let me = this;
            var url = "/descuento2/listarTipoDescuentos";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta1 = response.data.subcategoria;
                    var respuesta2 = response.data.descuentos;                  
                    me.arraySubCategoria = respuesta1;
                    me.arrayTipoDescuento =respuesta2;                  
        
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarAlmTienda() {
            let me = this;
            var url = "/traslado/listarSucursal";
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
                    me.tituloModal = "Registro de descuentos";
            
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
