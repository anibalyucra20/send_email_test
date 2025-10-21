// REGISTRO
if (document.querySelector('#frm_signup')) {
  const frm_signup = document.querySelector('#frm_signup');
  frm_signup.onsubmit = function (e) {
    e.preventDefault();
    registrarNuevo();
  }
}

async function registrarNuevo() {
  try {
    const nombre = document.getElementById('nombre').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const contrasena = document.getElementById('contrasena').value;

    if (!nombre || !correo || !contrasena) { alert("Error, campos vacíos"); return; }
    if (contrasena.length < 8) { alert("La contraseña debe tener mínimo 8 caracteres"); return; }

    const datos = new FormData(document.getElementById('frm_signup'));
    let r = await fetch(base_url + 'control/AuthController.php?tipo=signup', {
      method: 'POST', mode: 'cors', cache: 'no-cache', body: datos
    });
    let json = await r.json();
    if (json.status) {
      alert(json.msg + " Revisa tu correo para el código de verificación.");
      document.getElementById('frm_signup').reset();
      // precargar correo en verificación
      document.getElementById('correo_verify').value = correo;
    } else {
      alert(json.msg);
    }
  } catch (e) { console.log("Error signup:", e); }
}

// VERIFICAR CÓDIGO
if (document.querySelector('#frm_verify')) {
  const frm_verify = document.querySelector('#frm_verify');
  frm_verify.onsubmit = function (e) {
    e.preventDefault();
    verificarCodigo();
  }
}

async function verificarCodigo() {
  try {
    const correo = document.getElementById('correo_verify').value.trim();
    const codigo = document.getElementById('codigo_verify').value.trim();

    if (!correo || !codigo) { alert("Completa correo y código"); return; }
    if (!/^\d{6}$/.test(codigo)) { alert("El código debe tener 6 dígitos"); return; }

    const datos = new FormData();
    datos.append('correo', correo);
    datos.append('codigo', codigo);

    let r = await fetch(base_url + 'control/AuthController.php?tipo=verificar_codigo', {
      method: 'POST', mode: 'cors', cache: 'no-cache', body: datos
    });
    let json = await r.json();
    if (json.status) {
      alert("¡Cuenta activada! Ya puedes iniciar sesión.");
      document.getElementById('frm_verify').reset();
      // location.replace(base_url + 'login');
    } else {
      alert(json.msg);
    }
  } catch (e) { console.log("Error verify:", e); }
}

// REENVIAR CÓDIGO
if (document.getElementById('btn_resend')) {
  document.getElementById('btn_resend').onclick = async function () {
    try {
      const correo = (document.getElementById('correo_verify')?.value || '').trim() ||
                     prompt("Ingresa tu correo para reenviar el código:");
      if (!correo) return;

      const datos = new FormData();
      datos.append('correo', correo);

      let r = await fetch(base_url + 'control/AuthController.php?tipo=reenviar_codigo', {
        method: 'POST', mode: 'cors', cache: 'no-cache', body: datos
      });
      let json = await r.json();
      alert(json.msg);
      if (json.status) document.getElementById('correo_verify').value = correo;
    } catch (e) { console.log("Error resend:", e); }
  }
}

// LOGIN BÁSICO (requiere estado=1)
// Usa ids: #correo_login y #password_login; llama a esta función onsubmit
async function iniciar_sesion_basico() {
  const correo = document.getElementById('correo_login').value;
  const password = document.getElementById('password_login').value;
  if (!correo || !password) { alert("Error, campos vacíos"); return; }

  try {
    const datos = new FormData();
    datos.append('correo', correo);
    datos.append('contrasena', password);

    let r = await fetch(base_url + 'control/AuthController.php?tipo=login', {
      method: 'POST', mode: 'cors', cache: 'no-cache', body: datos
    });
    let json = await r.json();
    if (json.status) {
      location.replace(base_url + 'dashboard'); // ajusta
    } else {
      alert(json.msg);
    }
  } catch (e) { console.log("Error login:", e); }
}
