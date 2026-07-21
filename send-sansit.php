<?php
// send-sansit.php — odbiór ankiety/zapisu z hellosansit.com → mail na biuro@qubatura.eu.
// Wariant mailera qubatura.eu (src/send.php) dostrojony pod Sansita: pola ankiety + email.
// Dane zostają na NASZYM serwerze (żaden pośrednik) — czysto pod RODO. PHP 7.4, bez zależności.
//
// WGRANIE (przy decyzji o hostingu):
//  - jeśli Sansit ląduje na LiteSpeed (ta sama serwerownia) → wrzuć obok index.html,
//    front strzela same-origin (ustaw SURVEY_ENDPOINT='send-sansit.php') — CORS zbędny.
//  - jeśli Sansit zostaje na GitHub Pages → wrzuć na qubatura.eu, a poniższy CORS
//    przepuści POST z domeny Sansita (uzupełnij $ALLOW o docelową domenę).

$ALLOW = array(
  'https://hellosansit.com',
  'https://www.hellosansit.com',
  'https://qubatura.github.io',   // GitHub Pages (podgląd/tymczasowo)
);
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $ALLOW, true)) {
  header('Access-Control-Allow-Origin: ' . $origin);
  header('Vary: Origin');
  header('Access-Control-Allow-Methods: POST, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
}
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405); echo json_encode(array('ok' => false, 'error' => 'method')); exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { $data = $_POST; }
$get = function ($k) use ($data) { return isset($data[$k]) ? trim((string) $data[$k]) : ''; };

// honeypot — bot wypełnił ukryte pole → udajemy sukces, ale NIE wysyłamy
if ($get('botcheck') !== '') { echo json_encode(array('ok' => true)); exit; }

$subject = $get('subject'); if ($subject === '') { $subject = 'Sansit — sygnał'; }
$lang    = $get('lang');
$kontakt = $get('kontakt');           // email (zapis) — opcjonalny
$message = $get('message');           // gotowy, czytelny tekst zbudowany po froncie

if ($message === '' && $kontakt === '') {
  http_response_code(422); echo json_encode(array('ok' => false, 'error' => 'empty')); exit;
}

$noCRLF  = function ($s) { return str_replace(array("\r", "\n"), ' ', $s); };
$subject = $noCRLF($subject);

$to   = 'biuro@qubatura.eu';
$body =
  "Sygnał z hellosansit.com\n" .
  "Język: " . ($lang !== '' ? $lang : '(brak)') . "\n" .
  ($kontakt !== '' ? "Email: " . $kontakt . "\n" : '') .
  "\n" . $message . "\n";

$headers  = "From: hellosansit.com <no-reply@qubatura.eu>\r\n";
if (filter_var($kontakt, FILTER_VALIDATE_EMAIL)) {
  $headers .= "Reply-To: " . $noCRLF($kontakt) . "\r\n";
}
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

$subjectEnc = '=?UTF-8?B?' . base64_encode($subject) . '?=';
$ok = @mail($to, $subjectEnc, $body, $headers);

if ($ok) { echo json_encode(array('ok' => true)); }
else { http_response_code(500); echo json_encode(array('ok' => false, 'error' => 'mail')); }
