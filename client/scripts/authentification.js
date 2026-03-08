const BASE_URL = "https://harmony-messaging-backend.alwaysdata.net/";

$(document).ready(function () {
    $("#inscriptionForm").on("submit", submitInscriptionForm);
    $("#loginForm").on("submit", submitLoginForm);
});

function submitInscriptionForm(e) {
    e.preventDefault();
    const email = $("#email").val();
    const username = $("#username").val();
    const password = $("#password").val();

    $.ajax({
        type: "POST",
        url: BASE_URL + "CreateUser.php",
        data: JSON.stringify({
            password: password,
            email: email,
            username: username,
        }),
        success: function (msg) {
            console.log("Compte crée");
            getToken(email, password);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(
                "jqXHR : " +
                    jqXHR +
                    " textStatus : " +
                    textStatus +
                    " errorThrown : " +
                    errorThrown,
            );
        },
    });
}

function submitLoginForm(e) {
    e.preventDefault();
    const email = $("#email").val();
    const password = $("#password").val();
    getToken(email, password);
}

function getToken(email, password) {
    $.ajax({
        type: "POST",
        url: BASE_URL + "Authentification.php",
        data: JSON.stringify({
            password: password,
            email: email,
        }),
        success: function (msg) {
            const token = msg;
            console.log("Token : " + token);
            localStorage.setItem("token", token);
            window.location.href = "../pages/chat.html";
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(
                "jqXHR : " +
                    jqXHR +
                    " textStatus : " +
                    textStatus +
                    " errorThrown : " +
                    errorThrown,
            );
        },
    });
}
