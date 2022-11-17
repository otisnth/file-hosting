'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('.form-reg');
    let errorBlock = form.querySelector('.error-block');

    form.addEventListener('change', (e) => {
        if (e.target.type == 'password') {
            let password = form.querySelector('[name="password"]').value,
                passwordRepeat = form.querySelector('[name="password-repeat"]').value;

            const regexpMain = /[a-zA-Z0-9\-]{6,}/; // численно-символьный не менее 6 символов и -
            const regexpW = /[a-zA-Z]{1,}/; // хотя бы одна буква

            if (!regexpMain.test(password) || !regexpW.test(password)) {
                errorBlock.textContent = 'Пароль должен быть не менее 6 символов и включать хотя бы одну букву!';
            } else if (password != passwordRepeat) {
                errorBlock.textContent = 'Пароли не совпадают!';
            } else {
                errorBlock.textContent = '';
            }
        }
    })

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!errorBlock.textContent == ''){
            return;
        }

        const fData = new FormData(form);

        postData('/admin/api/user/reg', fData, {})
            .then((data) => {
                window.location.href = '/'; // переадресация на главную
            })
            .catch((error) => {
                let errorMessege = getCookieError();
                errorBlock.textContent = errorMessege ? errorMessege : 'Произошла непредвиденная ошибка';
            })
    })
})