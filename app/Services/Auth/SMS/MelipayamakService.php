<?php
namespace App\Services\Auth\SMS;

use App\Http\Interfaces\NotificationServiceInterface;
use Melipayamak\MelipayamakApi;

class MelipayamakService implements NotificationServiceInterface
{
    private array $text;
    private string $to;
    private int $bodyId;

    public function setText(array $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function setTo(string $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function setBodyId(int $bodyId): self
    {
        $this->bodyId = $bodyId;
        return $this;
    }

    public function send(): void
    {
        try {
            $api = new MelipayamakApi(config('otp.username'), config('otp.password'));
            $api->sms('soap')->sendByBaseNumber($this->text, $this->to, $this->bodyId);
        } catch (\Exception $e) {
            logger()->error("SMS send failed: " . $e->getMessage());
        }
    }
}
