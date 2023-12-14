<?php

declare(strict_types=1);

namespace App\DDDBundle\Infrastructure\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211118215512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add event_store table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event_store (id VARCHAR(32) NOT NULL, type_name VARCHAR(255) NOT NULL, event_body CLOB NOT NULL, aggregate_root_id VARCHAR(255) NOT NULL, user_id VARCHAR(32) NOT NULL, published BOOLEAN NOT NULL, occurred_on DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_store');
    }
}
