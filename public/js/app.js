function event() {
    document.getElementById("Ville").addEventListener("click",ajouterLieu());
}

function ajouterLieu(){
    var ville = document.getElementById('Ville').value;


    var lieu =

    var liLieu=document.createElement("li");
    var pLieu= document.createElement("p");

    pLieu.innerText=lieu;

    var lieu=document.getElementById("listeLieu");
    listeLieu.appendChild(liLieu);
    liLieu.appendChild(pLieu);
}
