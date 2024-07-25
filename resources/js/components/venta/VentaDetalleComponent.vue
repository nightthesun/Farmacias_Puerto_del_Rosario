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
                    <i class="fa fa-align-justify"></i> Detalle de ventas               
                 
                </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2" style="text-align: center">
                     <label for="">Almacen o Tienda:</label>
                </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control" v-model="sucursalSeleccionada"    @change="listarVentas(0)">
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
                                    placeholder="Texto a buscar por usuario, nombre a facturar, numero de documento"
                                    v-model="buscar"
                                    @keyup.enter="listarVentas(1)" 
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                />
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    @click="listarVentas(1)"
                                    :hidden="sucursalSeleccionada == 0"
                                    :disabled="sucursalSeleccionada == 0"
                                >
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                       

            </div>
             

            <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="row">
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="start-date">Fecha inicial:</label>
          <input  @change="listarVentas(1)" id="start-date" type="date" class="form-control" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="sucursalSeleccionada !== 0">
          <label for="end-date">Fecha final:</label>
          <input  @change="listarVentas(1)" id="end-date" type="date" class="form-control" v-model="endDate">
        </div>
      </div>
    </div>
  </div>
  <br>
  
    <div v-if="sucursalSeleccionada ===0" class="alert alert-secondary" role="alert">
  Debe seleccionar una sucursal!
</div>
<div v-else>
    <!---inserte tabla-->
    <table class="table table-bordered table-striped table-sm table-responsive" >
                <thead>
                    <tr>
                        <th class="col-md-1">Opciones</th>
                        <th class="col-md-1">Cliente</th>
                        <th class="col-md-1">Nro Documento</th>
                        <th class="col-md-1">Nro Transaccion</th>
                        <th class="col-md-1">Tipo Comprobante</th>
                        
                        <th class="col-md-1">Total</th>
                        <th class="col-md-1">Efectivo</th>
                        <th class="col-md-1">Cambio</th>
                        <th class="col-md-2">Fecha/Hora</th>
                        <th class="col-md-1">Usuario</th>
                        <th>Anulado</th>
                        <th>Estado</th>

                    </tr>
                </thead>
                <tbody>
                    <tr v-for="v in arrayVentas" :key="v.id">
                        <td class="col-md-1">
            <!-- Example single danger button -->
<div class="btn-group">
  <button type="button" style="color: white;" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu">
    <a @click.prevent="abrirModal('recibo_r', v);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura ticket</a>
    <a @click.prevent="abrirModal('pdf_plana', v);" class="dropdown-item" href="#"><i style="color: black;" class="fa fa-file-pdf-o" aria-hidden="true"></i> Re imprimir factura plana</a>
    <a @click.prevent="abrirModal('ver_detalle_venta',v)"  class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver estado</a>
    <a class="dropdown-item" href="#"><i style="color: black;" class="fa fa-trash" aria-hidden="true"></i> Anular venta</a>
    
    
  </div>
</div>                       
                        </td>
                        <td class="col-md-1" v-text="v.nom_a_facturar"></td>
                        <td class="col-md-1" v-text="v.num_documento"></td>
                        <td class="col-md-1" v-text="v.nro_comprobante_venta"></td>
                        <td class="col-md-1" v-text="v.tipo_venta_reci_fac"></td>
                        <td class="col-md-1" v-text="v.total_venta"></td>
                        <td class="col-md-1" v-text="v.efectivo_venta"></td>
                        <td class="col-md-1" v-text="v.cambio_venta"></td>
                        <td class="col-md-2" v-text="v.created_at"></td>
                        <td class="col-md-1" v-text="v.name"></td>
                        <td>
                            <div v-if="v.anulado===0">
                                <span class="badge badge-pill badge-success">Activo</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-dange">Eliminado</span>
                            </div>
                        </td>
                        <td >
                            <div v-if="v.estado_venta==='ACEPTADO'">
                                <span class="badge badge-pill badge-success">Valida</span>
                            </div>
                            <div v-else>
                                <span  class="badge badge-pill badge-warning">Revisar</span>
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
 
            


            </div>   
  
        <!-- fin de index -->
        </div>   
           <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade"
            tabindex="-1"
            role="dialog"
            arial-labelledby="myModalLabel"
            id="ver_detalle"
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
                            @click="cerrarModal('ver_detalle')"
                        >
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      
                      <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th  class="col-md-1" style="font-size: 11px; text-align: center">Codigo de cliente</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center">Nombre de cliente</th>
          <th  class="col-md-2" style="font-size: 11px; text-align: center">Nombre a facturar</th>
          <th  class="col-md-2" style="font-size: 11px; text-align: center">Numero de documento</th>
          <th  class="col-md-3" style="font-size: 11px; text-align: center">Correo</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">{{cod_cliente}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{nom_cliente}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{nom_facturar}}</td>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{num_documento}}</td>
          <td class="col-md-3" style="font-size: 11px; text-align: center;">{{ correo }}</td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Numero de identificacion</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">
          <span v-if="tipo_per_emp===1">Numero recibo</span>
          <span v-if="tipo_per_emp===2">Numero factura</span>
          </th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Fecha</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Hora</th>
          <th class="col-md-6" style="font-size: 11px; text-align: center;">Descuentos</th>        
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Estado</th>
          
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="col-md-2" style="font-size: 11px; text-align: center;">{{num_identificacion}}</td>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">{{num_reci_fac}}</td>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">{{fecha_x}}</td>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">{{hora_x}}</td>
          <td class="col-md-6" style="font-size: 11px; text-align: center;">
            <span v-for="nombre in array_nombre_des" :key="nombre.id" style="display: inline-block; margin-right: 5px;">
      {{"["+nombre.nombre+"-"+ nombre.nombre_descuento+"]"}}
    </span>
            
          </td>
          <td class="col-md-1" style="font-size: 11px; text-align: center;">
            <span class="badge badge-pill badge-success" v-if="anulado_x==='ACTIVO'"> {{anulado_x}}</span>
            <span class="badge badge-pill badge-danger" v-if="anulado_x==='ANULADO'"> {{anulado_x}}</span>
           
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered table-striped table-sm table-responsive">
      <thead>
        <tr>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Cod. Prod</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Liena</th>
          <th class="col-md-4" style="font-size: 11px; text-align: center;">Producto</th>
          <th class="col-md-1" style="font-size: 11px; text-align: center;">Cantidad</th>
          <th class="col-md-3" style="font-size: 11px; text-align: center;">Descuento</th>
          <th class="col-md-2" style="font-size: 11px; text-align: center;">Sub Total</th>
          
        </tr>
      </thead>
        <tbody>
          <tr>
            <td class="col-md-1" style="font-size: 11px; text-align: center;">1</td>
            <td class="col-md-1" style="font-size: 11px; text-align: center;">1</td>
            <td class="col-md-4" style="font-size: 11px; text-align: center;">1</td>
            <td class="col-md-1" style="font-size: 11px; text-align: center;">1</td>
            <td class="col-md-3" style="font-size: 11px; text-align: center;">1</td>
            <td class="col-md-2" style="font-size: 11px; text-align: center;">1</td>
          </tr>
          <tr>
            <th colspan="5" style="font-size: 11px; text-align:right">Suma Total:</th>
            <th style="font-size: 11px; text-align:right">{{suma_total}} Bs</th>
        </tr>        
        <tr>
          <th colspan="5" style="font-size: 11px; text-align:right ">Descuento:</th>
          <th style="font-size: 11px; text-align:right">{{descuento}} Bs</th>
        </tr>
        <tr>
          <th colspan="5" style="font-size: 11px; text-align:right">Total:</th>
          <th style="font-size: 11px; text-align:right">{{total}} Bs</th>
        </tr>
        <tr>
          <th colspan="5" style="font-size: 11px; text-align:right">Efectivo:</th>
          <th style="font-size: 11px; text-align:right">{{efectivo}} Bs</th>
        </tr>
        <tr>
          <th colspan="5" style="font-size: 11px; text-align:right">Cambio:</th>
          <th style="font-size: 11px; text-align:right">{{cambio}} Bs</th>
        </tr>
   
        </tbody>       
    </table>
 
               
                    </div>
                  
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="cerrarModal('ver_detalle')"
                        >
                            Cerrar
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
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
// Asigna los fonts a pdfmake
pdfMake.vfs = pdfFonts.pdfMake.vfs;
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
            sucursalSeleccionada:0,
            arraySucursal:[],
            buscar:"",
            tipoAccion:1,
            startDate: '',
      endDate: '',
      id_seleccionada_sucursal:0,
      cod_seleccionada_sucursal:'',
      arrayVentas:[],
      arrayDetalle_venta:[],
      recibo_ticket_plana:0,
       // datos de vista detalle venta
       cod_cliente:'',
       nom_cliente:'',
       nom_facturar:'',
       num_documento:'',
       correo:'',
       num_identificacion:'',
       num_reci_fac:'',
       fecha_x:'',
       hora_x:'',
       anulado_x:'',
       suma_total:'',
       descuento:'',
       total:'',
       efectivo:'',
       cambio:'',
       nom_user:'',
       tipo_per_emp:'',
       //---
       array_nombre_des:[],
        };
    },

    watch: {
    sucursalSeleccionada(valor) {
      if (valor !== 0) {
        let sucursal = this.arraySucursal.find(element => element.codigo === valor);

        if (sucursal) {
          this.id_seleccionada_sucursal = sucursal.id_sucursal;
          this.cod_seleccionada_sucursal = sucursal.codigo;
        }

        console.log(this.id_seleccionada_sucursal);
        console.log(this.cod_seleccionada_sucursal);
        console.log(this.startDate);
        console.log(this.endDate);
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

            ///////////////////////////////funciones para la venta///////////////////////////////////////////////////////
    generarPDF(nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1) {
      // Define el contenido del PDF

 // Crea el cuerpo de la tabla dinámicamente
 const tableBody = [
    // Agrega los encabezados de la tabla
    [
      { text: 'CANT.', style: 'tableHeader_2' }, 
      { text: 'DESCRIPCIÓN', style: 'tableHeader_2' }, 
      { text: 'P.U.', style: 'tableHeader_2' }, 
      { text: 'TOT.', style: 'tableHeader_2' }
    ]
  ];

  // Itera sobre los datos y agrega filas a la tabla
  array_recibo.forEach(item => {
    tableBody.push([
      { text: item.cantidad_venta, fontSize: 8, alignment: 'center' },
      { text: item.leyenda, fontSize: 8, alignment: 'left' },
      { text: item.precio_unitario, fontSize: 8, alignment: 'center' },
      { text: (item.cantidad_venta * item.precio_unitario).toFixed(2), fontSize: 8, alignment: 'center' } // Operación y formato
    ]);
  });

      const documentDefinition = {
        pageMargins: [5, 8, 5, 4], // Configura los márgenes en cero
        pageSize: {
    width: 80 * 2.83465, // Ancho en puntos (conversión a puntos desde mm)
    height: 'auto',
    columnGap: 2,
  },
 
      content: [
      {
        text: nom_empresa,
      
        style: 'header'
      },
      {
        text: direccionMayusculas,
    
        style: 'header'
      },
      {
        text: nomsucursal,
   
        style: 'header'
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 2, space: 3 } } // Línea punteada
        ],
        margin: [1, 5, 1, 5]
      },
      {
        text: 'TICKETT Nro.:'+'           '+nuevoComprobante,   
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'FECHA:'+'                       '+fecha+'   HORA:  '+hora,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'NIT/CI.:'+'                       '+num_documento,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        text: 'NOMBRE:'+'                   '+nom_a_facturar,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 2, space: 3 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        style: 'tableExample',
        table: {
          headerRows: 1,
          widths: ['12%', '54%', '17%', '17%'], // Ajusta los anchos de las columnas
          body: tableBody
        },
        layout: 'noBorders'
		},
        {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 2, space: 3 } } // Línea punteada
        ],
        margin: [6, 4, 8, 4]
      },
      {
        text: 'IMPORTE TOTAL: Bs.   '+total_sin_des,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'DESCUENTO: Bs.   '+descuento_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'IMPORTE A PAGAR: Bs.   '+total_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      
      {
        text: 'IMPORTE NO VALIODO PARA CRÉDITO FISCAL.',      
        alignment: 'left',fontSize: 8, margin: [6, 4, 6, 4]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 2, space: 3 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        text: 'PAGO EN EFECTIVO: Bs.   '+efectivo_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'CAMBIO: Bs.   '+cambio_venta,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },

      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 2, space: 3 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        text: '* CODIGO DE CONTROL: '+nuevoComprobante,    
        style: 'datos_f'
      },
      {
        text: '* Valido desde '+ fecha +' hasta '+fechaMas7Dias,    
        style: 'datos_f'
      },
      {
        text: '* Ticket para el cambio valido hasta '+ fecha+' en la misma tienda, términos y codiciones según politica de cambios y devoluiones',
        style: 'datos_f'
      },
      {
        text: '* Atención al cliente whatsapp '+numero_referencia,
        style: 'datos_f'
      },
      {
        text: 'Usuario: '+ nombreCompleto_1,
        style: 'datos_f'
      },
      
        ],
        styles: {
          header: {
            fontSize: 8,
            bold: true,
            alignment: 'center',         
          },
          header_1: {
            fontSize: 8,
            bold: true,
            alignment: 'right', 
                    
          },
          datos_f: {
            fontSize: 8,
            bold: true,
            alignment: 'left',         
          },
          tableExample: {
			margin: [1, 6, 1, 6],
           
		},
        tableHeader_1: {
        bold: true,
        fontSize: 8,
            bold: true,
            alignment: 'justify',
      },
      tableHeader_2: {
        bold: true,
        fontSize: 8,
            bold: true,
            alignment: 'center',
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open();
    },
  
    generarFacPlana(cod_cliente,numero_identificacion,respuesta_total,nom_empresa,direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1) {
  
  // Crea el cuerpo de la tabla dinámicamente
 const tableBody = [
    // Agrega los encabezados de la tabla
    [
      { text: 'Código Producto / Servicio.', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Descripción', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Vencimiento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' }, 
      { text: 'Lote', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Linea', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Cantidad', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Precio Unitario', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Venta Total', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'Descuento', style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
      { text: 'SubTotal' , style: 'tableHeader_2',alignment: 'center',fillColor: '#d3d3d3' },
    ]
  ];
  
  // Itera sobre los datos y agrega filas a la tabla
  array_recibo.forEach(item => {
    tableBody.push([
      { text: item.cod_prod, fontSize: 8, alignment: 'left' },
      { text: item.leyenda, fontSize: 8, alignment: 'left' },
      { text: item.fecha_vencimiento, fontSize: 8, alignment: 'left' },
      { text: item.lote, fontSize: 8, alignment: 'left' },
      { text: item.linea_nombre, fontSize: 8, alignment: 'left' },
      { text: item.cantidad_venta, fontSize: 8, alignment: 'right' },
      { text: item.precio_unitario, fontSize: 8, alignment: 'right' },
      { text: item.tot, fontSize: 8, alignment: 'right' },
      { text: item.descuento, fontSize: 8, alignment: 'right' },
      
      { text: (item.tot - item.descuento).toFixed(2), fontSize: 8, alignment: 'right' } // Operación y formato
    ]);
  });
  // Agrega las filas con colspan al final del tableBody
tableBody.push(
  [
    { text: 'SubTotal', colSpan: 9, fontSize: 8, alignment: 'right', border: [false, true, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: total_sin_des, fontSize: 8, alignment: 'right' }
  ],
  [
    { text: 'Descuento', colSpan: 9, fontSize: 8, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: descuento_venta, fontSize: 8, alignment: 'right' }
  ],
  [
    { text: 'Total', colSpan: 9, fontSize: 8, alignment: 'right', border: [false, false, true, false] },
    {}, {}, {}, {}, {}, {}, {}, {},
    { text: total_venta , fontSize: 8, alignment: 'right' }
  ]
);
      const docDefinition = {
    pageSize: 'LETTER', // Tamaño carta
    pageMargins: [25, 30, 25, 30], // Márgenes: [left, top, right, bottom]
    content: [

    {
			
			table: {
				widths: [120,'*',90, 100],
				body: [
					[{text: nom_empresa ,fontSize: 9},
          { },{text: 'IDENTIFICACIÓN ',fontSize: 9},{text: numero_identificacion ,fontSize: 9}
          ],
					[{text: nomsucursal,fontSize: 9},{ },
           {text: 'RECIBO Nº:',fontSize: 9},
            {text: nuevoComprobante,fontSize: 9}, 
            ],
            [{text: direccionMayusculas+' \n'+'TELEFONO '+numero_referencia ,fontSize: 9},{ },
           {text: 'COD DE CONTROL:',fontSize: 9},
            {text: nuevoComprobante ,fontSize: 9}, 
            ],
				]
			},
      layout: 'noBorders'
		},
    {text: 'RECIBO', style: 'header' },
    {
			
			table: {
				widths: [90,120,'*',60,105],
				body: [
					[{text: 'Fecha:',fontSize: 9},
          {text: fecha+' '+ hora ,fontSize: 9},{ },
          {text: 'NIT/CI/CEX:',fontSize: 9},
          {text: num_documento,fontSize: 9},         
          ],
					[{text: 'Nombre/Razón Social:',fontSize: 9},
          {text: nom_a_facturar,fontSize: 9},{ },
          {text: 'Cod. Cliente:',fontSize: 9},
          {text: cod_cliente,fontSize: 9},         
          ],
				]
			},
      layout: 'noBorders'
		},
    {
        style: 'tableExample',
        margin:[0,10,0,0],
        table: {
          headerRows: 1,
          widths: [50,'*',55,34,32,37,40,40,43,40], // Ajusta los anchos de las columnas
          body: tableBody         
        },
      
		},
    
    {
      text: respuesta_total ,fontSize: 8
    },
    {
      text:'Valido desde '+ fecha +' hasta '+fechaMas7Dias+',valor de vigencia para el recibo para el cambio valido hasta '+ fecha+' en la misma tienda, términos y codiciones según politica de cambios y devoluiones'
      ,fontSize: 8
    },
   
      {
        text: 'Usuario: '+ nombreCompleto_1,
        fontSize: 8
      },
    ],
    styles: {
      header: {
        fontSize: 13,
        bold: true,
        color: 'black',
        alignment: 'center',
        margin:[0,10,0,10]
      },
      cabeza: {
        fontZise: 9,

      },
      tableExample:{
        fontSize: 9,
        bold: true,
      },
		tableHeader: {
			bold: true,
			fontSize: 9,
			color: 'black',
     // margin:[ 0, 0, 100,0]
		},
    tableHeader2 : {
			bold: true,
			fontSize: 8,
			color: 'black',
      margin:[ 180, 0, 20,0] 
		},
    tableHeader3 : {
			bold: true,
			fontSize: 8,
			color: 'black',
      margin:[ 50, 0, 10,0] 
		}
    }
  };
 // margin: [left, top, right, bottom]
  // Generar y abrir el PDF
  pdfMake.createPdf(docDefinition).open();
},

listarDetalle_producto_x(id,tipo_per_emp) {
            let me = this;           
            var url = "/detalle_venta_2/verCliente_x_venta?id_venta="+id+"&tipo_per_emp="+tipo_per_emp;
            axios
                .get(url)
                .then(function (response) {
                    var respuesta_1 = response.data.descuento;                    
                    me.array_nombre_des=respuesta_1;
                    console.log(me.array_nombre_des);                 
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
        },


    listarVentas(page){
        let me=this;       
        var url = "/detalle_venta_2/index?page="+page+"&buscar=" +me.buscar+"&id_sucursal="+me.id_seleccionada_sucursal+"&codigo_tienda_almacen="+me.cod_seleccionada_sucursal+"&startDate="+me.startDate+"&endDate="+me.endDate;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.pagination = respuesta.pagination;
                    me.arrayVentas = respuesta.ventas_show.data;
                    console.log(me.arrayVentas);
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
            me.listarVentas(page);
        },
        
        detalleVenta(cod_cliente,validor_12,id,tipo,direccion,razon_social,nro_comprobante_venta,fecha_formateada,hora_formateada,
        num_documento,nom_a_facturar,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fecha_mas_siete,
        numero_referencia,nombre_completo){
          let me=this;  
          console.log(validor_12);    
        var url = "/detalle_venta_2/re_imprecion?id_venta="+id+"&tipofactura="+tipo+"&venta="+total_venta;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;   
                    let respuesta_empresa = respuesta.datos_empresa;
                    let respuesta_total = respuesta.datoTexto_v2;
                   console.log(respuesta);
                    let nit =respuesta_empresa[0].nit;  
                    let nom_empresa =respuesta_empresa[0].nom_empresa;             
                     me.arrayDetalle_venta = respuesta.detalle_venta;             
               
                     if (validor_12===1) {
                      me.generarPDF(nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo);  
                     } 
                     if (validor_12===2) {
                      me.generarFacPlana(cod_cliente,id,respuesta_total,nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo);
                     } 
                     
                })
                .catch(function (error) {
                    error401(error);
                    console.log(error);
                });
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
                case "ver_detalle_venta":{
                  me.tituloModal = "Detalle de venta de la sucursal "+data.razon_social+" codigo "+data.cod;
                  //console.log(data);
                  me.cod_cliente=data.id_cliente;
                  me.nom_cliente=data.nombre_completo;
                  me.nom_facturar=data.nom_a_facturar;
                  me.num_documento=data.num_documento;
                  me.correo=data.correo;
                  me.num_identificacion=data.id;
                  me.num_reci_fac=data.nro_comprobante_venta;
                  me.fecha_x=data.fecha_formateada;
                  me.hora_x=data.hora_formateada;
                  if(data.anulado===0){
                  me.anulado_x="ACTIVO";    
                  }else{
                    me.anulado_x="ANULADO";  
                  }                  
                  me.suma_total=data.total_sin_des;
                  me.descuento=data.descuento_venta;
                  me.total=data.total_venta;
                  me.efectivo=data.efectivo_venta;
                  me.cambio=data.cambio_venta;
                  me.nom_user=data.name;
                  me.tipo_per_emp=data.tipo_per_emp;
                  me.listarDetalle_producto_x(data.id,data.tipo_per_emp);
                  me.classModal.openModal("ver_detalle");
                  break;
                }

                case "recibo_r": {
                  me.recibo_ticket_plana=1;
                    console.log(data);
                    if (data.tipo_venta_reci_fac==="RECIBO") {
                      me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo
                    );
                    }
                   
                    break;
                }

                case "pdf_plana":{
                  me.recibo_ticket_plana=2;
                  console.log(data.id);
                  if (data.tipo_venta_reci_fac==="RECIBO") {
                    me.detalleVenta(data.id_cliente,me.recibo_ticket_plana,data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo
                    );
              
                  }         
                 
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
        this.sucursalFiltro();
        this.fecha_inicial();
        this.classModal.addModal("registrar");

        this.classModal.addModal("ver_detalle");
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
