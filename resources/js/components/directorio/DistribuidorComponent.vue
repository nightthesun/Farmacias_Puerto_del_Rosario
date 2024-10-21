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
                    <i class="fa fa-align-justify"></i>Distribuidor                
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar',listarCliente(0,0))"
                        :disabled="selectTipo == 0"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-1" style="text-align: center">
                     <label for="">Tipo:</label>
                </div>
                <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="selectTipo" @change="changeRango();listarDistribuidor()">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="t in arrayTipo" :key="t.id" :value="t.id" v-text="t.tipo"></option>
                                </select>
                          </div>                       
                        </div>
                       
                        <div class="col-md-6">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Buscar nombre a facturar / numero de documento "
                                    v-model="buscar"
                                    @keyup.enter="listarDistribuidor(1)"
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarDistribuidor(1)"
                                    :hidden="selectTipo == 0"
                                    :disabled="selectTipo == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
          
            </div>   
            <div class="form-group row"  :hidden="selectTipo == 0" :disabled="selectTipo == 0">
                <div class="col-md-1">
                     <label for=""></label>
                </div>
                <div class="col-md-5">                    
                        <label for=""></label>                                       
                </div>
        <div class="col-md-3">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" :disabled="selectTipo===0" @change="listarDistribuidor(0)">
        </div>
        <div class="col-md-3">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" :disabled="selectTipo===0" @change="listarDistribuidor(0)">
        </div>
        
            </div>   
  <br>
 
            <!---inserte tabla-->
            <div v-if="selectTipo===0" class="alert alert-warning" role="alert">
  Debe seleccionar una opcion!
</div>
            <table v-else class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>                       
                        <th class="col-md-2">Nombre a facturar</th>
                        <th class="col-md-1">Nro documento </th>
                        <th class="col-md-1">Contacto</th>
                        <th class="col-md-1">Correo</th>
                        <th class="col-md-1">Telefono</th>
                        <th class="col-md-2">Linea</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Estado</th>         
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in arrayIndex" :key="i.id">
                        <td class="col-md-1">
                            <div  class="d-flex justify-content-start">
                                <div  v-if="puedeEditar==1">
                                    <button type="button" @click="listarCliente(1,i)" class="btn btn-warning btn-sm" style="margin-right: 5px;"><i class="icon-pencil"></i></button>                           
                                </div>
                                <div v-else>
                                    <button type="button" class="btn btn-light btn-sm" style="margin-right: 5px;"><i class="icon-pencil"></i></button>                           
                                </div>  
                                <div v-if="puedeActivar==1">
                                <button v-if="i.estado==1" type="button" @click="eliminar(i.id)" class="btn btn-danger btn-sm" style="margin-right: 5px;"><i class="icon-trash"></i></button>
                                <button v-else type="button" @click="activar (i.id)" class="btn btn-info btn-sm" style="margin-right: 5px;"><i class="icon-check"></i></button>
                            </div>
                            <div v-else>
                                <button v-if="i.estado==1" type="button" class="btn btn-light btn-sm" style="margin-right: 5px;"><i class="icon-trash"></i></button>
                                <button v-else type="button"  class="btn btn-light btn-sm" style="margin-right: 5px;"><i class="icon-check"></i></button>
                            </div>   
                            </div>
                        </td>
                        <td class="col-md-1">{{ i.nom_a_facturar }}</td> 
                        <td class="col-md-2">{{ i.num_documento }}</td> 
                        <td class="col-md-1">{{ i.contacto }}</td> 
                        <td class="col-md-1">{{ i.correo }}</td> 
                        <td class="col-md-1">{{ i.telefono }}</td> 
                        <td class="col-md-2">{{ i.nom_linea_array }}</td> 
                        <td class="col-md-2">{{ i.fecha_mas_reciente }}</td> 
                        <td class="col-md-1">{{ i.name }}</td> 
                        <td>
                            <span v-if="i.estado===1" class="badge badge-pill badge-success">Activo</span>
                            <span v-else class="badge badge-pill badge-danger">Desactivado</span>                            
                        </td>
                    </tr>
                </tbody>
               
            </table>    
 <!-----fin de tabla------->
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
                        <button type="button" class="close" aria-label="Close" @click="cerrarModal('registrar')">
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
                                <div class="form-group row"  >
                                <strong  class="col-md-3 form-control-label" for="text-input">Cliente: <span v-if="selected == null" class="error" >(*)</span></strong>
                                <div class="col-md-7 input-group mb-3">
                                    
                    <VueMultiselect
                        v-model="selected"
                        :options="arrayCliente"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nom_a_facturar" 
                        :custom-label="nameWithLang"                     
                        track-by="id"
                        class="w-350"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado"                      
                        @input="changeLinea()"
                       >
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect>           

                                    <!-- <option value="0" disabled>Seleccionar...</option>
                                    <option v-if="producto.almacenprimario == 1" :key="producto.idproduc" :value="producto.idproduc" v-text="producto.cod"></option> -->
                                </div>
                           
                                <span v-if="selected==null" class="error">Debe Ingresar el Nombre del producto</span>
                            </div>                          
                            </div>
                            <div v-if="selected===null" class="alert alert-info" role="alert">
                                    Debe seleccionar.
                                            </div>
                                            <div v-else class="modal-body">                      
                                                <table class="table table-bordered table-striped table-sm table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-1" style="font-size: 11px; text-align: center">Nro</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nombre</th>
                                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Nom a Facturar</th>                                                         
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Pais</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Ciudad</th>
                                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Num documento</th>
                                                        
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                       
                                                            <tr> 
                                                            <td  class="col-md-1" style="font-size: 11px; text-align: center">{{selected.id}}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.name_all }}</td>
                                                            <td  class="col-md-3" style="font-size: 11px; text-align: center">{{ selected.nom_a_facturar }}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.pais }}</td>                                                        
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.ciudad }}</td>
                                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{ selected.num_documento }}</td>
                                                        </tr>                                                        
                                                    </tbody>
                                                </table>                                                  
                                                <div class="container">                                
                                <div class="form-group row"  >
                                    <strong  class="col-md-3 form-control-label" for="text-input">Conctaco: <span v-if="contacto == ''" class="error" >(*)</span></strong>
                                        <div class="col-md-7 input-group mb-3">
                                            <input type="text" class="form-control rounded" placeholder="Datos adicionales" v-model="contacto">
                                        </div>
                                </div>
                             </div>  
                             <!-------------------------------------------------------->     
                             <div class="container">                                
                                <div class="form-group row"  >
                                  
                                    <strong  class="col-md-3 form-control-label" for="text-input">Linea: <span v-if="options == null" class="error" >(*)</span></strong>
                                        <div class="col-md-7 input-group mb-3">
                                            <multiselect v-model="value"
             :options="options" 
             :multiple="true" 
             :close-on-select="false" 
             :clear-on-select="false"
            :preserve-search="true" 
            placeholder="Seleccione una opción" 
            label="nombre" 
            track-by="id" 
            :preselect-first="false"
            selectLabel="Añadir a seleccion"
            deselectLabel="Quitar seleccion"
            selectedLabel="Seleccionado"
        
            >
          
                <template #selection="{ values, search, isOpen }">
                    <span class="multiselect__single" v-if="values.length>1" v-show="!isOpen">{{ values.length }} opciones seleccionadas</span>
                    <span class="multiselect__single" v-else v-show="!isOpen">{{ values.length }} opcion seleccionada</span>
                </template>
            </multiselect>
                                        </div>
                                        <div class="col-md-2">
                                            <button v-if="value.length>0" type="button" class="btn btn-primary" @click="clearAll()"><i class="fa fa-exclamation" aria-hidden="true"></i>Limpiar</button>
                                            <button v-else type="button" class="btn btn-light" ><i class="fa fa-exclamation" aria-hidden="true"></i>Limpiar</button>
                                        </div>
                                </div>
                            </div>               
                            <div v-if="value.length===0" class="alert alert-info" role="alert">
                                    Debe seleccionar una linea.
                            </div>
                                <table v-else class="table table-bordered table-striped table-sm table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="col-md-2" style="font-size: 11px; text-align: center">Nro</th>
                                            <th class="col-md-3" style="font-size: 11px; text-align: center">Codigo</th>
                                            <th class="col-md-7" style="font-size: 11px; text-align: center">Nombre</th>                                                         
                                        </tr>
                                    </thead>
                                    <tbody>                                       
                                        <tr v-for="o in value" :key="o.id">
                                            <td  class="col-md-2" style="font-size: 11px; text-align: center">{{o.id}}</td>
                                            <td  class="col-md-3" style="font-size: 11px; text-align: center">{{ o.codigo }}</td>
                                            <td  class="col-md-7" style="font-size: 11px; text-align: center">{{ o.nombre }}</td>                                                           
                                        </tr>                                                        
                                    </tbody>
                                </table>  
                             <div>
       <!--<pre class="language-json"><code>{{ value }}</code></pre>--> 

  </div>
  <!---------------------------------------------------------------->   
                              </div>   
                                           
                                 
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">Cerrar</button>
                        <div  class="d-flex justify-content-start">
                            <div  v-if="isSubmitting==false">
                                <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" @click="crear()" :disabled="!sicompleto">Guardar</button>
                                <button type="button" v-if="tipoAccion == 2" class="btn btn-primary" @click="editar()" :disabled="!sicompleto">Actualizar</button>
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
         
    </main>
</template>

<script>
import Swal from "sweetalert2";
import { error401 } from "../../errores";
import VueMultiselect from 'vue-multiselect';
import Multiselect from 'vue-multiselect'

//Vue.use(VeeValidate);
export default {
     //---permisos_R_W_S
     props: ['codventana'],
        //-------------------
    components: { VueMultiselect ,Multiselect},
   
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
            offset:3,
            isSubmitting: false, // Controla el estado del botón de envío
            selected:null,
            arrayTipo:[{id:1,tipo:'Persona'},{id:2,tipo:'Empresa'}],

            arrayLimite:[{id:1,limite:10},
                {id:2,limite:20},
                {id:3,limite:50},
                {id:4,limite:100},
                {id:5,limite:200},
                {id:6,limite:0},
                ],
            limite_X:10,

            selectTipo:0,  
            arrayCliente:[],

            tituloModal:'',
            contacto:'',
           
            buscar:'',
            tipoAccion:1,

            arrayIndex:[],
            id_distribuidor:'',
              //---permisos_R_W_S
              puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------  


            arraylinea:[],    
         //----selector multiple   
        value: [],
        options: [],       
      //------------------------
 //limitado                    
 startDate: '',
            endDate: '',

        };
    },

    

    computed: {
       sicompleto() {
           let me = this;
           if ( me.contacto != "" && me.selected != null && me.value.length != 0)
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
//--------------------------------------------------------------  
    
         changeRango(){
            this.limite_X=10;
         },   

         clearAll () {
      this.value = [];
    },

        listarDistribuidor(page){
              //   /transaccion/listar_   
              let me=this;             
                var url='/distribuidor/listarDistribuidor?page='+page+'&buscar='+me.buscar+'&tipo='+me.selectTipo+"&ini="+me.startDate+"&fini="+me.endDate;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayIndex = respuesta.distribuidor.data;           

                })
                .catch(function(error){
                    error401(error);
                    console.log(error);
                });
        },

        listarLinea() {
        let me = this;           
        var url = "/distribuidor/getLinea";
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.options = respuesta;
                               
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        listarCliente(a,data) {
        let me = this;           
        var url = "/proveedor/get?tipo_per_emp="+me.selectTipo;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayCliente = respuesta;
                    if (a===1) {
                        me.abrirModal('actualizar',data)  
                    }                  
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },
     
        editar(){
            let me = this;
            const ids = me.value.map(item => item.id).join(',');
            const nom = me.value.map(item => item.nombre).join(',');
            axios.post("/distribuidor/editar", {
                contacto: me.contacto,              
                id_cliente: me.selected.id,
                selectTipo:me.selectTipo,
                id_distribuidor:me.id_distribuidor,
          
                linea_nom:nom,
                ids_linea:ids
                                                              
                    })       
                    .then(function (response) {
                        console.log(response.data);
                        me.cerrarModal("registrar");
                        me.listarDistribuidor();                  
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    })                
                  .catch(function (error) { 
                   console.log(error);
                    // console.log(error.response.data);          
                  //  this.errorMessage = error.response.data; // Aquí guardamos el error
                  //  Swal.fire("Error comunicarse con el administrador",""+errorMessage,"error");               
            });
        },

        crear(){
            let me = this;
              // Si ya está enviando, no permitas otra solicitud
      if (me.isSubmitting) return;

me.isSubmitting = true; // Deshabilita el botón
            const ids = me.value.map(item => item.id).join(',');
            const nom = me.value.map(item => item.nombre).join(',');
                axios.post("/distribuidor/registrar", {
                contacto: me.contacto,              
                id_cliente: me.selected.id,
                selectTipo:me.selectTipo,
                linea_nom:nom,
                ids_linea:ids
                               
                    })       
                    .then(function (response) {
       
                        me.cerrarModal("registrar");
                           me.listarDistribuidor();                  
                        Swal.fire(
                            "Se registro exitosamente",
                            "Haga click en Ok",
                            "success",
                        );
                    })                
                  .catch(function (error) { 
                    error401(error);
                    console.log(error);
        }).finally(() => {
          me.isSubmitting = false; // Habilita el botón nuevamente al finalizar
        });
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },

        fecha_inicial() {
    // Obtener la fecha actual
    const today = new Date();    
    // Obtener la fecha actual menos 5 días
    const startDate = new Date();
    startDate.setDate(today.getDate() - 30);
    // Formatear el año, mes y día para la fecha de inicio
    const startYear = startDate.getFullYear();
    const startMonth = String(startDate.getMonth() + 1).padStart(2, '0'); // Meses en JavaScript son de 0 a 11
    const startDay = String(startDate.getDate()).padStart(2, '0');
    // Formatear el año, mes y día para la fecha final (hoy)
    const endYear = today.getFullYear();
    const endMonth = String(today.getMonth() + 1).padStart(2, '0');
    const endDay = String(today.getDate()).padStart(2, '0');
    // Asignar las fechas a los campos correspondientes
    this.startDate = `${startYear}-${startMonth}-${startDay}`;  // Fecha de inicio (5 días antes)
    this.endDate = `${endYear}-${endMonth}-${endDay}`;  // Fecha final (hoy)
},


        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
            me.listarDistribuidor(page); 
        },

        abrirModal(accion, data = []) {
            let me = this;        
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.isSubmitting=false;
                    if (me.selectTipo===1) {
                        me.tituloModal = "Registrar distribuidor tipo persona"; 
                    } else {
                        if (me.selectTipo===2) {
                        me.tituloModal = "Registrar distribuidor tipo empresa";    
                        } else {
                        me.tituloModal = "Error...";    
                        }                       
                    }                   
                    me.selected =null;
                    me.contacto="Sin contacto...";
                    me.value=[];
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                    me.isSubmitting=false;
                    if (me.selectTipo===1) {
                        me.tituloModal = "Registrar proveedor tipo persona"; 
                    } else {
                        if (me.selectTipo===2) {
                        me.tituloModal = "Registrar proveedor tipo empresa";    
                        } else {
                        me.tituloModal = "Error...";    
                        }                       
                    }    
              
            let cliente = me.arrayCliente.find(c => c.id === data.id_cliente);
            if (cliente) {
                me.selected = cliente;
            } else {
                me.selected = null;
            }
       
            let array = (data.id_linea_array).split(',').map(Number);
            array.forEach(function(item, index) {
            let linea = me.options.find(c => c.id === item);
          
             //  if (linea) {
             me.value.push(linea);
             //   me.value = cliente;
              //  } 
            });      
                    me.contacto=data.contacto;
                    me.id_distribuidor=data.id;
                    me.classModal.openModal("registrar");

                    break;
                }
            
            }
        },

       
        cerrarModal(accion) {
            let me = this;
            if (accion == "registrar") {
                me.isSubmitting=false;
                me.classModal.closeModal(accion);
                me.selected =null;
                me.contacto="";
                me.id_transaccion="";
                me.tituloModal = "";
                me.value=[];         
            }
        },

        eliminar(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: '¿Esta Seguro de Desactivar?',
                text: "Es una eliminacion logica",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, Desactivar',
                cancelButtonText: 'No, Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                     axios.put('/distribuidor/desactivar',{
                        'id': id
                    }).then(function (response) {
                
                        me.listarDistribuidor();   
                        swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro a sido desactivado Correctamente',
                            'success'
                        )                     
                        
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
            activar(id){
                let me=this;
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
                     axios.put('/distribuidor/activar',{
                        'id': id
                    }).then(function (response) {
                     
                        me.listarDistribuidor();   
                        swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro a sido Activado Correctamente',
                            'success'
                        )
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

        nameWithLang ({name_all,nom_a_facturar, num_documento,}) {
            
            return `${name_all} - ${nom_a_facturar} - ${num_documento} `
          },

        toggle () {
      this.$refs.multiselect.$el.focus()

      setTimeout(() => {
        this.$refs.multiselect.$refs.search.blur()
      }, 1000)
    },
    open () {
      this.$refs.multiselect.activate()
    },
    close () {
      this.$refs.multiselect.deactivate()
    },

        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
        this.classModal = new _pl.Modals();
   //-------permiso E_W_S-----
   this.listarPerimsoxyz();        
            //-----------------------
            this.listarLinea();
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>