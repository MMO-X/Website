<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "oauth.php";

if (get("action") == "login") {
    $data = botAuth();
    $params = array(
        "client_id" => $data["client_id"],
        "redirect_uri" => $data["redirect_uri"],
        "response_type" => "code",
        "scope" => $data["scope"]
    );
    $url = "https://discordapp.com/api/oauth2/authorize" . "?" . http_build_query($params);
	header("Location: " . $url);
	die();
}

if (get("action") == "logout") {
	if (session("access_token")) {
		$data = botAuth();
		apiRequest($data["discord_token_url"] . "/revoke", ["token" => $_SESSION["access_token"]]);
		unset($_SESSION["access_token"]);
		header("Location: https://mmo.xxarox.de/apply/");
	}
}

if (get("code")) {
    $data = botAuth();
    $token = apiRequest($data["discord_token_url"], array(
        "grant_type" => "authorization_code",
        "client_id" => $data["client_id"],
        "client_secret" => $data["client_secret"],
        "redirect_uri" => $data["redirect_uri"],
        "code" => get("code")
    ));
    $logout_token = $token["access_token"];
	$_SESSION["access_token"] = $token["access_token"];
	header("Location: https://mmo.xxarox.de/apply/");
}

if (session("access_token")) {
    $data = botAuth();
    $user = apiRequest($data["discord_api_url"] . "/users/@me");
	$guild = apiRequest($data["discord_token_url"] . "/guilds/810486613275967568/members/{$user["id"]}", ["token" => $_SESSION["access_token"]], [], true);
	var_dump($guild);
}

echo "Error, while redirect, <a href='https://mmo.xxarox.de/oauth?action=login'>click here</a>";