function validateProductFormInputFields(event) {
    event.preventDefault();

    let productForm = document.getElementById("product_form");
    let productFormData = new FormData(productForm);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open('POST', '/product-web-app/add-product/handle-validation', true);
    xmlHttp.send(productFormData);
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
            console.log(xmlHttp.responseText);
            try {
                let response = JSON.parse(xmlHttp.responseText);

                if (response.success) {
                    console.log('success');
                    window.location.href = "/product-web-app/";
                } else {
                    console.log('not success');

                    if (response.errors.sku_error !== '') {
                        document.getElementById('sku').classList.add("is-invalid");
                        document.getElementById('sku_error').innerHTML = response.errors.sku_error;
                    } else {
                        document.getElementById('sku').classList.remove("is-invalid");
                        document.getElementById('sku_error').innerHTML = response.errors.sku_error;
                    }

                    if (response.errors.name_error !== '') {
                        document.getElementById('name').classList.add("is-invalid");
                        document.getElementById('name_error').innerHTML = response.errors.name_error;
                    } else {
                        document.getElementById('name').classList.remove("is-invalid");
                        document.getElementById('name_error').innerHTML = response.errors.name_error;
                    }

                    if (response.errors.price_error !== '') {
                        document.getElementById('price').classList.add("is-invalid");
                        document.getElementById('price_error').innerHTML = response.errors.price_error;
                    } else {
                        document.getElementById('price').classList.remove("is-invalid");
                        document.getElementById('price_error').innerHTML = response.errors.price_error;
                    }

                    if (response.errors.productTypeError !== undefined && response.errors.productTypeError !== '') {
                        document.getElementById('productTypeError').innerHTML = response.errors.productTypeError;
                    } else {
                        document.getElementById('productTypeError').innerHTML = '';
                    }

                    if (response.errorAttrs && Object.keys(response.errorAttrs).length > 0)
                        for (let error in response.errorAttrs) {
                            let inputId = error.replace("Err", "");
                            console.log(inputId);

                            if (response.errorAttrs[error] !== '') {
                                document.getElementById(inputId).classList.add("is-invalid");
                                document.getElementById(error).innerHTML = response.errorAttrs[error];
                            } else {
                                document.getElementById(inputId).classList.remove("is-invalid");
                                document.getElementById(error).innerHTML = response.errorAttrs[error];
                            }
                        }
                }
            } catch (error) {
                console.log('Error parsing JSON response:', error);
            }
        }
    };
}