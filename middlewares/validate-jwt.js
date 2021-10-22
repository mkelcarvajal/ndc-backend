const jwt = require('jsonwebtoken');
const {request, response} = require('express');
const {getUserByIdRepository} = require('../repository/user.repository');

const validateJWT = async (req = request, res = response, next) => {

    const token = req.header('x-token');

    if (!token) {
        return res.status(401).json({
            msg: 'Token no exist in the request'
        })
    }

    try {

        const {id} = jwt.verify(token, process.env.SECRETORPUBLICKEY);
        let userPetition = await getUserByIdRepository(id);
        userPetition = userPetition.body.user;
        if (userPetition === 0 || userPetition.length === 0 || userPetition === undefined) {
           return res.status(401).json({
                msg: 'User not exist in DB'
            })
        }

        // if (!userPetition.state) {
        //     return res.status(401).json({
        //         msg: 'User inactive'
        //     })
        // }
        req.userPetition = userPetition;
        next();
    } catch (e) {
        console.log(e);
        return res.status(401).json({
            msg: 'Token not valid',
            e
        })
    }
}

module.exports = {
    validateJWT
}
