<?php

namespace App\Builder\Contract;

use App\Entity\User;

interface StatementInterface
{

    public function setContent(string $content);

    public function setPhoto(null|string $photo);

    public function setOwner(User $user);

}