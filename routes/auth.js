const {Router} = require('express');
const {check} = require('express-validator');
const {fieldValidator} = require("../middlewares/field-validate");
const {googleSignIn, login, microsoftSignIn} = require("../controllers/auth");

const router = new Router();


router.post('/login', [
    check('email', 'The email is required or format is not correct').isEmail(),
    check('password', 'The password can not be empty').not().isEmpty(),
    fieldValidator,
], login);


router.post('/google', [
    check('id_token', 'The ID TOKEN Google is necessary').not().isEmpty(),
    fieldValidator,
], googleSignIn);

router.post('/microsoft', [
    check('id', 'El campo id es vacio').not().isEmpty(),
    fieldValidator,
], microsoftSignIn);



module.exports = router
