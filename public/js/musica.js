// PARA QUE LA MUSICA FUNCIONE NO HAY QUE LINKEAR NADA PQ EL LINK DEL JS ESTE YA LO TIENE EL HEADER (q lo inlcuyen todas las pags)
// y el header tmb tiene el input hidden para saber si tiene q estar activada o no la musica

document.addEventListener("DOMContentLoaded", () => {
    const musicaActivada = document.getElementById("valueMusica").value;
    const audio = document.getElementById("fondo-musica");
    console.log(musicaActivada)
    console.log(audio)
    if (musicaActivada) {
        //audio.play(); // este no lo puedo poner pa me salta un error de que no puedo hacerle play pq necesita interaccion del user pero bueno lo "solucione" poniendo el autoload en el audio, eso si, si recarga la pag, no anda el audio, pero si recarga con f5 o se va a otra si que anda
    } else {
        audio.pause();
    }

    document.querySelectorAll('input[name="musica"]').forEach((elem) => {
        elem.addEventListener("change", () => {
            const musicaActivada = elem.value === "SI";
            console.log("aaa" + musicaActivada)

            if (musicaActivada) {
                audio.play();
            } else {
                audio.pause();
            }

            document.getElementById('musicaForm').submit();
        });
    });
});