import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';

//pdfMake.vfs = pdfFonts.pdfMake.vfs;
pdfMake.vfs = pdfFonts.vfs;

export function generarPDF_factura_a4(
  direccionMayusculas,nomsucursal,nuevoComprobante,fecha,hora,num_documento,nom_a_facturar,array_recibo,
  total_sin_des,descuento_venta,total_venta,efectivo_venta,cambio_venta,fechaMas7Dias,numero_referencia,
  nombreCompleto_1,nombre_empresa,actividad_economica,num_auto,cod_autorizacion,fecha_e_2,ciudad_su_1,
  departamento_su_1,numero_factura,cliente_id,descuento_final_2,total_literal,nit_2,tipo_venta_1,
  monto_vale_1,monto_apagar_1,credito_fiscal,leyenda,puntoVenta,url_qr,ambiente,factura_,montoGiftCard,moneda
){

  try {

    let watermark = {};

    if (ambiente === 2) {
      watermark = {
        text: 'SIN VALOR LEGAL',
        color: 'red',
        angle: -45,
        opacity: 0.3,
        bold: true,
        italics: false,
        fontSize: 40
      };
    }

    if (Number(montoGiftCard) === 0) {
      montoGiftCard = "0.00";
    }
   
    const tableBody = [
      [
        { text: 'Código Producto / Servicio.', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'Cantidad', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'Unidad de medida', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'Descripción', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'Precio unitario', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'Descuento', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
        { text: 'SubTotal', style: 'tableHeader_2', alignment: 'center', fillColor: '#d3d3d3' },
      ]
    ];

    let sumador_subtotal = 0;

    array_recibo.forEach(item => {

      let descuento_dosificacion_2 = "";
      let cant = Number(item.cant);
      let p_u = Number(item.p_u);
      let descuento = Number(item.descuento);
      let sub_1 = 0;

      if (Number.isInteger(descuento)) {

        descuento_dosificacion_2 = descuento.toFixed(2);

      } else if (descuento === 0) {

        descuento_dosificacion_2 = "0.00";

      } else {

        descuento_dosificacion_2 = descuento.toFixed(2);
      }

      sumador_subtotal = sumador_subtotal + ((cant * p_u) - descuento);

      sub_1 = (cant * p_u) - descuento;
      
      tableBody.push([
        { text: item.cod_pro, fontSize: 8, alignment: 'left' },
        { text: item.cant, fontSize: 8, alignment: 'right' },
        { text: item.unidad_medida, fontSize: 8, alignment: 'left' },
        { text: item.descrip, fontSize: 8, alignment: 'left' },
        { text: item.p_u, fontSize: 8, alignment: 'right' },
        { text: descuento_dosificacion_2, fontSize: 8, alignment: 'right' },
        { text: sub_1.toFixed(2), fontSize: 7, alignment: 'right' }
      ]);

    });
tableBody.push(

      [
        { text: 'SubTotal ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, true, true, false] },
        {}, {}, {}, {}, {},
        { text: (sumador_subtotal).toFixed(2), fontSize: 7, alignment: 'right' }
      ],
       [
        { text: 'Descuento ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
        {}, {}, {}, {},{},
        { text: Number(descuento_final_2).toFixed(2), fontSize: 7, alignment: 'right' }
      ],

      [
        { text: 'Total ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
        {}, {}, {}, {},{},
        { text: Number(total_venta).toFixed(2), fontSize: 7, alignment: 'right' }
      ],

      [
        { text: 'Monto GIFT CARD ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
        {}, {}, {}, {},{},
        { text: montoGiftCard, fontSize: 7, alignment: 'right' }
      ],

      [
        { text: 'Monto a pagar ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
        {}, {}, {}, {},{},
        { text: Number(total_venta).toFixed(2), fontSize: 7, alignment: 'right' }
      ],

      [
        { text: 'Importe base crédito fiscal ' + moneda, colSpan: 6, fontSize: 7, alignment: 'right', border: [false, false, true, false] },
        {}, {}, {}, {},{},
        { text: Number(total_venta).toFixed(2), fontSize: 7, alignment: 'right' }
      ]

      
    );
  

    const docDefinition = {

      pageSize: 'LETTER',
      pageMargins: [25, 30, 25, 30],

      content: [

        {
          table: {
            widths: [120, '*', 90, 100],
            body: [

              [
                { text: nombre_empresa, fontSize: 8 },
                {},
                { text: 'NIT ', fontSize: 8 },
                { text: nit_2, fontSize: 8 }
              ],

              [
                { text: nomsucursal, fontSize: 8 },
                {},
                { text: 'FACTURA Nº', fontSize: 8 },
                { text: numero_factura, fontSize: 8 },
              ],

              [
                {
                  text:
                    'No. Punto venta ' + puntoVenta +
                    ' \n' + direccionMayusculas +
                    ' \nTelefono: ' + numero_referencia +
                    ' \n' + ciudad_su_1,
                  fontSize: 8
                },
                {},
                { text: 'CÓD. AUTORIZACIÓN', fontSize: 8 },
                { text: num_auto, fontSize: 8 },
              ],

            ]
          },

          layout: 'noBorders'
        },

        { text: factura_+' \n'+credito_fiscal, style: 'header' },    

        {
          table: {
            widths: [90, 120, '*', 60, 105],
            body: [

              [
                { text: 'Fecha:', fontSize: 8 },
                { text: fecha + ' ' + hora, fontSize: 8 },
                {},
                { text: 'NIT/CI/CEX:', fontSize: 8 },
                { text: num_documento, fontSize: 8 },
              ],

              [
                { text: 'Nombre/Razón Social:', fontSize: 8 },
                { text: nom_a_facturar, fontSize: 8 },
                {},
                { text: 'Cod. Cliente:', fontSize: 8 },
                { text: cliente_id, fontSize: 8 },
              ],

            ]
          },

          layout: 'noBorders'
        },

        {
          style: 'tableExample',
          margin: [0, 10, 0, 0],

          table: {
            headerRows: 1,
            widths: [62, 52, 57, '*', 42, 43, 42],
            body: tableBody
          },


        },

        {
          text: 'Son: ' + total_literal + ' ' + moneda,
          fontSize: 8,
          bold: true,
        },

        {
       table: {
  widths: ['*', 85],
  body: [

    [
      {
        text: [
          '"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY."\n',
          leyenda + '\n',
          'Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea.'
        ],
        fontSize: 7,
        alignment: 'justify',
        margin: [0, 10, 5, 0]
      },

      {
        qr: url_qr,
        fit: 80,
        alignment: 'right',
        margin: [0, 0, 0, 0]
      }
    ]

  ]
},

          layout: 'noBorders'
        },

      ],

      watermark: watermark, // Agrega la marca de agua condicionalmente
    styles: {
      header: {
        fontSize: 12,
        bold: true,
        color: 'black',
        alignment: 'center',
        margin:[0,10,0,10]
      },
      cabeza: {
        fontZise: 8,

      },
      tableExample:{
        fontSize: 8,
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
			fontSize: 7,
			color: 'black',
      margin:[ 180, 0, 20,0] 
		},
    tableHeader3 : {
			bold: true,
			fontSize: 7,
			color: 'black',
      margin:[ 50, 0, 10,0] 
		}
    }

    };

    pdfMake.createPdf(docDefinition).open();
    

  } catch (error) {
 
    return error;

  }

}