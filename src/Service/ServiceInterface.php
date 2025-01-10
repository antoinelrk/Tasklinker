<?php

namespace App\Service;

use App\Entity\Entity;

interface ServiceInterface
{
    public function create(Entity $project): Entity|bool;
    public function update(Entity $project): Entity|bool;
    public function delete(Entity $project): bool;
}
