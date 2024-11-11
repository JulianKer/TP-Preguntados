let popup = document.querySelector(".popupCompleto");
let btnReanudar = document.getElementById("btnReanudar");

if (btnReanudar){
    btnReanudar.addEventListener("click", ()=>{
        popup.style.display = "none";
    })
}


// ----------- para perfil ----------------------
let qr = document.querySelector(".qr");
if (qr){
    qr.addEventListener("click", ()=>{
        popup.style.display = "flex";
    })
}
// --------------------------------------------



// ----------- para ranking ----------------------
let infoIcon = document.getElementById("infoIcon");
if (infoIcon){
    infoIcon.addEventListener("click", ()=>{
        popup.style.display = "flex";
    })
}
// --------------------------------------------
