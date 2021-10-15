const {request, response} = require('express');
const Destinary = require('../models/destinary');
const {encryptPassword} = require('../helpers/utils');


const getAllDestinary = async (req = request, res = response) => {

    const {limit = 5, from = 0} = req.query;
    const query = {state: true};

    const [totalDestinaries, users] = await Promise.all([
        Destinary.countDocuments(query),
        Destinary.find(query)
            .skip(parseInt(from))
            .limit(parseInt(limit))
    ]);

    res.json({
        totalDestinaries,
        users
    });
}

const getDestinaryByRut = async (req = request, res = response) => {

    const rut = req.params.rut;

    const destinary = await Destinary.findOne({rut});

    res.json({
        destinary
    });
}

const createDestinary = async (req = request, res = response) => {
    try {

        const {name, rut, email, phoneNumber, bank, accountType, accountNumber} = req.body;
        const destinary = new Destinary({name, rut, email, phoneNumber, bank, accountType, accountNumber});

        // Save in DB
        await destinary.save();
        res.json({
            msg: 'post API - Controller',
            destinary
        });
    } catch (e) {
        console.log(e);
    }
}



module.exports = {
    getAllDestinary,
    getDestinaryByRut,
    createDestinary
}
