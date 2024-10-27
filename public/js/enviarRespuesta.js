let form = document.getElementById("form");

let labels = document.querySelectorAll("form");

labels.forEach((label)=>{
    label.addEventListener("click", ()=>{
        form.submit();
    })
})