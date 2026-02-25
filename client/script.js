const GET_MESSAGE_URL =
    "http://localhost/R4A.10/Harmony-Messaging/server/GetMessage.php";
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
        },
    });
}
