
async function iniciar_sesion() {
    let usuario = document.getElementById("correo").value;
    let password = document.getElementById("contrasena").value;
    if (usuario == "" || password == "") {
        alert("Error, campos vacios!");
        return;
    }
    try {
        const datos = new FormData(frm_login);
        let respuesta = await fetch(base_url + 'control/AuthController.php?tipo=login', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // -------------------------
        let json = await respuesta.json();
        // validamos que json.status sea = True
        if (json.status) { //true
            location.replace(base_url + 'index');
        } else {
            alert(json.msg);
        }

    } catch (error) {
        console.log(error);
    }
}