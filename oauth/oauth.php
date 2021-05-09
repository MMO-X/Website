<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

/**
 * Function botAuth
 * @return string[]
 */
function botAuth(): array{
    return [
		"client_id" => "820085867996905472",
		"client_secret" => "Wlytmaxa25wcBmd_O9YzpecbbFsxIxv-",
		"scope" => "identify email guilds guilds.join",
		"redirect_uri" => "https://mmo.xxarox.de/oauth/index.php",
		"discord_token_url" => "https://discord.com/api/oauth2/token",
		"discord_api_url" => "https://discord.com/api/v6",
	];
}

/**
 * Function makeAvatarURL
 * @param array $user
 * @return string
 */
function makeAvatarURL(array $user): string{
	if (empty($user["avatar"])) {
		$avatarDiscrim = (int)$user["discriminator"] %5;
		return "https://cdn.discordapp.com/embed/avatars/{$avatarDiscrim}.png?size=100";
	}
	return "https://cdn.discordapp.com/avatars/{$user["id"]}/{$user["avatar"]}.png";
}

function apiRequest($url, $post = FALSE, $headers = array(), $put = false): array{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die(curl_error($ch));
    }
    if ($post) {
    	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	}
    $headers[] = "Accept: application/json";

    if (session("access_token")) {
    	$headers[] = "Authorization: Bearer " . session("access_token");
	}
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    //var_dump($response);
    return json_decode($response, true);
}

function session($key, $default = null) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

function get($key, $default = null) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
