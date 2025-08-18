<?php
// Встроенный упрощённый парсер XLSX (на базе публичной реализации SimpleXLSX, сокращённый)
// Поддерживает чтение листа в массив строк/ячеек без внешних зависимостей

class SimpleXLSX {
    private $workbook;
    private $sheets = [];
    private $sharedStrings = [];

    public static function parse($filename) {
        $xlsx = new self();
        if (!$xlsx->open($filename)) return false;
        return $xlsx;
    }

    public function open($filename) {
        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) return false;
        // sharedStrings
        $xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($xml) {
            $sx = new SimpleXMLElement($xml);
            foreach ($sx->si as $si) {
                if (isset($si->t)) $this->sharedStrings[] = (string)$si->t;
                elseif (isset($si->r)) {
                    $t = '';
                    foreach ($si->r as $r) { $t .= (string)$r->t; }
                    $this->sharedStrings[] = $t;
                }
            }
        }
        // workbook rels
        $wb = $zip->getFromName('xl/workbook.xml');
        $rels = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if (!$wb || !$rels) return false;
        $wbXml = new SimpleXMLElement($wb);
        $relsXml = new SimpleXMLElement($rels);
        $idToTarget = [];
        foreach ($relsXml->Relationship as $r) {
            $idToTarget[(string)$r['Id']] = (string)$r['Target'];
        }
        $sheetFiles = [];
        foreach ($wbXml->sheets->sheet as $s) {
            $rid = (string)$s['id'];
            $sheetFiles[] = 'xl/' . $idToTarget[$rid];
        }
        foreach ($sheetFiles as $f) {
            $xml = $zip->getFromName($f);
            if (!$xml) continue;
            $sx = new SimpleXMLElement($xml);
            $rows = [];
            foreach ($sx->sheetData->row as $row) {
                $r = [];
                foreach ($row->c as $c) {
                    $t = (string)$c['t'];
                    $v = isset($c->v) ? (string)$c->v : '';
                    if ($t === 's') {
                        $idx = (int)$v; $r[] = $this->sharedStrings[$idx] ?? '';
                    } else {
                        $r[] = $v;
                    }
                }
                $rows[] = $r;
            }
            $this->sheets[] = $rows;
        }
        $zip->close();
        return true;
    }

    public function rows($sheetIndex = 0) {
        return $this->sheets[$sheetIndex] ?? [];
    }
}




