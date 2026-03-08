const BASE_URL = "https://harmony-messaging-backend.alwaysdata.net/";

const MESSAGE_POLL_FREQUENCY = 1000;
const MESSAGE_POLLED_NUMBER = 10; //limit of messages get each poll
const ROOM_POLL_FREQUENCY = 2000;

$(document).ready(function () {
    verifyToken();
    $("form").on("submit", function (e) {
        verifyToken();
        e.preventDefault();
        const message = $("#message-field").val();
        if (message === "") {
            return; //vérifie que l'utilisateur a bien renseigné un message et un speudo
        } else postMessage(message, localStorage.getItem("selectedRoomId"));
        clearMessageField();
    });
    getAllMessages(); // rempli tout les messages au lancement du site
    setInterval(pollMessages, MESSAGE_POLL_FREQUENCY);
    getAllRooms();
});

function verifyToken() {
    const tokenJWT = localStorage.getItem("token");
    if (!tokenJWT) {
        disconnect();
    }
    //si un token existe on vérifie qu'il soit valide
    $.ajax({
        type: "POST",
        url: BASE_URL + "IsTokenValid.php",
        headers: {
            Authorization: "Bearer " + tokenJWT,
        },
        success: function (msg) {
            if (!msg["valid"]) {
                disconnect();
            }
        },
    });
}

function disconnect() {
    localStorage.removeItem("token");
    window.location.href = "../pages/connection.html";
}

function postMessage(message, roomId) {
    console.log("Message : " + message);
    $.ajax({
        type: "POST",
        url: BASE_URL + "SendMessage.php",
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
        data: JSON.stringify({ content: message, room: roomId }),
        success: function (msg) {
            console.log("Message posté : " + msg);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == "401") {
                disconnect();
            }
        },
    });
}

function pollMessages() {
    $.ajax({
        type: "GET",
        url: BASE_URL + "GetMessage.php",
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
        data:
            "room=" +
            localStorage.getItem("selectedRoomId") +
            "&limit=" +
            MESSAGE_POLLED_NUMBER, //get le dernier message
        success: function (data) {
            buildMessageList(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == "401") {
                disconnect();
            }
        },
    });
}

function getAllRooms() {
    $.ajax({
        type: "GET",
        url: BASE_URL + "GetRoom.php",
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
        success: function (data) {
            buildRoomList(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == "401") {
                disconnect();
            }
        },
    });
}

function buildRoomList(roomList) {
    resetRoomList();
    for (var i = roomList.length - 1; i >= 0; i -= 1) {
        createRoom(roomList[i]);
    }
}

function resetRoomList() {
    $(".room-list").empty();
}

function resetMessageList() {
    $(".messages").empty();
}

function createRoom(jsonRoom) {
    var $roomHtml;
    var roomId = jsonRoom["roomId"];
    if (!localStorage.getItem("selectedRoomId")) {
        localStorage.setItem("selectedRoomId", roomId);
    }
    isActive = roomId == localStorage.getItem("selectedRoomId");
    if (isActive) {
        $roomHtml = $("<li class='room active'></li>");
    } else {
        $roomHtml = $("<li class='room'></li>");
    }
    $roomHtml.append(
        "<div class='room-body'>" +
            "<div class='room-top'>" +
            "<span class='room-name'>" +
            jsonRoom["roomName"] +
            "</span>" +
            "<span class='room-time'>20:10</span>" +
            "</div>" +
            "<div class='room-last'>" +
            "Il fait plus souvent beau à Perpi" +
            "</div>" +
            "</div>" +
            "</li>",
    );
    $roomHtml.data("roomId", roomId);
    $roomHtml.click(function () {
        var selectedRoomId = $(this).data("roomId");
        if (localStorage.getItem("selectedRoomId") == selectedRoomId) {
            return;
        }
        localStorage.setItem("selectedRoomId", selectedRoomId);
        getAllRooms();
        getAllMessages();
    });
    $(".room-list").append($roomHtml);
}

function getAllMessages() {
    resetMessageList();
    $.ajax({
        type: "GET", //sans parametre le serveur fait une requête sql sans limit de selection, il get tout
        headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
        },
        url: BASE_URL + "GetMessage.php",
        data: "room=" + localStorage.getItem("selectedRoomId"), //get le dernier message
        success: function (data) {
            buildMessageList(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == "401") {
                disconnect();
            }
        },
    });
}

function buildMessageList(messageList) {
    for (var i = messageList.length - 1; i >= 0; i -= 1) {
        if (messageAlreadyExist(messageList[i]["messageId"])) {
            continue;
        }
        createMessage(messageList[i]);
    }
}

function createMessage(jsonMessage) {
    const date = new Date(jsonMessage["postDate"]);
    $("#messages").append(
        "<div class='message from-them'>" +
            "<div class='bubble'>" +
            jsonMessage["content"] +
            "<div class='meta'>" +
            jsonMessage["username"] +
            " • " +
            date.getHours() +
            ":" +
            date.getMinutes() +
            "<input type='hidden' class='message-id' value=" +
            jsonMessage["messageId"] +
            " />" +
            "</div>" +
            "</div>" +
            "</div>",
    );
    messages.scrollTo({
        top: messages.scrollHeight,
        behavior: "smooth",
    });
}

function messageAlreadyExist(messageId) {
    //renvoie un boolean selon si oui ou non le message est déjà dans la liste html des messages
    return (
        $(".message-id").filter(function () {
            return $(this).val() == messageId;
        }).length > 0
    );
}

function clearMessageField() {
    $("#message-field").val("");
}
