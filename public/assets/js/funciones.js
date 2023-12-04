// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO EL NUMERO VALORES REQUERIDOS DE ACUERDO A LA LONGITUD MAXLENGTH DEL CAMPO -->

function maxlengthNumber(obj) {

    if (obj.value.length > obj.maxLength) {
        obj.value = obj.value.slice(0, obj.maxLength);
        alert("Debe ingresar solo el numeros de digitos requeridos");
    }
}

function maxcelNumber(obj) {

    if (obj.value.length > obj.maxLength) {
        obj.value = obj.value.slice(0, obj.maxLength);
        alert("Debe ingresar solo 10 numeros.");
    }
}

// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO LETRAS -->

function multipletext(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    letras = "qwertyuiopasdfghjklñzxcvbnm";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            alert("Debe ingresar solo letras y espacios en el campo");
            break;
        }
    }

    if (letras.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
        alert("Debe ingresar solo letras y espacios en el campo");
    }
}


// FUNCION PARA PERMITIR SOLO LETRAS Y ESPACIOS EN EL FORMULARIO

function soloLetrasEspacios(event) {
    const input = event.target;
    const regex =/^[a-zA-ZÀ-ÿ\s]{5,20}$/; // Expresión regular para letras y espacios

    if (!regex.test(input.value)) {
        input.value = input.value.replace(/^[a-zA-ZÀ-ÿ\s]{5,20}$/, ''); // Eliminar caracteres no permitidos
    }
}

function soloLetrasEspaciosNum(event) {
    const input = event.target;
    const regex = /^[a-zA-ZÀ-ÿ\s0-9]{5,20}$/; // Expresión regular para letras, espacios y números

    if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s0-9]/g, ''); // Eliminar caracteres no permitidos
    }
}

// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO NUMEROS EN EL FORMULARIO ASIGNADO -->

function multiplenumber(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    numeros = "1234567890";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            alert("Debe ingresar solo numeros en el formulario");
            break;
        }
    }

    if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
        alert("Debe ingresar solo numeros en el formulario ");
    }
}


// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO LETRAS Y ESPACIOS EN EL CAMPO EL CUAL SE INVOCA EL EVENTO  -->

function textspace(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    letrasspace = "qwertyuiopasdfghjklñzxcvbnm123456789";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            alert("Debe ingresar solo letras y espacios en el campo asignado");
            break;
        }
    }

    if (letrasspace.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
        alert("Debe ingresar solo letras y espacios en el campo asignado");
    }
}


// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO LETRAS Y ESPACIOS EN EL CAMPO EL CUAL SE INVOCA EL EVENTO  -->

function textguions(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    letrasguions = "qwertyuiopasdfghjklñzxcvbnm1234567890_";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            alert("Debe ingresar solo letras y espacios en el campo asignado");
            break;
        }
    }

    if (letrasguions.indexOf(teclado) == -1 && !teclado_especial) {
        return false;
    }
}

// <!-- FUNCION DE JAVASCRIPT QUE PERMITE INGRESAR SOLO LETRAS. NUMEROS Y GUIONES BAJOS PARA LA CONTRASEÑA   -->


function validarPassword(event) {
    // Obtenemos la tecla que se ha presionado
    var key = event.keyCode || event.which;

    // Convertimos el código de la tecla a su respectivo carácter
    var char = String.fromCharCode(key);

    // Definimos una expresión regular que solo permita números, letras y guiones bajos
    var regex = /[0-9a-zA-Z_]/;

    // Validamos si el carácter ingresado cumple con la expresión regular
    if (!regex.test(char)) {
        // Si no cumple, cancelamos el evento de ingreso de datos
        event.preventDefault();
        return false;
    }
}


function fechaCumple() {
    var fecha = new Date();
    var mes = fecha.getMonth() + 1;
    var dia = fecha.getDate();
    var anio = fecha.getFullYear() - 18;

    if (mes < 10) {
        mes = '0' + mes;
    }

    if (dia < 10) {
        dia = '0' + dia;
    }

    var fechaCumple = anio + '-' + mes + '-' + dia;

    return fechaCumple;
}