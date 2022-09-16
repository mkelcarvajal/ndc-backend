class User
{
    constructor(id, businessPhones, displayName, givenName, jobTitle, mail, mobilePhone, officeLocation, preferredLanguage, surname, userPrincipalName, rol)
    {   
        this.id = id;
        this.telefonos = businessPhones;
        this.nombreCompleto = displayName;
        this.puestoTrabajo = jobTitle;
        this.mail = mail;
        this.telefonoPersonal = mobilePhone;
        this.officeLocation = officeLocation;
        this.preferredLanguage = preferredLanguage;
        this.nombre = givenName;
        this.apellidos = surname;
        this.userPrincipalName = userPrincipalName;
        this.rol = rol;
    }
}

module.exports = {
    User
};