const {request, response} = require('express');
const bcryptjs = require('bcryptjs');
const User = require('../models/user');
const {googleVerify} = require("../helpers/google-verify");
const {generateJWT} = require("../helpers/utils");
const {createUserRepositoryMicrosoft, getUserByIdRepository} = require('../repository/user.repository');


const login = async (req = request, res = response) => {

    try {

        const {email, password} = req.body;

        // Verify if email exist
        const user = await User.findOne({email});
        if (!user) {
            return res.status(400).json({
                msg: "Email incorrect or user not exist"
            })
        }

        // Verify if user is active
        if (!user.state) {
            return res.status(400).json({
                msg: "User inactive"
            })
        }

        // Verify password
        const validPassword = bcryptjs.compareSync(password, user.password);
        if (!validPassword) {
            return res.status(400).json({
                msg: "Password incorrect"
            })
        }

        // Generate JWT
        const token = await generateJWT(user.id);
        res.json({
            user,
            token
        })

    } catch (e) {
        return res.status(500).json({
            msg: 'Error',
            e
        })
    }
}

const googleSignIn = async (req = request, res = response) => {

    const {id_token} = req.body;

    try {

        const {name, email, img} = await googleVerify(id_token);

        let user = await User.findOne({email});

        if (!user) {
            // create user
            const data = {
                name,
                email,
                img,
                password: 'na',
                google: true
            }

            user = new User(data);
            await user.save();
        }

        // if user have state false on DB
        if (!user.state) {
            return res.status(401).json({
                msg: 'Talk with an Admin, user blocked'
            });
        }

        const token = await generateJWT(user.id);

        res.json({
            user,
            token
        })

    } catch (e) {
        console.log(e);
        res.status(400).json({
            msg: 'Google Token is not valid'
        })
    }

}

const microsoftSignIn = async (req = request, res = response) => {
    const id = req.body.id;
    try {
        let user = await getUserByIdRepository(id);
        if (user === 0 || user.length === 0 || user.body.user === undefined) {
            user = await createUserRepositoryMicrosoft(req);
        }

        // if user have state false on DB
        // if (!user.state) {
        //     return res.status(401).json({
        //         msg: 'Talk with an Admin, user blocked'
        //     });
        // }
        const token = await generateJWT(user.body.user.id);
        const usuario = user.body.user;
        res.json({
            user: usuario,
            token
        })

    } catch (error) {
        console.log(error);
        res.status(400).json({
            msg: 'Microsoft Token is not valid'
        })
    }

}

module.exports = {
    login,
    googleSignIn,
    microsoftSignIn
}
