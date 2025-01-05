<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPframework\Support\Services;

use InvalidArgumentException;
use RuntimeException;
use Urisoft\DotAccess;

class IDGenerator
{
    public $constraints;
    private $Id;
    private $config;
    private $existingIDs;
    private $format;
    private $randomLength;
    private $maxRetries;
    private $sequenceStart;
    private $idLength;
    private $hashAlgorithm;
    private $auditLogs;
    private $prefix;
    private $suffix;
    private $delimiter;
    private $tenantId;

    public function __construct(array $tenantConfig, array $existingIDs = [])
    {
        $this->config = new DotAccess($tenantConfig);
        $this->existingIDs = $existingIDs;

        // Load core ID settings
        $this->format = $this->getConfig('format');
        $this->randomLength = $this->getConfig('random_length');
        $this->maxRetries = $this->getConfig('random_retries', 5);
        $this->sequenceStart = $this->getConfig('sequence_start', 1);
        $this->idLength = $this->getConfig('id_length');
        $this->hashAlgorithm = $this->getConfig('hash_algorithm');
        $this->auditLogs = $this->getConfig('audit_logs');

        // Load prefix, suffix, and delimiter
        $this->prefix = $this->getConfig('prefix');
        $this->suffix = $this->getConfig('suffix');
        $this->delimiter = $this->getConfig('delimiter');

        // Load constraints
        $this->constraints = [
            'min_length' => $this->getConfig('constraints.min_length'),
            'max_length' => $this->getConfig('constraints.max_length'),
            'allowed_characters' => $this->getConfig('constraints.allowed_characters'),
            'restricted_characters' => $this->getConfig('constraints.restricted_characters'),
        ];

        $this->validateConfig();
    }

    public function generateID()
    {
        switch ($this->format) {
            case 'uuid':
                $this->Id = $this->generateUUID();

                break;
            case 'number':
            case 'num':
                $this->Id = $this->generateNumberID();

                break;
            case 'hash':
                $this->Id = $this->generateHashID();

                break;
            case 'random':
                $this->Id = $this->generateRandomID();

                break;
            default:
                throw new InvalidArgumentException("Invalid format specified.");
        }

        $this->setTenantId($this->Id);

        return $this;
    }

    public function setTenantId($id): void
    {
        $this->existingIDs[$id] = [
            'id' => $id,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'delimiter' => $this->delimiter,
            'tenant_id' => $this->applyPrefixSuffix($id),
        ];
    }

    public function getExistingIDs()
    {
        return $this->existingIDs;
    }


    public function getID()
    {
        return $this->Id;
    }

    /**
     * Retrieves the tenant ID based on the provided or current ID.
     *
     * If no ID is provided, it attempts to retrieve the tenant ID
     * associated with the current object's ID. If an ID is provided,
     * it looks for the tenant ID associated with that ID.
     *
     * @param null|string $id Optional. The ID to look up. Defaults to null.
     *
     * @return mixed The tenant ID if found, or null if not available.
     */
    public function getTenantId(?string $id = null)
    {
        if (! $id) {
            return $this->existingIDs[$this->Id]['tenent_id'] ?? null;
        }

        return $this->existingIDs[$id] ?? null;
    }

    /**
     * @param null|int $default
     *
     * @psalm-param 1|5|null $default
     */
    protected function getConfig(string $key, ?int $default = null)
    {
        return $this->config->get($key, $default);
    }

    private function generateNumberID()
    {
        static $currentNumber;
        if (! isset($currentNumber)) {
            $currentNumber = $this->sequenceStart;
        } else {
            $currentNumber++;
        }

        $id = str_pad($currentNumber, $this->idLength, '0', STR_PAD_LEFT);

        return $this->enforceLengthConstraints($id);
    }

    /**
     * Generate a hashed identifier based on a random string and specified hash algorithm.
     *
     * @throws InvalidArgumentException If the specified hashing algorithm is invalid or if idLength is not a positive integer.
     *
     * @return null|string The generated hash ID or null if no algorithm is provided.
     *
     * @see https://www.php.net/manual/en/function.hash-algos.php
     */
    private function generateHashID()
    {
        if (! $this->hashAlgorithm) {
            return null;
        }

        $randomString = bin2hex(random_bytes(16));

        if (! \in_array($this->hashAlgorithm, hash_algos(), true)) {
            throw new InvalidArgumentException("Invalid algorithm format specified, hash must be a valid hashing algorithm.");
        }

        $hash = hash($this->hashAlgorithm, $randomString);
        $hashChunk = substr($hash, 0, $this->idLength);

        return $this->enforceLengthConstraints($hashChunk);
    }

    private function generateRandomID()
    {
        for ($attempt = 0; $attempt < $this->maxRetries; $attempt++) {
            $stringID = $this->randomNumericString($this->randomLength);
            $randomID = $this->enforceLengthConstraints($stringID);

            if (! isset($this->existingIDs[$randomID])) {
                return $randomID;
            }
        }

        throw new RuntimeException("Failed to generate a unique random ID after {$this->maxRetries} retries.");
    }

    private function randomNumericString($length)
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= mt_rand(0, 9);
        }

        return $digits;
    }

    private function applyPrefixSuffix($id)
    {
        return trim($this->prefix . $this->delimiter . $id . $this->delimiter . $this->suffix, $this->delimiter);
    }

    private function validateConfig(): void
    {
        if (! $this->format) {
            throw new InvalidArgumentException("format is required.");
        }

        if (! \in_array($this->format, ['uuid', 'number', 'hash', 'random'], true)) {
            throw new InvalidArgumentException("Invalid format. Allowed values: uuid, number, hash, random.");
        }

        if (! empty($this->idLength)) {
            if (! \is_int($this->idLength) || $this->idLength <= 0) {
                throw new InvalidArgumentException("idLength must be a positive integer.");
            }
        }

        if ('random' === $this->format && empty($this->randomLength)) {
            throw new InvalidArgumentException("random_length is required for format 'random'.");
        }
    }

    private function generateUUID()
    {
        return \sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * @param false|string $id
     */
    private function enforceLengthConstraints($id)
    {
        if ($this->randomLength) {
            $minLength = $this->randomLength;
        } else {
            $minLength = $this->constraints['min_length'];
        }

        $maxLength = $this->constraints['max_length'];

        if ($minLength && \strlen($id) < $minLength) {
            $id = str_pad($id, $minLength, '0', STR_PAD_RIGHT);
        }

        if ($maxLength && \strlen($id) > $maxLength) {
            $id = substr($id, 0, $maxLength);
        }

        return $id;
    }
}
