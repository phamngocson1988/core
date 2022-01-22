<?php
namespace common\queue\drivers\db;

use yii\base\Exception;
use yii\db\Query;

class Queue extends \yii\queue\db\Queue
{
    public function executeSingleJob($id) 
    {
        try {
            // Reserve one message
            $payload = (new Query())
                ->from($this->tableName)
                ->andWhere(['channel' => $this->channel, 'reserved_at' => null, 'id' => $id])
                ->andWhere('[[pushed_at]] <= :time - [[delay]]', [':time' => time()])
                ->limit(1)
                ->one($this->db);
            if (is_array($payload)) {
                $payload['reserved_at'] = time();
                $payload['attempt'] = (int) $payload['attempt'] + 1;
                $this->db->createCommand()->update($this->tableName, [
                    'reserved_at' => $payload['reserved_at'],
                    'attempt' => $payload['attempt'],
                ], [
                    'id' => $payload['id'],
                ])->execute();

                // pgsql
                if (is_resource($payload['job'])) {
                    $payload['job'] = stream_get_contents($payload['job']);
                }
            }

            if (!$payload) return;

            if ($this->handleMessage(
                $payload['id'],
                $payload['job'],
                $payload['ttr'],
                $payload['attempt']
            )) {
                $this->release($payload);
            }
        } finally {
            $this->mutex->release(__CLASS__ . $this->channel);
        }
    }
}