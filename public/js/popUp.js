let popup = document.querySelector(".popupCompleto");
let btnReanudar = document.getElementById("btnReanudar");

if (btnReanudar){
    btnReanudar.addEventListener("click", ()=>{
        popup.style.display = "none";
    })
}

let qr = document.querySelector(".qr");

if (qr){
    qr.addEventListener("click", ()=>{
        popup.style.display = "flex";
    })
}