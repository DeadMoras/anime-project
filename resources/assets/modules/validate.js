export function justLength(object, value, nameInput, min, max) {
        object.error[nameInput] = {};

        if (value.length < min) {
            object.error[nameInput].min = 'Поле ' + nameInput + ' не может содержать менее ' + min + ' символов';
            object.disabledButton = true;
        } else if (value.length > max) {
            object.error[nameInput].max = 'Поле ' + nameInput + ' не может содержать более ' + max + ' символов';
            object.disabledButton = true;
        } else if (value.length > min && value.length < max) {
            if (object.error !== undefined) {
                delete object.error[nameInput];
                if (!object.error.email && !object.error.login && !object.error.password) {
                    object.disabledButton = false;
                }
            }
        }
    }