<?php

/*
 * This file is part of the WPframework package.
 *
 * (c) Uriel Wilson <uriel@wpframework.io>
 *
 * The full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 namespace WPframework\Support;

 use DeviceDetector\ClientHints;
 use DeviceDetector\DeviceDetector;
 use Psr\Log\LoggerInterface;

 class Device
 {
     private LoggerInterface $logger;

     public function __construct(LoggerInterface $logger)
     {
         $this->logger = $logger;
     }

     public function isBot(): bool
     {
         try {
             $userAgent = $this->getUserAgent();
             $clientHints = $this->getClientHints();

             $this->logger->debug('Initializing DeviceDetector with user agent', ['userAgent' => $userAgent]);

             $deviceDetector = $this->initializeDeviceDetector($userAgent, $clientHints);

             if ($deviceDetector->isBot()) {
                 $this->logger->info('Bot detected', ['userAgent' => $userAgent]);
                 return true;
             }

             $this->logger->debug('No bot detected for user agent', ['userAgent' => $userAgent]);
             return false;
         } catch (\Exception $e) {
             $this->logger->error('An error occurred while detecting the device', [
                 'exception' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
             return false;
         }
     }

     private function getUserAgent(): string
     {
         return $_SERVER['HTTP_USER_AGENT'] ?? '';
     }

     private function getClientHints(): ClientHints
     {
         try {
             return ClientHints::factory($_SERVER);
         } catch (\Exception $e) {
             $this->logger->warning('Failed to get client hints', [
                 'exception' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
             return new ClientHints();
         }
     }

     private function initializeDeviceDetector(string $userAgent, ClientHints $clientHints): DeviceDetector
     {
         $dd = new DeviceDetector($userAgent, $clientHints);
         $this->logger->debug('DeviceDetector instance created', [
             'userAgent' => $userAgent,
             'clientHints' => $clientHints
         ]);

         // Improves performance by skipping bot details
         $dd->discardBotInformation();

         try {
             $dd->parse();
             $this->logger->debug('DeviceDetector parsing complete', [
                 'userAgent' => $userAgent,
                 'clientHints' => $clientHints
             ]);
         } catch (\Exception $e) {
             $this->logger->error('An error occurred while parsing the user agent', [
                 'exception' => $e->getMessage(),
                 'userAgent' => $userAgent,
                 'trace' => $e->getTraceAsString()
             ]);
         }

         return $dd;
     }
}
