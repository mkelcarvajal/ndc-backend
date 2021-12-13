const bcryptjs = require('bcryptjs');
const jwt = require('jsonwebtoken');
const fs = require("fs");
const PDFDocument = require("pdfkit");
var moment = require('moment');  
moment.locale('es');

const encryptPassword = (password) => {
    const salt = bcryptjs.genSaltSync();
    return bcryptjs.hashSync(password, salt);
}

const generateJWT = async (id = '') => {
    return new Promise((resolve, reject) => {
        const payload = { id };
        jwt.sign(payload, process.env.SECRETORPUBLICKEY, {
        //    expiresIn: '4h'
        }, (error, token) => {
            if (error) {
                reject('Token can not was possible generate');
            } else {
                resolve(token);
            }
        });

    });
}


const generateCertificate = async (response) => {
    const doc = new PDFDocument({
        layout: "landscape",
        size: "A4",
    });
    doc.pipe(fs.createWriteStream(`./certificados/${response.nombrecompleto}.pdf`));

    doc.image("images/diploma.jpg", 0, 0, { width: 842 });

    // doc.fontSize(60).text(name, 20, 265, {
    //   align: "center"
    // });

    doc.moveDown();
    doc.moveDown();
    doc.fontSize(16).text(`CERTIFICADO`, {
    align: 'center'
    });

    doc.moveDown();
    doc.moveDown();

    doc.fontSize(14).text('Se otorga el presente certificado al/la trabajador/a:', {
    align: "center"
    });


    doc.moveDown();

    doc.fontSize(15).text(response.nombrecompleto, {
    align: "center"
    });

    doc.moveDown();
    doc.moveDown();

    doc.fontSize(14).text('Por cumplir el curso de:', {
    align: "center"
    });

    doc.moveDown();

    doc.fontSize(14).text(response.nombrecurso, {
    align: "center"
    });

    doc.moveDown();

    if (response.fechainscripcion !== null) {
        doc.fontSize(14).text(`Realizado el: ${moment(response.fechainscripcion).format('LL')}`, {
        align: "center"
        });
    }
    doc.fontSize(14).text(`Con fecha de vigencia hasta: ${moment(response.fechavencimiento).format('LL')}`, {
    align: "center"
    });

    doc.moveDown();
    doc.fontSize(14).text(`El presente curso fue realizado por la empresa`, {
    align: "center"
    });
    doc.fontSize(14).text('NDC PERSSO GROUP', {
    align: "center"
    });
    doc.moveDown();
    doc.moveDown();

    doc.fontSize(14).text('GERENCIA DE SEGURIDAD Y SALUD OCUPACIONAL', {
    align: "center"
    });
    doc.moveDown();
    doc.fontSize(14).text('TECK CARMEN DE ANDACOLLO', {
    align: "center"
    });

    doc.end();
}


module.exports = {
    encryptPassword,
    generateJWT,
    generateCertificate
}
