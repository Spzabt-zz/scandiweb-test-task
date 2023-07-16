function switchProductType(str) {
    if (str === "") {
        document.getElementById("productAttr").innerHTML = this.responseText;
    } else {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "/product-web-app/add-product/switch-type?q=" + encodeURIComponent(str), true);
        xmlHttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("productAttr").innerHTML = xmlHttp.responseText;
            }
        };
        xmlHttp.send();
    }
}
