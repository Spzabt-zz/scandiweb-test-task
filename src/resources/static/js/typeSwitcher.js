function switchProductType(str) {
    if (str === "") {
        document.getElementById("productAttr").innerHTML = this.responseText;
    } else {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("productAttr").innerHTML = xmlHttp.responseText;
            }
        };
        xmlHttp.open("GET", "switchType.php?q=" + str, true);
        xmlHttp.send();
    }
}