<?php

declare(strict_types=1);

namespace rouda\monologHandler;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class ApiLogger extends AbstractProcessingHandler
{
    protected $apiUrl;
    protected $authToken;
    protected $applicationId;
    protected $applicationEnv;

    public function __construct(string $authToken, string $applicationId, string $applicationEnv, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->apiUrl = 'https://rouda.online/api/application/log';
        $this->authToken = $authToken;
        $this->applicationId = $applicationId;
        $this->applicationEnv = $applicationEnv;

        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $date = $record->datetime;

        $data = [
            'time' => $date->format('Y-m-d\TH:i:s.uO'),
            'type' => $record->channel,
            'data' => array_merge($record->context, ['level' => $record->level]),
        ];

        $this->sendRequest($data);
    }

    protected function sendRequest(array $data): void
    {
        $ch = curl_init($this->apiUrl);

        $payload = json_encode($data);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->authToken,
            'X-Application-Id: ' . $this->applicationId,
            'X-Application-env: ' . $this->applicationEnv,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);
    }
}
