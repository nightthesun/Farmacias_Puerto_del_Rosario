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
                    <i class="fa fa-align-justify"></i> Moneda               
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');"
                        :disabled="selectPais == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="selectPais == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un pais.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Pais:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="selectPais"  @change="listarIndex(0)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="p in arrayPais" :key="p.id"  :value="p.id" v-text="p.pais"></option>
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
                                    @keyup.enter="listarIndex(1)" 
                                    :hidden="selectPais == 0"
                                    :disabled="selectPais == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"
                                    :hidden="selectPais == 0"
                                    :disabled="selectPais == 0"
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
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-2">Tipo de moneda</th>
                        <th class="col-md-2">Valor</th>
                        <th class="col-md-2">Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1">
                            <button type="button" class="btn btn-warning " style="margin-right: 5px;">
                <i class="icon-pencil"></i>
                </button>
                        </td>
                        <td class="col-md-2" v-text="i.tipo_corte"></td>
                        <td class="col-md-2" v-text="i.valor"></td>
                        <td class="col-md-2" v-text="i.unidad"></td>
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
                                   
                                    <div class="row">
                                            <div class="form-group col-sm-4">
                                                <strong>Nombre: <span  v-if="nombre==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" v-model="nombre"  placeholder="tipo_corte, monedao billete"  >
                                                <span  v-if="nombre==''" class="error">Debe la unidad</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Valor: <span  v-if="valor==''" class="error">(*)</span></strong>
                                                <input type="number" class="form-control" placeholder="puede ser 0.10 o enteros 100.00"  v-model="valor" >
                                                <span  v-if="valor==''" class="error">Debe la unidad</span>
                                            </div>
                                            <div class="form-group col-sm-4">
                                                <strong>Unidad: <span  v-if="unidad==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" placeholder="puede ser ctvo o Bs,Peso..."  v-model="unidad" >
                                              <span  v-if="unidad==''" class="error">Debe la unidad</span>
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
                            @click="registrar()"  
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
    props: ['codventana','idmodulo'],
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
          
            offset: 3,
            tituloModal:'',
            arrayPais:[],
            selectPais:0,
            arrayIndex:[],
            simbolo:'',

            nombre:'',
            valor:'',
            unidad:'',

            buscar:'',
            tipoAccion:1,
     
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
    watch: {
        selectPais(valor) {
      if (valor !== 0) {
        let p = this.arrayPais.find(element => element.id === valor);

        if (p) {
          this.simbolo = p.simbolo;
        
        }

      }
    }
  },
    methods: {

        listarIndex(page){
        let me=this;       
        var url = "/moneda/index?page="+page+"&buscar=" +me.buscar+"&id="+me.selectPais;
        axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.monedas.data;                
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
    },
        
        listarPais(){
            let me = this;
            var url = "/moneda/listarNacionalidad"; 
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPais = respuesta;
                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        registrar() {
            let me = this;
            if (
                me.nombre === "" ||
                me.valor === "" ||
                me.unidad === ""           
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                axios.post("/moneda/store", {
                    nombre: me.nombre,
                    valor: me.valor,
                    unidad: me.unidad,

                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:cadena
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                        Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                        me.listarIndex();
                     
                    })                    
                  
                  .catch(function (error) {           
                
                if (error.response.status === 500) {
                    me.errorMsg = error.response.data.error; // Asigna el mensaje de error a la variable errorMsg
                Swal.fire(
                    "Error",
                    "500 (Internal Server Error)"+me.errorMsg, // Muestra el mensaje de error en el alert
                    "error"
                );
                }else{
                    Swal.fire(
                    "Error",
                    ""+error, // Muestra el mensaje de error en el alert
                    "error"
                );  
                }

               
            });
            }
        },
    
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.arrayIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Registro de tipo de moneda";

            
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.tituloModal = "Registro de tipo de moneda";
          
            
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
                    me.nombre="";
                    me.valor="";
                    me.unidad="";
                  
              
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
        this.listarPais();
    
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
