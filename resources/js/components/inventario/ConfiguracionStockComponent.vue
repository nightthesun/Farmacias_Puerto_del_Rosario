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
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">             
                <li class="nav-item">
                    <a class="nav-link" @click="listarStockMedio();listarTabla();listarSucursalLista();" id="pills-bitacora-tab" data-toggle="pill" href="#pills-bitacora" role="tab" aria-controls="pills-bitacora" aria-selected="false">Bitacora stock</a>
                </li>    
                <li class="nav-item" v-show="puedeHacerOpciones_especiales==1">
                    <a class="nav-link" @click="listarSincroGesStock();listarSucursalLista();" id="pills-sincro-tab" data-toggle="pill" href="#pills-sincro" role="tab" aria-controls="pills-sincro" aria-selected="false">Sincronizar hora</a>
                </li>                     
            </ul>
           
        </div>
        <div class="card-body">           
             <div class="tab-content" id="pills-tabContent">                  
<!--------------------------------------------------------------------------------------------------------------------------------------------->
        
                    <div class="tab-pane fade" id="pills-bitacora" role="tabpanel" aria-labelledby="pills-bitacora-tab">
                        <div class="card">
                            <div class="card-header">
                                Modo manual
                            </div>
                           
    <div class="card-body">
        <div class="alert alert-danger" v-show="tipoStockMedio==0" role="alert">
            <span>Modo activado <strong>por defecto</strong> stock medio 1</span>
        </div>
        <div class="alert alert-success" v-show="tipoStockMedio==1" role="alert">
            <span>Modo activado <strong>stock medio 1</strong> revisar el configuración de administrador</span>
        </div>
        <div class="alert alert-success" v-show="tipoStockMedio==2" role="alert">
            <span>Modo activado <strong>stock medio 2</strong> revisar el configuración de administrador</span>
        </div>
            <div id="accordion">
  <div class="card">
<!-------------------usuario 1:1 sucursal---------------------------->
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Sucursal X Usuario
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
            <div class="row" v-if="sucursalNoActivada==0">
            <div class="col-sm-4">
                <span>Realizar operación: </span>
                <button  type="button" class="btn btn-primary" @click="actualziarTablaBitacora(0)"><i class="fa fa-code-fork" aria-hidden="true"></i> Bistacora stock</button>                               
            </div>
            <div class="col-sm-8">
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(a, index) in arrayTabla" :key="index">
                                <td>{{a.tipo}}</td>
                                <td>{{a.name}}</td>
                                <td>{{a.fecha}}</td>
                                <td>{{a.hora}}</td>
                                <td>{{a.accion}}</td>
                            </tr>
                        </tbody>
                    </table>        
            </div>
            </div>
            <div class="alert alert-danger" role="alert" v-else>
                <h3>LA SUCURSAL NO ESTA ACTIVADA CONTÁCTESE CON EL ADMINISTRADOR</h3>
            </div>
        </div>
    </div>
  </div>
  <!-------------------sucursales ---------------------------->
  <div class="card" v-show="puedeHacerOpciones_especiales==1">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Todas las sucursales
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                            <tr>
                                <th class="col-md-3">Opciones</th>
                                <th class="col-md-9">Nombre</th>
                            </tr>
                        </thead>    
                            <tbody>
                                <tr v-for="(i, index) in arraySucursal" :key="index">
                                    <td class="col-md-3">
                                         <button @click="actualziarTablaBitacora(i.id)" type="button" class="btn btn-primary"><i class="fa fa-code-fork" aria-hidden="true"></i> Bistacora stock</button>     
                                    </td>
                                    <td class="col-md-9">
                                        {{i.razon_social}}
                                    </td>
                                </tr>
                            </tbody>
                        
                    </table>        
                </div>
            </div>        
        </div>
    </div>
  </div>

</div>
              
        
    </div>
  
                        </div>
                    </div>        
<!---------------------------------------------------------------------------------------------------------------------------->
                    <div class="tab-pane fade" id="pills-sincro" role="tabpanel" aria-labelledby="pills-sincro-tab">
                        <div class="card">
                            <div class="card-header">
                               Sincronizacion de hora
                            </div>
                            <div class="card-body">
                                 <form action="" class="form-horizontal">  
                            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-2">Hora de sincronización</th>
                        <th class="col-md-2">Frecuencia para la sincronización</th>
                        <th class="col-md-2">Sucursales</th>
                        <th class="col-md-2">A en enviar</th> 
                        <th class="col-md-1">En lista</th>                                               
                        <th class="col-md-1">Hora server</th>
                        <th class="col-md-1">Estado</th>                        
                        <th class="col-md-1">Accion</th>                             
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="col-md-2"><input  type="time" class="form-control" v-model="horaS"></td>
                            <td class="col-md-2">
                                <select  class="form-control"  v-model="selectFrecuencia">
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="1">Ejecutar todos los días</option>
                                            <option value="2">Ejecutar el ultimo dia de la semana laboral</option>
                                            <option value="3">Ejecutar el último día del mes</option>
                                            <option value="4">Ejecutar cada trimestre el día 1</option>                                                                                                                          
                                </select>
                            </td>
                            <td  class="col-md-2">
                         <select class="form-control" v-model="selectSucurlsa">
                                    <option disabled value="0">Seleccionar...</option>
                                    <option v-for="(item, index) in arraySucursal" 
                                        :key="index" 
                                    :value="item.id">
                                {{ item.razon_social }}
                                </option>
                                </select>
                             <button  type="button" class="btn btn-secondary btn-sm btn-block" v-if="selectSucurlsa=='0'" > Añadir / Quitar</button>     
                             <button  type="button" class="btn btn-primary btn-sm btn-block" v-else @click="añadirS(selectSucurlsa)"> Añadir / Quitar</button>     
                             
                            </td>
                            <td class="col-md-2">
                                {{arrayListaS}}
                            </td>
                            <td class="col-md-1">{{ id_sucursales}}</td>
                            <td class="col-md-1">
                                <span class="badge badge-pill badge-dark">{{timeServerS}}</span>
                            </td>
                            <td class="col-md-1">
                                <span class="badge badge-pill badge-success" v-if="activoS==1">Activo</span>
                                <span class="badge badge-pill badge-danger" v-else>Desactivado</span>
                            </td>
                            <td class="col-md-1">
                                <button  type="button" class="btn btn-warning btn-sm btn-block" v-if="activoS==1" style="color: white;" @click="actualziarSincro()"><i class="fa fa-simplybuilt" aria-hidden="true"></i> Sincronización</button>     
                                <button  type="button" class="btn btn-secondary btn-sm btn-block" v-else style="color: white;" @click="actualziarSincro()"><i class="fa fa-simplybuilt" aria-hidden="true"></i> Sincronización</button>                               
                            </td>
                        </tr>
                </tbody>
                </table> 
                            <table class="table table-bordered table-striped table-sm table-responsive" >
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Estado</th>
                                        <th class="col-md-10">Informe</th>
                                    </tr>                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="col-md-2">
                                            <span class="badge badge-pill badge-success" v-if="estadoS==0">Sin errores</span>
                                            <span class="badge badge-pill badge-danger" v-else>Observado</span>
                                        </td>
                                        <td class="col-md-10">{{informeS}}</td>
                                    </tr>
                                </tbody>
                            </table>                   
                          
                        </form>
                              
                            </div>            
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
import { data } from "jquery";
import { forEach } from "lodash";
//Vue.use(VeeValidate);

export default {
    components: { VueMultiselect},
     //---permisos_R_W_S
     props: ['codventana','idmodulo'],
        //-------------------
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
            tipoAccion:1,
            tituloModal:'',
       showModal: false,
                arrayIndex:[], 
                arraySucursal:[],
                
                //-------stockmedio gestion stock----------
                    tipoStockMedio:100,
                    arrayTabla:[],
                    sucursalNoActivada:0,
                //---------------sincro-----------------------
            horaS:'',
            timeServerS:'',
            activoS:'',
            selectFrecuencia:'0',
            selectSucurlsa:'0',
            id_S:'',
            estadoS:'',
            informeS:'',
            arrayListaS:[],
            cadenaEnviar:[],
            id_sucursales:'',
                //---permisos_R_W_S
                puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------   


                



        };
    },
   
   

    computed: {
      
        isActived: function () {
            return this.pagination.current_page;
        },

       function () {
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
        
        });
},
//-------------------------------------------------------------- 

                    
         
        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;
            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },      
       

        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
        //   me.listarIndex(page);
        },

        añadirS(id){
            let me=this;
            let nom="";
            me.id_sucursales="";
         
             let seleccion = me.arraySucursal.find(
                    (element) => element.id === id,
                );
   
                if (seleccion) {                   
                     nom =seleccion.razon_social;
                }

if (me.cadenaEnviar.includes(id)) {
 
    me.cadenaEnviar = me.cadenaEnviar.filter(f => f !== id);
    me.arrayListaS = me.arrayListaS.filter(f => f !== nom);
}else{

    me.cadenaEnviar.push(id);    
    me.arrayListaS.push(nom); 
}
let cadena = me.cadenaEnviar.join(',');
me.id_sucursales=cadena;          
        },

           
        listarSucursalLista() {
            let me = this;   
            me.arraySucursal=[];    
           var url = "/listarSucursalNormal";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    me.arraySucursal=respuesta;    
                                                       
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        listarTabla() {
            let me = this;   
            me.arrayTabla=[];   
            me.sucursalNoActivada=0; 
           var url = "/configuracion-stock/listarTabla";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;                  
                    if (respuesta==1000 ) {                                                   
                        me.sucursalNoActivada=1;                            
                    }else{                        
                       me.arrayTabla=respuesta;
                    }                               
                })
                .catch(function (error) {
                    error401(error);
                });
        },
        
         actualziarSincro(){      
            let me = this;
      
            if (me.id_sucursales==null||me.id_sucursales=="") {  
                            Swal.fire("Error encontrado: ","Al menos se debe tener una sucursal en la lista","error",);                                
                          }else{
                if (me.activoS==1) {
                    me.activoS=0;
                } else {
                    me.activoS=1;
                }                  
                axios.put("/configuracion-stock/cargardatoSincro", {                        
                    'hora':me.horaS,
                    'activo':me.activoS,
                    'frecuencia':me.selectFrecuencia,
                    'id':me.id_S, 
                    'id_sucursales':me.id_sucursales                                       
                    })
                    .then(function (response) {               
                          var respuesta = response.data;
                          me.listarSincroGesStock();                        
                          if (respuesta.length>=1) {  
                            Swal.fire("Error encontrado: "+respuesta,"Haga click en Ok","error",);                                
                          }else{
                            Swal.fire("Operación realizada con exito.","Haga click en Ok","success",); 
                          }                        
                    })
                   .catch(function (error) {                               
            });
                          }                            
        },

        listarSincroGesStock(){          
            let me = this;   
           var url = "/configuracion-stock/listarSincro";
            axios.get(url)
                .then(function (response) {
                    let respuesta_1 = (response.data).hora;
                    let respuesta_2 = (response.data).log;  
                    me.horaS=respuesta_2.hora;
                    me.timeServerS=respuesta_1;
                 
                    me.activoS=respuesta_2.activo;
                    me.selectFrecuencia=respuesta_2.frecuencia; 
                    me.id_S=respuesta_2.id; 
                    me.estadoS=respuesta_2.error;
                    me.informeS=respuesta_2.informe;
                    me.id_sucursales=respuesta_2.id_sucursales;              
                    
                                             
                })
                .catch(function (error) {
                    error401(error);
                });
        },

        actualziarTablaBitacora(data_succursal){      
            let me = this;                  
                axios.post("/configuracion-stock/añadirBitacora", {
                        'tipoTabla':  me.tipoStockMedio,
                        'data_sucursal': data_succursal                   
                    })
                    .then(function (response) {               
                          var respuesta = response.data;
                          me.listarTabla();
                      
                          if (respuesta.length>=1) {  
                            Swal.fire("Error encontrado: "+respuesta,"Haga click en Ok","error",);                                
                          }else{
                            Swal.fire("Operación realizada con exito.","Haga click en Ok","success",); 
                          }                        
                    })
                   .catch(function (error) {                               
            });            
        },

         listarStockMedio() {
            let me = this;       
           var url = "/gestor-stock/stockMedio";
            axios.get(url)
                .then(function (response) {
                    let respuesta = response.data;
                    me.tipoStockMedio=respuesta.stock_medio;
                                          
                })
                .catch(function (error) {
                    error401(error);
                });
        },
             
        abrirModal(accion, data = []) {
            let me = this;     
         switch (accion) {
              
                case "modal": {
                    //me.showModal = true;
                    //me.tipoAccion = 1; 
                    //me.tituloModal="Sincronización manual"; 
                    //me.classModal.openModal("manual");
                  
                    break;
                }                                      
            }
        },

        cerrarModal(accion) {
            let me = this;
            me.classModal.closeModal(accion);            
        },            
                          
        selectAll: function (event) {
            setTimeout(function () {
                event.target.select();
            }, 0);
        },
    },

    mounted() {
       
        //-------permiso E_W_S-----
        this.listarPerimsoxyz();      
        //-------------------------     
        this.listarSucursalLista(); 
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