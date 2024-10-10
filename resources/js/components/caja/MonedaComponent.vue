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
                        <th class="col-md-1">Valor</th>
                        <th class="col-md-1">Valor entero</th>
                        <th class="col-md-5">Valor literal</th>
                        <th class="col-md-1">Unidad</th>
                        <th class="col-md-1">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1">
                            <div class="button-container">
                                <div  class="d-flex justify-content-start">
                                    <div  v-if="puedeEditar==1">
                                        <button type="button" class="btn btn-warning " @click="abrirModal('actualizar',i);" style="margin-right: 5px;">
                                            <i class="icon-pencil"></i></button>                                             
                                    </div>
                                    <div v-else>
                                        <button type="button" class="btn btn-light " style="margin-right: 5px;">
                                            <i class="icon-pencil"></i></button>  
                                    </div>   
                                      
                                    <div v-if="puedeActivar==1">
                                        <button v-if="i.activo === 1" @click="eliminar(i.id);" type="button" class="btn btn-danger" style="margin-right: 5px;">
                                        <i class="icon-trash"></i></button>
                                    <button v-else type="button" @click="activar(i.id);"  class="btn btn-info" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                    </div>
                                    <div v-else>
                                        <button v-if="i.activo === 1"  type="button" class="btn btn-light" style="margin-right: 5px;">
                                        <i class="icon-trash"></i></button>
                                    <button v-else type="button"  class="btn btn-light" style="margin-right: 5px;">
                                        <i class="icon-check"></i>
                                    </button>
                                    </div>
                                  
                                </div>
                            </div>  
                        </td>  
                        <td class="col-md-2" v-text="i.tipo_corte"></td>
                        <td class="col-md-1" style="text-align: right;" v-text="i.valor"></td>
                        <td class="col-md-1" style="text-align: right;" v-text="i.unidad_entera"></td>
                        <td class="col-md-5" v-text="i.texto_unidad_entera"></td>
                        <td class="col-md-1" v-text="i.unidad"></td>
                        <td class="col-md-1">
                            <div v-if="i.activo == 1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-warning">Desactivado</span>
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
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" role="dialog" arial-labelledby="myModalLabel" id="registrar" aria-hidden="true" data-backdrop="static" data-key="false" >
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
                                             
                   
                                   
                                    <div class="row"> 
                                            <div class="form-group col-sm-3">
                                                <strong>Moneda: <span  v-if="tipoMoneda==''" class="error">(*)</span></strong>
                                                 <select name="" id="" v-model="tipoMoneda" class="form-control">
                                                    <option value="0" disabled>Seleccionar...</option>
                                                        <option value="Moneda">Moneda</option>
                                                        <option value="Billete">Billete</option>
                                                 </select>
                                             
                                                <span  v-if="tipoMoneda==''" class="error">Debe la Moneda</span>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <strong>Valor: <span  v-if="valor==''" class="error">(*)</span></strong>
                                                <input type="number" class="form-control" placeholder="puede ser 0.10 o enteros 100.00"  v-model="valor" >
                                                <span  v-if="valor==''" class="error">Debe la  esccribir la unidad</span>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <strong>Valor entero: <span  v-if="valor_entero==''" class="error">(*)</span></strong>
                                                <input type="text" class="form-control" placeholder="Solo valores enteros" v-model.number="valor_entero" @input="validateInteger">
                                                <span  v-if="valor_entero==''" class="error">Debe ingresar la unidad entera</span>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <strong>Simbolo: <span  v-if="unidad==''" class="error">(*)</span></strong>
                                                
                                                
                                                <select name="" id="" v-model="unidad" class="form-control">
                                                    <option value="0" disabled>Seleccionar...</option>
                                                        <option value="ctvo">ctvo</option>
                                                        <option :value="simbolo" >{{simbolo}}</option>
                                                 </select>
                                             
                                              <span  v-if="unidad==''" class="error">Sin accion</span>
                                            </div>
                                        </div> 
                                       
        
                                                         
                         
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="registrar()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="actualizar()" :disabled="!sicompleto">Actualizar</button>
                            </div>
                            <div v-else>
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-light">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-light">Actualizar</button>
                            </div>
                        </div>
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
            isSubmitting: false, // Controla el estado del botón de envío
            tituloModal:'',
            arrayPais:[],
            selectPais:0,
            arrayIndex:[],
            simbolo:'',
            valor_entero:'',
      
            valor:'',
            unidad:0,
            tipoMoneda:0,
            id_:"",
            buscar:'',
            tipoAccion:1,
            nombre_pais:"",
             //---permisos_R_W_S
             puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
     
        };
    },

    

    computed: {
        sicompleto() {
           let me = this;
           if (   me.valor != "" &&
                me.unidad != 0 &&
               me.tipoMoneda !=0 && me.valor_entero!=""
          )
             return true;
           else return false;
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
    watch: {
        selectPais(valor) {
      if (valor !== 0) {
        let p = this.arrayPais.find(element => element.id === valor);

        if (p) {
          this.simbolo = p.simbolo;
          this.nombre_pais = p.pais;
        }

      }
    }
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
            console.log(error);
        });
},
//---------------------------------------------------
        validateInteger(event) {
      // Elimina cualquier caracter que no sea un número o el signo negativo
      let value = event.target.value.replace(/[^0-9-]/g, '');

      // Si empieza con cero, eliminarlo, a menos que el número sea 0
      if (value !== '' && value[0] === '0' && value.length > 1) {
        value = value.substring(1);
      }

      // Actualiza el valor si es un entero válido
      this.valor_entero = value;
    },

    actualizar() {
            let me = this;
            if (
                me.nombre === "" ||
                me.valor === "" ||
                me.unidad === "" ||
                me.valor_entero === ""          
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                let cadena="actualizacion de datos "+me.nombre_pais;
            axios.post("/moneda/actualizar", {
                    id:me.id_,
                    nombre: me.tipoMoneda,
                    valor: me.valor,
                    unidad: me.unidad,
                    valor_entero:me.valor_entero,
                    id_nacionalidad_pais:me.selectPais,
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:cadena
            
                })
                .then(function (response) {
                      if (response.data===null || response.data==="" || (response.data).length<=0) {
                            Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        ); 
                        } else {
                            Swal.fire(
                            " "+response.data,
                            "Haga click en Ok",
                            "error",
                        );   
                        }
                        me.cerrarModal("registrar");
                        me.listarIndex();
                })
                //.catch(function (error) {
                //    error401(error);
                //});
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
            me.cerrarModal("registrar");
            }           
        },

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
                me.unidad === "" ||
                me.valor_entero === ""          
            ) {
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            } else {
                  // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
                let cadena="creacion de moneda "+me.nombre_pais;
                axios.post("/moneda/store", {
                    nombre: me.tipoMoneda,
                    valor: me.valor,
                    unidad: me.unidad,
                    valor_entero:me.valor_entero,
                    id_nacionalidad_pais:me.selectPais,
                    id_modulo: me.idmodulo,
                    id_sub_modulo:me.codventana, 
                    des:cadena
                    })
                    .then(function (response) {
                        if (response.data===null || response.data==="" || (response.data).length<=0) {
                            Swal.fire(
                            "Registrado exitosamente",
                            "Haga click en Ok",
                            "success",
                        ); 
                        } else {
                            Swal.fire(
                            " "+response.data,
                            "Haga click en Ok",
                            "error",
                        );   
                        }
                        me.cerrarModal("registrar");
                        me.listarIndex();   
                    })                    
                  
                  .catch(function (error) {           
                    error401(error);
          console.log(error);
        })
        .finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
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
                    me.isSubmitting=false;
                    me.tituloModal = "Registro de tipo de moneda";
                    me.tipoMoneda=0;
                    me.unidad=0;
                    me.valor="";
                    me.valor_entero="";
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.isSubmitting=false;
                    me.tituloModal = "Registro de tipo de moneda";
                    me.id_=data.id;          
                    me.tipoMoneda=data.tipo_corte === null ? 0 : data.tipo_corte;
                    me.valor=data.valor; 
                    me.valor_entero=data.unidad_entera;
                    me.unidad=data.unidad === null ? 0 : data.unidad
                    me.classModal.openModal("registrar");
                    break;
                }
            
            }
        },

      
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.classModal.closeModal(accion);
                me.isSubmitting=false;   
                    me.tituloModal = " ";
                    me.tipoMoneda=0;
                    me.valor="";
                    me.unidad=0;
                    me.nombre_pais="";
                    me.valor_entero="";
              
            }
        },

        eliminar(id) {
            let me = this;           
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Desactivar?",
                    text: "Es una eliminacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Desactivar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let cadena="desactivacion de moneda "+me.nombre_pais;
                        axios.post("/moneda/desactivar", {
                                id: id,
                                id_modulo: me.idmodulo,
                                id_sub_modulo:me.codventana, 
                                des:cadena                               
                            })
                            .then(function (response) {
                                me.listarIndex();
                                swalWithBootstrapButtons.fire(
                                    "Desactivado!",
                                    "El registro a sido desactivado Correctamente",
                                    "success",
                                );                              
                            })                          
                           .catch(function (error) {           
                
                if (error.response) {
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
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                    }
                });
        },

        activar(id) {
            let me = this;
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });
            swalWithBootstrapButtons
                .fire({
                    title: "Esta Seguro de Activar?",
                    text: "Es una Activacion logica",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, Activar",
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let cadena="activo de moneda "+me.nombre_pais;
                        axios.post("/moneda/activar", {
                                id: id,
                                id_modulo: me.idmodulo,
                                id_sub_modulo:me.codventana, 
                                des:cadena    
                            })
                            .then(function (response) {
                                me.listarIndex();
                                swalWithBootstrapButtons.fire(
                                    "Activado!",
                                    "El registro a sido Activado Correctamente",
                                    "success",
                                );
                            })
                           .catch(function (error) {           
                
                if (error.response) {
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
                });
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
    //-------permiso E_W_S-----
    this.listarPerimsoxyz();
             // this.listarAlmacenes_tiendas_con_permisos();
            //-----------------------
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
