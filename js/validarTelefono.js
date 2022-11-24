let tel = document.getElementById('celular');

tel.addEventListener('keypress', (event) => {
    event.preventDefault();

    let codigoTecla = event.keyCode;
    let valorTecla = String.fromCharCode(codigoTecla);
    let valor = parseInt(valorTecla);

    if (valor || event.key === "0") {
        tel.value += valor;
    } 

});