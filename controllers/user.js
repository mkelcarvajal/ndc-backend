const { request, response } = require('express');
const User = require('../models/user').default;
const { createUserRepositoryMicrosoft, createUserRepositoryMicrosoftGlobal, getUserByIdRepository, getUserByEmailRepository, getAllHistoricRepository, getUserByData, patchHistoricByIdRepository, getUserByRutRepository, getAllUsersRepository, patchUserByIdRepository, getCursoByIdRepository, getCursosByRepository, createCursoRepository, patchCursoByIdRepository, getCursoByClaveRepository, getUserBySerialRepository, getUserVerifyRepository, getAllUsersExternosRepository, createUserExternosRepositoryMicrosoftGlobal, patchUserExternosByIdRepository, deleteUserExternosByIdRepository, getUserExternosByRutRepository, deleteUserByIdRepository} = require('../repository/user.repository');
const { getCursoByIdRepository2 } = require('../repository/curso.repository');
const { encryptPassword } = require('../helpers/utils');
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
const atob = require("atob");


const getAllUser = async (req = request, res = response) => {

    const users = await getAllUsersRepository();
    res.json({
        users
    });
}

const getAllUserExternos = async (req = request, res = response) => {

    const users = await getAllUsersExternosRepository();
    res.json({
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

const getUserByRut = async (req = request, res = response) => {

    const id = req.params.rut;

    const user = await getUserByRutRepository(id);

    res.json({
        user
    });
}

const getUserExternosByRut = async (req = request, res = response) => {

    const id = req.params.rut;

    const user = await getUserExternosByRutRepository(id);

    res.json({
        user
    });
}

const getUserBySerial = async (req = request, res = response) => {

    const id = req.params.id;

    const user = await getUserBySerialRepository(id);

    res.json({
        user
    });
}

const getUserByEmail = async (req = request, res = response) => {

    const email = atob(req.params.email);
    //console.log(email);
    // let usuario = await axios({
    //     url: "https://us-api.365.systems/odata/v2/Users('"+email+"')", //your url
    //     method: 'GET',
    //     responseType: 'json', // important
    //     auth: {
    //         username: 'api',
    //         password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
    //     }
    //     });
    // usuario = usuario.data;


    const user = await getUserByEmailRepository(email);
    res.json({
        user
    });
}


const patchUserById = async (req = request, res = response) => {
    const id = req.params.id;
    const user = await patchUserByIdRepository(id, req.body);
    res.json({
        user
    });
}

const deleteExternoById = async (req = request, res = response) => {
    const id = req.params.id;
    const user = await deleteUserExternosByIdRepository(id);
    res.json({
        user
    });
}

const patchUserExternosById = async (req = request, res = response) => {
    const id = req.params.id;
    const user = await patchUserExternosByIdRepository(id, req.body);
    res.json({
        user
    });
}

const patchHistoricoById = async (req = request, res = response) => {
    const id = req.params.id;
    const user = await patchHistoricByIdRepository(id, req.body);
    res.json({
        user
    });
}

const patchCursoById = async (req = request, res = response) => {
    const id = req.params.id;
    const user = await patchCursoByIdRepository(id, req.body);
    res.json({
        user
    });
}

const updateUser = async (req = request, res = response) => {
    const { id } = req.params.id;
    const { _id, password, google, email, ...user } = req.body

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

const createUserExternosMicrosoftGlobal = async (req = request, res = response) => {
    try {

        // Save in DB
        const result = await createUserExternosRepositoryMicrosoftGlobal(req);

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
        await deleteUserByIdRepository(id);
        res.json({
            msg: "Eliminado con exito"
        });
    } catch (e) {
        res.json({
            msg: "error"
        });
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

    const qrimage = await qr.toDataURL(`https://ndc-backend.crvsoft.cl/api/users/verificarcertificados/${response.idcurso}/${response.puestotrabajo}/${response.rut}/`);

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

        doc.image(qrimage, 400, 470, { fit: [60, 60], align: 'center', valign: 'center' });

    } else if (response.idcurso === '65c61b1a-04c1-46e6-9d6e-41576a0ce14f') {
        // Inducción de Mantención - TECK Carmen de Andacollo

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

        doc.image(qrimage, 400, 470, { fit: [60, 60], align: 'center', valign: 'center' });

    } else {

        // const curso = await axios({
        //     url: 'https://us-api.365.systems/odata/v2/Courses/IncludeDeleted()', //your url
        //     method: 'GET',
        //     responseType: 'json', // important
        //     auth: {
        //         username: 'api',
        //         password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
        //     }
        // });

        const qrimage = await qr.toDataURL(`https://ndc-backend.crvsoft.cl/api/users/verificarcertificadosLms/${response.courseId}/${response.user.correondc}/${response.user.rut}/`);

        const curso = await getCursoByIdRepository(response.courseId);

        const path = './uploads/' + response.courseId + '.png'

        if (fs.existsSync(path)) {
            doc.image('./uploads/' + response.courseId + '.png', 0, 0, { width: 842 });
        } else {
            doc.image("./images/base.png", 0, 0, { width: 842 });
        }



        doc.moveDown();
        doc.moveDown();
        doc.moveDown();
        doc.moveDown();
        doc.moveDown();
        doc.moveDown();

        doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('Se otorga el presente certificado a:', {
            align: "center"
        });

        doc.moveDown();


        doc.font('./fonts/calibri-bold.ttf').fontSize(18).text(response.user.nombrecompleto, {
            align: "center"
        });

        doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('RUT: ' + response.user.rut, {
            align: "center"
        });

        if (!!response.user.sap) {
            doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('SAP: ' + response.user.sap, {
                align: "center"
            });

        } else {
            doc.moveDown();
        }

        doc.moveDown();

        doc.font('./fonts/calibri-regular.ttf').fontSize(12).text(`Por su PARTICIPACIÓN y APROBACIÓN en el curso:`, {
            align: "center"
        });

        doc.moveDown();

        doc.font('./fonts/calibri-bold.ttf').fontSize(18).text(response.course, {
            align: "center"
        });

        doc.moveDown();

        doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(`con un total de ${curso.duracion} Horas completado el ${moment(response.fechacompletado).format('LL')}`, {
            align: "center"
        });
        let date = response.fechavencimiento.split('/');
        doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(`Fecha de Vigencia del Certificado: ${moment(new Date(date[2], date[1] - 1, date[0])).format('LL')}`, {
            align: "center"
        });

        doc.moveDown();
        doc.moveDown();

        doc.font('./fonts/calibri-regular.ttf').fontSize(12).text(`Realizado por la Empresa NDC PERSSO GROUP ®`, {
            align: "center"
        });

        doc.moveDown();
        doc.moveDown();

        doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(curso.empresa, {
            align: "center"
        });

        doc.image(qrimage, 378, 480, { fit: [90, 90], align: 'center', valign: 'center' });

        // if (cur) {

        // }
    }
    doc.end();
}

async function buildPDFexternos(response, dataCallback, endCallback) {
    const doc = new PDFDocument({ bufferPages: true, layout: "landscape", size: "A4", });

    doc.on('data', dataCallback);
    doc.on('end', endCallback);

    

    const qrimage = await qr.toDataURL(`https://ndc-backend.crvsoft.cl/api/users/verificarcertificadoExternos/${response.courseId}/${response.user.correondc}/${response.user.rut}/`);

    const curso = await getCursoByIdRepository2(response.courseId);

    const path = './uploads/' + response.courseId + '.png'

    if (fs.existsSync(path)) {
        doc.image('./uploads/' + response.courseId + '.png', 0, 0, { width: 842 });
    } else {
        doc.image("./images/base.png", 0, 0, { width: 842 });
    }



    doc.moveDown();
    doc.moveDown();
    doc.moveDown();
    doc.moveDown();
    doc.moveDown();
    doc.moveDown();

    doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('Se otorga el presente certificado a:', {
        align: "center"
    });

    doc.moveDown();


    doc.font('./fonts/calibri-bold.ttf').fontSize(18).text(response.user.nombrecompleto, {
        align: "center"
    });

    doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('RUT: ' + response.user.rut, {
        align: "center"
    });

    if (!!response.user.sap) {
        doc.font('./fonts/calibri-regular.ttf').fontSize(12).text('SAP: ' + response.user.sap, {
            align: "center"
        });

    } else {
        doc.moveDown();
    }

    doc.moveDown();

    doc.font('./fonts/calibri-regular.ttf').fontSize(12).text(`Por su PARTICIPACIÓN y APROBACIÓN en el curso:`, {
        align: "center"
    });

    doc.moveDown();

    doc.font('./fonts/calibri-bold.ttf').fontSize(18).text(response.course, {
        align: "center"
    });

    doc.moveDown();

    doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(`con un total de ${curso.duracion} Horas completado el ${moment(response.fechacompletado).format('LL')}`, {
        align: "center"
    });
    let date = response.fechavencimiento.split('/');
    doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(`Fecha de Vigencia del Certificado: ${moment(new Date(date[2], date[1] - 1, date[0])).format('LL')}`, {
        align: "center"
    });

    doc.moveDown();
    doc.moveDown();

    doc.font('./fonts/calibri-regular.ttf').fontSize(12).text(`Realizado por la Empresa NDC PERSSO GROUP ®`, {
        align: "center"
    });

    doc.moveDown();
    doc.moveDown();

    doc.font('./fonts/calibri-bold.ttf').fontSize(12).text(curso.empresa, {
        align: "center"
    });

    doc.image(qrimage, 378, 480, { fit: [90, 90], align: 'center', valign: 'center' });

    // if (cur) {

    // }
    doc.end();
}

const generateUserCertificate = async (req = request, res = response) => {
    try {

        const response = JSON.parse(req.query.data);
        const stream = res.writeHead(200, {
            'Content-Type': 'application/pdf',
            'Content-Disposition': `attachment;filename=${response.nombrecompleto}.pdf`,
        });

        await buildPDF(response, (chunk) => stream.write(chunk), () => stream.end());

    } catch (e) {
        console.log(e);
    }
}

const generateUserExternosCertificate = async (req = request, res = response) => {
    try {

        const response = JSON.parse(req.query.data);
        const stream = res.writeHead(200, {
            'Content-Type': 'application/pdf',
            'Content-Disposition': `attachment;filename=${response.nombrecompleto}.pdf`,
        });

        await buildPDFexternos(response, (chunk) => stream.write(chunk), () => stream.end());

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
            fs.readFile(path.join(__dirname, '../public/verificarcertificado.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                const html = data.replace("{1}", nombrecompleto.toString());
                const test = html.replace("{2}", nombrecurso.toString());
                const test1 = test.replace("{3}", fechavencimiento.toString());
                const test2 = test1.replace("{4}", rut.toString());
                res.type('.html')
                res.send(test2);
            });
        } else {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                res.type('.html')
                res.send(data);
            });
        }
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
            if (err) {
                return console.log(err);
            }
            res.type('.html')
            res.send(data);
        });
    }
}

const updatePlantilla = async (req = request, res = response) => {
    let file = req.file;
    let id = req.params.id;
    file.originalname = id + ".png";
    if (!file) {
        const error = new Error('No File')
        error.httpStatusCode = 400
        return next(error)
    }
    res.status(200).json({ "ok": true });
}

const verifyVersion = async (req = request, res = response) => {
    res.status(200).json({ "ok": true });
}

const verifyCertificateLms = async (req = request, res = response) => {
    try {

        let enrollments = await axios({
            url: 'https://us-api.365.systems/odata/v2/Enrollments/IncludeDeletedUsers()', //your url
            method: 'GET',
            responseType: 'json', // important
            auth: {
                username: 'api',
                password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
            }
        });

        enrollments = enrollments.data.value;
        enrollments = enrollments.filter(x => x.UserLoginName === 'i:0#.f|membership|' + req.params.puestotrabajo && x.CourseId === req.params.idcurso);

        // console.log(moment(enrollments[0].CompletionDate).add(48, 'months').format('YYYY-MM-DD'));
        // return;

        let curso = await getCursoByIdRepository(req.params.idcurso);

        let result = await getUserByRutRepository(req.params.rut);
        if (result.body.user === 0) {
            result = await getUserByEmailRepository(req.params.puestotrabajo);
        }
        result = result.body.user;
        const nombrecompleto = result.nombrecompleto;
        const rut = result.rut;
        const fechavencimiento = moment(new Date(enrollments[0].CompletionDate)).add(curso.vigencia, 'months').format('LL');
        const nombrecurso = curso.nombre_curso;
        const fecha1 = moment(enrollments[0].CompletionDate).add(curso.vigencia, 'months').format('YYYY-MM-DD');
        const fecha2 = moment().format('YYYY-MM-DD');
        if (fecha1 >= fecha2) {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                const html = data.replace("{1}", nombrecompleto.toString());
                const test = html.replace("{2}", nombrecurso.toString());
                const test1 = test.replace("{3}", fechavencimiento.toString());
                const test2 = test1.replace("{4}", rut.toString());
                res.type('.html')
                res.send(test2);
            });
        } else {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                res.type('.html')
                res.send(data);
            });
        }
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
            if (err) {
                return console.log(err);
            }
            res.type('.html')
            res.send(data);
        });
    }
}

const verifyCertificateExternos = async (req = request, res = response) => {
    try {
        const result = await getUserVerifyRepository(req.params);
        const nombrecompleto = result.user.nombrecompleto;
        const rut = result.user.rut;
        const fechavencimiento = moment(result.curso.fecha_fin).add(result.curso_info.vigencia, 'months').format('LL');
        const nombrecurso = result.curso.nombre_curso;
        const fecha1 = moment(result.curso.fecha_fin).add(result.curso_info.vigencia, 'months').format('YYYY-MM-DD');
        const fecha2 = moment().format('YYYY-MM-DD');
        if (fecha1 >= fecha2) {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                const html = data.replace("{1}", nombrecompleto.toString());
                const test = html.replace("{2}", nombrecurso.toString());
                const test1 = test.replace("{3}", fechavencimiento.toString());
                const test2 = test1.replace("{4}", rut.toString());
                res.type('.html')
                res.send(test2);
            });
        } else {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                res.type('.html')
                res.send(data);
            });
        }
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
            if (err) {
                return console.log(err);
            }
            res.type('.html')
            res.send(data);
        });
    }
}

const getCursoById = async (req = request, res = response) => {
    try {
        const id = req.params.id;
        const result = await getCursoByIdRepository(id);

        res.json({
            result
        });

    } catch (e) {
        console.log(e);
    }
}

const getCursoByClave = async (req = request, res = response) => {
    try {
        const id = req.params.id;
        const result = await getCursoByClaveRepository(id);

        res.json({
            result
        });

    } catch (e) {
        console.log(e);
    }
}

const getCursos = async (req = request, res = response) => {
    try {
        const result = await getCursosByRepository();

        res.json({
            result
        });

    } catch (e) {
        console.log(e);
    }
}

const createCurso = async (req = request, res = response) => {
    try {
        const result = await createCursoRepository(req);
        res.json({
            result
        });

    } catch (e) {
        console.log(e);
    }
}

const verifyCertificateCodelco = async (req = request, res = response) => {
    try {

        const usuarios = [
            {
                "RUT": "17.818.815-1",
                "NOMBRES": "SERGIO IGNACIO",
                "APELLIDOS": "SALINAS MUÑOZ",
                "SAP": "70924",
                "EVAL.DIAG": "71",
                "EVAL.FINAL": "100",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "15.881.874-4",
                "NOMBRES": "JOSE RODOLFO",
                "APELLIDOS": "MEDINA OPAZO",
                "SAP": "65727",
                "EVAL.DIAG": "71",
                "EVAL.FINAL": "100",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "16.873.525-1",
                "NOMBRES": "ALEJANDRO DAVID",
                "APELLIDOS": "MOYA MIRANDA",
                "SAP": "74367",
                "EVAL.DIAG": "43",
                "EVAL.FINAL": "100",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "13.900.787-5",
                "NOMBRES": "GABRIEL ALFONSO",
                "APELLIDOS": "ARANCIBIA RIVERA",
                "SAP": "70920",
                "EVAL.DIAG": "86",
                "EVAL.FINAL": "100",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "18.178.980-8",
                "NOMBRES": "DIEGO ANTONIO",
                "APELLIDOS": "CALDERA PALLAUTA",
                "SAP": "65176",
                "EVAL.DIAG": "71",
                "EVAL.FINAL": "97",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "20.517.520-2",
                "NOMBRES": "DAVID ARMANDO",
                "APELLIDOS": "ROJAS VILCHES",
                "SAP": "300891",
                "EVAL.DIAG": "57",
                "EVAL.FINAL": "97",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            },
            {
                "RUT": "19.767.384-2",
                "NOMBRES": "SEBASTIAN EMMANUEL",
                "APELLIDOS": "ALVAREZ ALLENDE",
                "SAP": "301739",
                "EVAL.DIAG": "86",
                "EVAL.FINAL": "97",
                "ASISTENCIA": "100",
                "NOTAFINAL": "A",
                "FECHA_DE_VIGENCIA": "2025-10-22"
            }
        ]

        const result = usuarios.filter(x => x.RUT === req.params.rut && x.SAP === req.params.sap && x.NOTAFINAL === req.params.nota);
        console.log(result);
        const nombrecompleto = result[0].NOMBRES + ' ' + result[0].APELLIDOS;
        const rut = result[0].RUT;
        const fechavencimiento = moment(result[0].FECHA_DE_VIGENCIA).format('LL');
        const nombrecurso = "Corte de elementos con lanzas térmica Oxiflame";
        const fecha1 = moment(result[0].FECHA_DE_VIGENCIA).format('YYYY-MM-DD');
        const fecha2 = moment().format('YYYY-MM-DD');
        if (result.length !== 0) {
            if (fecha1 >= fecha2) {
                fs.readFile(path.join(__dirname, '../public/verificarcertificado-codelco.html'), 'utf8', function (err, data) {
                    if (err) {
                        return console.log(err);
                    }
                    const html = data.replace("{1}", nombrecompleto.toString());
                    const test = html.replace("{2}", nombrecurso.toString());
                    const test1 = test.replace("{3}", fechavencimiento.toString());
                    const test2 = test1.replace("{4}", rut.toString());
                    res.type('.html')
                    res.send(test2);
                });
            } else {
                fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
                    if (err) {
                        return console.log(err);
                    }
                    res.type('.html')
                    res.send(data);
                });
            }
        } else {
            fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
                if (err) {
                    return console.log(err);
                }
                res.type('.html')
                res.send(data);
            });
        }
    } catch (e) {
        fs.readFile(path.join(__dirname, '../public/verificarcertificado-invalidos.html'), 'utf8', function (err, data) {
            if (err) {
                return console.log(err);
            }
            res.type('.html')
            res.send(data);
        });
    }
}

cron.schedule('58 7,11,17,23 * * *', async function () {
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
    result = await axios({
        url: 'https://us-api.365.systems/odata/v2/Certificates', //your url
        method: 'GET',
        responseType: 'json', // important
        auth: {
            username: 'api',
            password: 'ab29f3ed-787f-4df6-9c22-aa0a5c4fa629'
        }
    });
    certificates = result.data;
    for (const element of certificates.value) {
        for (const elementUser of users.value) {
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
        };
    };

    const currentArray = userCertificates.filter(x => moment.utc(x.fechafinalizacion).format().toString().split("T")[0] === moment.utc(new Date()).format().toString().split("T")[0]);
    for (const element of currentArray) {
        const fixFecha = moment(element.fechavencimiento).format().split("T");
        const queryResult = await pool.query("SELECT * FROM historicocert WHERE to_char(fechavencimiento , 'YYYY-MM-DD') = $1 AND rut = $2 AND idcurso = $3", [fixFecha[0], element.rut, element.idcurso]);
        if (queryResult.rowCount === 0) {
            await pool.query('INSERT INTO historicocert (rut, nombrecompleto, correopersonal, puestotrabajo, idcurso, nombrecurso, fechafinalizacion, fechavencimiento, fechainscripcion, nombreempresa, rutempresa) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)', [!!element.rut ? element.rut : "NA", !!element.nombrecompleto ? element.nombrecompleto : "NA", !!element.correopersonal ? element.correopersonal : "NA", !!element.puestotrabajo ? element.puestotrabajo : "NA", element.idcurso, element.nombrecurso, element.fechafinalizacion, element.fechavencimiento, '-', 'NA', 'NA']);
            console.log('ejecutado', element);
        }
    };

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
    verifyCertificate,
    patchHistoricoById,
    getUserByEmail,
    getUserByRut,
    verifyCertificateCodelco,
    patchUserById,
    verifyCertificateLms,
    getCursoById,
    getCursos,
    createCurso,
    patchCursoById,
    updatePlantilla,
    getCursoByClave,
    getUserBySerial,
    generateUserExternosCertificate,
    verifyCertificateExternos,
    verifyVersion,
    getAllUserExternos,
    createUserExternosMicrosoftGlobal,
    patchUserExternosById,
    deleteExternoById,
    getUserExternosByRut
}
