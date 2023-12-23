<?php

declare(strict_types=1);

namespace App\DDDBundle\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218214525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create event_store table';
    }

    public function up(Schema $schema): void
    {
        $eventStore = $schema->createTable('event_store');
        $eventStore->addColumn('id', 'uuid', ['length' => 36]);
        $eventStore->addColumn('event_name', 'string');
        $eventStore->addColumn('event_body', 'json');
        $eventStore->addColumn('aggregate_root_id', 'string');
        $eventStore->addColumn('user_id', 'string');
        $eventStore->addColumn('published', 'boolean');
        $eventStore->addColumn('occurred_on', 'datetime_immutable');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('event_store');
    }
}
