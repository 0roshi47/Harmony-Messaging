const GET_MESSAGE_URL =
    "http://harmony-messaging-backend.alwaysdata.net/GetMessage.php";
const POST_MESSAGE_URL =
    "http://harmony-messaging-backend.alwaysdata.net/SendMessage.php";

$(document).ready(function () {
    $("form").on("submit", function (e) {
        const message = $("#message-field").val();
        const speudo = $("#pseudo-field").val();
        postMessage(message, speudo);
        clearMessageField();
        e.preventDefault();
    });
    getAllMessages(); // rempli tout les messages au lancement du site
});

function postMessage(message, pseudo) {
    console.log("Message : " + message);
    console.log("Speudo : " + pseudo);
    $.ajax({
        type: "POST",
        url: POST_MESSAGE_URL,
        data: JSON.stringify({ content: message, author: pseudo }),
        success: function (msg) {
            console.log("Message posté : " + msg);
            getLastMessage();
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

function getLastMessage() {
    $.ajax({
        type: "GET",
        url: GET_MESSAGE_URL,
        data: "limit=" + 1, //get le dernier message
        success: function (data) {
            // console.log(data);
            // alert(data[0]);
            // const result = JSON.parse(data);
            createMessage(data[0]);
        },
    });
}

function getAllMessages() {
    $.ajax({
        type: "GET", //sans parametre le serveur fait une requête sql sans limit de selection, il get tout
        url: GET_MESSAGE_URL,
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                createMessage(data[i]);
            }
        },
    });
}

function createMessage(jsonMessage) {
    const date = new Date(jsonMessage["postDate"]);
    $("#messages").append(
        "<div class='message from-them'>" +
            "<div class='bubble'>" +
            jsonMessage["content"] +
            "<div class='meta'>" +
            jsonMessage["author"] +
            " • " +
            date.getHours() +
            ":" +
            date.getMinutes() +
            "</div>" +
            "</div>" +
            "</div>",
    );
    messages.scrollTo({
        top: messages.scrollHeight,
        behavior: "smooth",
    });
}

function clearMessageField() {
    $("#message-field").val("");
}
