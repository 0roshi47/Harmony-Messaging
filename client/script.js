const GET_MESSAGE_URL =
	"https://harmony-messaging-backend.alwaysdata.net/GetMessage.php";
const POST_MESSAGE_URL =
	"https://harmony-messaging-backend.alwaysdata.net/SendMessage.php";

const DELAI_RECUPERATIONS_MS = 500; //recupérations des messages toute les 2 secondes

$(document).ready(function () {
	$("form").on("submit", function (e) {
		e.preventDefault();
		const message = $("#message-field").val();
		const pseudo = $("#pseudo-field").val();
		if (pseudo === "") {
			alert("Renseignez un pseudo");
			return;
		}
		if (message === "") {
			return; //vérifie que l'utilisateur a bien renseigné un message et un speudo
		} else postMessage(message, pseudo);
		clearMessageField();
	});
	getAllMessages(); // rempli tout les messages au lancement du site
	setInterval(pollMessages, DELAI_RECUPERATIONS_MS);
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
			// getLastMessage();
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

function pollMessages() {
	const LIMIT = 10;
	console.log("poll");
	$.ajax({
		type: "GET",
		url: GET_MESSAGE_URL,
		data: "limit=" + LIMIT, //get le dernier message
		success: function (data) {
			buildMessageList(data);
		},
	});
}

function getAllMessages() {
	$.ajax({
		type: "GET", //sans parametre le serveur fait une requête sql sans limit de selection, il get tout
		url: GET_MESSAGE_URL,
		success: function (data) {
			buildMessageList(data);
		},
	});
}

function buildMessageList(messageList) {
	for (var i = messageList.length - 1; i >= 0; i -= 1) {
		if (messageAlreadyExist(messageList[i]["idMessage"])) {
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
			jsonMessage["author"] +
			" • " +
			date.getHours() +
			":" +
			date.getMinutes() +
			"<input type='hidden' class='message-id' value=" +
			jsonMessage["idMessage"] +
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
