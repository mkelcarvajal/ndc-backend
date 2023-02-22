const {Router} = require('express');
const {check} = require('express-validator');
const {fieldValidator} = require("../middlewares/field-validate");
const {getAllUser, getUserById,updateUser, deleteUser, patchUser, createUserMicrosoftGlobal, getAllHistoric, generateUserCertificate, verifyCertificate, patchHistoricoById, getUserByEmail, getUserByRut, verifyCertificateCodelco, patchUserById, verifyCertificateLms, getCursoById, getCursos, createCurso, patchCursoById, updatePlantilla, getCursoByClave, getUserBySerial, generateUserExternosCertificate, verifyCertificateExternos, verifyVersion, getAllUserExternos, createUserExternosMicrosoftGlobal, patchUserExternosById, deleteExternoById, getUserExternosByRut, getByDaysHistoric, getBySpecificDaysHistoric } = require("../controllers/user");
const {roleExist, emailExist, userExist} = require("../helpers/db-validator");
const {validateJWT} = require("../middlewares/validate-jwt");
const {isAdminRole, haveRole} = require("../middlewares/validate-roles");
const multer = require('multer');

const router = new Router();

router.get('/checkversion/', [
    fieldValidator
], verifyVersion);

router.get('/verificarcertificados/:idcurso/:puestotrabajo/:rut', [
    fieldValidator
], verifyCertificate);

router.get('/verificarcertificadosCodelco/:rut/:sap/:nota', [
    fieldValidator
], verifyCertificateCodelco);

router.get('/verificarcertificadosLms/:idcurso/:puestotrabajo/:rut', [
    fieldValidator
], verifyCertificateLms);

router.get('/verificarcertificadoExternos/:idcurso/:puestotrabajo/:rut', [
    fieldValidator
], verifyCertificateExternos);

router.get('/getUserBySerial/:id', [
    validateJWT,
    fieldValidator
], getUserBySerial);

router.get('/getCursoByClave/:id', [
    validateJWT,
    fieldValidator
], getCursoByClave);

router.get('/getCursoById/:id', [
    validateJWT,
    fieldValidator
], getCursoById);

router.get('/getCursos/', [
    validateJWT,
    fieldValidator
], getCursos);

router.get('/verifyById/:id', [
    validateJWT,
    fieldValidator
], getUserById);

router.get('/verifyByEmail/:email', [
    validateJWT,
    fieldValidator
], getUserByEmail);

router.get('/verifyUserByRut/:rut', [
    validateJWT,
    fieldValidator
], getUserByRut);

router.get('/verifyUserExternosByRut/:rut', [
    validateJWT,
    fieldValidator
], getUserExternosByRut);

router.get('/', [
    validateJWT,
    fieldValidator
], getAllUser);

router.get('/externos', [
    validateJWT,
    fieldValidator
], getAllUserExternos);


router.get('/getAllHistoric', [
    validateJWT,
    fieldValidator
], getAllHistoric);


router.get('/getByDaysHistoric/:days', [
    validateJWT,
    fieldValidator
], getByDaysHistoric);

router.get('/getBySpecificDaysHistoric/:date', [
    validateJWT,
    fieldValidator
], getBySpecificDaysHistoric);


router.get('/userCertificateExternos', [
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    //haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], generateUserExternosCertificate);

router.get('/userCertificate', [
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    //haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], generateUserCertificate);

router.get('/:id', [
    validateJWT,
    check('id').custom(id => userExist(id)),
    fieldValidator
], getUserById);

router.patch('/updateUser/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], patchUserById);

router.delete('/externos/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], deleteExternoById);

router.patch('/updateUserExternos/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], patchUserExternosById);

router.patch('/updateHistoric/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], patchHistoricoById);

router.patch('/updateCurso/:id', [
    validateJWT,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], patchCursoById);

router.put('/:id', [
    validateJWT,
    isAdminRole,
    check('id').custom(id => userExist(id)),
    check('email').custom(email => emailExist(email)),
    check('role').custom(role => roleExist(role)),
    fieldValidator
], updateUser);

const storage = multer.diskStorage({
    destination: (req, file, callBack) => {
        callBack(null, 'uploads')
    },
    filename: (req, file, callBack) => {
        file.originalname = req.params.id + ".png"
        callBack(null, `${file.originalname}`)
    }
  })
const upload = multer({ storage: storage })

router.post('/updatePlantilla/:id', upload.single('file'),[
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], updatePlantilla);

router.post('/crearcurso', [
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], createCurso);

router.post('/getcertificado', [
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    // haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], verifyCertificate);

router.post('/', [
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], createUserMicrosoftGlobal);

router.post('/externos', [
    validateJWT,
    // //isAdminRole,
    // check('name', 'The name is required').not().isEmpty(),
    // check('email', 'This email is not valid').isEmail(),
    // check('password', 'The password must be higher to 6 character').isLength({min: 6}),
    haveRole("ADMIN_ROLE"),
    // //check('role').custom(role => roleExist(role)),
    // check('email').custom(email => emailExist(email)),
    fieldValidator
], createUserExternosMicrosoftGlobal);

// router.delete('/:id', [
//     validateJWT,
//     //isAdminRole,
//     haveRole("ADMIN_ROLE", "SALE_ROLE"),
//     check('id').custom(id => userExist(id)),
//     fieldValidator
// ], deleteUser);

router.delete('/:id', [
    validateJWT,
    //isAdminRole,
    haveRole("ADMIN_ROLE"),
    fieldValidator
], deleteUser);

// router.patch('/', deleteUser);

module.exports = router;
