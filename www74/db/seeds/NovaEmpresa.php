<?php

use Phinx\Seed\AbstractSeed;

class NovaEmpresa extends AbstractSeed {

    public function run() {
        $data = [
            [
                'nome' => 'Like Sistemas'
            ]
        ];

        $posts = $this->table('empresa');
        $posts->insert($data)
              ->saveData();
    }

}