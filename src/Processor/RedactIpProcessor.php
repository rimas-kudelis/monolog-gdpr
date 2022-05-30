<?php

namespace Egeniq\Monolog\Gdpr\Processor;

class RedactIpProcessor extends AbstractProcessor
{
    /**
     * @param array|\Monolog\LogRecord $record
     *
     * @return array|\Monolog\LogRecord
     */
    public function __invoke($record)
    {
        // serialise to a JSON string so we can scan the entire tree
        $serialised = json_encode($record);

        $filtered = preg_replace_callback(
            "/(\b\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3}\b)/",
            function($matches) {
                return $this->getHashedValue($matches[0]);
            },
            $serialised
        );

        if ($filtered) {
            return json_decode($filtered, true);
        }

        return $record;
    }
}
