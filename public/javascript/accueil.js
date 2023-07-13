document.getElementById("accueil_form_promoter").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_registered").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_notRegistered").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_oldEvent").addEventListener("click", checkClickFunc);
function checkClickFunc() {
    let liste = document.getElementById("accueil_form_site");
    document.forms['accueil_form'].submit();
}