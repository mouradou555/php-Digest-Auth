<?php
session_start();

$users = [
    'user' => 'password'
];

function http_digest_parse($txt) {
    $needed_parts = [
        'nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1
    ];
    $data = [];
    $keys = implode('|', array_keys($needed_parts));
      preg_match_all('@('.$keys.')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

  foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="Restricted area",qop="auth",nonce="'.uniqid().'",opaque="'.md5('Restricted area').'"');
    die('You have been logged out.');
}
if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="Restricted area",qop="auth",nonce="'.uniqid().'",opaque="'.md5('Restricted area').'"');
    die('Text to send if user hits Cancel button');
}

if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']])) {
    die('Wrong Credentials!');
}

$A1 = md5($data['username'] . ':Restricted area:' . $users[$data['username']]);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response) {
    die('Wrong Credentials!');
}

$_SESSION['username'] = $data['username'];


?>

<html>
<head>
        <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <a href="?logout=true">Logout</a>
    <br><br>

    <?php
    include 'dashboard.php';
    ?>
</body>
</html>
