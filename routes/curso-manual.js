const {Router} = require('express');
const {check} = require('express-validator');
const {fieldValidator} = require("../middlewares/field-validate");
const {getCursos, createCurso, getCursoById, asignarAlumno, getAsignadoById, generarCertificado, verificarCertificacion, getCursosExternos, createCursoExternos, patchCursoById, getCursoByClave, patchCalificacionById} = require("../controllers/curso");
const {validateJWT} = require("../middlewares/validate-jwt");
const {isAdminRole, haveRole} = require("../middlewares/validate-roles");

const router = new Router();

router.get('/getCursoByClave/:id', [
    validateJWT,
    fieldValidator
], getCursoByClave);

router.get('/generarCertificado', [
    validateJWT,
    // check('name', 'The name is required').not().isEmpty(),
    // check('rut', 'This rut is not valid').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('phoneNumber', 'This phoneNumber is not valid').not().isEmpty(),
    // check('bank', 'This bank is not valid').not().isEmpty(),
    // check('accountType', 'This accountType is not valid').not().isEmpty(),
    // check('accountNumber', 'This accountNumber is not valid').not().isEmpty(),
    fieldValidator
], generarCertificado);

router.get('/getCursosExternos/', [
    //check('rut').custom(rut => cursoExist(rut)),
    fieldValidator
], getCursosExternos);

router.get('/verificarCertificacion/:id', [
    //check('rut').custom(rut => cursoExist(rut)),
    fieldValidator
], verificarCertificacion);

router.get('/asignados/:id', [
    validateJWT,
    //check('rut').custom(rut => cursoExist(rut)),
    fieldValidator
], getAsignadoById);

router.get('/:id', [
    validateJWT,
    //check('rut').custom(rut => cursoExist(rut)),
    fieldValidator
], getCursoById);

router.get('/', [
    validateJWT,
    //check('rut').custom(rut => cursoExist(rut)),
    fieldValidator
], getCursos);

router.post('/createCursoExternos', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    // check('name', 'The name is required').not().isEmpty(),
    // check('rut', 'This rut is not valid').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('phoneNumber', 'This phoneNumber is not valid').not().isEmpty(),
    // check('bank', 'This bank is not valid').not().isEmpty(),
    // check('accountType', 'This accountType is not valid').not().isEmpty(),
    // check('accountNumber', 'This accountNumber is not valid').not().isEmpty(),
    fieldValidator
], createCursoExternos);

router.post('/asignar', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    // check('name', 'The name is required').not().isEmpty(),
    // check('rut', 'This rut is not valid').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('phoneNumber', 'This phoneNumber is not valid').not().isEmpty(),
    // check('bank', 'This bank is not valid').not().isEmpty(),
    // check('accountType', 'This accountType is not valid').not().isEmpty(),
    // check('accountNumber', 'This accountNumber is not valid').not().isEmpty(),
    fieldValidator
], asignarAlumno);

router.post('/', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    // check('name', 'The name is required').not().isEmpty(),
    // check('rut', 'This rut is not valid').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('phoneNumber', 'This phoneNumber is not valid').not().isEmpty(),
    // check('bank', 'This bank is not valid').not().isEmpty(),
    // check('accountType', 'This accountType is not valid').not().isEmpty(),
    // check('accountNumber', 'This accountNumber is not valid').not().isEmpty(),
    fieldValidator
], createCurso);

router.patch('/updateCalifacion/:id_user/:id_curso', [
    validateJWT,
    fieldValidator
], patchCalificacionById);

router.patch('/updateCurso/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], patchCursoById);

module.exports = router;
