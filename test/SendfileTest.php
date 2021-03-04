<?php declare(strict_types=1);

namespace ZeroBounce\Test;

use ZeroBounce\Response\SendfileResponse;

/**
 * Class SendfileTest
 * @package ZeroBounce\Test
 * @see https://www.zerobounce.net/docs/email-validation-api-quickstart/#file_management_overview__v2__
 */
class SendfileTest extends Base
{
    const HTTP_OPTIONS = [
        'base_uri' => 'https://bulkapi.zerobounce.net/v2/',
    ];

    const SEED_LIST = [
        '1' => 'valid@example.com',
        '2' => 'invalid@example.com',
        '3' => 'catch_all@example.com',
        '4' => 'donotmail@example.com',
        '5' => 'unknown@example.com',
    ];

    /**
     * @throws \ZeroBounce\Exception\ClientException
     */
    final public function testSendfile(): string
    {
        /** @var SendfileResponse $response */
        $response = $this->api->sendfile('./bulktest.csv', [
            'email_address_column' => 2,
        ]);

        $this->assertInstanceOf(SendfileResponse::class, $response);
        $this->assertIsString($response->getFileId());
        $this->assertEquals($response->getFileName(), 'bulktest.csv');

        return $response->getFileId();
    }

    /**
     * @throws \ZeroBounce\Exception\ClientException
     * @depends testSendfile
     */
    final public function testFilestatus(string $fileId): string
    {
        /** @var SendfileResponse $response */
        $response = $this->api->filestatus($fileId);

        $this->assertInstanceOf(SendfileResponse::class, $response);
        $this->assertIsString($response->getFileId());
        $this->assertEquals($response->getFileName(), 'bulktest.csv');
        $this->assertIsString($response->getUploadDate());
        $this->assertIsString($response->getFileStatus());
        $this->assertIsString($response->getCompletePercentage());
        $this->assertIsString($response->getReturnUrl());

        return $fileId;
    }

    /**
     * @throws \ZeroBounce\Exception\ClientException
     * @depends testFilestatus
     */
    final public function testGetfile(string $fileId): void
    {
        do {
            $response = $this->api->filestatus($fileId);
            fwrite(STDERR, PHP_EOL . $response->getFileStatus());
            sleep(1);
        } while ($response->getFileStatus() != 'Complete');

        $response = $this->api->getfile($fileId);
        $rows = explode("\n", trim($response));

        // 5 entries plus a header
        $this->assertEquals(count($rows), 6);

        // Verify each row
        foreach($rows as $i => $row) {
            if ($i == 0) {
                continue; // ignore header
            }
            $cols = str_getcsv($row);
            $this->assertEquals($cols[1], self::SEED_LIST[$cols[0]]);
        }
    }

}
