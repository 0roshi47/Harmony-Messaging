const GET_MESSAGE_URL =
    "http://localhost/R4A.10/Harmony-Messaging/server/GetMessage.php?limit=";
const POST_MESSAGE_URL =
    "http://localhost/R4A.10/Harmony-Messaging/server/SendMessage.php";

$(document).ready(function () {
    $("form").on("submit", function (e) {
        const message = $("#message-field").val();
        const speudo = $("#pseudo-field").val();
        postMessage(message, speudo);
        e.preventDefault();
    });
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
    });
}

function getLastMessage() {
    $.ajax({
        type: "GET",
        url: GET_MESSAGE_URL + 1, //get le dernier message
        success: function (data) {
            console.log(data);
            // alert(data);
            const result = JSON.parse(data);
            createMessage(result[0]);
        },
    });
}

function updateMessages() {
    $.ajax({
        type: "GET",
        url: GET_MESSAGE_URL,
        success: function (data) {
            console.log(data);
            alert(data);
        },
    });
}

function createMessage(jsonMessage) {
    $("#message-list").append(
        "<p class='content'>" + jsonMessage["content"] + "</p>",
    );
}
