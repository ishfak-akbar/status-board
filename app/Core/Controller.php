<?php
namespace App\Core;

abstract class Controller {
    protected function view(string $path, array $data = []) {
        extract($data);
        
        // Simply include the view file and let IT handle everything
        include __DIR__ . '/../Views/' . $path;
    }
}
