<?php

namespace Plugin\Track\Service;

use Aws\S3\S3Client;

class AwsService
{
    /**
     * @var S3Client
     */
    private $s3;
    /**
     * @var string
     */
    private $bucket;

    /**
     * @var string
     */
    private $expiration;

    function __construct()
    {
        $this->setupS3OrFail([
            'bucket' => ipGetOption('Track.awsS3Bucket'),
            'expiration' => ipGetOption('Track.awsUrlExpiration'),
            'region' => ipGetOption('Track.region'),
            'version' => ipGetOption('Track.version'),
            'credentials' => [
                'key' => ipGetOption('Track.awsKey'),
                'secret' => ipGetOption('Track.awsSecret')
            ]
        ]);
    }

    /**
     * @param string $uri
     * @return null|string
     */
    public function createPresignedUrl(String $uri): ?String
    {
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $uri
        ]);

        $request = $this->s3->createPresignedRequest($cmd, $this->expiration);

        return (string)$request->getUri();
    }

    private function setupS3OrFail($config)
    {
        // TODO:ffl - Implement logic to fail if config is missing

        $this->bucket = $config['bucket'];
        $this->expiration = $config['expiration'];
        $this->s3 = new S3Client([
            'region' => $config['region'],
            'version' => $config['version'],
            'credentials' => [
                'key' => $config['credentials']['key'],
                'secret' => $config['credentials']['secret']
            ]
        ]);
    }
}
