import axios from 'axios';

export function uploadImage(file, object) {
    object.justWait = true;
    let files = file.target.files || file.dataTransfer.files;
    if (!files.length) {
        return;
    }

    let fd = new FormData;

    for (let k of files) {
        fd.append('image['+ k.name +']', k);
    }

    fd.append('path_to_save_image', object.pathToSaveImage);
    fd.append('image_bundle', object.imageBundle);
    fd.append('image_width', object.imageWidth);
    fd.append('image_height', object.imageHeight);

    axios.post('/save_image', fd).then(function (response) {
        for ( let k of response.data.image ) {
            let newObject = {
                imageId: k.id,
                imageName: k.name,
                imageType: k.mimetype
            };
            object.imageResponse.push(newObject);
        }
    })

    object.imageUploaded = true;

    createImage(files, object);
}

function createImage(files, object) {
    object.justWait = false;

    for ( let k of files ) {
        let newObject = {
            file: k
        };
        object.image.push(newObject);
    }
}

export function removeImage(object) {
    object.image = [];
    object.imageUploaded = false;
}
