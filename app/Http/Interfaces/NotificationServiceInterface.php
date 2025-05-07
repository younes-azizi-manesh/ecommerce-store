<?php

namespace App\Http\Interfaces;

interface NotificationServiceInterface
{
    public function setText(array $text): self;
    public function setTo(string $to): self;
    public function setBodyId(int $bodyId): self;
    public function send();
}
