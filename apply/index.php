<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!empty($_POST)) {
	var_dump($_POST);
}

session_start();
include_once "../oauth/oauth.php";

include_once "../Medoo/SQLite3Database.php";
$database = new \xxAROX\SQLite3Database(__DIR__ . "/database.db");
if (!$database->isTable("apply")) {
	$database->createTable("apply", "displayName VARCHAR, id VARCHAR(18) PRIMARY KEY,`group` VARCHAR,enabled BOOLEAN DEFAULT 'true',texture VARCHAR,color VARCHAR");
}
$isApplyEnabled = file_get_contents(__DIR__ . "/.isApplyEnabled") == "y";
$ranks = [];
foreach ($database->getMedoo()->select("apply", "*") as $_ => $object) {
    unset($object["icon_textures"]);
	$ranks[] = $object;
}

function isLoggedIn(): bool{
	return isset($_SESSION["access_token"]);
}
$user = null;
if (isLoggedIn()) {
	$data = botAuth();
	$user = apiRequest($data["discord_api_url"] . "/users/@me");
	//var_dump($user);
}

if (isLoggedIn() && !$isApplyEnabled) {
    header("Location: ../oauth/index.php?action=logout");
}
?>
<html>

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <title>Home - MMOX</title>
    <meta name="description" content="MMOX is a Minecraft: Bedrock Edition Multiplayer Server made in Germany">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css?h=fef2672e317a8a947b5624d3e6a24577">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="../assets/fonts/simple-line-icons.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css?h=2cbf12caab31562d03bae9544edcad5f">
    <link rel="stylesheet" href="../assets/css/styles.css?h=2ccb56d13bf21fd0038063f962753e89">
</head>

<body id="page-top"><a class="menu-toggle rounded" href="#"><i class="fa fa-bars"></i></a>
<nav class="navbar navbar-light navbar-expand" id="sidebar-wrapper">
    <div class="container">
        <button data-toggle="collapse" data-target="#" class="navbar-toggler d-none"></button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav sidebar-nav" id="sidebar-nav">
                <li class="nav-item sidebar-brand">
                    <a class="nav-link active js-scroll-trigger" href="../">MMOX</a>
                </li>
                <li class="nav-item sidebar-nav-item">
                    <a class="nav-link js-scroll-trigger" href="../">Home</a>
                </li>
                <?php
				if ($isApplyEnabled) {
				    echo '<li class="nav-item sidebar-nav-item">
                    <a class="nav-link js-scroll-trigger" href="#apply">Apply</a>
                </li>';
					if (isLoggedIn()) {
						echo '<li class="nav-item sidebar-nav-item fixed-bottom">
                            <span class="nav-link js-scroll-trigger" style="color: white"><b><img style="height: 35px; padding-right: 5px" class="rounded-circle" src="' . makeAvatarURL($user) . '" alt="' . $user["avatar"] . '" />' . "{$user["username"]}#{$user["discriminator"]}" . '</b><button onclick="window.location.href = \'../oauth/index.php?action=logout\';" style="margin-left: 15px" class="btn btn-danger">Logout</button></span>
                    </li>';
					} else {
						echo '<li class="nav-item sidebar-nav-item fixed-bottom">
                            <a class="nav-link js-scroll-trigger" href="../oauth/index.php?action=login" style="color: limegreen"><b>Login</b></a>
                    </li>';
					}
				} else {
					echo '<li class="nav-item sidebar-nav-item">
                    <a class="nav-link js-scroll-trigger">Application phase is <span style="color: crimson">CLOSED</span></a>
                </li>';
				}
                ?>
            </ul>
        </div>
    </div>
</nav>
<header class="d-flex masthead" style="background: url('https://cdn.discordapp.com/attachments/810576465632559185/835794643572228127/nlfusa1c16z41.png');background-size: cover;">
    <div class="container text-center my-auto">
        <h1 class="mb-1" style="color: white; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000">Application phase:</h1>
        <?php
        if ($isApplyEnabled && !empty($ranks)) {
			echo '<h3 class="mb-5" style="color: green; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000"><em>OPEN</em></h3>
                  <a class="btn btn-success btn-xl js-scroll-trigger" role="button" href="#apply" style=" text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000">Apply</a>';
        } else {
			echo '<h3 class="mb-5" style="color: crimson; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000"><em>CLOSED</em></h3>';
        }
        ?>
        <div class="overlay"></div>
    </div>
</header>
<?php   if ($isApplyEnabled):   ?>
<section id="apply" class="callout login-dark text-white" style="background: rgb(29,128,159);">
    <div class="container text-center">
        <form id="apply-form" action="index.php" method="post">
            <?php   if (isset($_POST["apply"])):    ?>
            <div class="illustration" id="apply-form-success">
                <i class="fas fa-check" data-toggle="tooltip" style="color: var(--green);"></i>
                <h1 style="color: var(--green);">Success</h1>
            </div>
            <?php   else:   ?>
            <div class="d-none illustration" id="apply-form-success">
                <i class="fas fa-check" data-toggle="tooltip" style="color: var(--green);"></i>
                <h1 style="color: var(--green);">Success</h1>
            </div>
            <div id="apply-form-div" class="illustration">
                <?php
                if (!isLoggedIn() || is_null($user)) {
					echo '<a href="../oauth/index.php?action=login"><button class="btn btn-primary btn-block" data-toggle="tooltip" type="button">Login to discord</button></a>';
				} else {
                    echo '<img src="' . makeAvatarURL($user) . '" class="rounded-circle" />';
                    echo '<h4  style="color: #6b78b5; font-family: Arial">' . "{$user["username"]}#{$user["discriminator"]}" . '</h4>';
                    $select = '
                        <optgroup label="Staff" style="color: #001aff; font-family: Arial">
                            <option value="810486613275967573" style="color: #000000;">Builder</option>
                            <option value="810486613275967575" style="color: #000000;" selected>Moderator</option>
                            <option value="810486613275967572" style="color: #000000;">Designer</option>
                            <option value="810486613275967574" style="color: #000000;">Developer</option>
                        </optgroup>
                        <optgroup label="Other" style="color: #001aff; font-family: Arial"">
                            <option value="811549920904871956" style="color: #000000;">Source of Content</option>
                        </optgroup>
                        <optgroup label="Community" style="color: #001aff; font-family: Arial"">
                            <option value="812294163352780801" style="color: #000000;">Partner</option>
                            <option value="812334452315389992" style="color: #000000;">Promoter</option>
                        </optgroup>
                    ';
                    $elements = [];
					$defaultSet = false;
					if (empty($ranks)) {
					    $select = "<option value='null' style='color: crimson;' disabled>Not available</option>";
                    } else {
						foreach ($ranks as $_ => $object) {
							if (!isset($elements[$object["group"]])) {
								$elements[$object["group"]] = [];
							}
							if (!$defaultSet) {
								$defaultSet = true;
								$elements[$object["group"]][] = "<option data-thumbnail='https://raw.githubusercontent.com/MMO-X/Resource-Pack/master/textures/forms/{$object["texture"]}.png' value='{$object["id"]}' style='color: {$object["color"]};' selected>{$object["displayName"]}</option>";
							} else {
								$elements[$object["group"]][] = "<option data-thumbnail='https://raw.githubusercontent.com/MMO-X/Resource-Pack/master/textures/forms/{$object["texture"]}.png' value='{$object["id"]}' style='color: {$object["color"]};'>{$object["displayName"]}</option>";
							}
						}
						$select = "";
						foreach (array_keys($elements) as $group) {
							$select .= '<optgroup label="' . $group . '" style="color: #001aff; font-family: Arial"">' . implode(PHP_EOL . "\t", $elements[$group]) . '</optgroup>';
						}
                    }
					echo '
                <div class="form-group apply-form-action-group">
                    <select onchange="onChangeRankSelection()" class="form-control form-control-lg" id="apply-group" name="rank" value="Rank">
                    ' . $select . '
                    </select>
                </div>
                <input type="text" class="form-control" name="discord-id" value="' . $user["id"] . '" hidden/>
                <input type="text" class="form-control" name="apply" value="true" hidden/>
                <div class="form-group apply-form-action-group">
                </div>
                <div id="rankFormGroups" hidden>
                    <div class="form-group apply-form-action-group">
                        <input type="text" class="form-control"
                               id="apply-discord-id" name="discord-id"
                               placeholder="Discord ID" maxlength="18"
                               minlength="18"/>
                    </div>
                    <div class="form-group apply-form-action-group">
                        <input type="text" class="form-control"
                               id="apply-username" name="username"
                               placeholder="Username" maxlength="32"
                               minlength="5"/>
                    </div>
                    <div class="form-group apply-form-action-group">
                        <textarea class="form-control" id="apply-text"
                                  placeholder="Write something about yourself"
                                  name="text" maxlength="2000"
                                  minlength="120">
    
                        </textarea>
                    </div>
                </div>
                <div class="form-group apply-form-action-group">
                    <button class="btn btn-primary btn-block" data-toggle="tooltip" type="submit"
                            title="You have to join on the MMOX discord server." onclick="sendApplication();">Apply
                    </button>
                </div>';
                }
                ?>
            </div>
            <?php   endif;  ?>
        </form>
    </div>
</section>
<?php
endif;
?>
<footer class="text-center footer">
    <div class="container">
        <ul class="list-inline mb-5">
            <li class="list-inline-item"><a class="text-white social-link rounded-circle"
                                            href="https://paypal.me/xxarox"><i class="icon-paypal"></i></a></li>
            <li class="list-inline-item"><a class="text-white social-link rounded-circle"
                                            href="https://twitter.com/xx_arox"><i class="icon-social-twitter"></i></a>
            </li>
            <li class="list-inline-item"><a class="text-white social-link rounded-circle"
                                            href="https://github.com/MMO-X"><i class="icon-social-github"></i></a></li>
            <li class="list-inline-item"><a class="text-white social-link rounded-circle"
                                            href="https://www.patreon.com/arox_xx"><i class="fab fa-patreon"></i></a>
            </li>
        </ul>
        <p class="text-muted mb-0 small">Copyright  © Moobloom Solutions 2021</p>
    </div>
    <a class="js-scroll-trigger scroll-to-top rounded" href="#page-top"><i class="fa fa-angle-up"></i></a>
</footer>
<script src="../assets/js/jquery.min.js?h=89312d34339dcd686309fe284b3f226f"></script>
<script src="../assets/bootstrap/js/bootstrap.min.js?h=ba42258394f86cd7822f0e850e2d60b1"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="../assets/js/script.js?h=846c454eed525f04ee48fd790b60a066"></script>
<script>
	let current = "n";
	$.get("/apply/.isApplyEnabled", function (current) {
		setInterval(() => {
			$.get("/apply/.isApplyEnabled", function (data) {
				if (current !== data) {
					location.reload();
				}
			}, "text");
		}, 1000 * 2);
	}, 'text');
</script>
</body>

</html>