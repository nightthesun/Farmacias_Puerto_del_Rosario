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
                    <i class="fa fa-align-justify"></i> Gestor de sctock               
                    <button type="button" class="btn btn-secondary" style="color:white;" @click="abrirModal('registrar');iniciarOperacio();" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                        <i class="icon-plus"></i>&nbsp;Generar
                    </button>&nbsp;
                    <button type="button" class="btn btn-info"  style="color:white;" @click="abrirModal('registrar');" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary"  @click="abrirModal('registrar');" :disabled="sucursalSeleccionada == 0" :hidden="sucursalSeleccionada == 0">
                       <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
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
                        <div class="col-md-4">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada">
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
                        <div class="col-md-6">
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
             

            <div class="row">
                <div class="col-md-2" >
                     <label for="">&nbsp;</label>
                </div>
    
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
     
         <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">Usar fecha:</label>
            <button type="button" class="btn btn-success mt-1" style="color: white;">
                <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>

        <div class="col-md-2" v-if="sucursalSeleccionada !== 0">
            <div class="d-flex flex-column">
            <label for="end-date">No usar fecha:</label>
            <button type="button" class="btn btn-warning mt-1" style="color: white;">
                <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
            </button>
            </div>
        </div>


  </div>
  <br>
            <!---inserte tabla-->
            <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th>Opciones</th>
                        <th>Nro</th>
                        <th>Distribuidor</th>
                        <th>Fecha de pedigo</th>
                        <th>Total</th>
                        <th>Fecha de pago</th>
                        <th>Formato de pago</th>
                        <th>Turno de entrega</th>
                        <th>Plazo</th>
                        <th>Obs.</th>
                        <th>Tipo</th>
                        <th>Usuario</th>
                        <th>Estado</th>       
                    </tr>
                </thead>
            </table>    

            <!-----fin de tabla------->
        </div>


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
         <transition name="fade"> 
  <div v-if="showModal" class="modal d-block fullscreen-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-primary modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ tituloModal }}</h4>
          <button type="button" class="close" @click="cerrarModal('registrar')">
            <span>Cerrar</span>
          </button>
        </div>
        <div class="modal-body">     
    <div class="card-header">
        <br>
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Opciones:            
                    
                    <button type="button" class="btn btn-info"  style="color:white;" @click="accionDescargarPDF()">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Exportar PDF
                    </button>&nbsp;
                    <button type="button" class="btn btn-primary" @click="descargarExcell()"   >
                       <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel
                    </button>
             
                </div>
                <br>
                 <div class="form-group row">
                  
                <div class="col-md-1" style="text-align: center">
                     <label for="">Accion:</label>
                </div>
                        <div class="col-md-6">
                           <button type="button" @click="datosFiltrados(10)" class="btn" style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver todo</button>&nbsp;
<button type="button" class="btn" @click="datosFiltrados(2)" style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock alerta</button>&nbsp;
<button type="button" class="btn" @click="datosFiltrados(1)"  style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock minimo </button>&nbsp;
<button type="button" class="btn" @click="datosFiltrados(0)"  style="background-color: darkseagreen; color: white;"><i class="fa fa-eye" aria-hidden="true"></i> Ver stock cero </button>&nbsp;

                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="texto"
                                    name="texto"
                                    class="form-control"
                                    placeholder="Texto a buscar por liena o nombre de producto"
                                    v-model="buscar"
                               
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="buscarProductos()"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             
          <!-- Formulario -->
          <form class="form-horizontal">
            <div class="container-fluid">
              <div class="form-group row">
                <div class="modal-body" style="max-height: 120vh; overflow-y: auto;">                    
          
                <table class="table table-bordered table-striped table-sm table-responsive" >                    
                    <thead>
                        <tr>
                            <th>Linea</th>
                            <th>Producto</th>
                            <th>Ciclo de Stock</th>
                            <th>Consumo promedio mensual</th>
                            <th>Plazo de entrega</th>
                            <th>Consumo promedio venta</th>
                            <th>Stock Maximo</th>
                            <th>Stock Medio</th>
                            <th>Stock Actual</th>
                            <th>Stock pedido</th>
                            <th>Indice de rotacion</th>
                            <th>Indice de cobertura</th>
                            <th>Rentabilidad</th>
                        </tr>                        
                    </thead>
                    <tbody>
                        <tr v-for="i in arrayModalOperacionGestor" :key="i.id_producto"
                        :style="{ backgroundColor: i.color === 0 ? 'red' : 
                     i.color === 1 ? '#f5d033' : 
                     i.color === 2 ? 'orange' :
                     i.color ===3 ? 'transparent': 'transparent'}" >
                            <td style="text-align: left;">{{i.linea}}</td>
                            <td style="text-align: left;">{{i.producto}}</td>
                            <td style="text-align: right;">{{ i.ciclo }}</td>
                            <td style="text-align: right;">{{i.consumo_mensual}}</td>
                            <td style="text-align: right;">{{i.plazo}}</td>
                            <td style="text-align: right;">{{i.consumo_dia}}</td>
                            <td style="text-align: right;">{{i.stmax}}</td>
                            <td style="text-align: right;">{{i.stmedio}}</td>
                            <td style="text-align: right;">{{i.stock_total}}</td>
                            <td style="text-align: right;">{{i.stpedido}}</td> 
                            <td style="text-align: right;">{{i.indicerot}}</td>
                            <td style="text-align: right;">{{i.indicecober}}</td>
                            <td style="text-align: right;">{{i.rentabilidad}}</td>
                           
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
          </form>
        </div>

      
       
            <div class="card">
  <h5 class="card-header">Crear gestor stock</h5>
  <div class="card-body">
    <div class="container">                               
                <div class="form-group row">
                    <label class="col-md-2 form-control-label" for="text-input">
                     <strong>Distribuidor:</strong>                                
                    </label>
                        <div class="col-md-5 input-group mb-3">
                        <VueMultiselect
                        v-model="selected"
                        
                        :options="arrayDistribuidor"
                        :max-height="190"                   
                        :block-keys="['Tab', 'Enter']"                       
                        placeholder="Seleccione una opción"
                        label="id" 
                        :custom-label="nameWithLang"                     
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
                        <div class="col-md-5 input-group mb-3">
<button type="button" class="btn" style="background-color: orange; color: white;">Stock alerta</button>&nbsp;
<button type="button" class="btn" style="background-color: yellow; color: black;">Stock minimo</button>&nbsp;
<button type="button" class="btn" style="background-color: red; color: white;">Stock cero</button>&nbsp;
<button type="button" class="btn btn-secondary">Cerrar modal</button>
                        </div>
                </div>        
            </div>
  </div>
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
import * as XLSX from 'xlsx';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
export default {
     components: { VueMultiselect},
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
  selected:null,
            tituloModal: "",
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',

            arrayDistribuidor:[],

            arrayModalOperacionGestor:[],
            arrayModalFalso:[],
            ocualto:0,
              filtroColor: 'todos' // puede ser: 'todos', 1, 2
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

        accionDescargarPDF(){
            let me=this;
            me.descargaPDF(me.arrayModalOperacionGestor);
        },

        descargaPDF(array_reporte){

            const tableBody =[
                //agregar encabezados de la tabla
                [
                    {text: 'Linea', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3'},
                    {text: 'Producto', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Ciclo de stock', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Consumo promedio mensual', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Plazo de entrega', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Consumo promedio venta', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock maximo', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock medio actual', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Stock pedido', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Indice rotación', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Indice de cobertura', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                    {text: 'Rentabilidad', style: 'tableHeader_2', alignment: 'center', fillColor:'#d3d3d3'},
                ]
            ];
            // Itera sobre los datos y agrega fila a la tabla 
            array_reporte.forEach(item =>{
               tableBody.push([
  {text: item.linea, fontSize:8, alignment: 'left'},
  {text: item.producto, fontSize:8, alignment: 'left'},
  {text: item.ciclo, fontSize:8, alignment: 'left'},
  {text: item.consumo_mensual, fontSize:8, alignment: 'left'},
  {text: item.plazo, fontSize:8, alignment: 'left'}, // ← FALTABA ESTA
  {text: item.consumo_dia, fontSize:8, alignment: 'left'},
  {text: item.stmax, fontSize:8, alignment: 'left'},
  {text: item.stmedio, fontSize:8, alignment: 'left'},
  {text: item.stpedido, fontSize:8, alignment: 'left'},
  {text: item.indicerot, fontSize:8, alignment: 'left'},
  {text: item.indicecober, fontSize:8, alignment: 'left'},
  {text: item.rentabilidad, fontSize:8, alignment: 'left'},
]);

            });

            const docDefinition ={
                pageSize: 'LETTER',
                pageMargins: [25, 30, 25, 30],
                content:[
                  {text: 'INFORME GESTOR DE STOCK', style:'header'},
                  {
                    style: 'tableExample',
                    margin:[0,10,0,0],
                    table:{
                         headerRows: 1,
                        widths: [30,'*',30,34,30,30,30,30,30,30,30,30], // Ajusta los anchos de las columnas
                        body: tableBody
                    }
                  },
                ],
      
                styles: {
                    header: {
                        fontSize: 12,
                        bold: true,
                        color: 'black',
                        alignment: 'center',
                        margin:[0,10,0,10]
                    },
                    tableExample:{
                        fontSize: 8,
                        bold: true,
                    },
                    tableHeader2 : {
			            bold: true,
			            fontSize: 7,
			            color: 'black',
                        margin:[ 180, 0, 20,0] 
		            },
                    tableHeader_2: {
                        bold: true,
                        fontSize: 7,
                        bold: true,
                        alignment: 'center',
                    },
                }
                };    
                  // Generar y abrir el PDF
  pdfMake.createPdf(docDefinition).open();   
        },

        descargarExcell(){
           let me = this;

// 1. Define columnas con títulos bonitos
const columnas = [
  { key: 'id_producto', titulo: 'ID Producto' },
  { key: 'linea', titulo: 'Linea' },
  { key: 'ciclo', titulo: 'Ciclo de stock' },
  { key: 'consumo_mensual', titulo: 'Consumo promedio mensual' },
  { key: 'plazo', titulo: 'Plazo de entrega' },
  { key: 'consumo_dia', titulo: 'Consumo promedio venta' },
  { key: 'stmax', titulo: 'Stock maximo' },
  { key: 'stmedio', titulo: 'Stock medio' },
  { key: 'stock_total', titulo: 'Stock actual' },
  { key: 'stpedido', titulo: 'Stock pedido' }, // corregido aquí
  { key: 'indicerot', titulo: 'Indice de rotación' },
  { key: 'indicecober', titulo: 'Indice de cobertura' },
  { key: 'rentabilidad', titulo: 'Rentabilidad' }
];

// 2. Filtra y renombra columnas
const datosFiltrados = me.arrayModalOperacionGestor.map(item => {
  const fila = {};
  columnas.forEach(col => {
    fila[col.titulo] = item[col.key];
  });
  return fila;
});

// 3. Crear hoja vacía
const ws = XLSX.utils.json_to_sheet([]);

// 4. Agrega un título grande en la primera fila
XLSX.utils.sheet_add_aoa(ws, [['Informe']], { origin: 'A1' });

// 5. Agrega los datos debajo del título (desde fila 3)
XLSX.utils.sheet_add_json(ws, datosFiltrados, {
  origin: 'A3',
  skipHeader: false
});

// 6. Crear libro y exportar
const wb = XLSX.utils.book_new();
XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');
XLSX.writeFile(wb, 'informe_emisiones.xlsx'); 


    // Convertir los datos a un formato que XLSX pueda entender
      //  const ws = XLSX.utils.json_to_sheet(nuevo);
         // Crear un libro de trabajo de Excel con esos datos
      //   const wb = XLSX.utils.book_new();
      //  XLSX.utils.book_append_sheet(wb, ws, 'Emisiones');
      //  let archivo="gestion_stock";    
        // Generar un archivo Excel y forzar la descarga
     //   XLSX.writeFile(wb, archivo+'.xlsx');
        },
        
        buscarProductos() {
        let me=this;       
        const texto = me.buscar.toLowerCase(); 
        const existe = me.arrayModalFalso.some(item =>
        (item.linea && item.linea.toLowerCase().includes(texto)) ||
        (item.producto && item.producto.toLowerCase().includes(texto))
        ); 
        if (existe) {
            // Filtra por coincidencias en línea o producto
    me.arrayModalOperacionGestor = me.arrayModalFalso.filter(item =>
      (item.linea && item.linea.toLowerCase().includes(texto)) ||
      (item.producto && item.producto.toLowerCase().includes(texto))
    );
        } else {
            me.arrayModalOperacionGestor=me.arrayModalFalso; 
        }
        console.log(existe);    
    },
  

datosFiltrados(data) {
           let me=this;
            if (data===10) {
                 console.log(data);
                me.arrayModalOperacionGestor=me.arrayModalFalso; 
            }
            if (data===1) {
                 console.log(data);
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data);              
            }
            if (data===2) {
                 console.log(data);
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data); 
            }
            if (data===0) {
                 console.log(data);
              me.arrayModalOperacionGestor=me.arrayModalFalso.filter(item => item.color === data); 
            }   
  },

        iniciarOperacio(){
            let me=this;
            me.listarModalGestorOperacion();
            me.listarDistribuidor();
        },

        verTodo(){
            let me = this;
            me.ocualto=0;
        },

        listarModalGestorOperacion(){
            
            let me=this;
            me.arrayModalOperacionGestor=[];
            var url = "/gestor-stock/listarGestorStockModal";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayModalOperacionGestor = respuesta;
                    me.arrayModalFalso = respuesta; 
            
                    console.log("*******************");
                    console.log(respuesta);
                 console.log("*******************");
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });

        },

        sucursalFiltro() {
            let me = this;
      
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
       
        nameWithLang ({nom_a_facturar,nom_linea_array}) {            
            return `Dist: ${nom_a_facturar} Linea: ${nom_linea_array}`
          },

        listarDistribuidor() {
            let me = this;       
           var url = "/gestor-stock/listarDistribuidor";
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayDistribuidor = respuesta;
                    console.log(me.arrayDistribuidor);
                 
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
              
                    me.tituloModal = "Ventana gestor de stock";
                    me.showModal = true;
                    me.selected0null;
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
                me.showModal = false;
                me.tituloModal = " ";            
               me.selected=null;
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
.fullscreen-modal .modal-dialog {
  max-width: 100%;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

.fullscreen-modal .modal-content {
  height: 100%;
  border-radius: 0;
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>