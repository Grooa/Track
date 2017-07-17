<?php

namespace Plugin\Track\Model;

use \Aws\S3\S3Client;
use \Ip\Exception;

class AwsS3
{

    private static $s3;
    private static $bucket;
    private static $expiration;

    public static function init()
    {
        AwsS3::$bucket = ipGetOption('Track.awsS3Bucket');
        AwsS3::$expiration = ipGetOption('Track.awsUrlExpiration');
        AwsS3::$s3 = new S3Client([
            'region' => ipGetOption('Track.region'),
            'version' => ipGetOption('Track.version'),
            'credentials' => [
                'key' => ipGetOption('Track.awsKey'),
                'secret' => ipGetOption('Track.awsSecret')
            ]
        ]);
    }

    public static function getPresignedUri($item, $config = [])
    {
        if (empty(AwsS3::$bucket) && empty($config['bucket'])) {
            throw new Exception("Missing default Aws S3 `bucket` in Plugin configuration");
        }

        if (empty(AwsS3::$expiration) && empty($config['expiration'])) {
            throw new Exception("Missing default `expiration` time for Presigned Uris, for Aws S3");
        }

        $cmd = AwsS3::$s3->getCommand('GetObject', [
            'Bucket' => !empty($config['bucket']) ? $config['bucket'] : AwsS3::$bucket,
            'Key' => $item
        ]);

        $request = AwsS3::$s3->createPresignedRequest(
            $cmd,
            !empty($config['expiration']) ? $config['expiration'] : AwsS3::$expiration
        );

        return (string)$request->getUri();
    }
}

AwsS3::init(); // Workaround to init static fields