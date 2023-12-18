<?php

declare(strict_types=1);

namespace App\DDDBundle\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231218215833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'published column index in the event_store table';
    }

    public function up(Schema $schema): void
    {
        $schema->getTable('event_store')->addIndex(['published'], 'IDX_BE4CE95B683C6017');
    }

    public function down(Schema $schema): void
    {
        $schema->getTable('event_store')->dropIndex('IDX_BE4CE95B683C6017');
    }
}
