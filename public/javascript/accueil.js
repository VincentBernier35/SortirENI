document.getElementById("accueil_form_promoter").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_registered").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_notRegistered").addEventListener("click", checkClickFunc);
document.getElementById("accueil_form_oldEvent").addEventListener("click", checkClickFunc);
function checkClickFunc() {
    document.forms['accueil_form'].submit();
}