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
    <a class="dropdown-item" href="#"><i style="color: black;" class="fa fa-eye" aria-hidden="true"></i> Ver estado</a>
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
        text: 'FARMACIA PUERTO DEL ROSARIO',
      
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
  
    generarFacPlana() {
  const docDefinition = {
    pageSize: 'LETTER', // Tamaño carta
    pageMargins: [25, 30, 25, 30], // Márgenes: [left, top, right, bottom]
    content: [

    {
			
			table: {
				widths: [120,'*',90, 100],
				body: [
					[{text: 'PUERTO DEL ROSARIO',fontSize: 9},
          { },{text: 'NIT:',fontSize: 9},{text: '1020603028',fontSize: 9}
          ],
					[{text: 'SUCURSAL EL ALTO',fontSize: 9},{ },
           {text: 'RECIBO Nº:',fontSize: 9},
            {text: '2024984',fontSize: 9}, 
            ],
            [{text: 'DIRECION ZONA NOSE SABE FSAFSAFSAS1231321321232132132\n telefono 123132132',fontSize: 9},{ },
           {text: 'COD DE CONTROL:',fontSize: 9},
            {text: '45CF3B8E784D40176D6865AECBDCD4866B53D917C3750AD4C2A3D8E74',fontSize: 9}, 
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
          {text: '04/02/2024 04::40 PM',fontSize: 9},{ },
          {text: 'NIT/CI/CEX:',fontSize: 9},
          {text: '6912345',fontSize: 9},         
          ],
					[{text: 'Nombre/Razón Social:',fontSize: 9},
          {text: 'CHOQUE',fontSize: 9},{ },
          {text: 'Cod. Cliente:',fontSize: 9},
          {text: '6912345',fontSize: 9},         
          ],
				]
			},
      layout: 'noBorders'
		},
    {
			margin:[0,10,0,10],
			table: {
		    widths: [50,'*',55,34,32,36,40,40,40,40],
				body: [
					[{text: 'Código Producto / Servicio',fontSize: 8 ,alignment: 'center',fillColor: '#d3d3d3'},
          {text: 'Descripción',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Vencimiento',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Lote',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Linea',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Cantidad',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Precio Unitario',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Venta Total',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'Descuento',fontSize: 8 ,alignment: 'center' ,fillColor: '#d3d3d3'},
          {text: 'SubTotal',fontSize: 8 ,alignment: 'center',fillColor: '#d3d3d3'},       
          ],
					[{text: 'FPR0477',fontSize: 8},
          {text: 'DEXTROMETORFANO 15MG CAJA X 100 - COMPRIMIDOS',fontSize: 8 },          
          {text: '29/20/2000',fontSize: 8},
          {text: '23121ds',fontSize: 8},
          {text: 'INTI',fontSize: 8},
          {text: '2',fontSize: 8,alignment: 'right'},
          {text: '1.50',fontSize: 8,alignment: 'right' },
          {text: '3.00',fontSize: 8,alignment: 'right' },       
          {text: '0.00',fontSize: 8,alignment: 'right' },
          {text: '3.00',fontSize: 8,alignment: 'right' },  
        ],
        [
          
            { text: 'SubTotal', colSpan: 9, fontSize: 8,alignment: 'right',	border: [false, true, true, false] },
            {}, {}, {}, {}, {}, {}, {}, {},
            { text: '10.00', fontSize: 8, alignment: 'right' }
          ],
          [
            { text: 'Descuento', colSpan: 9, fontSize: 8,alignment: 'right',border: [false, false, true, false]  },
            {}, {}, {}, {}, {}, {}, {}, {},
            { text: '0.00', fontSize: 8, alignment: 'right' }
          ],
          [
            { text: 'Total', colSpan: 9, fontSize: 8,alignment: 'right',border: [false, false, true, false]  },
            {}, {}, {}, {}, {}, {}, {}, {},
            { text: '10.00', fontSize: 8, alignment: 'right' }
          ]
				]
			},
     
		},
    {
      text:'Son Dies 00/100 Bolivianos',fontSize: 8
    },
    {
      text:'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.'
      ,fontSize: 8
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
        
        detalleVenta(id,tipo,direccion,razon_social,nro_comprobante_venta,fecha_formateada,hora_formateada,
        num_documento,nom_a_facturar,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fecha_mas_siete,
        numero_referencia,nombre_completo){
          let me=this;       
        var url = "/detalle_venta_2/re_imprecion?id_venta="+id+"&tipofactura="+tipo;
        axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;   
                    let respuesta_empresa = respuesta.datos_empresa;
                   
                    let nit =respuesta_empresa[0].nit;  
                    let nom_empresa =respuesta_empresa[0].nom_empresa;             
                     me.arrayDetalle_venta = respuesta.detalle_venta;             
                     console.log( me.arrayDetalle_venta);
                     me.generarPDF(nom_empresa,direccion,razon_social,nro_comprobante_venta,fecha_formateada,
                     hora_formateada,num_documento,nom_a_facturar,me.arrayDetalle_venta,total_sin_des,descuento_venta,total_venta,
                    efectivo_venta,cambio_venta,fecha_mas_siete,numero_referencia,nombre_completo);  
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

                case "recibo_r": {
                    console.log(data);
                    me.detalleVenta(data.id,data.tipo_venta_reci_fac,data.direccion,data.razon_social,data.nro_comprobante_venta,
                    data.fecha_formateada,data.hora_formateada,data.num_documento,data.nom_a_facturar,data.total_sin_des,
                    data.descuento_venta,data.total_venta,data.efectivo_venta,data.cambio_venta,data.fecha_mas_siete,
                    data.numero_referencia,data.nombre_completo
                    );
                    break;
                }

                case "pdf_plana":{
                  me.generarFacPlana();
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
    
    
    },
};
</script>
<style scoped>
.error {
    color: red;
    font-size: 10px;
}
</style>
