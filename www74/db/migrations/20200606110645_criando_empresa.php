<?php

use Phinx\Migration\AbstractMigration;

class CriandoEmpresa extends AbstractMigration {

    public function change() {
        $table = $this->table('empresa');
        $table->addColumn('nome', 'string')
              ->create();
    }

}