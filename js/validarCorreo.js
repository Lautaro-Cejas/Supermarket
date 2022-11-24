let correo = document.querySelector('#email');

correo.addEventListener('keyup', (event) => {
    event.preventDefault();
    let regExp = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if (correo.value.match(regExp)) {
        email.classList.remove("border-danger");
        email.classList.add("border-success");
    } else {
        email.classList.remove("border-success");
        email.classList.add("border-danger");
    }
}); 