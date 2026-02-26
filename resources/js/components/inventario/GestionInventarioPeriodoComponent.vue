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
                <div class="card-header" v-if="puedeCrear==1" >
                    <i class="fa fa-align-justify"></i> Gestion inventario por periodo               
                    <button 
                        type="button"
                        class="btn btn-secondary"
                        @click="abrirModal('registrar');"
                        :disabled="sucursalSeleccionada == 0 || peridoSelect=='0'"
                    >
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                    <span v-if="sucursalSeleccionada == 0" class="error"
                        >&nbsp; &nbsp;Debe Seleccionar un almacen o
                        tienda.</span >
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada" @change="cambioSucursal(sucursalSeleccionada)">
                                    <option value="0" disabled selected>Seleccionar...</option>
                                    <option v-for="sucursal in arraySucursal" :key="sucursal.id"  :value="sucursal.codigo"
                                        v-text="sucursal.codigoS +' -> ' +sucursal.codigo+' ' +sucursal.razon_social"
                                    ></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar"
                                    v-model="buscar"
                                    @keyup.enter="listarIndex(1)"                                
                                    :hidden="sucursalSeleccionada == 0 || peridoSelect=='0'"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarIndex(1)"                               
                                    :hidden="sucursalSeleccionada == 0 || peridoSelect=='0'"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

           <div class="form-group row" v-if="sucursalSeleccionada !== 0">
    <div class="col-md-2">
    </div>
    <div class="col-md-3">
         <label for="start-date">Periodo:</label>
        <div class="input-group">
            <select class="form-control" v-model="peridoSelect" @change="listarIndex(0)">
                <option value="0" disabled selected>Seleccionar...</option>
                <option value="D">Diario</option>
                <option value="S">Semanal</option>
                <option value="M">Mensual</option>
                <option value="T">Trimestral</option>
            </select>
        </div>
    </div>
     <div class="col-md-3">
        <label for="">Estado</label>
         <div class="input-group">
            <select class="form-control" v-model="estadoSelect" @change="listarIndex(0)">
                <option value="0" disabled selected>Seleccionar...</option>
                <option value="A" v-if="puedeActivar==1">Ver anulado</option>
                <option value="N">Ver normal</option>
            </select>
        </div>
     </div>
        <div class="col-md-2">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate" @change="listarIndex(0)">
        </div>
        <div class="col-md-2">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate" @change="listarIndex(0)">
        </div>     
  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-2">Opciones</th>
                        <th class="col-md-2">linea</th>                      
                        <th class="col-md-2">Turno</th>
                        <th class="col-md-3">Fecha de creacion</th>
                        <th class="col-md-2">Usuario</th>
                        <th class="col-md-1">Estado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayInicio" :key="index">
                        <td class="col-md-2">
                            <div class="button-container">
                                <div  class="d-flex justify-content-start">
                                      <button  type="button" class="btn btn-primary" style="margin-right: 5px;" @click="verPDF(i); listarProductoDetalle(i.id,1);">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </button>
                            <button  type="button" class="btn btn-warning" style="margin-right: 5px; color: white;" @click="abrirModal('ver',i);listarProductoDetalle(i.id,0);">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                                    <div v-if="puedeActivar==1">
                            <button v-if="i.estado == 'ACEPTADO'" type="button" class="btn btn-danger" style="margin-right: 5px;" @click="cambioEstado(i.id,2)">
                                <i class="icon-trash"></i>
                            </button>
                            <button v-else type="button" class="btn btn-success" style="margin-right: 5px;" @click="cambioEstado(i.id,1)">
                                <i class="icon-check"></i>
                            </button>
                                    </div>
                                      <div v-else>
                <button v-if="i.estado == 'ACEPTADO'" type="button" class="btn btn-light "
                 style="margin-right: 5px;">
                <i class="icon-trash"></i>
            </button>
            <button v-else type="button" class="btn btn-light"  style="margin-right: 5px;">
                <i class="icon-check"></i>
            </button>
            </div>
                                </div>
                            </div>    
                          
                            
                        </td>
                        <td class="col-md-2">
                            {{i.nom_linea}}
                        </td>
                         <td class="col-md-2">
                            {{i.turno}}
                        </td>
                         <td class="col-md-3">
                            {{i.fecha_creacion}}
                        </td>
                        <td class="col-md-2">
                            {{i.name}}
                        </td>
                        
                         <td class="col-md-1">
                            <div v-if="i.estado == 'ACEPTADO'">
                                        <span class="badge badge-success"
                                            >ACEPTADO</span
                                        >
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-warning"
                                            >ELIMINADO</span
                                        >
                                    </div>
                    </td>
                    </tr>
                </tbody>
            </table>    
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
            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
           <transition name="fade">
            <div v-if="showModal" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('registrar')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                        <div class="alert alert-warning" role="alert">
                            Todos los campos con (*) son requeridos
                        </div>
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row">
                                    
                                    <div class="col-md-4">
                                        <label for="">Turno:</label>
                                        <select class="form-control" v-model="turnoSelect"> 
                                            <option value="0" disabled selected>Seleccionar...</option>
                                            <option value="MAÑANA">Mañana</option>
                                            <option value="TARDE">Tarde</option>
                                            <option value="COMPLETO">Completo</option> 
                                        </select>
                                    </div> 
                                    <div class="col-md-5">
                                        <label for="">Linea:</label>
                                         <VueMultiselect
                        v-model="lineaSelect"
                       
                        :options="arrayLinea"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="nombre" 
                                           
                        track-by="id"
                        class="w-250"
                        selectLabel="Añadir a seleccion"
                        deselectLabel="Quitar seleccion"
                        selectedLabel="Seleccionado">
                       <template #noResult>
                        No se encontraron elementos. Considere cambiar la consulta de búsqueda.
                      </template>
                    </VueMultiselect> 
                                    </div> 
                                    <div class="col-md-2">
                                        <button type="button" style="margin-top: 18px;" class="btn btn-primary" :disabled="turnoSelect=='0'||lineaSelect==null" @click="listarProducto(lineaSelect.id)">Procesar</button>
                                    </div>      
                                </div>
                               
                            </div>
                            <div class="alert alert-primary" role="alert" v-if="turnoSelect=='0'||lineaSelect==null">
  Debe seleccionar y completar todas las opciones 
</div>
            <table class="table table-bordered table-striped table-sm table-responsive" v-else>
                <thead>
                    <tr>
                        <th class="col-md-5">Producto</th>
                        <th class="col-md-1">Lote</th>
                        <th class="col-md-2">Fecha ingreso</th>
                        <th class="col-md-2">Fecha vencimiento</th>
                        <th class="col-md-2">Cantidad</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayProductoLineaIngreso" :key="index">
                        <td>{{i.nom_producto+" - "+i.nom_dis+" X "+i.cantidad_d+" "+i.nom_forma_faraceutica+" "+i.envase}}</td>
                        <td>{{i.lote}}</td>
                        <td>{{i.fecha_ingreso}}</td>
                        <td>{{i.fecha_vencimiento}}</td>
                        <td>
                            <input type="number" class="form-control"     @input="guardarProducto(i, $event.target.value)">
                        </td>
                    </tr>
                </tbody>
            </table> 
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('registrar')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary" :disabled="  turnoSelect=='0'||lineaSelect==null||isSubmitting==true" @click="registrar()">
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

        <!--Inicio del modal ver-->
           <transition name="fade">
            <div v-if="showModal_2" class="modal d-block" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-primary modal-super-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">{{ tituloModal }}</h4>
                        <button type="button" class="close" @click="cerrarModal('ver')">
                            <span>&times;</span>
                        </button>
                    </div>
                      <div class="modal-body"  style="max-height: 70vh; overflow-y: auto;">  
                   
                        <form action="" class="form-horizontal">
                        
                            <!-- insertar datos -->
                            <div class="container">                                
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <strong>USUARIO: {{usuario_ver}}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Linea: {{linea_ver}}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>FECHA: {{fecha_ver}}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>ESTADO: {{estado_ver}}</strong>
                                    </div>
                                </div>
                               
                            </div>
            
            <table class="table table-bordered table-striped table-sm table-responsive">
                <thead>
                    <tr>
                        <th class="col-md-4">Producto</th>
                        <th>Lote</th>
                        <th>Fecha vencimiento</th>
                        <th>Cantidad sistema</th>
                        <th>Cantidad registrada</th>
                        <th>Diferencia</th>
                        <th>Resultado</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in arrayProductoDetalle" :key="index">
                         <td class="col-md-4">{{i.nom_producto+" - "+i.nom_dis+" X "+i.cantidad_d+" "+i.nom_forma_faraceutica+" "+i.envase}}</td>
                         <td>{{i.lote}}</td>
                       <td>{{i.fecha_v}}</td>
                       <td>{{i.cantidad_sis_detalle_inventario}}</td>
                       <td>{{i.cantidad_reg_detalle_inventario}}</td>
                       <td>{{i.diferencia_detalle_inventario}}</td>
                       <td>{{i.estado}}</td>                       
                    </tr>
                </tbody>
                
            </table> 
                        </form>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal('ver')">
                            Cerrar
                        </button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary">
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
import VueMultiselect from 'vue-multiselect';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
//Vue.use(VeeValidate);
export default {
     components: { VueMultiselect },
     //---permisos_R_W_S
        props: ['codventana'],
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
            showModal: false,
            showModal_2: false,
            offset:3,
            isSubmitting:false,

            tituloModal: '',
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:'',
            tipoAccion:1,
            startDate: '',
      endDate: '',

            id_sucursal:'',
            id_tienda:'',
            id_almacen:'',
            peridoSelect:'0',

arrayProductoLineaIngreso:[],
            arrayLinea:[],

            dateInput:'',
            turnoSelect:'0',
            lineaSelect:null,   
            productosSeleccionados: [],

            estadoSelect:'N',
            arrayInicio:[],

            usuario_ver:'',
            linea_ver:'',
            fecha_ver:'',
            estado_ver:'',
            arrayProductoDetalle:[],

             //---permisos_R_W_S
            puedeEditar:2,
                puedeActivar:2,
                puedeHacerOpciones_especiales:2,
                puedeCrear:2,
                //-----------
          
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

//-------------------------------------------------------------

        descargaPDF(array_reporte) {

    let me = this;

    const tableBody = [];

    // 🔹 Fila informativa superior (usa colSpan)
    tableBody.push([
        { text: 'USUARIO: ' + me.usuario_ver, colSpan: 7, alignment: 'left', fillColor: '#eeeeee', fontSize: 8 }, {}, {}, {}, {}, {}, {}
    ]);

    tableBody.push([
        { text: 'LINEA: ' + me.linea_ver + '   |   FECHA: ' + me.fecha_ver + '   |   ESTADO: ' + me.estado_ver, colSpan: 7, alignment: 'left', fillColor: '#eeeeee', fontSize: 8 }, {}, {}, {}, {}, {}, {}
    ]);

    // 🔹 Encabezados reales de tabla
    tableBody.push([
        { text: 'PRODUCTO', style: 'tableHeader' },
        { text: 'LOTE', style: 'tableHeader' },
        { text: 'F. VENC.', style: 'tableHeader' },
        { text: 'SISTEMA', style: 'tableHeader' },
        { text: 'REGISTRO', style: 'tableHeader' },
        { text: 'DIF.', style: 'tableHeader' },
        { text: 'ESTADO', style: 'tableHeader' },
    ]);

    // 🔹 Datos
    array_reporte.forEach(i => {

        tableBody.push([
            {
                text: i.nom_producto + " - " + i.nom_dis + " X " + i.cantidad_d + " " + i.nom_forma_faraceutica + " " + i.envase,
                fontSize: 7,
                alignment: 'left'
            },
            { text: i.lote || '', fontSize: 7 },
            { text: i.fecha_v || '', fontSize: 7, alignment: 'center' },
            { text: i.cantidad_sis_detalle_inventario?.toString() || '0', fontSize: 7, alignment: 'right' },
            { text: i.cantidad_reg_detalle_inventario?.toString() || '0', fontSize: 7, alignment: 'right' },
            { text: i.diferencia_detalle_inventario?.toString() || '0', fontSize: 7, alignment: 'right' },
            { text: i.estado || '', fontSize: 7, alignment: 'center' }
        ]);

    });

    const docDefinition = {
        pageSize: 'LETTER',
        pageMargins: [25, 40, 25, 40],

        content: [

            { text: 'INFORME DE CONTEO', style: 'header' },

            {
                margin: [0, 10, 0, 0],
                table: {
                    headerRows: 3,
                    widths: ['*', 50, 55, 45, 45, 45, 50],
                    body: tableBody
                }
            }

        ],

        styles: {

            header: {
                fontSize: 13,
                bold: true,
                alignment: 'center',
                margin: [0, 0, 0, 10]
            },

            tableHeader: {
                bold: true,
                fontSize: 8,
                color: 'black',
                fillColor: '#d3d3d3',
                alignment: 'center'
            }

        }
    };

    pdfMake.createPdf(docDefinition).open();
},

        verPDF(data){
            let me=this;
            me.usuario_ver=data.name;
                me.linea_ver=data.nom_linea;
                me.fecha_ver=data.fecha_creacion;
                me.estado_ver=data.estado;
        },
      

       listarIndex(page){
            let me=this;            
                var url='/inventario-periodo/listarInicio?page='+page+'&id_sucursal='+me.id_sucursal+'&buscar='+me.buscar+'&estadoSelect='+me.estadoSelect+'&tipo_inventario='+me.peridoSelect+'&id_tienda='+me.id_tienda+'&id_almacen='+me.id_almacen+'&ini='+me.startDate+'&fini='+me.endDate;
             
                axios.get(url)
                .then(function(response){
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.pagination = respuesta.pagination;
                    me.arrayInicio = respuesta.resultados.data;
                 
                })
                .catch(function(error){
                    error401(error);
                });
        },

        guardarProducto(producto, cantidad) {
        const index = this.productosSeleccionados.findIndex(
            p => p.id === producto.id
        );

        if (index !== -1) {
            // Si ya existe, solo actualiza cantidad
            this.productosSeleccionados[index].cantidad = Number(cantidad);

        } else {
            // Si no existe, lo agrega completo
            this.productosSeleccionados.push({
                id_ingreso: producto.id,
                id_prod_producto: producto.id_prod_producto,
                stock_ingreso: producto.stock_ingreso,                
                envase: producto.envase,
                cantidad: Number(cantidad),  
                lote: producto.lote,
                fecha_vencimiento: producto.fecha_vencimiento,            
            });

        }

    },

        registrar(){
            let me =this;
            console.log(me.arrayProductoLineaIngreso.length);
            console.log(me.productosSeleccionados.length);
            console.log(me.peridoSelect);
            console.log(me.productosSeleccionados);
            if (me.arrayProductoLineaIngreso.length==me.productosSeleccionados.length) {
              
                   me.isSubmitting=true;
                axios.post("/inventario-periodo/registrar", { 
                        'array': me.productosSeleccionados,
                        'id_sucursal':me.id_sucursal,
                        'id_tienda':me.id_tienda,
                        'id_almacen':me.id_almacen,
                        'id_lineas': me.lineaSelect.id, 
                        'tipo_inventario':me.peridoSelect,
                        'turno':me.turnoSelect                                          
                    })
                    .then(function (response) {
                        me.cerrarModal("registrar");
                          me.isSubmitting=false;
                          var respuesta = response.data;   
                          console.log(respuesta);                      
                                if (respuesta===0) {
                                     Swal.fire("Se guardo correctamente.","Haga click en Ok","success",);
                                }else{
                                    Swal.fire(respuesta,"Haga click en Ok","error",);
                                }                          
                         me.listarIndex();
                    })
                   .catch(function (error) {                
                      error401(error);             
            });
            }else{
                 me.isSubmitting=false;
                me.productosSeleccionados=[];
                Swal.fire(
                    "No puede ingresar valor nulos  o vacios",
                    "Haga click en Ok",
                    "warning",
                );
            }           
        },

        cambioEstado(id,dato) {
            let me = this;
            let title_2="";
             let text_2="";
              let estado_2="";
            if(dato==1){
                title_2="¿Esta Seguro de Activar?";
                text_2="Es una activacion logica";
                estado_2="Activar";
            }
            else{
                title_2="¿Esta Seguro de Desactivar?";
                text_2="Es una eliminacion logica";
                estado_2="Desactivar";
            }
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: title_2,
                    text: text_2,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Si, "+estado_2,
                    cancelButtonText: "No, Cancelar",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .put("/inventario-periodo/cambioEstado", {
                                id: id,
                                dato:dato,
                            })
                            .then(function (response) {  
                                console.log(response.data);
                                                        
                                swalWithBootstrapButtons.fire(
                                    estado_2+"!",
                                    "El registro a sido "+estado_2+" Correctamente",
                                    "success",
                                );
                                me.listarIndex();
                            })
                          
                           .catch(function (error) {           
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
                });
        },

        listarProducto(id_linea){
            let me = this;  
            console.log(me.peridoSelect);      
            var url = "/inventario-periodo/listarProducto?id_linea="+id_linea+"&id_tienda="+me.id_tienda+"&id_almacen="+me.id_almacen;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductoLineaIngreso=respuesta;
                    console.log(respuesta);                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

         listarProductoDetalle(id,dato){
            let me = this;    
            me.arrayProductoDetalle=[];           
            var url = "/inventario-periodo/listarProductoDetalle?id="+id;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayProductoDetalle=respuesta;
                    console.log("****");
                    console.log(me.arrayProductoDetalle);
                    if (dato==1) {
                        me.descargaPDF(me.arrayProductoDetalle);
                    }                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },

        

        listarLinea() {
            let me = this;           
            var url = "/inventario-periodo/listarLinea";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayLinea = respuesta;
                    console.log(me.arrayLinea);                 
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

        cambioSucursal(codigo){
            let me = this;
           const registro = me.arraySucursal.find(item => item.codigo === codigo);

if (registro) {
    me.id_sucursal=registro.id_sucursal;
    me.id_tienda=registro.id_tienda;
    me.id_almacen=registro.id_almacen;
} else {    
    me.id_sucursal="";
    me.id_tienda="";
    me.id_almacen="";
}
        },

        cambiarPestana(idPestana) {
            this.pestañaActiva = idPestana;

            // Agrega aquí la lógica adicional que necesites al cambiar la pestaña
        },



        cambiarPagina(page) {
            let me = this;
            me.pagination.current_page = page;
           me.listarIndex(page);
        },

        abrirModal(accion, data = []) {
            let me = this;
        //    let respuesta = me.arraySucursal.find(
        //        (element) => element.codigo == me.sucursalSeleccionada,
        //    );
           
         switch (accion) {
                case "registrar": {
                    me.tipoAccion = 1;
                    me.tituloModal = "Crear inventario";
                    me.showModal = true;
                    me.dateInput="";
                    me.turnoSelect="0";
                    me.lineaSelect=null;   
                    me.productosSeleccionados= [];
                    me.isSubmitting=false;
                    me.classModal.openModal("registrar");
                    break;
                }
                case "actualizar": {
                    me.tipoAccion = 2;
                   
          
            
                    me.classModal.openModal("registrar");

                    break;
                }
                case "ver":{
                console.log(data);
                me.showModal_2 = true;
                me.tituloModal = "Ver detalle";

                me.usuario_ver=data.name;
                me.linea_ver=data.nom_linea;
                me.fecha_ver=data.fecha_creacion;
                me.estado_ver=data.estado;

                me.classModal.openModal("ver");
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
                me.showModal = false;
                me.tituloModal = " ";
              me.dateInput="";
                    me.turnoSelect="0";
                    me.lineaSelect=null;   
            me.productosSeleccionados= [];
             me.isSubmitting=false;
            }
            if (accion == "ver") {
                me.classModal.closeModal(accion);
                me.showModal_2 = false;
                me.tituloModal = " ";
                me.usuario_ver="";
                me.linea_ver="";
                me.fecha_ver="";
                me.estado_ver="";
                me.arrayProductoDetalle=[];
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
        this.listarLinea();
        this.listarPerimsoxyz();
        this.classModal.addModal("registrar");
        this.classModal.addModal("ver");
    
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

.modal-super-lg {
    max-width: 90% !important;
}
</style>
