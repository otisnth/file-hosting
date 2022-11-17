'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('.form-post');
    let errorBlock = form.querySelector('.error-block');

    const getDiscipline = function() {
        let select = form.querySelector("select");

        getData('/admin/api/discipline')
            .then((data) => {
                data.forEach(element => {
                    let option = getElement('option', '', {
                        innerHTML: `<option >${element.name}</option>
                        `,
                        value: element.id
                    } , {id : element.id });
                    select.append(option);
                });
            })
            .catch((error) => {
                let errorMessege = getCookieError();
                errorBlock.textContent = errorMessege ? errorMessege : 'Произошла непредвиденная ошибка';
            })
        
    }

    getDiscipline();
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const fData = new FormData(form);

        postData('/admin/api/post', fData, {})
            .then((data) => {
                // window.location.href = '/'; // переадресация на главную
            })
            .catch((error) => {
                let errorMessege = getCookieError();
                errorBlock.textContent = errorMessege ? errorMessege : 'Произошла непредвиденная ошибка';
            })

    })
})