const {request, response} = require('express');
const User = require('../models/user').default;
const {createUserRepositoryMicrosoft, createUserRepositoryMicrosoftGlobal, getUserByIdRepository, generateUserCertificateRepository, getAllHistoricRepository, getUserByData} = require('../repository/user.repository');
const {encryptPassword} = require('../helpers/utils');
const sgMail = require('@sendgrid/mail')
const fs = require("fs");
const path = require('path');
const PDFDocument = require("pdfkit");
const cron = require('node-cron');
const moment = require('moment');
moment.locale('es');
const qr = require("qrcode");
const axios = require('axios');
const pool = require('../database/configpg');

const getAllUser = async (req = request, res = response) => {

    const {limit = 5, from = 0} = req.query;
    const query = {state: true};

    const [totalUsers, users] = await Promise.all([
        User.countDocuments(query),
        User.find(query)
            .skip(parseInt(from))
            .limit(parseInt(limit))
    ]);

    res.json({
        totalUsers,
        users
    });
}

const getUserById = async (req = request, res = response) => {

    const id = req.params.id;

    const user = await getUserByIdRepository(id);

    res.json({
        user
    });
}

const updateUser = async (req = request, res = response) => {
    const {id} = req.params.id;
    const {_id, password, google, email, ...user} = req.body

    if (password) {
        encryptPassword(password);
    }

    const updateUser = await User.findById(id);
    console.log(updateUser);
    res.json({
        msg: 'put API - Controller',
        updateUser
    });
}

const createUserMicrosoft = async (req = request, res = response) => {
    try {

        // Save in DB
        const result = await createUserRepositoryMicrosoft(req);

        // sgMail.setApiKey(process.env.SENDGRID_API_KEY)
        // const msg = {
        // to: email, // Change to your recipient
        // from: 'maikel.carvajal@egt.cl', // Change to your verified sender
        // subject: 'Bienvenido al Sistema',
        // text: 'Registro exitoso, ahora podras acceder a todas las funcionalidades',
        // html: '<strong>Registro exitoso</strong>',
        // }
        // sgMail
        // .send(msg)
        // .then(() => {
        //     console.log(`Registro exitoso email -> ${email}`);
        // })
        // .catch((error) => {
        //     console.error(error);
        // })

        res.json({
            msg: 'post API - Controller',
            result
        });
    } catch (e) {
        console.log(e);
    }
}

const createUserMicrosoftGlobal = async (req = request, res = response) => {
    try {

        // Save in DB
        const result = await createUserRepositoryMicrosoftGlobal(req);

        // sgMail.setApiKey(process.env.SENDGRID_API_KEY)
        // const msg = {
        // to: email, // Change to your recipient
        // from: 'maikel.carvajal@egt.cl', // Change to your verified sender
        // subject: 'Bienvenido al Sistema',
        // text: 'Registro exitoso, ahora podras acceder a todas las funcionalidades',
        // html: '<strong>Registro exitoso</strong>',
        // }
        // sgMail
        // .send(msg)
        // .then(() => {
        //     console.log(`Registro exitoso email -> ${email}`);
        // })
        // .catch((error) => {
        //     console.error(error);
        // })

        res.json({
            msg: 'post API - Controller',
            result
        });
    } catch (e) {
        console.log(e);
    }
}

const deleteUser = async (req = request, res = response) => {

    try {
        const id = req.params.id;
        const user = await User.findByIdAndUpdate(id, {state: false});
        const userPetition = req.userPetition;
        await user.save();
        res.json({
            user,
            userPetition
        });
    } catch (e) {
        console.log(e);
    }
}

const patchUser = (req = request, res = response) => {
    res.json({
        msg: 'patch API - Controller'
    });
}

const getAllHistoric = async (req = request, res = response) => {
    const result = await getAllHistoricRepository();
    res.json({
        result
    });
}

async function buildPDF(response, dataCallback, endCallback) {
    const doc = new PDFDocument({ bufferPages: true, layout: "landscape", size: "A4", });
  
    doc.on('data', dataCallback);
    doc.on('end', endCallback);

    const qrimage = await qr.toDataURL(`https://ndc-backend.herokuapp.com/api/users/verificarcertificados/${response.idcurso}/${response.puestotrabajo}/${response.rut}/`);

    //persona nueva
    if (response.idcurso === 'cc49457f-da5d-40c4-8e06-271f7bed6819') {
        
        doc.image("./images/diploma.jpg", 0, 0, { width: 842 });
    
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
    
        if (response.fechafinalizacion !== null) {
            doc.fontSize(14).text(`Realizado el: ${moment(response.fechafinalizacion).format('LL')}`, {
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

        doc.moveDown();
        doc.moveDown();

        doc.image(qrimage, 400, 470, {fit: [60, 60], align: 'center', valign: 'center'});
        
    }

    // Inducción de Mantención - TECK Carmen de Andacollo
    if (response.idcurso === '65c61b1a-04c1-46e6-9d6e-41576a0ce14f') {
        
        doc.image("./images/diploma.jpg", 0, 0, { width: 842 });
    
        doc.moveDown();
        doc.moveDown();
        doc.moveDown();
        doc.moveDown();
        doc.fontSize(24).text(`CERTIFICADO`, {
        align: 'center'
        });
    
        doc.moveDown();
        doc.moveDown();
    
        doc.fontSize(24).text('Se otorga el presente certificado a:', {
        align: "center"
        });        
        doc.fontSize(24).text(response.nombrecompleto, {
        align: "center"
        });

        doc.fontSize(24).text(`Por completar la Inducción de Mantención - TECK Carmen de Andacollo realizado ${moment(response.fechafinalizacion).format('LL')}`, {
        align: "center"
        });
    
        doc.moveDown();
        doc.fontSize(24).text(`El presente certificado es valido hasta: ${moment(response.fechavencimiento).format('LL')}`, {
        align: "center"
        });    
        doc.moveDown();
        doc.moveDown();

        doc.image(qrimage, 400, 470, {fit: [60, 60], align: 'center', valign: 'center'})
        .text('Centered', 430, 0);
        
    }
    doc.end();
  }

const generateUserCertificate = async (req = request, res = response) => {
    try {

        const response = JSON.parse(req.query.data);
        const stream = res.writeHead(200, {
            'Content-Type': 'application/pdf',
            'Content-Disposition': `attachment;filename=${response.nombrecompleto}.pdf`,
        });

        await buildPDF(response ,(chunk) => stream.write(chunk), () => stream.end());

    } catch (e) {
        console.log(e);
    }
}

const verifyCertificate = async (req = request, res = response) => {
    try {
        const result = await getUserByData(req.params);
        const nombrecompleto = result.user.nombrecompleto;
        const rut = result.user.rut;
        const fechavencimiento = moment(result.user.fechavencimiento).format('LL');
        const nombrecurso = result.user.nombrecurso;
        const fecha1 = moment(result.user.fechavencimiento).format('YYYY-MM-DD');
        const fecha2 = moment().format('YYYY-MM-DD');
        if (fecha1 >= fecha2) {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado.html'), 'utf8', function (err,data) {
                if (err) {
                return console.log(err);
                }
                const html = data.replace("{1}", nombrecompleto.toString());
                const test = html.replace("{2}", nombrecurso.toString());
                const test1 = test.replace("{3}", fechavencimiento.toString());
                const test2 = test1.replace("{4}", rut.toString());
                res.type('.html')
                res.write(test2);
            });
        } else {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err,data) {
                if (err) {
                return console.log(err);
                }
                res.type('.html')
                res.write(data);
            });
        }
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err,data) {
            if (err) {
            return console.log(err);
            }
            res.type('.html')
            res.write(data);
        });
    }
}

cron.schedule('15 3,8,14,20 * * *', async function () {
    let userCertificates = [];
    let certificates = [];
    let users = [];
    let result = await axios({
        url: 'https://us-api.365.systems/odata/v2/Users', //your url
        method: 'GET',
        responseType: 'json', // important
        auth: {
        username: 'api',
        password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
        }
    });
    users = result.data;
    result =  await axios({
        url: 'https://us-api.365.systems/odata/v2/Certificates', //your url
        method: 'GET',
        responseType: 'json', // important
        auth: {
        username: 'api',
        password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
        }
    });
    certificates = result.data;
    certificates.value.forEach(async (element) => {
        users.value.forEach(async (elementUser) => {
        if (element.UserId === elementUser.Id) {
            if ('65c61b1a-04c1-46e6-9d6e-41576a0ce14f' === element.CourseId) {
            const userCertificate = {
                "nombrecompleto": elementUser.Title,
                "rut": elementUser.Department,
                "puestotrabajo": elementUser.JobTitle,
                "idcurso": element.CourseId,
                "correopersonal": elementUser.LoginName.replace("i:0#.f|membership|", ""),
                "fechafinalizacion": element.Issued,
                "fechavencimiento": element.Expiry,
                "nombrecurso": "Inducción de Mantención - TECK Carmen de Andacollo"
            }
            userCertificates.push(userCertificate);
            }
    
            if ('cc49457f-da5d-40c4-8e06-271f7bed6819' === element.CourseId) {
            const userCertificate = {
                "nombrecompleto": elementUser.Title,
                "rut": elementUser.Department,
                "puestotrabajo": elementUser.JobTitle,
                "idcurso": element.CourseId,
                "correopersonal": elementUser.LoginName.replace("i:0#.f|membership|", ""),
                "fechafinalizacion": element.Issued,
                "fechavencimiento": element.Expiry,
                "nombrecurso": "Inducción de Persona Nueva - TECK Carmen de Andacollo"
            }
            userCertificates.push(userCertificate);
            }
        }
        });
    });

    const currentArray = userCertificates.filter(x => moment.utc(x.fechafinalizacion).format().toString().split("T")[0] === moment.utc(new Date()).format().toString().split("T")[0]);
    currentArray.forEach(async (element) => {
        const fixFecha = moment(element.fechavencimiento).format().split("T");
        const queryResult = await pool.query("SELECT * FROM historicocert WHERE to_char(fechavencimiento , 'YYYY-MM-DD') = $1 AND rut = $2 AND idcurso = $3", [fixFecha[0], element.rut, element.idcurso]);
        if (queryResult.rowCount === 0) {
            await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA",!!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso,element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
            console.log('ejecutado', element);
        }
    })

    console.log('CRON EJECUTADO');

});

// cron.schedule('* * * * * *', function () {
//     //leo toda la LMS
//     //veo todos los certificados emitidos desde hace 3 dias atras -> me devuelve un nuevo array
//     //verifico si los certificados de la lms estan en la bd, si no estan entonces lo inserto
// });
  

module.exports = {
    getAllUser,
    getUserById,
    updateUser,
    createUserMicrosoft,
    createUserMicrosoftGlobal,
    deleteUser,
    patchUser,
    getAllHistoric,
    generateUserCertificate,
    verifyCertificate
}
