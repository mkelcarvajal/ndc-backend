const {Router} = require('express');
const {check} = require('express-validator');
const {fieldValidator} = require("../middlewares/field-validate");
const {getAllDestinary, getDestinaryByRut, createDestinary} = require("../controllers/destinary");
const {destinaryExist} = require("../helpers/db-validator");
const {validateJWT} = require("../middlewares/validate-jwt");

const router = new Router();

router.get('/', [
    validateJWT,
    fieldValidator
], getAllDestinary);


router.get('/:rut', [
    validateJWT,
    check('rut').custom(rut => destinaryExist(rut)),
    fieldValidator
], getDestinaryByRut);

router.post('/', [
    validateJWT,
    check('name', 'The name is required').not().isEmpty(),
    check('rut', 'This rut is not valid').not().isEmpty(),
    check('email', 'This email is not valid').isEmail(),
    check('phoneNumber', 'This phoneNumber is not valid').not().isEmpty(),
    check('bank', 'This bank is not valid').not().isEmpty(),
    check('accountType', 'This accountType is not valid').not().isEmpty(),
    check('accountNumber', 'This accountNumber is not valid').not().isEmpty(),
    fieldValidator
], createDestinary);

module.exports = router;
