export function showAlert(alertClass, text) {
    let div = document.querySelector('#success-js_button');

    if ( 'success-js_button--error' == div.className ) {
        return false;
    }

    div.className = alertClass;
    div.innerHTML = text;
    setTimeout(
        () => {
            div.className += ' hide-alert';
            setTimeout(() => {
                div.className = '';
                div.innerHTML = '';
            }, 500);
        }, 3000
    );
}