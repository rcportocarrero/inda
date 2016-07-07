function refresh_login()
{
    document.getElementById('ref_login').src = root + "/usuario/captcha/logincaptcha?rnd=" + Math.random();
}

function refresh_registro()
{
    document.getElementById('ref_registro').src = root + "/usuario/captcha/registrocaptcha?rnd=" + Math.random();
}

function refresh_recuperar()
{
    document.getElementById('ref_recuperar').src = root + "/usuario/captcha/clavecaptcha?rnd=" + Math.random();
}


