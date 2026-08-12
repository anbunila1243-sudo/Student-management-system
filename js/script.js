window.onload = function () {

    const btn = document.querySelector(".theme-toggle");

    if(localStorage.getItem("theme") === "dark"){
        document.body.classList.add("dark-mode");
    }

    if(btn){
        btn.innerHTML = document.body.classList.contains("dark-mode")
        ? "☀ Light Mode"
        : "🌙 Dark Mode";
    }

};

function toggleTheme(){

    document.body.classList.toggle("dark-mode");

    const btn = document.querySelector(".theme-toggle");

    if(document.body.classList.contains("dark-mode")){
        localStorage.setItem("theme","dark");
        if(btn) btn.innerHTML="☀ Light Mode";
    }else{
        localStorage.setItem("theme","light");
        if(btn) btn.innerHTML="🌙 Dark Mode";
    }

}