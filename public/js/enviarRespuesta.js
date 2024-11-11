let form = document.getElementById("form");
let labels = document.querySelectorAll(".option-button");

let btnNext = document.querySelector(".next-button");
let btnVolver = document.querySelector(".back-button");

labels.forEach((label)=>{
    label.addEventListener("click", (e)=>{
        if (!e.target.closest("label")) {
            e.stopPropagation();
            e.preventDefault();
        }else{
            if (btnNext || btnVolver){
                e.preventDefault();
            }else{
                form.submit();
            }
        }
    })
})




