export const fetchWithToken = (url, options = {}) => {
    return new Promise(async (resolve, reject) => {
        let res, data;
        try {
            res = await fetch(url, {
                ...options,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "X-Requested-With": "XMLHttpRequest",
                    ...(options.headers || {}),
                },
            });

            data = await res.json();
            resolve({ res, data });
        } catch (error) {
            reject({ res, data, error });
        }
    });
};


export const builtValidationElement = (errors) => {
    const keyOfErrors = Object.getOwnPropertyNames(errors);
    return keyOfErrors
        .map((key) => `<li class="d-block">${errors[key][0]}</li>`)
        .join("");
};

export const toFormurlenconded = (data) => {
    let formBody = [];
    for (var property in data) {
        var encodedKey = encodeURIComponent(property);
        var encodedValue = encodeURIComponent(data[property]);
        formBody.push(encodedKey + "=" + encodedValue);
    }
    return formBody.join("&");
};
