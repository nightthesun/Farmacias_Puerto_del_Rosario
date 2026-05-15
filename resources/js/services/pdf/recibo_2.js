import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';


//pdfMake.vfs = pdfFonts.pdfMake.vfs;
pdfMake.vfs = pdfFonts.vfs;

export function  generarPDF_recibo(direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,nombreCompleto_1,nombre_empresa,tipo_venta_1,monto_vale_1,monto_apagar_1) {
      // Define el contenido del PDF
try {
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
      { text: item.cant, fontSize: 7, alignment: 'center' },
      { text: item.descrip, fontSize: 7, alignment: 'left' },
      { text: item.p_u, fontSize: 7, alignment: 'center' },
      { text: (item.cant * item.p_u).toFixed(2), fontSize: 7, alignment: 'center' } // Operación y formato
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
        text: nombre_empresa,
      
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
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
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
        text: 'TIPO VENTA:'+'              '+tipo_venta_1,    
        style: 'datos_f',
        margin: [ 6, 1, 6, 1 ]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
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
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 8, 4]
      },
      {
        text: 'IMPORTE TOTAL: Bs.   '+total_sin_des.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'DESCUENTO: Bs.   '+descuento_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'TOTAL CON DESC.: Bs.   '+total_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'VALE: Bs.   '+monto_vale_1.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'IMPORTE A PAGAR: Bs.   '+monto_apagar_1,      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      
      {
        text: 'IMPORTE NO VALIODO PARA CRÉDITO FISCAL.',      
        alignment: 'left',fontSize: 8, margin: [6, 4, 6, 4]
      },
      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
        ],
        margin: [6, 4, 6, 4]
      },
      {
        text: 'PAGO EN EFECTIVO: Bs.   '+efectivo_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },
      {
        text: 'CAMBIO: Bs.   '+cambio_venta.toFixed(2),      
        style: 'header_1',margin: [0, 0, 8, 0]
      },

      {
        canvas: [
          { type: 'line', x1: 0, y1: 0, x2: 226.8, y2: 0, lineWidth: 1, dash: { length: 1, space: 2 } } // Línea punteada
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
            fontSize: 7,
            bold: true,
            alignment: 'center',         
          },
          header_1: {
            fontSize: 7,
            bold: true,
            alignment: 'right', 
                    
          },
          datos_f: {
            fontSize: 7,
            bold: true,
            alignment: 'left',         
          },
          tableExample: {
			margin: [1, 6, 1, 6],
           
		},
        tableHeader_1: {
        bold: true,
        fontSize: 7,
            bold: true,
            alignment: 'justify',
      },
      tableHeader_2: {
        bold: true,
        fontSize: 7,
            bold: true,
            alignment: 'center',
      }
        }
      };

      // Genera el PDF y abre una nueva ventana con el documento
      pdfMake.createPdf(documentDefinition).open();
} catch (error) {
     return error;
} 
    }