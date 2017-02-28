export function checkToken() {
    let token = getToken();
    return token ? true : false;
}

export function getToken() {
    return localStorage.getItem('userToken');
}

export function saveToken(token) {
    localStorage.setItem('userToken', token);
    return true;
}

export function deleteToken() {
    localStorage.removeItem('userToken');
    return true;
}