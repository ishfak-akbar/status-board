<?php
namespace App\Core;

abstract class Controller {
    protected function view(string $path, array $data = []) {
        extract($data);
        
        
        include __DIR__ . '/../Views/' . $path;
    }
}
