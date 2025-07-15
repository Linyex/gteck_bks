<?php

class searchController extends BaseController {
    
    public function index() {
        return $this->mane();
    }
    
    public function mane() {
        $query = $this->getGet('q');
        $results = [];
        
        if ($query) {
            $results = $this->performSearch($query);
        }
        
        return $this->render('search/mane', [
            'query' => $query,
            'results' => $results,
            'title' => 'Поиск'
        ]);
    }
    
    private function performSearch($query) {
        $results = [];
        $filesFile = "application/views/allfile.dat";
        
        if (!file_exists($filesFile)) {
            return $results;
        }
        
        $files = file($filesFile);
        
        foreach ($files as $file) {
            $content = file_get_contents(trim($file));
            if (strpos(mb_strtolower($content), mb_strtolower($query)) !== false) {
                $fullstr = mb_strtolower(mb_substr(
                    preg_replace('/[^а-яА-Я,.]/ui', ' ', 
                    stristr(mb_strtolower($content), mb_strtolower($query))), 0, 500));
                
                $fullstr2 = $this->mb_ucfirst($fullstr);
                
                $silka = mb_substr(trim($file), 18);
                $extension = trim($silka, ".php");
                
                $mytext = preg_replace('/'.$query.'/', 
                    '<span style="background-color: #ffe403;">'.$query.'</span>', $fullstr2);
                $mytext2 = preg_replace('/'.$this->mb_ucfirst($query).'/', 
                    '<span style="background-color: #ffe403;">'.$this->mb_ucfirst($query).'</span>', $mytext);
                
                $results[] = [
                    'title' => trim($file),
                    'fullstr' => $mytext2,
                    'silks' => $extension,
                ];
    }
        }
        
        return $results;
    }
    
    private function mb_ucfirst($str, $encoding = 'UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}
