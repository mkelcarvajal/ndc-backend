const {request, response} = require('express');
const User = require('../models/user').default;
const {createUserRepository, getUserByIdRepository} = require('../repository/user.repository');
const {encryptPassword} = require('../helpers/utils');
const sgMail = require('@sendgrid/mail')

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

const createUser = async (req = request, res = response) => {
    try {

        // Save in DB
        const result = await createUserRepository(req);

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


module.exports = {
    getAllUser,
    getUserById,
    updateUser,
    createUser,
    deleteUser,
    patchUser
}
