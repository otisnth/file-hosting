'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('.form-auth');
    let errorBlock = form.querySelector('.error-block');
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const fData = new FormData(form);

        postData('/admin/api/user/auth', fData, {})
            .then((data) => {
                window.location.href = '/'; // переадресация на главную
            })
            .catch((error) => {
                let errorMessege = getCookieError();
                errorBlock.textContent = errorMessege ? errorMessege : 'Произошла непредвиденная ошибка';
            })
    })
})