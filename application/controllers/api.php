<?php

require_once ENGINE_DIR . 'main/db.php';
require_once APPLICATION_DIR . 'models/ContentModel.php';
require_once APPLICATION_DIR . 'models/TranslationModel.php';

class apiController extends BaseController {
    public function index() {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);
    }

    // POST /api/translate
    // body: { lang: 'en', html: '<html>...</html>' }
    public function translate() {
        // Гарантируем чистый JSON
        if (ob_get_level() > 0) { @ob_end_clean(); }
        header_remove('X-Powered-By');
        header('Content-Type: application/json; charset=utf-8');
        try {
            $dst = trim($_POST['lang'] ?? '');
            $html = (string)($_POST['html'] ?? '');
            if ($dst === '' || $html === '') { echo json_encode(['success'=>false,'message'=>'lang/html required']); return; }
            $allowed = ['ru','be','en','zh','fr','es','ja','hi','ar','pt','ur','bn'];
            if (!in_array($dst, $allowed, true)) { echo json_encode(['success'=>false,'message'=>'lang not allowed']); return; }
            // Detect source language by cookie or default
            $src = $_COOKIE['lang'] ?? 'ru';
            if (!in_array($src, $allowed, true)) { $src = 'ru'; }
            $translated = $this->machineTranslateHtml($html, $src, $dst);
            // mark as translated and set <html lang="...">
            $translated = preg_replace('/<html(\s+[^>]*)?>/i', function($m) use ($dst){
                $attrs = $m[1] ?? '';
                // remove existing lang
                $attrs = preg_replace('/\slang=["\'][^"\']*["\']/', '', $attrs);
                return '<html lang="' . htmlspecialchars($dst, ENT_QUOTES, 'UTF-8') . '" data-translated="1"' . $attrs . '>';
            }, $translated, 1);
            echo json_encode(['success'=>true,'html'=>$translated], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(200);
            echo json_encode(['success'=>false,'message'=>'translate error'], JSON_UNESCAPED_UNICODE);
        }
    }

    private function machineTranslateHtml(string $html, string $src, string $dst): string {
        // Mask <script> and <style> blocks to avoid translation inside them
        $maskedBlocks = [];
        $blockIndex = 0;
        $out = preg_replace_callback('/<(script|style)([^>]*)>([\s\S]*?)<\/\1>/i', function($m) use (&$maskedBlocks, &$blockIndex) {
            $ph = '%%BLOCK' . ($blockIndex++) . '%%';
            $maskedBlocks[$ph] = $m[0];
            return $ph;
        }, $html);

        // Extract text nodes via simple placeholdering
        $placeholders = [];
        $texts = [];
        $i = 0;
        $pattern = '/>([^<>]+)</u';
        $out = preg_replace_callback($pattern, function($m) use (&$i, &$placeholders, &$texts) {
            $txt = trim($m[1]);
            if ($txt === '' || preg_match('/^\s+$/u', $txt)) return $m[0];
            // skip if only numbers or punctuation
            if (preg_match('/^[\d\s\p{P}]+$/u', $txt)) return $m[0];
            $key = '%%T' . ($i++) . '%%';
            $placeholders[$key] = $m[1];
            $texts[] = $m[1];
            return '>' . $key . '<';
        }, $out);
        $model = new TranslationModel();
        $cache = $model->getMany($src, $dst, $texts);
        $toTranslate = [];
        foreach ($texts as $t) {
            $h = sha1($t);
            if (!isset($cache[$h])) { $toTranslate[$t] = true; }
        }
        $newPairs = [];
        if (!empty($toTranslate)) {
            $chunk = array_keys($toTranslate);
            $apiPairs = $this->callTranslationApi($chunk, $src, $dst);
            foreach ($apiPairs as $orig => $tr) { $newPairs[$orig] = $tr; }
            if (!empty($newPairs)) { $model->saveMany($src, $dst, $newPairs); }
        }
        // Build final map
        $finalMap = [];
        foreach ($texts as $t) {
            $h = sha1($t);
            $finalMap[$t] = $newPairs[$t] ?? ($cache[$h] ?? $t);
        }
        // Replace placeholders back
        $out = preg_replace_callback('/%%T(\d+)%%/', function($m) use ($placeholders, $finalMap) {
            $placeholder = $m[0];
            $orig = $placeholders[$placeholder] ?? '';
            $val = $finalMap[$orig] ?? $orig;
            return $val;
        }, $out);

        // Restore masked blocks
        $out = preg_replace_callback('/%%BLOCK(\d+)%%/', function($m) use ($maskedBlocks) {
            return $maskedBlocks[$m[0]] ?? '';
        }, $out);
        return $out;
    }

    private function callTranslationApi(array $texts, string $src, string $dst): array {
        $pairs = [];
        if (empty($texts)) return $pairs;
        // Prefer Google Translate v2 if API key provided
        $apiKey = getenv('GOOGLE_TRANSLATE_API_KEY') ?: ($this->settings['google_translate_api_key'] ?? '');
        if ($apiKey) {
            // Batch request (multiple q params)
            $params = [
                'target' => $dst,
                'source' => $src,
                'format' => 'text',
            ];
            $postFields = http_build_query($params, '', '&');
            foreach ($texts as $t) {
                $postFields .= '&' . http_build_query(['q' => $t]);
            }
            $ctx = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $postFields,
                    'timeout' => 8,
                ]
            ]);
            $url = 'https://translation.googleapis.com/language/translate/v2?key=' . urlencode($apiKey);
            $resp = @file_get_contents($url, false, $ctx);
            if ($resp) {
                $j = json_decode($resp, true);
                if (!empty($j['data']['translations'])) {
                    $i = 0;
                    foreach ($texts as $t) {
                        $pairs[$t] = $j['data']['translations'][$i]['translatedText'] ?? $t;
                        $i++;
                    }
                    return $pairs;
                }
            }
            // If Google failed, fall back to MyMemory
        }
        foreach ($texts as $t) {
            $q = urlencode($t);
            $url = 'https://api.mymemory.translated.net/get?q=' . $q . '&langpair=' . urlencode($src.'|'.$dst);
            $resp = @file_get_contents($url);
            if ($resp) {
                $j = json_decode($resp, true);
                if (isset($j['responseData']['translatedText'])) {
                    $pairs[$t] = $j['responseData']['translatedText'];
                    continue;
                }
            }
            $pairs[$t] = $t;
        }
        return $pairs;
    }
    // GET /api/content-overrides?path=/...
    public function contentoverrides() {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $path = $_GET['path'] ?? '/';
            $model = new ContentModel();
            // Пытаемся учесть и URL без завершающего слэша, и с ним
            $alt = rtrim($path, '/'); if ($alt === '') $alt = '/';
            $paths = array_values(array_unique([$path, $alt]));
            $rows = $model->getOverridesForPaths($paths);
            // Гарантируем массив
            if (!is_array($rows)) { $rows = []; }
            echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(200);
            echo json_encode([]);
        }
    }
}


